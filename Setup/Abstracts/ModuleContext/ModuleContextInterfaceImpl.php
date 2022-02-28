<?php

namespace Creatuity\Base\Setup\Abstracts\ModuleContext;

use Magento\Framework\Setup\ModuleContextInterface as CoreModuleContextInterface;
use Magento\Framework\Module\ModuleListInterface;
use Creatuity\Base\Setup\ModuleContextInterface as BackwardCompatibilityModuleContextInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class ModuleContextInterfaceImpl implements BackwardCompatibilityModuleContextInterface
{

    /**
     * @var CoreModuleContextInterface
     */
    protected $orgContext;

    /**
     * @var ModuleListInterface
     */
    protected $moduleList;

    /**
     * @var string
     */
    protected $setupClassName;

    public function __construct(CoreModuleContextInterface $orgContext, ModuleListInterface $moduleList, $setupClassName)
    {
        $this->orgContext = $orgContext;
        $this->moduleList = $moduleList;
        $this->setupClassName = $setupClassName;
    }


    /**
     * @return CoreModuleContextInterface
     */
    public function coreContext()
    {
        return $this->orgContext;
    }

    public function getVersionInDb()
    {
        return $this->orgContext->getVersion();
    }

    public function getVersionInFiles()
    {
        list($package, $module) = explode('\\', $this->setupClassName);
        $moduleInfo = $this->moduleList->getOne("{$package}_${module}");
        if (!$moduleInfo) {
            throw new \Exception(sprintf('Cannot find module info for %s_%s', $package, $module));
        }

        return $moduleInfo['setup_version'];
    }

    public function isAlreadyInstalledInDb()
    {
        return $this->getVersionInDb() != "";
    }

    public function isVersionInFilesIsHigherThanDb()
    {
        return version_compare($this->getVersionInFiles(), $this->getVersionInDb(), '>');
    }

    public function isVersionInFilesIsLowerThanDb()
    {
        return version_compare($this->getVersionInFiles(), $this->getVersionInDb(), '<');
    }

    public function isVersionInFilesIsSameInDb()
    {
        return version_compare($this->getVersionInFiles(), $this->getVersionInDb(), '=');
    }

    public function isVersionInDbIs($version)
    {
        return version_compare($this->getVersionInDb(), $version, '=');
    }

    public function isVersionInDbIsHigherThan($version)
    {
        return version_compare($this->getVersionInDb(), $version, '>');
    }

    public function isVersionInDbIsLowerThan($version)
    {
        return version_compare($this->getVersionInDb(), $version, '<');
    }

    public function isVersionInFilesIs($version)
    {
        return version_compare($this->getVersionInFiles(), $version, '=');
    }

    public function isVersionInFilesIsHigherThan($version)
    {
        return version_compare($this->getVersionInFiles(), $version, '>');
    }

    public function isVersionInFilesIsLowerThan($version)
    {
        return version_compare($this->getVersionInFiles(), $version, '<');
    }

}
