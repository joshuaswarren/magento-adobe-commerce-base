<?php


namespace Creatuity\Base\Model\Catalog\CategoriesModifier;


use Creatuity\Base\Helpers\Creatuity;
use Magento\Catalog\Api\CategoryManagementInterface;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Api\Data\CategoryInterfaceFactory;
use Magento\Catalog\Model\Category;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

class CategoriesProcessor implements CategoriesProcessorInterface
{

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoriesRepo;

    /**
     * @var CategoryInterfaceFactory
     */
    protected $categoriesFactory;

    /**
     * @var CategoryManagementInterface
     */
    protected $categoryManagement;

    /**
     * @var Creatuity
     */
    protected $creatuity;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var CatalogHelper
     */
    protected $catalogHelper;

    /**
     * @var DataProcessor
     */
    protected $dataProcessor;

    /**
     * @var Config
     */
    protected $config;
    /**
     * @var HelperFactory
     */
    protected $helperFactory;
    /**
     * @var CatalogHelper
     */
    protected $catalogHelperFactory;
    /**
     * @var DataProcessor
     */
    protected $dataProcessorFactory;
    /**
     * @var Config
     */
    protected $configFactory;
    /**
     * @var array
     */
    protected $keyToIds = [];
    /**
     * @var int[]
     */
    protected $rootCategoryIds = [];
    /**
     * @var int[]
     */
    protected $ourRootCategoryIds = [];
    /**
     * @var int
     */
    protected $topCategoryId = 0;


    public function __construct(
        CategoryRepositoryInterface $categoriesRepo,
        CategoryInterfaceFactory $categoriesFactory,
        CategoryManagementInterface $categoryManagement,
        Creatuity $creatuity,
        StoreManagerInterface $storeManager,
        HelperFactory $helperFactory,
        CatalogHelperFactory $catalogHelperFactory,
        DataProcessorFactory $dataProcessorFactory,
        ConfigFactory $configFactory
    ) {
        $this->categoriesRepo = $categoriesRepo;
        $this->categoriesFactory = $categoriesFactory;
        $this->categoryManagement = $categoryManagement;
        $this->creatuity = $creatuity;
        $this->storeManager = $storeManager;
        $this->helperFactory = $helperFactory;
        $this->catalogHelperFactory = $catalogHelperFactory;
        $this->dataProcessorFactory = $dataProcessorFactory;
        $this->configFactory = $configFactory;

        $this->clearState();
    }

    protected function clearState()
    {
        $this->helper = null;
        $this->catalogHelper = null;
        $this->dataProcessor = null;
        $this->config = null;
        $this->topCategoryId = null;
        $this->rootCategoryIds = [];
        $this->ourRootCategoryIds = [];
        $this->keyToIds = [];
    }

    public function process(Config $config, array $data)
    {
        try {
            $this->clearState();

            list($data, $this->config) = $this->stripConfigFromData($data, $config);

            $this->helper = $this->helperFactory->create([
                'config' => $this->config,
            ]);
            $this->catalogHelper = $this->catalogHelperFactory->create([
                'config' => $this->config,
                'helper' => $this->helper,
            ]);
            $this->dataProcessor = $this->dataProcessorFactory->create([
                'config' => $this->config,
                'helper' => $this->helper,
            ]);

            $this->doProcess($data);

        } finally {
            $this->clearState();
        }
    }

    protected function stripConfigFromData(array $data, Config $config)
    {
        $configData = null;
        foreach($data as $key => $item) {
            if (empty($item['is_config_node'])) {
                continue;
            }

            if ($configData) {
                throw new CategoriesModifierException("Only one 'is_config_node' is allowed!");
            }

            unset($data[$key]);
            $configData = $item['config'];
        }

        if ($configData !== null) {
            $config = $this->configFactory->create($configData, $config);
        }

        return [ $data, $config ];
    }

    protected function doProcess(array $data)
    {
        $this->helper->runSafely(function() use ($data) {
            $this->helper->log("Start new database transaction");
            $this->runIndented(function() use ($data) {
                $this->helper->log('Preparing categories data...');
                $this->runIndented(function() use (&$data) {
                    $data = $this->dataProcessor->processData($data);
                    $this->helper->log('Done.');
                });

                $this->helper->log('Finding top category...');
                $this->runIndented(function() {
                    $this->determineTopCategory();
                    $this->helper->log('Done.');
                });

                $this->helper->log( 'Creating our root categories...');
                $this->runIndented(function() use ($data) {
                    $this->createRootCategories($data);
                    $this->helper->log('Done.');
                });

                $this->helper->log( 'Collecting all root categories...');
                $this->runIndented(function() {
                    $this->determineRootCategories();
                    $this->helper->log('Done.');
                });

                if ($this->config->replaceCurrentCategories()) {
                    $this->helper->log('Replacing is enabled, so starting to remove existing categories first...');
                    $this->runIndented(function() use ($data) {
                        $categoriesToBeSaved = $this->categoriesReferencedById($data);
                        $this->removeAllCategories($categoriesToBeSaved);
                        $this->helper->log('Done.');
                    });
                } else {
                    $this->helper->log('Replacing is disabled. Leaving original categories as they are');
                }

                $this->helper->log('Creating categories...');
                $this->runIndented(function() use ($data) {
                    $this->createNonRootCategories($data);
                    $this->helper->log('Done.');
                });
            });

            $this->helper->log("Committing database transaction...");
            $this->helper->log('Done.');
        });
    }

