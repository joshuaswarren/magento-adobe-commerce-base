<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Creatuity\Base\Helpers\Creatuity;
use Exception;
use Magento\Framework\App\State;
use Magento\Framework\Registry;
use Magento\Framework\App\Config\MutableScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class Emulate extends SubjectAbstract
{
    private State $magentoArea;
    private Registry $registry;
    private MutableScopeConfigInterface $config;
    private StoreManagerInterface $storeManager;

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

    /**
     * @throws Exception
     */
    public function runInFrontendArea(callable $callback)
    {
        return $this->magentoArea->emulateAreaCode('frontend', $callback);
    }

    /**
     * @throws Exception
     */
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

    /**
     * @param string|int $storeIdOrCode
     * @param callable $callback
     * @param array $params
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
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

    public function runWithConfig(string $path, $value, callable $callback, string $scopeType = ScopeInterface::SCOPE_STORE, string $scopeCode = null)
    {
        return $this->runWithConfigMany([$path => $value], $callback, $scopeType, $scopeCode);
    }

    public function runWithConfigMany(array $settings, callable $callback, string $scopeType = ScopeInterface::SCOPE_STORE, string $scopeCode = null)
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
