<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Creatuity\Base\Helpers\Creatuity;
use Magento\Framework\Indexer\IndexerInterface;
use Magento\Indexer\Model\Processor;
use Magento\Indexer\Model\Indexer\CollectionFactory;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class Indexer extends SubjectAbstract
{
    private CollectionFactory $indexesCollectionFactory;
    private Processor $indexer;

    /**
     * @var IndexerInterface[]
     */
    private array $indexes;

    public function __construct(Creatuity $creatuity, Processor $indexer, CollectionFactory $indexesCollectionFactory)
    {
        parent::__construct($creatuity);
        $this->indexer = $indexer;
        $this->indexesCollectionFactory = $indexesCollectionFactory;
    }


    public function reindexAll(): self
    {
        $this->creatuity()->report()->printMessage("Reindexing....");

        $this->indexer->reindexAll();

        $this->creatuity()->report()->printMessage("Reindexing done.");
    }

    public function reindexAllInvalid(): self
    {
        $this->creatuity()->report()->printMessage("Reindexing invalid....");

        $this->indexer->reindexAllInvalid();

        $this->creatuity()->report()->printMessage("Reindexing done.");
    }

    public function reindexDesignConfigGrid(): self
    {
        return $this->reindexSelected(['design_config_grid']);
    }

    public function reindexCustomerGrid(): self
    {
        return $this->reindexSelected(['design_config_grid']);
    }

    public function reindexCatalogCategoryProduct(): self
    {
        return $this->reindexSelected(['catalog_category_product']);
    }

    public function reindexCatalogProductCategory(): self
    {
        return $this->reindexSelected(['catalog_product_category']);
    }

    public function reindexCatalogProductPrice(): self
    {
        return $this->reindexSelected(['catalog_product_price']);
    }

    public function reindexCatalogProductAttribute(): self
    {
        return $this->reindexSelected(['catalog_product_attribute']);
    }

    public function reindexCatalogInventoryStock(): self
    {
        return $this->reindexSelected(['cataloginventory_stock']);
    }

    public function reindexCatalogRuleRule(): self
    {
        return $this->reindexSelected(['catalogrule_rule']);
    }

    public function reindexCatalogRuleProduct(): self
    {
        return $this->reindexSelected(['catalogrule_product']);
    }

    public function reindexCatalogSearchFulltext(): self
    {
        return $this->reindexSelected(['catalogsearch_fulltext']);
    }

    public function reindexTargetRuleProductRule(): self
    {
        return $this->reindexSelected(['targetrule_product_rule']);
    }

    public function reindexTargetRuleRuleProduct(): self
    {
        return $this->reindexSelected(['targetrule_rule_product']);
    }

    public function reindexSalesRuleRule(): self
    {
        return $this->reindexSelected(['salesrule_rule']);
    }

    public function reindexSelected(array $listOfIndexesIds): self
    {
        foreach (array_intersect_key($this->loadIndexes(), array_flip($listOfIndexesIds)) as $index ) {
            /** @var IndexerInterface $index */
            $this->creatuity()->report()->printMessage(sprintf('Reindex of %s started', $index->getId()));
            try {
                $index->reindexAll();
            } catch (\Exception $e) {
                $this->creatuity()->report()->printError('Reindex of ' . $index->getId() . ' failed.');
                $this->creatuity()->report()->printError($e);
                continue;
            }

            $this->creatuity()->report()->printSuccess(sprintf('Reindex of %s done.', $index->getId()));
        }
        return $this;
    }

    /**
     * @return IndexerInterface[]
     */
    private function loadIndexes(): array
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
