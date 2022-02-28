<?php

namespace Creatuity\Base\Setup\Abstracts\Files;

use Creatuity\Base\Setup\AbstractUpgradeFiles;
use Magento\Framework\Module\ModuleListInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Setup\Model\ModuleContext;
use Magento\Framework\Setup\ModuleContextInterface as CoreModuleContextInterface;

/**
 * @package waltwo
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2017 Joshua Warren (https://warrenappliedlabs.com)
 */
class UpgradeFilesInstaller
{
    /**
     * @var ModuleListInterface
     */
    protected $modules;

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var bool
     */
    protected $force = false;

    public function __construct(ModuleListInterface $modules, ObjectManagerInterface $objectManager, $force = false)
    {
        $this->modules = $modules;
        $this->objectManager = $objectManager;
        $this->force = $force;
    }

    public function upgradeFilesForAllModules()
    {
        foreach ($this->allModulesHavingOurFileUpgradeScripts() as $moduleName) {
            $this->performUpgradeForModule($moduleName);
        }
    }

    public function upgradeFilesForModule($moduleName)
    {
        $this->performUpgradeForModule($moduleName);
    }

    public function upgradeFiles(ModuleDataSetupInterface $setup = null, CoreModuleContextInterface $context = null)
    {
        foreach ($this->allModulesHavingOurFileUpgradeScripts() as $moduleName) {
            $this->performUpgradeForModule($moduleName, $setup, $context);
        }
    }

    protected function allModulesHavingOurFileUpgradeScripts()
    {
        $ret = [];

        foreach ($this->modules->getNames() as $moduleName) {
            $className = $this->installerClassName($moduleName);
            if (!class_exists($className)) {
                continue;
            }
            $ret[] = $moduleName;
        }

        return $ret;
    }

    protected function performUpgradeForModule($moduleName, ModuleDataSetupInterface $setup = null, CoreModuleContextInterface $context = null)
    {
        $className = $this->installerClassName($moduleName);
        $installer = $this->createInstaller($className);
        $installer->upgrade($this->toSetup($setup), $this->toContext($context));
    }

    /**
     * @param $className
     * @return AbstractUpgradeFiles
     * @throws \Exception
     */
    protected function createInstaller($className)
    {
        $object = $this->objectManager->create($className, [
            'force' => $this->force,
        ]);

        if (!$object instanceof AbstractUpgradeFiles) {
            throw new \Exception("Expected " . AbstractUpgradeFiles::class);
        }

        return $object;
    }

    protected function toSetup(ModuleDataSetupInterface $setup = null)
    {
        if ($setup == null) {
            $setup = new UpgradeFilesFakeSetup();
        }

        return $setup;
    }

    protected function toContext(CoreModuleContextInterface $context = null)
    {
        if ($context == null) {
            $context = new ModuleContext('');
        }

        return $context;
    }

    protected function installerClassName($moduleName)
    {
        return str_replace('_', '\\', $moduleName) . '\Setup\UpgradeFiles';
    }
}