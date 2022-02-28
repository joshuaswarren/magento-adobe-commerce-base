<?php

namespace Creatuity\Base\ImportExport\Core;

use Creatuity\Base\Helpers\Creatuity;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingError;

/**
 * @package m2newbuild
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class CliCsvImporter
{

    /**
     * @var CoreCsvImporterFactory
     */
    protected $coreCsvImporterFactory;

    /**
     * @var CoreCsvImporter
     */
    protected $coreCsvImporter;

    /**
     * @var Creatuity
     */
    protected $helper;

    public function __construct(CoreCsvImporterFactory $coreCsvImporterFactory, Creatuity $helper)
    {
        $this->coreCsvImporterFactory = $coreCsvImporterFactory;
        $this->helper = $helper;
    }


    public function importProductsFromFile($pathToFile, $multipleCategorySeparator = ',', $importImagesDir = '', $method = 'append')
    {
        $this->helper->runInSecuredArea(function () use ($pathToFile, $multipleCategorySeparator, $importImagesDir, $method) {
            $this->coreCsvImporter()->importProductsFromFile($pathToFile, $multipleCategorySeparator, $importImagesDir, $method);
        });
    }

    public function importProductPricesFromFile($pathToFile, $method = 'append')
    {
        $this->helper->runInSecuredArea(function () use ($pathToFile, $method) {
            $this->coreCsvImporter()->importProductPricesFromFile($pathToFile, $method);
        });
    }

    public function importStockSourcesFromFile($pathToFile, $method = 'append')
    {
        $this->helper->runInSecuredArea(function () use ($pathToFile, $method) {
            $this->coreCsvImporter()->importStockSourcesFromFile($pathToFile, $method);
        });
    }

    public function importCustomersMainFromFile($pathToFile, $method = 'append')
    {
        $this->helper->runInSecuredArea(function () use ($pathToFile, $method) {
            $this->coreCsvImporter()->importCustomersMainFromFile($pathToFile, $method);
        });
    }

    public function importCustomersCompositeFromFile($pathToFile, $method = 'append')
    {
        $this->helper->runInSecuredArea(function () use ($pathToFile, $method) {
            $this->coreCsvImporter()->importCustomersCompositeFromFile($pathToFile, $method);
        });
    }

    public function importCustomersAddressesFromFile($pathToFile, $method = 'append')
    {
        $this->helper->runInSecuredArea(function () use ($pathToFile, $method) {
            $this->coreCsvImporter()->importCustomersAddressesFromFile($pathToFile, $method);
        });
    }

    public function summary()
    {
        return $this->coreCsvImporter()->summary();
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return $this->coreCsvImporter()->hasErrors();
    }

    /**
     * @return ProcessingError[]
     */
    public function getErrors()
    {
        return $this->coreCsvImporter()->getErrors();
    }

    /**
     * @return CoreCsvImporter
     */
    protected function coreCsvImporter()
    {
        if ($this->coreCsvImporter === null) {
            $this->coreCsvImporter = $this->coreCsvImporterFactory->create();
        }

        return $this->coreCsvImporter;
    }


}