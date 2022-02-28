<?php

namespace Creatuity\Base\ImportExport\Core\Importer;

/*
 * Core have a.. let say performance bug in core.
 *
 *
 *
 * @package Project
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
use Magento\CatalogImportExport\Model\Import\Product as ImportProduct;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;

class AdvancedPricing extends \Magento\AdvancedPricingImportExport\Model\Import\AdvancedPricing
{

    protected function saveProductPrices(array $priceData, $table)
    {
        $this->countItemsUpdated += sizeof($priceData);

        return parent::saveProductPrices($priceData, $table);
    }

    protected function processCountExistingPrices($prices, $table)
    {
        // this method is super slow for big amount of data, so we're maintaining $this->countItemsUpdated ourselves
        return $this;
    }

    protected function processCountNewPrices(array $tierPrices)
    {
        // this method is super slow for big amount of data, so we're maintaining $this->countItemsUpdated ourselves
        return $this;
    }

}
