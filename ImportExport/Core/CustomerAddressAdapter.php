<?php

namespace Creatuity\Base\ImportExport\Core;

use Creatuity\Base\Model\MagentoVersion;
use Magento\CustomerImportExport\Model\Import\Address;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magento\Framework\App\ObjectManager;
use Magento\ImportExport\Model\Import;
use Magento\ImportExport\Model\Import\AbstractSource;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingError;

/**
 * @category Creatuity
 * @package intb2
 * @copyright Copyright (c) 2008-{2016} Joshua Warren (https://warrenappliedlabs.com)
 * @license https://warrenappliedlabs.com/license
 */
class CustomerAddressAdapter extends Address
{
    /**
     * @return Address
     */
    protected $adapted;

    /**
     * @return string
     */
    protected $magentoVersion;

    /**
     * @return Address
     */
    protected function adapted()
    {
        if ( !$this->adapted ) {
            if ( MagentoVersion::instance()->ifLowerThan('2.3') ) {
                $this->adapted = $this->includeAndLoad('Earlier23');
            } else {
                $this->adapted = $this->includeAndLoad('From23');
            }
        }
        return $this->adapted;
    }

    /**
     * @param string $versionClass
     * @return Address
     */
    protected function includeAndLoad($versionClass)
    {
        require_once('CustomerAddress' . DIRECTORY_SEPARATOR . $versionClass . '.inc');
        return ObjectManager::getInstance()->get('\Creatuity\Base\ImportExport\Core\CustomerAddress\\' . $versionClass);
    }

    public function validateRow(array $rowData, $rowNumber)
    {
        return $this->adapted()->validateRow($rowData, $rowNumber);
    }

    public function getCustomerStorage()
    {
        return $this->adapted()->getCustomerStorage(); 
    }

    public function getWebsiteId($websiteCode)
    {
        return $this->adapted()->getWebsiteId($websiteCode); 
    }

    public function getEntityTypeId()
    {
        return $this->adapted()->getEntityTypeId(); 
    }

    public function getAttributeCollection()
    {
        return $this->adapted()->getAttributeCollection(); 
    }

    public function getErrorAggregator()
    {
        return $this->adapted()->getErrorAggregator(); 
    }

    public function addRowError($errorCode,
                                $errorRowNum,
                                $colName = null,
                                $errorMessage = null,
                                $errorLevel = ProcessingError::ERROR_LEVEL_CRITICAL,
                                $errorDescription = null)
    {
        return $this->adapted()->addRowError($errorCode, $errorRowNum, $colName, $errorMessage, $errorLevel, $errorDescription); 
    }

    public function addMessageTemplate($errorCode, $message)
    {
        return $this->adapted()->addMessageTemplate($errorCode, $message); 
    }

    public function getBehavior(array $rowData = null)
    {
        return $this->adapted()->getBehavior($rowData); 
    }

    public static function getDefaultBehavior()
    {
        return \Magento\CustomerImportExport\Model\Import\Address::getDefaultBehavior();
    }

    public function getProcessedEntitiesCount()
    {
        return $this->adapted()->getProcessedEntitiesCount(); 
    }

    public function getProcessedRowsCount()
    {
        return $this->adapted()->getProcessedRowsCount(); 
    }

    public function getSource()
    {
        return $this->adapted()->getSource(); 
    }

    public function importData()
    {
        return $this->adapted()->importData(); 
    }

    public function isAttributeParticular($attributeCode)
    {
        return $this->adapted()->isAttributeParticular($attributeCode); 
    }

    public function getMasterAttributeCode()
    {
        return $this->adapted()->getMasterAttributeCode(); 
    }

    public function isAttributeValid($attributeCode,
                                     array $attributeParams,
                                     array $rowData,
                                     $rowNumber,
                                     $multiSeparator = Import::DEFAULT_GLOBAL_MULTI_VALUE_SEPARATOR)
    {
        return $this->adapted()->isAttributeValid($attributeCode, $attributeParams, $rowData, $rowNumber, $multiSeparator); 
    }

    public function isImportAllowed()
    {
        return $this->adapted()->isImportAllowed(); 
    }

    public function isRowAllowedToImport(array $rowData, $rowNumber)
    {
        return $this->adapted()->isRowAllowedToImport($rowData, $rowNumber); 
    }

    public function isNeedToLogInHistory()
    {
        return $this->adapted()->isNeedToLogInHistory(); 
    }

    public function setParameters(array $parameters)
    {
        return $this->adapted()->setParameters($parameters); 
    }

    public function setSource(AbstractSource $source)
    {
        return $this->adapted()->setSource($source); 
    }

    public function getCreatedItemsCount()
    {
        return $this->adapted()->getCreatedItemsCount(); 
    }

    public function getUpdatedItemsCount()
    {
        return $this->adapted()->getUpdatedItemsCount(); 
    }

    public function getDeletedItemsCount()
    {
        return $this->adapted()->getDeletedItemsCount(); 
    }

    public function getValidColumnNames()
    {
        return $this->adapted()->getValidColumnNames(); 
    }

    public function getAttributeOptions(AbstractAttribute $attribute, array $indexAttributes = [])
    {
        return $this->adapted()->getAttributeOptions($attribute, $indexAttributes); 
    }

    public function prepareCustomerData($rows): void
    {
        $this->adapted()->prepareCustomerData($rows); 
    }

    public function validateData()
    {
        return $this->adapted()->validateData(); 
    }

    public function getEntityTypeCode()
    {
        return $this->adapted()->getEntityTypeCode();
    }

    public static function getDefaultAddressAttributeMapping()
    {
        return \Magento\CustomerImportExport\Model\Import\Address::getDefaultAddressAttributeMapping();
    }

    public function setCustomerAttributes($customerAttributes)
    {
        return $this->adapted()->setCustomerAttributes($customerAttributes); 
    }
}