<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Creatuity\Base\Helpers\Creatuity;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Store\Model\Group as StoreModel;
use Magento\Store\Model\Group;
use Magento\Store\Model\GroupFactory as StoreFactory;
use Magento\Store\Model\Store as StoreViewModel;
use Magento\Store\Model\StoreFactory as StoreViewFactory;
use Magento\Store\Model\Website as WebsiteModel;
use Magento\Store\Model\Website;
use Magento\Store\Model\WebsiteFactory;


/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
class Store extends SubjectAbstract
{
    const DEFAULT_ROOT_CATEGORY = 2;

    /**
     * @var StoreViewFactory
     */
    protected $storeViewFactory;
    /**
     * @var StoreFactory
     */
    protected $storeFactory;
    /**
     * @var WebsiteFactory
     */
    protected $websiteFactory;
    /**
     * @var StoreViewModel
     */
    protected $storeView;
    /**
     * @var StoreModel
     */
    protected $store;
    /**
     * @var WebsiteModel
     */
    protected $website;
    /**
     * @var ManagerInterface
     */
    protected $eventManager;


    public function __construct(
        StoreViewFactory $storeViewFactory,
        StoreViewModel $storeView,
        StoreFactory $storeFactory,
        StoreModel $store,
        WebsiteFactory $websiteFactory,
        WebsiteModel $website,
        ManagerInterface $eventManager,
        Creatuity $creatuity
    )
    {
        parent::__construct($creatuity);

        $this->storeViewFactory = $storeViewFactory;
        $this->storeFactory = $storeFactory;
        $this->websiteFactory = $websiteFactory;
        $this->storeView = $storeView;
        $this->store = $store;
        $this->website = $website;
        $this->eventManager = $eventManager;
    }

