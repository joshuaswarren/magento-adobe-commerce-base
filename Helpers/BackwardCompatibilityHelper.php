<?php

namespace Creatuity\Base\Helpers;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\App\State;
use Magento\Framework\Registry;
use Magento\Indexer\Model\Processor;
use Magento\Framework\App\Config\MutableScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class BackwardCompatibilityHelper
{
    /**
     * @var ResourceConnection
     */
    protected $connectionsPool;

    /**
     * @var State
     */
    protected $magentoArea;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Processor
     */
    protected $indexer;

    /**
     * @var MutableScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        ResourceConnection $connectionsPool,
        MutableScopeConfigInterface $scopeConfig,
        State $magentoArea,
        Registry $registry,
        Processor $indexer
    )
    {
        $this->connectionsPool = $connectionsPool;
        $this->magentoArea = $magentoArea;
        $this->registry = $registry;
        $this->indexer = $indexer;
        $this->scopeConfig = $scopeConfig;
    }


    /**
     * @return AdapterInterface
     * @deprecated use $this->creatuity()->database()->method instead
     */
    public function dbConnection()
    {
        return $this->connectionsPool->getConnection();
    }

    /**
     * @param string $table
     * @return string
     * @deprecated use $this->creatuity()->database()->method instead
     */
    public function tableName($table)
    {
        return $this->connectionsPool->getTableName($table);
    }

    /**
     * @throws \Exception
     * @deprecated use $this->creatuity()->database()->method instead
     */
    public function runInTransaction($callback, array $args = [])
    {
        try {
            $this->connectionsPool->getConnection()->beginTransaction();

            $return = call_user_func_array($callback, $args);

            $this->connectionsPool->getConnection()->commit();

            return $return;
        } catch (\Exception $e) {
            $this->connectionsPool->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * @deprecated use $this->creatuity()->emulate()->method instead
     */
    public function runWithConfig($path, $value, $callback, $scopeType = ScopeInterface::SCOPE_STORE, $scopeCode = null)
    {
        return $this->runWithConfigMany([$path => $value], $callback, $scopeType, $scopeCode);
    }

    /**
     * @deprecated use $this->creatuity()->emulate()->method instead
     */
    public function runWithConfigMany(array $settings, $callback, $scopeType = ScopeInterface::SCOPE_STORE, $scopeCode = null)
    {
        $prev = [];
        foreach (array_keys($settings) as $path) {
            $prev[ $path ] = $this->scopeConfig->getValue($path, $scopeType, $scopeCode);
        }
        try {
            foreach ($settings as $path => $value) {
                $this->scopeConfig->setValue($path, $value, $scopeType, $scopeCode);
            }

            return call_user_func($callback);
        } finally {
            foreach ($prev as $path => $value) {
                $this->scopeConfig->setValue($path, $value, $scopeType, $scopeCode);
            }
        }
    }

    /**
     * @deprecated use $this->creatuity()->indexer()->method instead
     */
    public function reindexAll()
    {
        $this->runInSecuredArea(function () {
            $this->indexer->reindexAll();
        });
    }

    /**
     * @deprecated use $this->creatuity()->emulate()->method instead
     */
    public function runInFrontendArea($callback)
    {
        return $this->magentoArea->emulateAreaCode('frontend', $callback);
    }

    /**
     * @deprecated use $this->creatuity()->emulate()->method instead
     */
    public function runInSecuredArea($callback)
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
     * @deprecated use $this->creatuityLogo()->method instead
     */
    public function writeCreatuityLogo(OutputInterface $output)
    {
        // we need to split into two separate calls,
        // because symphony formatter cannot process formatting tags if they have backslash before them
        $output->write('<fg=red;options=bold>_\\');
        $output->write('</><fg=yellow;options=bold>.</><fg=red;options=bold>/_</> <fg=blue;options=bold>CREATUITY</>');
    }

    /**
     * @deprecated use $this->creatuity()->seo()->method instead
     */
    public function nameToSeoUrlKey($humanReadableString)
    {
        $string = \mb_strtolower($humanReadableString, 'UTF-8');

        //Strip any unwanted characters
        $string = \preg_replace("/[^a-z0-9_\s-]/", "", $string);
        //Clean multiple dashes or whitespaces
        $string = \preg_replace("/[\s-]+/", " ", $string);
        //Convert whitespaces and underscore to dash
        $string = \preg_replace("/[\s_]/", "-", $string);

        $string = str_replace(' ', '-', $string);

        //Trim dashed
        $string = \trim($string, '-');

        return $string;
    }
}
