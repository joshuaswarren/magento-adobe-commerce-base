<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Creatuity\Base\Helpers\Creatuity;
use Magento\Config\Model\ResourceModel\Config;
use Magento\Config\Model\ResourceModel\Config\Data\CollectionFactory;
use Magento\Config\Model\ResourceModel\Config\Data\Collection;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ScopeDefault;
use Magento\Store\Model\ScopeInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2020 Joshua Warren (https://warrenappliedlabs.com)
 */
class Setting extends SubjectAbstract
{
    const UPDATE_PREPEND = 'prepend';
    const UPDATE_APPEND = 'append';

    /**
     * @var Config
     */
    protected $configResource;

    /**
     * @var ScopeConfigInterface
     */
    protected $appConfig;

    /**
     * @var CollectionFactory
     */
    private $configCollectionFactory;

    /**
     * @var string
     */
    protected $scopeType;

    /**
     * @var int
     */
    protected $scope;

    public function __construct(Config $config, ScopeConfigInterface $appConfig, CollectionFactory $configCollectionFactory, Creatuity $creatuity, $scopeType = 'default', $scope = 0)
    {
        parent::__construct($creatuity);
        $this->configResource = $config;
        $this->appConfig = $appConfig;
        $this->configCollectionFactory = $configCollectionFactory;
        $this->scopeType = $scopeType;
        $this->scope = $scope;
    }

    public function saveMany(array $settings, $scopeType = null, $scope = null)
    {
        foreach ($settings as $name => $value) {
            $this->save($name, $value, $scopeType, $scope);
        }
    }

    public function save($name, $value, $scopeType = null, $scope = null)
    {
        $this->configResource->saveConfig($name, $value,
            $this->scopeType($scopeType),
            $this->scope($scope)
        );

        $value = print_r($value, true);
        $this->creatuity()->report()->printSuccess("Setting changed: '{$name}' => {$value}");
    }

    public function prepened($name, $value, $scopeType = null, $scope = null)
    {
        $this->update(self::UPDATE_PREPEND, $name, $value, $scopeType, $scope);
    }

    public function append($name, $value, $scopeType = null, $scope = null)
    {
        $this->update(self::UPDATE_APPEND, $name, $value, $scopeType, $scope);
    }

    private function update($type, $name, $value, $scopeType = null, $scope = null)
    {
        $oldValue = $this->getOldValue($name, $scopeType, $scope);

        switch ($type) {
            case self::UPDATE_PREPEND:
                $this->save($name, $value . $oldValue, $scopeType, $scope);
                break;
            case self::UPDATE_APPEND:
            default:
                $this->save($name, $oldValue . $value, $scopeType, $scope);
                break;
        }
    }

    private function getOldValue($name, $scopeType = null, $scope = null)
    {
        /** @var  Collection $collection */
        $collection = $this->configCollectionFactory->create();
        $collection->addFieldToFilter('scope', $scopeType ?? ScopeDefault::SCOPE_DEFAULT);
        $collection->addFieldToFilter('scope_id', $scope ?? 0);
        $collection->addFieldToFilter('path', $name);

        return (string)$collection->getFirstItem()->getData('value');
    }

    public function deleteMany(array $names, $scopeType = null, $scope = null)
    {
        foreach($names as $name) {
            $this->delete($name, $scopeType, $scope);
        }
    }

    public function delete($name, $scopeType = null, $scope = null)
    {
        $this->configResource->deleteConfig($name,
            $this->scopeType($scopeType),
            $this->scope($scope)
        );

        $this->creatuity()->report()->printSuccess("Setting deleted: '{$name}'");
    }

    public function load($name, $default = null, $scopeType = null, $scope = null)
    {
        $value = $this->appConfig->getValue($name,
            $this->scopeType($scopeType),
            $this->scope($scope)
        );
        if ($value === null) {
            return $default;
        }

        return $value;
    }

    protected function scopeType($scopeType = null)
    {
        if ($scopeType === null) {
            $ret = $this->scopeType;
        } else {
            $ret = $scopeType;
        }

        $allowed = [
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            ScopeInterface::SCOPE_STORE,
            ScopeInterface::SCOPE_STORES,
            ScopeInterface::SCOPE_GROUP,
            ScopeInterface::SCOPE_WEBSITE,
            ScopeInterface::SCOPE_WEBSITES,
        ];

        if (!in_array($ret, $allowed)) {
            throw new \Exception("Invalid scope. Expected one of: " . implode(', ', $allowed));
        }

        return $ret;
    }

    protected function scope($scope = null)
    {
        if ($scope === null) {
            $ret = $this->scope;
        } else {
            $ret = $scope;
        }

        return $ret;
    }

    /**
     * @return Setting
     */
    public function settingsForStore($storeId)
    {
        return $this->creatuity->setting(
            $this->creatuity()->store()->storeViewModel($storeId)->getId(), ScopeInterface::SCOPE_STORES
        );
    }

    /**
     * @return Setting
     */
    public function settingsForStoreGroup($storeGroupId)
    {
        return $this->creatuity->setting(
            $this->creatuity()->store()->storeGroupModel($storeGroupId)->getId(), ScopeInterface::SCOPE_GROUP
        );
    }

    /**
     * @return Setting
     */
    public function settingsForWebsite($websiteId)
    {
        return $this->creatuity->setting(
            $this->creatuity()->store()->websiteModel($websiteId)->getId(), ScopeInterface::SCOPE_WEBSITES
        );
    }
}
