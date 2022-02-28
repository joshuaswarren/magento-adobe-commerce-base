<?php
namespace Creatuity\Base\ImportExport\Core;

use Creatuity\Base\Helpers\Database;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;

class Customer extends \Magento\CustomerImportExport\Model\Import\Customer
{
    /**
     * @var Database
     */
    protected $databaseHelper;

    protected $counter = 0;
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    private $output;


    public function __construct(
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\ImportExport\Model\ImportFactory $importFactory,
        \Magento\ImportExport\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\App\ResourceConnection $resource,
        ProcessingErrorAggregatorInterface $errorAggregator,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\ImportExport\Model\Export\Factory $collectionFactory,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\CustomerImportExport\Model\ResourceModel\Import\Customer\StorageFactory $storageFactory,
        \Magento\Customer\Model\ResourceModel\Attribute\CollectionFactory $attrCollectionFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        Database $databaseHelper,
        \Symfony\Component\Console\Output\OutputInterface $output,
        array $data = []
        )
    {
        parent::__construct($string, $scopeConfig, $importFactory, $resourceHelper, $resource, $errorAggregator, $storeManager, $collectionFactory, $eavConfig, $storageFactory, $attrCollectionFactory, $customerFactory, $data);
        
        $this->databaseHelper = $databaseHelper;
        $this->output = $output;
    }


    protected function _saveCustomerEntities(array $entitiesToCreate, array $entitiesToUpdate)
    {
        $this->output->writeln("Progress: " . ($this->counter += sizeof($entitiesToCreate) + sizeof($entitiesToUpdate)));
        return parent::_saveCustomerEntities($this->databaseHelper->normalizeDataSetForMultipleInsert($entitiesToCreate), $entitiesToUpdate);
    }
}