    protected function runIndented($callback)
    {
        return $this->helper->runIndented($callback);
    }

    protected function removeAllCategories(array $categoriesToBeSaved)
    {
        foreach($categoriesToBeSaved as $categoryId) {
            if (isset($this->rootCategoryIds[$categoryId])) {
                continue;
            }
            $this->disableRootCategoryInStoreGroups($categoryId);
            $this->categoryManagement->move($categoryId, $this->someRandomRootCategoryId());
        }

        $categories = $this->catalogHelper->categoriesInTopologicalOrder(true, ['name']);
        foreach ($categories as $category) {
            $id = $category->getId();

            if ($id == $this->topCategoryId) {
                continue;
            }

            if (isset($this->ourRootCategoryIds[$id])) {
                continue;
            }

            if (isset($this->rootCategoryIds[$id]) && empty($this->ourRootCategoryIds)) {
                continue;
            }

            if (isset($categoriesToBeSaved[$id])) {
                continue;
            }

            $this->disableRootCategoryInStoreGroups($id);
            $this->helper->log(sprintf("Deleting category '%s', given by id '%s'", $category->getName(), $id));
            $category->delete();
            unset($this->rootCategoryIds[$id]);
        }
    }

    /**
     * @return string[]
     */
    protected function categoriesReferencedById(array $data)
    {
        $ret = [];
        foreach($data as $item) {
            if (!empty($item['entity_id']) && is_numeric($item['entity_id'])) {
                $ret[$item['entity_id']] = $item['entity_id'];
                continue;
            }

            if (!empty($item['parent']) && is_numeric($item['parent'])) {
                $ret[$item['parent']] = $item['parent'];
                continue;
            }
        }
        return $ret;
    }

    protected function createRootCategories(array $categoriesData)
    {
        $rootCategories = array_filter($categoriesData, function ($item) {
            return !empty($item['is_root_category']);
        });

        if (empty($rootCategories)) {
            $this->helper->log("We have no root categories defined");
            return;
        }

        $this->createCategories($rootCategories);
    }

    protected function createNonRootCategories(array $categoriesData)
    {
        $this->createCategories(array_filter($categoriesData, function($item) {
            return empty($item['is_root_category']);
        }));
    }

    protected function createCategories(array $categoriesData)
    {
        $categoriesCount = count($categoriesData);
        $this->helper->log(sprintf('There are %s categories to process...', $categoriesCount));

        $progressCounter = 0;
        foreach ($categoriesData as $key => $item) {
            $this->helper->log(sprintf("Processing category '%s'...", $key));
            $this->runIndented(function() use ($key, $item) {
                $category = $this->setupCategory($key, $item);

                if (!$category->getName()) {
                    $this->helper->log(sprintf("Category '%s' saved (id=%s).", $key, $category->getId()));
                } else {
                    $this->helper->log(sprintf("Category '%s' saved (name=%s, id=%s).", $key, $category->getName(), $category->getId()));
                }

                if (isset($item['is_root_category_for_stores'])) {
                    $this->setupCategoryAsRootForStores($category->getId(), (array)$item['is_root_category_for_stores']);
                }

                if (isset($item['is_root_category_for_store_groups'])) {
                    $this->setupCategoryAsRootForStoreGroups($category->getId(), (array)$item['is_root_category_for_store_groups']);
                }
            });

            if (++$progressCounter % 50 == 0) {
                $this->helper->log(sprintf('Categories saved count: %s of %s', $progressCounter, $categoriesCount));
            }
        }
    }

    /**
     * @return Category
     */
    protected function setupCategory($key, array $item)
    {
        try{
            if ( !empty($item['stores']) ) {
                $category = $this->handlePerStoreCategorySetup($key, $item);
            } else {
                $category = $this->handleCategorySetup($key, $item);
            }
            return $category;
        } catch (\Exception $e) {
            throw new CategoriesModifierException("Error during processing category:\n" . var_export($item, true), 0, $e);
        }
    }

