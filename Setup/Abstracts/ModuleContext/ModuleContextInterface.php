<?php

namespace Creatuity\Base\Setup\Abstracts\ModuleContext;

use Magento\Framework\Setup\ModuleContextInterface as CoreModuleContextInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
interface ModuleContextInterface
{

    /**
     * @return CoreModuleContextInterface
     */
    public function coreContext();

    /**
     * @return string
     */
    public function getVersionInDb();

    /**
     * @return string
     */
    public function getVersionInFiles();

    /**
     * @return bool
     */
    public function isAlreadyInstalledInDb();

    /**
     * @return bool
     */
    public function isVersionInFilesIsLowerThanDb();

    /**
     * @return bool
     */
    public function isVersionInFilesIsHigherThanDb();

    /**
     * @return bool
     */
    public function isVersionInFilesIsSameInDb();

    /**
     * @return bool
     */
    public function isVersionInFilesIsHigherThan($version);

    /**
     * @return bool
     */
    public function isVersionInFilesIsLowerThan($version);

    /**
     * @return bool
     */
    public function isVersionInFilesIs($version);

    /**
     * @return bool
     */
    public function isVersionInDbIsHigherThan($version);

    /**
     * @return bool
     */
    public function isVersionInDbIsLowerThan($version);

    /**
     * @return bool
     */
    public function isVersionInDbIs($version);

}
