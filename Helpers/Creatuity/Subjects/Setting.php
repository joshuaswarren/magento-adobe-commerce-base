<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Creatuity\Base\Helpers\Creatuity;
use Exception;
use Magento\Config\Model\ResourceModel\Config;
use Magento\Config\Model\ResourceModel\Config\Data\CollectionFactory;
use Magento\Config\Model\ResourceModel\Config\Data\Collection;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ScopeInterface as FrameworkScopeInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class Setting extends SubjectAbstract
{
    const UPDATE_PREPEND = 'prepend';
    const UPDATE_APPEND = 'append';

    private Config $configResource;
    private ScopeConfigInterface $appConfig;
    private CollectionFactory $configCollectionFactory;
    private string $scopeType;
    private int $scope;

    public function __construct(
        Config $config,
        ScopeConfigInterface $appConfig,
        CollectionFactory $configCollectionFactory,
        Creatuity $creatuity,
        $scopeType = FrameworkScopeInterface::SCOPE_DEFAULT,
        $scope = 0
    ) {
        parent::__construct($creatuity);
        $this->configResource = $config;
        $this->appConfig = $appConfig;
        $this->configCollectionFactory = $configCollectionFactory;
        $this->scopeType = $scopeType;
        $this->scope = $scope;
    }

    public function saveMany(array $settings, ?string $scopeType = null, ?int $scope = null): void
    {
        foreach ($settings as $name => $value) {
            $this->save($name, $value, $scopeType, $scope);
        }
    }

    public function save(string $name, $value, ?string $scopeType = null, ?int $scope = null): void
    {
        $this->configResource->saveConfig($name, $value,
            $this->scopeType($scopeType),
            $this->scope($scope)
        );

        $value = print_r($value, true);
        $this->creatuity()->report()->printSuccess("Setting changed: '{$name}' => {$value}");
    }

    public function prepened(string $name, $value, ?string $scopeType = null, ?int $scope = null): void
    {
        $this->update(self::UPDATE_PREPEND, $name, $value, $scopeType, $scope);
    }

    public function append(string $name, $value, ?string $scopeType = null, ?int $scope = null): void
    {
        $this->update(self::UPDATE_APPEND, $name, $value, $scopeType, $scope);
    }

    private function update(string $type, string $name, $value, ?string $scopeType = null, ?int $scope = null): void
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
        $collection->addFieldToFilter('scope', $scopeType ?? FrameworkScopeInterface::SCOPE_DEFAULT);
        $collection->addFieldToFilter('scope_id', $scope ?? 0);
        $collection->addFieldToFilter('path', $name);

        return (string)$collection->getFirstItem()->getData('value');
    }

    public function deleteMany(array $names, ?string $scopeType = null, ?int $scope = null): void
    {
        foreach($names as $name) {
            $this->delete($name, $scopeType, $scope);
        }
    }

    public function delete(string $name, ?string $scopeType = null, ?int $scope = null): void
    {
        $this->configResource->deleteConfig($name,
            $this->scopeType($scopeType),
            $this->scope($scope)
        );

        $this->creatuity()->report()->printSuccess("Setting deleted: '{$name}'");
    }

    /**
     * @param string $name
     * @param mixed|null $default
     * @param string|null $scopeType
     * @param int|null $scope
     * @return mixed|null
     * @throws Exception
     */
    public function load(string $name, $default = null, ?string $scopeType = null, ?int $scope = null)
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

    /**
     * @throws Exception
     */
    private function scopeType(?string $scopeType = null): string
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
            throw new Exception('Invalid scope. Expected one of: ' . implode(', ', $allowed));
        }

        return $ret;
    }

    private function scope(?int $scope = null): int
    {
        if ($scope === null) {
            $ret = $this->scope;
        } else {
            $ret = $scope;
        }

        return $ret;
    }

    /**
     * @param int $storeId
     * @return Setting
     * @throws Exception
     */
    public function settingsForStore(int $storeId): self
    {
        return $this->creatuity->setting(
            $this->creatuity()->store()->storeViewModel($storeId)->getId(), ScopeInterface::SCOPE_STORES
        );
    }

    /**
     * @param int $storeGroupId
     * @return Setting
     * @throws Exception
     */
    public function settingsForStoreGroup(int $storeGroupId): self
    {
        return $this->creatuity->setting(
            $this->creatuity()->store()->storeGroupModel($storeGroupId)->getId(), ScopeInterface::SCOPE_GROUP
        );
    }

    /**
     * @param int $websiteId
     * @return Setting
     * @throws Exception
     */
    public function settingsForWebsite(int $websiteId): self
    {
        return $this->creatuity->setting(
            $this->creatuity()->store()->websiteModel($websiteId)->getId(), ScopeInterface::SCOPE_WEBSITES
        );
    }
}
