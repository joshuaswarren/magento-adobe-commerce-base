<?php

namespace Creatuity\Base\Model;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ProductMetadataInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2019 Joshua Warren (https://warrenappliedlabs.com)
 */
class MagentoVersion
{
    /**
     * @var MagentoVersion
     */
    protected static $instance;

    /**
     * @var ProductMetadataInterface
     */
    protected $productMetadata;

    /**
     * @var string
     */
    protected $currentMagentoVersion;

    public function __construct(ProductMetadataInterface $productMetadata)
    {
        $this->productMetadata = $productMetadata;
    }

    /**
     * @return bool
     */
    public function ifLowerThan($magentoVersion, callable $toExecute = null)
    {
        if ($toExecute) {
            throw new \Exception("Deprecated. Magento cannot handle anonymous functions in the compiler. Please use 'if (isLowerThan($magentoVersion)) {}' syntax.");
        }

        return $this->processCondition(
            version_compare($this->currentMagentoVersion(), $magentoVersion, '<'),
            $toExecute
        );
    }

    /**
     * @return bool
     */
    public function ifHigherThan($magentoVersion, callable $toExecute = null)
    {
        if ($toExecute) {
            throw new \Exception("Deprecated. Magento cannot handle anonymous functions in the compiler. Please use 'if (ifHigherThan($magentoVersion)) {}' syntax.");
        }

        return $this->processCondition(
            version_compare($this->currentMagentoVersion(), $magentoVersion, '>'),
            $toExecute
        );
    }

    /**
     * @return bool
     */
    public function ifBetween($magentoVersionOldest, $magentoVersionLatest, callable $toExecute = null)
    {
        if ($toExecute) {
            throw new \Exception("Deprecated. Magento cannot handle anonymous functions in the compiler. Please use 'if (ifBetween($magentoVersionOldest, $magentoVersionLatest)) {}' syntax.");
        }

        return $this->processCondition(true
            && version_compare($this->currentMagentoVersion(), $magentoVersionOldest, '>')
            && version_compare($this->currentMagentoVersion(), $magentoVersionLatest, '<'),
            $toExecute
        );
    }

    public function includeIfLowerThan(string $magentoVersion, string $fileToInclude)
    {
        if (version_compare($this->currentMagentoVersion(), $magentoVersion, '<')) {
            return require $fileToInclude;
        }
    }

    public function includeIfHigherThan(string $magentoVersion, string $fileToInclude)
    {
        if (version_compare($this->currentMagentoVersion(), $magentoVersion, '>')) {
            return require $fileToInclude;
        }
    }

    public function includeIfBetween(string $magentoVersionOldest, $magentoVersionLatest, string $fileToInclude)
    {
        if (true
            && version_compare($this->currentMagentoVersion(), $magentoVersionOldest, '>')
            && version_compare($this->currentMagentoVersion(), $magentoVersionLatest, '<')
        ) {
            return require $fileToInclude;
        }
    }

    /**
     * @param bool $condition
     * @return bool
     */
    protected function processCondition($condition, callable $toExecute = null)
    {
        if ( $condition ) {
            if (!$toExecute) {
                return true;
            }
            $toExecute();
        } else {
            if (!$toExecute) {
                return false;
            }
        }
    }

    /**
     * @return string
     */
    protected function currentMagentoVersion()
    {
        if (!$this->currentMagentoVersion) {
            $this->currentMagentoVersion = $this->productMetadata->getVersion();
        }
        return $this->currentMagentoVersion;
    }

    /**
     * @return MagentoVersion
     */
    public static function instance()
    {
        if (!static::$instance) {
            static::$instance = ObjectManager::getInstance()->get(static::class);
        }

        return static::$instance;
    }
}