    public function setupExampleStores()
    {
        // We're adding a a few new websites, groups and stores
        // Please note that in this example we're overriding Magento default base entities
        $this->setupStores([
            [
                'type' => 'website',
                'website_id' => 1,
                'code' => 'xyz_website',
                'name' => 'XYZ website',
                'is_default' => 1,
                'children' => [
                    [
                        'type' => 'store_group',
                        'group_id' => 1,
                        'code' => 'xyz_group',
                        'name' => 'XYZ Group',
                        'root_category_id' => 2,
                        'is_default' => 1,
                        'children' => [
                            [
                                'type' => 'store',
                                'store_id' => 1,
                                'code' => 'xyz_us',
                                'name' => 'XYZ US view',
                            ],
                            [
                                'type' => 'store',
                                'code' => 'xyz_pl',
                                'name' => 'XYZ PL view',
                                'is_default' => 1,
                            ],
                            [
                                'type' => 'store',
                                'code' => 'xyz_fr',
                                'name' => 'XYZ FR view',
                                'is_active' => 0,
                            ],
                        ],
                    ],
                ],
            ],
            [
                'type' => 'website',
                'code' => 'abc_website',
                'name' => 'ABC website',
                'children' => [
                    [
                        'type' => 'store_group',
                        'code' => 'abc_group',
                        'name' => 'ABC Group',
                        'root_category_id' => 2,
                        'children' => [
                            [
                                'type' => 'store',
                                'code' => 'abc_us',
                                'name' => 'ABC US view',
                            ],
                            [
                                'type' => 'store',
                                'code' => 'abc_pl',
                                'name' => 'ABC PL view',
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        // In this example we're adding a new store to the existing website/group
        $this->setupStores([
            [
                'type' => 'website',
                'code' => 'abc_website',
                'children' => [
                    [
                        'type' => 'store_group',
                        'code' => 'abc_group',
                        'children' => [
                            [
                                'type' => 'store',
                                'code' => 'abc_fr',
                                'name' => 'ABC FR view',
                                'is_default' => 1,
                            ],
                        ],
                    ]
                ],
            ]
        ]);

    }

    public function installStoresFromFile($path)
    {
        $this->creatuity()->resources()->ensureExists($path);

        $jsonContent = $this->creatuity()->resources()->jsonRead($path);

        $this->setupStores($jsonContent);
    }

    public function setupStores(array $stores)
    {
        $this->creatuity()->report()->printMessage("Setting up stores and websites:");

        foreach ($stores as $websiteData) {
            if (!is_array($websiteData)) {
                throw new \Exception("Invalid format. Expected array.");
            }

            $websiteIdentifier = isset($websiteData['website_id']) ? $websiteData['website_id'] : $websiteData['code'];
            $website = $this->websiteModel($websiteIdentifier, false);
            $website->addData($websiteData);
            $website->save();

            $this->creatuity()->report()->printSuccess("Website '{$website->getName()}' done");

            $defaultGroup = null;

            if (empty($websiteData['children'])) {
                continue;
            }

            foreach ($websiteData['children'] as $groupData) {
                if (!is_array($groupData)) {
                    throw new \Exception("Invalid format. Expected array of arrays in children nodes.");
                }

                $groupIdentifier = isset($groupData['group_id']) ? $groupData['group_id'] : $groupData['code'];
                $group = $this->storeGroupModel($groupIdentifier, false);

                $group->addData($groupData);
                $group->setWebsite($website);
                $group->save();

                $this->creatuity()->report()->printSuccess("  Store group '{$group->getName()}' done");

                if (!empty($groupData['is_default'])) {
                    if ($defaultGroup) {
                        throw new \Exception("You have two default groups for website {$websiteData['code']} ");
                    }
                    $defaultGroup = $group;
                }

                if (empty($groupData['children'])) {
                    continue;
                }

                $defaultStore = null;
                foreach ($groupData['children'] as $storeData) {
                    if (!is_array($storeData)) {
                        throw new \Exception("Invalid format. Expected array of arrays in children nodes.");
                    }

                    $storeIdentifier = isset($storeData['store_id']) ? $storeData['store_id'] : $storeData['code'];
                    $store = $this->storeViewModel($storeIdentifier, false);

                    $store->setIsActive(true);
                    $store->addData($storeData);
                    $store->setGroup($group);
                    $store->setWebsite($website);
                    $store->save();

                    $this->creatuity()->report()->printSuccess("    Store '{$store->getName()}' done");

                    if (!empty($storeData['is_default'])) {
                        if ($defaultStore) {
                            throw new \Exception("You have two default views for group {$groupData['code']} ");
                        }
                        $defaultStore = $store;
                    }
                }

                if ($defaultStore) {
                    $group->setDefaultStoreId($defaultStore->getId());
                    $group->save();

                    $this->creatuity()->report()->printSuccess("    Store '{$defaultStore->getName()}' set as default in group '{$group->getName()}'");
                }
            }

            if ($defaultGroup) {
                $website->setDefaultGroupId($defaultGroup->getId());
                $website->save();

                $this->creatuity()->report()->printSuccess("  Group '{$defaultGroup->getName()}' set as default in website '{$website->getName()}'");
            }
        }

        $this->creatuity()->report()->printMessage("Stores setup done.");
    }

    /**
     * @return Website
     */
    public function addWebsite($websiteName, $websiteCode, $newStoreGroupName, $newViewName, $newViewCode, $rootCategoryId = self::DEFAULT_ROOT_CATEGORY, $sortOrder = 0, $isDefault = 0)
    {
        $website = $this->websiteFactory->create();

        $website->setName($websiteName)
            ->setCode($websiteCode)
            ->setSortOrder($sortOrder)
            ->setIsDefault($isDefault)
            ->save();

        $store = $this->addStoreGroup($newStoreGroupName, $newViewName, $newViewCode, $website->getId(), $rootCategoryId);

        $website->setDefaultGroupId($store->getId())->save();

        $this->creatuity()->report()->printSuccess("Website '$websiteName' created'");

        return $website;
    }

    /**
     * @return Group
     */
    public function addStoreGroup($name, $defaultViewName, $defaultViewCode, $websiteNameOrCodeOrId, $rootCategoryId = self::DEFAULT_ROOT_CATEGORY)
    {
        $store = $this->storeFactory->create();

        $store->setName($name)
            ->setWebsiteId($this->websiteModel($websiteNameOrCodeOrId)->getWebsiteId())
            ->setRootCategoryId($rootCategoryId)
            ->save();

        $this->creatuity()->setting()->save('catalog/category/root_id', $rootCategoryId, 'stores', $store->getId());

        $storeView = $this->addStoreView($defaultViewName, $defaultViewCode, $websiteNameOrCodeOrId, $store->getId());

        $store->setDefaultStoreId($storeView->getId())->save();

        $this->creatuity()->report()->printSuccess("Store '$name' created'");

        return $store;
    }

    /**
     * @return StoreViewModel
     */
    public function addStoreView($name, $code, $websiteNameOrCodeOrId, $storeGroupNameOrId)
    {
        $storeView = $this->storeViewModel($code, false);

        $storeView->setName($name)
            ->setWebsiteId($this->websiteModel($websiteNameOrCodeOrId)->getId())
            ->setGroupId($this->storeGroupModel($storeGroupNameOrId)->getId())
            ->setIsActive(true)
            ->setCode($code)
            ->save();

        $this->eventManager->dispatch('store_add', [ 'store' => $storeView ]);

        $this->creatuity()->report()->printSuccess("Store View '$name' created'");

        return $storeView;
    }

    /**
     * @return bool
     */
    public function hasWebsite($codeOrNameOrId)
    {
        return $this->isExists( $this->websiteModel($codeOrNameOrId, false) );
    }

    /**
     * @return Website
     */
    public function defaultWebsiteModel()
    {
        return $this->websiteModel(StoreViewModel::DEFAULT_STORE_ID, true);
    }

    /**
     * @return bool
     */
    public function hasStoreGroup($codeOrNameOrId)
    {
        return $this->isExists( $this->storeGroupModel($codeOrNameOrId, false) );
    }

    /**
     * @return Group
     */
    public function defaultStoreGroupModel()
    {
        return $this->storeGroupModel(0);
    }

    /**
     * @return bool
     */
    public function hasStoreView($codeOrNameOrId)
    {
        return $this->isExists( $this->storeViewModel($codeOrNameOrId, false) );
    }

    /**
     * @return StoreViewModel
     */
    public function defaultStoreViewModel()
    {
        return $this->storeViewModel(1);
    }

    /**
     * @return Website
     */
    public function websiteModel($codeOrNameOrId, $mustExists = true)
    {
        $website = $this->websiteFactory->create()->load($codeOrNameOrId, is_numeric($codeOrNameOrId) ? null : 'code');
        if (!$this->isExists($website)) {
            $website = $this->websiteFactory->create()->load($codeOrNameOrId, 'name');
        }

        if ($mustExists && !$this->isExists($website)) {
            throw new \Exception("I couldn't find website given by '{$codeOrNameOrId}'");
        }
        return $website;
    }

    /**
     * @return Group
     */
    public function storeGroupModel($codeOrNameOrId, $mustExists = true)
    {
        $storeGroup = $this->storeFactory->create()->load($codeOrNameOrId, is_numeric($codeOrNameOrId) ? null : 'code');
        if ( !$this->isExists($storeGroup) ) {
            $storeGroup = $this->storeFactory->create()->load($codeOrNameOrId, 'name');
        }
        if ($mustExists && !$this->isExists($storeGroup)) {
            throw new \Exception("I couldn't find store group given by '{$codeOrNameOrId}'");
        }
        return $storeGroup;
    }

    /**
     * @return StoreViewModel
     */
    public function storeViewModel($codeOrNameOrId, $mustExists = true)
    {
        $storeView = $this->storeViewFactory->create()->load($codeOrNameOrId, is_numeric($codeOrNameOrId) ? null : 'code');
        if (!$this->isExists($storeView)) {
            $storeView = $this->storeViewFactory->create()->load($codeOrNameOrId, 'name');
        }
        if ($mustExists && !$this->isExists($storeView)) {
            throw new \Exception("I couldn't find store view given by '{$codeOrNameOrId}'");
        }
        return $storeView;
    }

    /**
     * @return bool
     */
    protected function isExists(AbstractModel $model)
    {
        return is_numeric($model->getId());
    }
}