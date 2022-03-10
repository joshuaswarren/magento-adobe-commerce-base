<?php

namespace Creatuity\Base\Model;

use Exception;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ProductMetadataInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class MagentoVersion
{
    private static MagentoVersion $instance;
    private string $currentMagentoVersion;

    private ProductMetadataInterface $productMetadata;

    public function __construct(ProductMetadataInterface $productMetadata)
    {
        $this->productMetadata = $productMetadata;
    }

    /**
     * @throws Exception
     */
    public function ifLowerThan($magentoVersion, callable $toExecute = null): bool
    {
        if ($toExecute) {
            throw new Exception("Deprecated. Magento cannot handle anonymous functions in the compiler. Please use 'if (isLowerThan($magentoVersion)) {}' syntax.");
        }

        return $this->processCondition(
            version_compare($this->currentMagentoVersion(), $magentoVersion, '<'),
            $toExecute
        );
    }

    /**
     * @throws Exception
     */
    public function ifHigherThan(string $magentoVersion, callable $toExecute = null): bool
    {
        if ($toExecute) {
            throw new Exception("Deprecated. Magento cannot handle anonymous functions in the compiler. Please use 'if (ifHigherThan($magentoVersion)) {}' syntax.");
        }

        return $this->processCondition(
            version_compare($this->currentMagentoVersion(), $magentoVersion, '>'),
            $toExecute
        );
    }

    /**
     * @throws Exception
     */
    public function ifBetween(string $magentoVersionOldest, string $magentoVersionLatest, callable $toExecute = null): bool
    {
        if ($toExecute) {
            throw new Exception("Deprecated. Magento cannot handle anonymous functions in the compiler. Please use 'if (ifBetween($magentoVersionOldest, $magentoVersionLatest)) {}' syntax.");
        }

        return $this->processCondition(
            version_compare($this->currentMagentoVersion(), $magentoVersionOldest, '>')
            && version_compare($this->currentMagentoVersion(), $magentoVersionLatest, '<'),
            $toExecute
        );
    }

    public function includeIfLowerThan(string $magentoVersion, string $fileToInclude)
    {
        if (version_compare($this->currentMagentoVersion(), $magentoVersion, '<')) {
            return require $fileToInclude;
        }

        return null;
    }

    public function includeIfHigherThan(string $magentoVersion, string $fileToInclude)
    {
        if (version_compare($this->currentMagentoVersion(), $magentoVersion, '>')) {
            return require $fileToInclude;
        }

        return null;
    }

    public function includeIfBetween(string $magentoVersionOldest, string $magentoVersionLatest, string $fileToInclude)
    {
        if (version_compare($this->currentMagentoVersion(), $magentoVersionOldest, '>')
            && version_compare($this->currentMagentoVersion(), $magentoVersionLatest, '<')
        ) {
            return require $fileToInclude;
        }

        return null;
    }

    private function processCondition(bool $condition, callable $toExecute = null): bool
    {
        if ($condition) {
            if (!$toExecute) {
                return true;
            }
            $toExecute();
        } else {
            if (!$toExecute) {
                return false;
            }
        }

        return true;
    }

    protected function currentMagentoVersion(): string
    {
        if (!$this->currentMagentoVersion) {
            $this->currentMagentoVersion = $this->productMetadata->getVersion();
        }

        return $this->currentMagentoVersion;
    }

    public static function instance(): MagentoVersion
    {
        if (!static::$instance) {
            static::$instance = ObjectManager::getInstance()->get(static::class);
        }

        return static::$instance;
    }
}
