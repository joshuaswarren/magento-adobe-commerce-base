<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Creatuity\Base\Helpers\Creatuity;
use Magento\Framework\Indexer\IndexerInterface;
use Magento\Indexer\Model\Processor;
use Magento\Indexer\Model\Indexer\CollectionFactory;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
class Indexer extends SubjectAbstract
{
    /**
     * @var CollectionFactory
     */
    protected $indexesCollectionFactory;

    /**
     * @var Processor
     */
    protected $indexer;

    /**
     * @var IndexerInterface[]
     */
    protected $indexes;

    public function __construct(Creatuity $creatuity, Processor $indexer, CollectionFactory $indexesCollectionFactory)
    {
        parent::__construct($creatuity);
        $this->indexer = $indexer;
        $this->indexesCollectionFactory = $indexesCollectionFactory;
    }


    public function reindexAll()
    {
        $this->creatuity()->report()->printMessage("Reindexing....");

        $this->indexer->reindexAll();

        $this->creatuity()->report()->printMessage("Reindexing done.");
    }

    public function reindexAllInvalid()
    {
        $this->creatuity()->report()->printMessage("Reindexing invalid....");

        $this->indexer->reindexAllInvalid();

        $this->creatuity()->report()->printMessage("Reindexing done.");
    }

    public function reindexDesignConfigGrid()
    {
        return $this->reindexSelected(['design_config_grid']);
    }

    public function reindexCustomerGrid()
    {
        return $this->reindexSelected(['design_config_grid']);
    }

    public function reindexCatalogCategoryProduct()
    {
        return $this->reindexSelected(['catalog_category_product']);
    }

    public function reindexCatalogProductCategory()
    {
        return $this->reindexSelected(['catalog_product_category']);
    }

    public function reindexCatalogProductPrice()
    {
        return $this->reindexSelected(['catalog_product_price']);
    }

    public function reindexCatalogProductAttribute()
    {
        return $this->reindexSelected(['catalog_product_attribute']);
    }

    public function reindexCatalogInventoryStock()
    {
        return $this->reindexSelected(['cataloginventory_stock']);
    }

    public function reindexCatalogRuleRule()
    {
        return $this->reindexSelected(['catalogrule_rule']);
    }

    public function reindexCatalogRuleProduct()
    {
        return $this->reindexSelected(['catalogrule_product']);
    }

    public function reindexCatalogSearchFulltext()
    {
        return $this->reindexSelected(['catalogsearch_fulltext']);
    }

    public function reindexTargetRuleProductRule()
    {
        return $this->reindexSelected(['targetrule_product_rule']);
    }

    public function reindexTargetRuleRuleProduct()
    {
        return $this->reindexSelected(['targetrule_rule_product']);
    }

    public function reindexSalesRuleRule()
    {
        return $this->reindexSelected(['salesrule_rule']);
    }

    public function reindexSelected(array $listOfIndexesIds)
    {
        foreach (array_intersect_key($this->loadIndexes(), array_flip($listOfIndexesIds)) as $index ) {
            /** @var IndexerInterface $index */
            $this->creatuity()->report()->printMessage(sprintf('Reindex of %s started', $index->getId()));
            $index->reindexAll();
            $this->creatuity()->report()->printSuccess(sprintf('Reindex of %s done.', $index->getId()));
        }
        return $this;
    }

    /**
     * @return IndexerInterface[]
     */
    protected function loadIndexes()
    {
        if ( !$this->indexes ) {
            foreach ( $this->indexesCollectionFactory->create()->getItems() as $index ) {
                /** @var IndexerInterface $index */
                $this->indexes[$index->getId()] = $index;
            }

        }
        return $this->indexes;
    }
}