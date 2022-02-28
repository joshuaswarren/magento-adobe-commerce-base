<?php
namespace Creatuity\Base\Setup;

use Creatuity\Base\Setup\Abstracts\Files\UpgradeFilesInstaller;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @package ygy
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class RecurringData implements InstallDataInterface
{
    /**
     * @var UpgradeFilesInstaller
     */
    protected $filesInstaller;

    public function __construct(UpgradeFilesInstaller $filesInstaller)
    {
        $this->filesInstaller = $filesInstaller;
    }

    /**
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->filesInstaller->upgradeFiles($setup, $context);
    }
}
