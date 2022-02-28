<?php

namespace Creatuity\Base\Setup;

use Creatuity\Base\Setup\Abstracts\AbstractUpgradeSchemaDataImpl;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface as CoreModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;


/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
abstract class AbstractUpgradeSchema extends AbstractUpgradeSchemaDataImpl implements UpgradeSchemaInterface
{
    /**
     * @see $this->creatuityDemo() - you will find there list of examples of Creatuity::class features
     */
    protected function upgrade_1_0_0(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        // $this->creatuityDemo();
        // template
    }

    final public function upgrade(SchemaSetupInterface $setup, CoreModuleContextInterface $context)
    {
        $this->run($setup, $context);
    }
}