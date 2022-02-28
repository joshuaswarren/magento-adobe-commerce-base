<?php


namespace Creatuity\Base\Model\Catalog\CategoriesModifier;


use Creatuity\Base\Helpers\Creatuity;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Eav\Api\AttributeSetRepositoryInterface;
use Magento\Eav\Api\Data\AttributeSetInterface as AttributeSetInterfaceAlias;
use Magento\Eav\Model\Config;
use Magento\Framework\Api\SearchCriteriaInterfaceFactory;
use Magento\Framework\DataObject as DataObjectAlias;

class CatalogHelper
{

    /**
     * @var CollectionFactory
     */
    protected $categoryCollectionFactory;
    /**
     * @var Creatuity
     */
    protected $creatuity;
    /**
     * @var Config
     */
    protected $eavConfig;
    /**
     * @var AttributeSetRepositoryInterface
     */
    protected $attributeSetRepository;
    /**
     * @var SearchCriteriaInterfaceFactory
     */
    protected $searchCriteriaInterfaceFactory;

    /**
     * @var AttributeRepositoryInterface
     */
    protected $attributeRepository;
    /**
     * @var array
     */
    protected $scopedAttributesCodes = null;
    

    public function __construct(
        CollectionFactory $categoryCollectionFactory,
        AttributeRepositoryInterface $attributeRepository,
        Config $eavConfig,
        AttributeSetRepositoryInterface $attributeSetRepository,
        SearchCriteriaInterfaceFactory $searchCriteriaInterfaceFactory,
        Creatuity $creatuity
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->creatuity = $creatuity;
        $this->eavConfig = $eavConfig;
        $this->attributeSetRepository = $attributeSetRepository;
        $this->searchCriteriaInterfaceFactory = $searchCriteriaInterfaceFactory;
        $this->attributeRepository = $attributeRepository;
    }

    public function defaultAttributeSetId()
    {
        foreach($this->allAttributeSets() as $attributeSet) {
            return $attributeSet->getId();
        }
        return null;
    }

    /**
     * @return AttributeSetInterfaceAlias[]
     */
    protected function allAttributeSets()
    {
        $categoryEavTypeId = $this->eavConfig->getEntityType(Category::ENTITY)->getEntityTypeId();
        $allAttributeSets = $this->attributeSetRepository->getList($this->searchCriteriaInterfaceFactory->create());

        $ret = [];
        foreach($allAttributeSets->getItems() as $attributeSet) {
            if ($attributeSet->getEntityTypeId() == $categoryEavTypeId) {
                $ret[$attributeSet->getId()] = $attributeSet;
            }
        }
        return $ret;
    }


    /**
     * @return bool
     */
    public function hasCategoryHavingId($id)
    {
        $found = $this->categoryCollectionFactory->create()
            ->addIdFilter([$id]);
        return !$found->getFirstItem()->isEmpty();
    }

    /**
     * @return DataObjectAlias[]
     */
    public function categoriesInTopologicalOrder($reversedOrder = false, $fields = [])
    {
        $collection = $this->categoryCollectionFactory->create();
        foreach($fields as $field) {
            $collection->addAttributeToSelect($field);
        }

        $categories = $collection->getItems();
        usort($categories, function ($categoryA, $categoryB) {
            return substr_count($categoryA->getPath(), '/')
                - substr_count($categoryB->getPath(), '/');
        });

        if ($reversedOrder) {
            return array_reverse($categories);
        }
        return $categories;
    }

    /**
     * @return int[]
     */
    public function determineRootCategoryIds()
    {
        $rootCategories = $this->categoryCollectionFactory->create()
            ->addFieldToFilter('level', 1);

        $ids = [];
        foreach($rootCategories as $rootCategory) {
            $ids[$rootCategory->getId()] = $rootCategory->getId();
        }
        return $ids;
    }

    /**
     * @return int|null
     */
    public function determineTopCategoryId()
    {
        $topCategories = $this->categoryCollectionFactory->create()
            ->addFieldToFilter('level', 0);

        if (!$topCategories->getFirstItem()) {
            return null;
        }
        return $topCategories->getFirstItem()->getId();
    }

    /**
     * @return array
     */
    public function designateDefaultAttributesCode(array $listOfCodesToChangeOnParticularScope)
    {
        $defaults = array_diff_key($this->obtainScopeCategoryAttributesCode(), $listOfCodesToChangeOnParticularScope);

        return array_combine(array_keys($defaults), array_fill(0, count($defaults), 1));
    }


    /**
     * @return array
     */
    public function obtainScopeCategoryAttributesCode()
    {
        if ( !$this->scopedAttributesCodes ) {
            $attributesList = $this->attributeRepository->getList(Category::ENTITY, $this->searchCriteriaInterfaceFactory->create());

            $this->scopedAttributesCodes = [];
            foreach ( $attributesList as $attribute ) {
                if ( !$attribute->isScopeGlobal() ) {
                    $this->scopedAttributesCodes[$attribute->getAttributeCode()] = null;
                }
            }
        }
        return $this->scopedAttributesCodes;
    }


}