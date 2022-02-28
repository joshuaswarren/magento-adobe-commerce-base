<?php

namespace Creatuity\Base\ImportExport\Core\CustomerAddress;

use Magento\Customer\Model\ResourceModel\Address\Attribute\Source\CountryWithWebsites as CountryWithWebsitesSource;
use Magento\CustomerImportExport\Model\Import\Address;
use Magento\CustomerImportExport\Model\ResourceModel\Import\Address\Storage as AddressStorage;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
class From23 extends Address
{
    /**
     * @var Logic
     */
    protected $logic;

    public function __construct(
        Logic $logic,
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
        \Magento\Customer\Model\AddressFactory $addressFactory,
        \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regionColFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Model\ResourceModel\Address\Attribute\CollectionFactory $attributesFactory,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\Customer\Model\Address\Validator\Postcode $postcodeValidator,
        array $data = [],
        CountryWithWebsitesSource $countryWithWebsites = null,
        AddressStorage $addressStorage = null
    ) {
        parent::__construct($string, $scopeConfig, $importFactory, $resourceHelper, $resource, $errorAggregator, $storeManager, $collectionFactory, $eavConfig, $storageFactory, $addressFactory, $regionColFactory, $customerFactory, $attributesFactory, $dateTime, $postcodeValidator, $data, $countryWithWebsites, $addressStorage);
        $this->logic = $logic;
    }

    protected function _saveAddressEntities(array $addRows, array $updateRows)
    {
        list($addRows, $updateRows) = $this->logic->process($addRows, $updateRows);
        return parent::_saveAddressEntities($addRows, $updateRows);
    }
}