    /**
     * @return Category
     */
    protected function handlePerStoreCategorySetup($key, array $item)
    {
        $category = $this->handleCategorySetup($key, $item);

        $storeCodesToIdsMap = [];
        foreach ( $item['stores'] as $storeCode => $categoryDataOnParticularStore ) {
            $this->helper->log("Customizing for store $storeCode...");

            if ( !isset($storeCodesToIdsMap[$storeCode]) ) {
                $storeCodesToIdsMap[$storeCode] = $this->creatuity->store()->storeViewModel($storeCode, true)->getId();
            }
            $storeId = $storeCodesToIdsMap[$storeCode];

            $this->creatuity->emulate()->runInStore($storeId, function() use ($key, $storeId, $categoryDataOnParticularStore) {
                $scopedCategory = $this->provideCategory($key, null, $storeId);
                $scopedCategory->setData('use_default', $this->catalogHelper->designateDefaultAttributesCode($categoryDataOnParticularStore) )
                    ->addData( array_merge($this->catalogHelper->obtainScopeCategoryAttributesCode(), $categoryDataOnParticularStore) )
                    ->setStoreId($storeId);

                $this->categoriesRepo->save($scopedCategory);
            });
        }

        return $category;
    }

    /**
     * @return Category
     */
    protected function handleCategorySetup($key, array $item)
    {
        $category = $this->provideCategory($key, isset($item['entity_id']) ? $item['entity_id'] : null)->addData($item);

        $isRootCategory = isset($item['is_root_category']) ? $item['is_root_category'] : false;

        if (isset($item['parent'])) {
            $parentId = $this->findParentIdOf($item['parent'], $isRootCategory);
            if ($parentId === null) {
                $this->helper->throwError($item, "Cannot find ID of a parent: '{$item['parent']}'");
            }

            $categoryId = isset($this->keyToIds[$key]) ? $this->keyToIds[$key] : $category->getId();
            if ($categoryId) {
                $this->categoryManagement->move($categoryId, $parentId);
            } else {
                $category->setParentId($parentId);
            }
        }

        $this->helper->log("Saving...");

        $this->categoriesRepo->save($category);
        $this->keyToIds[$key] = $category->getId();

        if ($isRootCategory) {
            $this->rootCategoryIds[$category->getId()] = $category->getId();
            $this->ourRootCategoryIds[$category->getId()] = $category->getId();
        }

        return $category;
    }

    /**
     * @return int
     */
    protected function findParentIdOf($parentKey, $isRootCategory)
    {
        $isParentSetAsRootKey = $parentKey == $this->config->treeDelimiter() || $parentKey == '';

        if ($isRootCategory) {
            if (!$isParentSetAsRootKey) {
                throw new CategoriesModifierException("If category is a root category, it's 'parent' key must be root or empty");
            }
            return $this->topCategoryId;
        }


        if (is_numeric($parentKey)) {
            return $parentKey;
        }

        if ($isParentSetAsRootKey) {
            if (count($this->rootCategoryIds) != 1) {
                throw new CategoriesModifierException(sprintf("Cannot determine to which root category 'parent' key refers to?\n" .
                    "We have two root categories: %s\n" .
                    "You have to either have only one root category or either provide numerical id of the root category under the 'parent' field.\n" .
                    "Alternatively, you can create root category by your own by setting 'is_root_category = 1' and refers to it in the 'parent' key\n",
                    implode(', ', $this->rootCategoryIds)
                    ));
            }

            return array_values($this->rootCategoryIds)[0];
        }

        if (empty($this->keyToIds[$parentKey])) {
            return null;
        }

        return $this->keyToIds[$parentKey];
    }

    /**
     * @return int
     */
    protected function someRandomRootCategoryId()
    {
        if (!empty($this->ourRootCategoryIds)) {
            return array_values($this->ourRootCategoryIds)[0];
        }

        if (!empty($this->rootCategoryIds)) {
            return array_values($this->rootCategoryIds)[0];
        }

        throw new CategoriesModifierException("No known root categories");
    }

    protected function determineTopCategory()
    {
        $this->topCategoryId = $this->catalogHelper->determineTopCategoryId();
        if (empty($this->topCategoryId)) {
            $this->topCategoryId = $this->createTopCategory();
            $this->helper->log("Top category was missed so I've created it (id={$this->topCategoryId}). ");
        } else {
            $this->helper->log("Top category found with id=" . $this->topCategoryId);
        }
    }

