<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Creatuity\Base\Helpers\Creatuity;
use Magento\Framework\App\State;
use Magento\Framework\Registry;
use Magento\Framework\App\Config\MutableScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
class Emulate extends SubjectAbstract
{
    /**
     * @var State
     */
    protected $magentoArea;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var MutableScopeConfigInterface
     */
    protected $config;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(
        StoreManagerInterface $storeManager,
        State $magentoArea,
        Registry $registry,
        MutableScopeConfigInterface $config,
        Creatuity $creatuity
    ) {
        parent::__construct($creatuity);
        $this->magentoArea = $magentoArea;
        $this->registry = $registry;
        $this->config = $config;
        $this->storeManager = $storeManager;
    }


    public function runInFrontendArea(callable $callback)
    {
        return $this->magentoArea->emulateAreaCode('frontend', $callback);
    }

    public function runInSecuredArea(callable $callback)
    {
        $prev = $this->registry->registry('isSecureArea');
        try {
            $this->registry->unregister('isSecureArea');

            $this->registry->register('isSecureArea', true);

            return $this->magentoArea->emulateAreaCode('adminhtml', $callback);
        } finally {
            $this->registry->unregister('isSecureArea');
            if ($prev !== null) {
                $this->registry->register('isSecureArea', $prev);
            }
        }
    }

    public function runInStore($storeIdOrCode, callable $callback, array $params = [])
    {
        $currentStore = $this->storeManager->getStore();
        try {
            $emulatedStore = $this->creatuity()->store()->storeViewModel($storeIdOrCode);
            $this->storeManager->setCurrentStore($emulatedStore->getCode());

            $result = call_user_func_array($callback, $params);
        } finally {
            $this->storeManager->setCurrentStore($currentStore->getCode());
        }
        return $result;
    }

    public function runWithConfig($path, $value, $callback, $scopeType = ScopeInterface::SCOPE_STORE, $scopeCode = null)
    {
        return $this->runWithConfigMany([$path => $value], $callback, $scopeType, $scopeCode);
    }

    public function runWithConfigMany(array $settings, $callback, $scopeType = ScopeInterface::SCOPE_STORE, $scopeCode = null)
    {
        $prev = [];
        foreach (array_keys($settings) as $path) {
            $prev[ $path ] = $this->config->getValue($path, $scopeType, $scopeCode);
        }
        try {
            foreach ($settings as $path => $value) {
                $this->config->setValue($path, $value, $scopeType, $scopeCode);
            }

            return call_user_func($callback);
        } finally {
            foreach ($prev as $path => $value) {
                $this->config->setValue($path, $value, $scopeType, $scopeCode);
            }
        }
    }
}