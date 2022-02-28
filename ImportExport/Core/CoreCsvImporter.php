<?php

namespace Creatuity\Base\ImportExport\Core;

use Creatuity\Base\Helpers\Creatuity;
use Creatuity\Base\ImportExport\Plugin\ImportExportStoragePlugin;
use Magento\Catalog\Model\Product as CatalogProduct;
use Magento\Customer\Model\Customer as MagentoCoreCustomer;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem\Directory\ReadFactory;
use Magento\Framework\Phrase;
use Magento\ImportExport\Helper\Data;
use Magento\ImportExport\Model\Import;
use Magento\ImportExport\Model\Import\AbstractEntity;
use Creatuity\Base\ImportExport\Core\Product as OurCoreProduct;
use Creatuity\Base\ImportExport\Core\Customer as OurCoreCustomer;
use Creatuity\Base\ImportExport\Core\CustomerAddressAdapter as OurCoreCustomerAddress;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingError;


/**
 * @package Project
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class CoreCsvImporter extends Import
{
    protected $entitiesConfig = [
        'catalog_product' => [
            'model' => OurCoreProduct::class,
        ],
        'customer' => [
            'model' => OurCoreCustomer::class,
        ],
        'customer_address' => [
            'model' => OurCoreCustomerAddress::class,
        ],
    ];

    protected $_debugMode = true;

    /**
     * @var Creatuity
     */
    protected $helper;

    /**
     * @var ImportExportStoragePlugin
     */
    protected $importExportStoragePlugin;

    /**
     * @var ReadFactory
     */
    protected $readFactory;

    /**
     * @var DirectoryList
     */
    protected $directoryList;
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    private $output;


    public function __construct(
        Creatuity $helper,
        ImportExportStoragePlugin $importExportStoragePlugin,
        ReadFactory $readFactory,
        DirectoryList $directoryList,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Filesystem $filesystem,
        Data $importExportData,
        \Magento\Framework\App\Config\ScopeConfigInterface $coreConfig,
        Import\ConfigInterface $importConfig,
        Import\Entity\Factory $entityFactory,
        \Magento\ImportExport\Model\ResourceModel\Import\Data $importData,
        \Magento\ImportExport\Model\Export\Adapter\CsvFactory $csvFactory,
        \Magento\Framework\HTTP\Adapter\FileTransferFactory $httpFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\ImportExport\Model\Source\Import\Behavior\Factory $behaviorFactory,
        \Magento\Framework\Indexer\IndexerRegistry $indexerRegistry,
        \Magento\ImportExport\Model\History $importHistoryModel,
        \Magento\Framework\Stdlib\DateTime\DateTime $localeDate,
        \Symfony\Component\Console\Output\OutputInterface $output,
        array $data = []
    )
    {
        parent::__construct($logger, $filesystem, $importExportData, $coreConfig, $importConfig, $entityFactory, $importData, $csvFactory, $httpFactory, $uploaderFactory, $behaviorFactory, $indexerRegistry, $importHistoryModel, $localeDate, $data);

        $this->helper = $helper;
        $this->importExportStoragePlugin = $importExportStoragePlugin;
        $this->readFactory = $readFactory;
        $this->directoryList = $directoryList;
        $this->output = $output;
    }


    public function importProductsFromFile($pathToFile, $multipleCategorySeparator = ',', $importImagesDir = '', $method = 'append')
    {
        $this->performImportOfEntitiesFromFile(200, $pathToFile, [
            'entity' => CatalogProduct::ENTITY,
            'behavior' => $method,
            'validation_strategy' => 'validation-stop-on-errors',
            'allowed_error_count' => 0,
            'multiple_value_category_separator' => $multipleCategorySeparator,
            'import_images_file_dir' => $importImagesDir,
        ]);

        return $this;
    }

    public function importProductPricesFromFile($pathToFile, $method = 'append')
    {
        $this->performImportOfEntitiesFromFile(15000, $pathToFile, [
            'entity' => 'advanced_pricing',
            'behavior' => $method,
            'validation_strategy' => 'validation-stop-on-errors',
            'allowed_error_count' => 0,
        ]);

        return $this;
    }

    public function importStockSourcesFromFile($pathToFile, $method = 'append')
    {
        $this->performImportOfEntitiesFromFile(200, $pathToFile, [
            'entity' => 'stock_sources',
            'behavior' => $method,
            'validation_strategy' => 'validation-stop-on-errors',
            'allowed_error_count' => 0,
        ]);

        return $this;
    }

    public function importCustomersMainFromFile($pathToFile, $method = 'append')
    {
        $this->performImportOfEntitiesFromFile(null, $pathToFile, [
            'entity' => MagentoCoreCustomer::ENTITY,
            'behavior' => $method,
            'validation_strategy' => 'validation-stop-on-errors',
            'allowed_error_count' => 0,
        ]);

        return $this;
    }

    public function importCustomersCompositeFromFile($pathToFile, $method = 'append')
    {
        $this->performImportOfEntitiesFromFile(null, $pathToFile, [
            'entity' => 'customer_composite',
            'behavior' => $method,
            'validation_strategy' => 'validation-stop-on-errors',
            'allowed_error_count' => 0,
        ]);

        return $this;
    }

    public function importCustomersAddressesFromFile($pathToFile, $method = 'append')
    {
        $this->performImportOfEntitiesFromFile(null, $pathToFile, [
            'entity' => 'customer_address',
            'behavior' => $method,
            'validation_strategy' => 'validation-stop-on-errors',
            'allowed_error_count' => 0,
        ]);

        return $this;
    }

    protected function performImportOfEntitiesFromFile($batchSize, $pathToFile, array $data)
    {
        $relativePath = $this->absFileToRelative($pathToFile);

        $toRun = function () use ($relativePath, $data) {
            $this->addData($data);

            return $this->importEntitiesFromFile($relativePath);
        };

        if ($batchSize > 0) {
            $settings = [
                AbstractEntity::XML_PATH_BUNCH_SIZE => $batchSize,
                Data::XML_PATH_BUNCH_SIZE => $batchSize,
            ];

            return $this->helper->runWithConfigMany($settings, $toRun);
        }

        return $toRun();
    }

    protected function importEntitiesFromFile($pathToFile)
    {
        try {
            $this->importExportStoragePlugin->openDumping();

            $hasContent = trim($this->_filesystem
                ->getDirectoryRead(DirectoryList::ROOT)
                ->readFile($pathToFile));

            if (!$hasContent) {
                return $this;
            }

            $relPathToFile = $this->_filesystem->getDirectoryRead(DirectoryList::ROOT)
                ->getRelativePath($pathToFile);

            $source = $this->_getSourceAdapter($relPathToFile);

            $this->validateSource($source);

            $this->ensureThereIsNoErrors();

            $this->importSource();

            $this->ensureThereIsNoErrors();

            return $this;
        } finally {
            $this->importExportStoragePlugin->closeDumping();
        }
    }

    protected function ensureThereIsNoErrors()
    {
        $errors = [];
        foreach ( $this->getErrorAggregator()->getAllErrors() as $validationError ) {
            $errors[] = sprintf('Error in %s line: %s', $validationError->getRowNumber(), $validationError->getErrorMessage());
        }

        if ( $errors ) {
            throw new \Exception('Import error(s): ' . PHP_EOL . implode(PHP_EOL, $errors));
        }
        return $this;
    }

    public function summary()
    {
        $summary = '';

        if ($this->_getEntityAdapter()->getErrorAggregator()->hasToBeTerminated()) {
            $summary .= "Status: Terminated\n";
        } else {
            $summary .= "Status: SUCCESS\n";
        }

        foreach ($this->_getEntityAdapter()->getErrorAggregator()->getAllErrors() as $error) {
            if ($error->getColumnName()) {
                $summary .= sprintf("Error at %s row, column %s: %s\n", $error->getRowNumber(), $error->getColumnName(), $error->getErrorMessage());
            } else {
                $summary .= sprintf("Error at %s row: %s\n", $error->getRowNumber(), $error->getErrorMessage());
            }
        }

        return $summary;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return true
            && $this->_getEntityAdapter()
            && $this->_getEntityAdapter()->getErrorAggregator()
            && !empty($this->_getEntityAdapter()->getErrorAggregator()->getAllErrors());
    }

    /**
     * @return ProcessingError[]
     */
    public function getErrors()
    {
        if (!$this->hasErrors()) {
            return [];
        }

        return $this->_getEntityAdapter()->getErrorAggregator()->getAllErrors();
    }

    public function addLogComment($debugData)
    {
        if ($this->_debugMode) {
            if (!is_array($debugData)) {
                $debugData = [$debugData];
            }
            foreach ($debugData as $item) {
                if ($item instanceof Phrase
                    || !is_array($item)
                ) {
                    $this->output->writeln('Core Importer: ' . $item);
                } else {
                    $this->output->writeln('Core Importer: ' . var_export($item));
                }
            }
        }

        return parent::addLogComment($debugData);
    }

    protected function _getEntityAdapter()
    {
        if (!$this->_entityAdapter) {
            // CREATUITY: start
            $entities = $this->loadEntities();
            // CREATUITY: end
            if (isset($entities[ $this->getEntity() ])) {
                try {
                    $this->_entityAdapter = $this->_entityFactory->create($entities[ $this->getEntity() ]['model']);
                } catch (\Exception $e) {
                    $this->_logger->critical($e);
                    throw new LocalizedException(
                        __('Please enter a correct entity model.') /* CREATUITY: begin */, $e /* CREATUITY: end */
                    );
                }
                if (!$this->_entityAdapter instanceof \Magento\ImportExport\Model\Import\Entity\AbstractEntity &&
                    !$this->_entityAdapter instanceof AbstractEntity
                ) {
                    throw new LocalizedException(
                        __(
                            'The entity adapter object must be an instance of %1 or %2.',
                            'Magento\ImportExport\Model\Import\Entity\AbstractEntity',
                            'Magento\ImportExport\Model\Import\AbstractEntity'
                        )
                    );
                }

                // check for entity codes integrity
                if ($this->getEntity() != $this->_entityAdapter->getEntityTypeCode()) {
                    throw new LocalizedException(
                        __('The input entity code is not equal to entity adapter code.')
                    );
                }
            } else {
                throw new LocalizedException(__('Please enter a correct entity.'));
            }
            $this->_entityAdapter->setParameters($this->getData());
        }

        return $this->_entityAdapter;
    }

    protected function loadEntities()
    {
        return array_replace_recursive($this->_importConfig->getEntities(), $this->entitiesConfig);
    }

    protected function absFileToRelative($absPath)
    {
        if (!empty($_SERVER['HOME'])) {
            $absPath = str_replace('~', $_SERVER['HOME'], $absPath);
        }
        $rootPath = $this->directoryList->getRoot();

        if ($this->readFactory->create($rootPath)->isExist($absPath)) {
            return $absPath;
        }

        $prefix = str_repeat("/..", substr_count($rootPath, '/')) . '/';

        return $prefix . $absPath;
    }
}