    protected function determineRootCategories()
    {
        $this->rootCategoryIds = $this->catalogHelper->determineRootCategoryIds();

        if (empty($this->rootCategoryIds) && empty($this->ourRootCategoryIds)) {
            $rootCategoryId = $this->createRootCategory($this->topCategoryId);
            $this->rootCategoryIds[$rootCategoryId] = $rootCategoryId;
            $this->helper->log("Root category was missed so I've created it (id={$rootCategoryId}). ");
        }

        if (!empty($this->rootCategoryIds)) {
            $this->helper->log("Found root category ids: " . implode(', ', $this->rootCategoryIds));
        }

        if (empty($this->ourRootCategoryIds)) {
            $this->helper->log("None of them was created by us");
        } else {
            $this->helper->log("From which our root categories are: " . implode(', ', $this->ourRootCategoryIds));
        }
    }

    protected function disableRootCategoryInStoreGroups($rootCategoryId)
    {
        $this->runIndented(function() use ($rootCategoryId) {
            foreach($this->storeManager->getGroups(true) as $group) {
                if ($group->getRootCategoryId() == $rootCategoryId) {
                    $newRootCategoryId = $this->someRandomRootCategoryId();

                    $group->setRootCategoryId($newRootCategoryId);

                    $this->helper->log(sprintf("Unlinking category '%s' from being root category of '%s' store group. Setting '%s' instead",
                        $rootCategoryId, $group->getName(), $newRootCategoryId));

                    $group->save();
                }
            }
        });
    }

    protected function setupCategoryAsRootForStores($categoryId, array $storeIdOrCodes)
    {
        $groupIds = [];
        foreach($storeIdOrCodes as $storeIdOrCode) {
            $groupIds[] = $this->storeManager->getStore($storeIdOrCode)->getStoreGroupId();
        }

        $this->setupCategoryAsRootForStoreGroups($categoryId, array_unique($groupIds));
    }

    protected function setupCategoryAsRootForStoreGroups($rootCategoryId, array $groupIdOrCodes)
    {
        if (!isset($this->rootCategoryIds[$rootCategoryId])) {
            throw new CategoriesModifierException(sprintf(
                "I cannot set category '%s' as root category for store. It's not root category.",
                $rootCategoryId ));
        }

        $groupsByCode = [];
        foreach($this->storeManager->getGroups(true) as $group) {
            $groupsByCode[$group->getCode()] = $group;
        }

        foreach($groupIdOrCodes as $groupIdOrCode) {
            if (isset($groupsByCode[$groupIdOrCode])) {
                $group = $groupsByCode[$groupIdOrCode];
            } else {
                $group = $this->storeManager->getGroup($groupIdOrCode);
                if (!$group) {
                    throw new CategoriesModifierException("Invalid store group: $groupIdOrCode");
                }
            }
            $group->setRootCategoryId($rootCategoryId);
            $group->save();

            $this->helper->log(sprintf("Set as a root category of '%s' store group.",
                $group->getName()
            ));
        }
    }

    /**
     * @return int|null
     */
    protected function createTopCategory($topCategoryId = 1)
    {
        return $this->forceCreateCategory($topCategoryId, [
            'path' => $topCategoryId,
            'level' => 0,
            'position' => 0,
            'children_count' => 0,
            'name' => 'Root Catalog',
        ]);
    }

    /**
     * @return int|null
     */
    protected function createRootCategory($topCategoryId, $rootCategoryId = 2)
    {
        return $this->forceCreateCategory($rootCategoryId, [
            'path' => $topCategoryId . $this->config->treeDelimiter() . $rootCategoryId,
            'name' => 'Default Category',
            'display_mode' => 'PRODUCTS',
            'is_active' => 1,
            'level' => 1,
            'position' => 0,
            'attribute_set_id' => $this->catalogHelper->defaultAttributeSetId(),
        ]);
    }

    /**
     * @return int
     */
    protected function forceCreateCategory($categoryId, $data)
    {
        if ($this->catalogHelper->hasCategoryHavingId($categoryId)) {
            throw new CategoriesModifierException("Category already exists");
        }

        $this->creatuity->database()->dbConnection()->delete(
            'sequence_catalog_category', 'sequence_value = ' . $categoryId);

        $category = $this->provideCategory(null)
            ->load($categoryId)
            ->setId($categoryId)
            ->setStoreId(0)
            ->addData($data)
            ->setInitialSetupFlag(true)
            ->save();

        return $category->getId();
    }

    /**
     * @return CategoryInterface
     */
    protected function provideCategory($key, $entityId = null, $storeId = null)
    {
        try {
            if (isset($this->keyToIds[$key]) && isset($entityId) && $this->keyToIds[$key] != $entityId) {
                throw new CategoriesModifierException("I'm confused. I don't know which category should I load ");
            }

            if (!empty($entityId)) {
                return $this->categoriesRepo->get($entityId, $storeId);
            }

            if (!empty($this->keyToIds[$key])) {
                return $this->categoriesRepo->get($this->keyToIds[$key], $storeId);
            }
        } catch (NoSuchEntityException $e) {
        }

        return $this->categoriesFactory->create();
    }



}