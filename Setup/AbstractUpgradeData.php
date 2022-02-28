<?php

namespace Creatuity\Base\Setup;

use Creatuity\Base\Setup\Abstracts\AbstractUpgradeSchemaDataImpl;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface as CoreModuleContextInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
abstract class AbstractUpgradeData extends AbstractUpgradeSchemaDataImpl implements UpgradeDataInterface
{
    protected $disableForeignKeysAndAllowZeroIds = true;


    protected function upgrade_1_0_0(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        // Here you can find list of example helpers which will speeding up most common and boring tasks

        // Hint: You can help yourself testing your upgrade script by running dev/creatuity/playground
        //       After first run, put into your /playground.main.php below line:
        //
        //            $context->testUpgradeDataScript("Creatuity_Module", "1.0.2");
        //
        //       Now, every time you run dev/creatuity/playground it will downgrade module to 1.0.2
        //       and run scripts so you can test them over and over. Have fun :)

        $this->demoEavCatalog();
        $this->demoEavCustomer();
        $this->runOnlyWhenCreatuityDevToolsAreAbsent(function() {
            // this will be executed only out of the creatuity infrastructure (i.e. live, staging, ...)
            // i.e. turn 3rd-party module to live mode
        });
        $this->runOnlyWhenCreatuityDevToolsArePresent(function() {
            // this will be executed only inside creatuity infrastructure (i.e. dockers, vagrants, preview ...)
            // i.e. turn 3rd-party module to demo mode
        });
    }

    private function demoEavCatalog()
    {
        // Want to create attribute. It's as "simple" as:  ( ;p )
        $this->eavCatalog()->attribute('attribute_code')->forProduct()
            ->requiredSettings()
                ->label('Demo Attribute')
                ->defaultValue('default')
                ->input()->price()
                ->attributeType()->decimal()
                ->scope()->scopeWebsite()
                ->isComparable(true)
                ->isRequired(false)
                ->isSearchable(true)
                ->isUnique(false)
                ->isUsedInFlatTables(false)
                ->isUsedInProductListing(true)
                ->isUserDefined(true)
                ->isVisible(true)
                ->isVisibleOnFront(true)
                ->done()
            ->optionalSettings()
                ->group("Group Name")
                ->applyToProductTypes()->addConfigurable()->addSimple()->done()
                ->attributeSets()->addToAllSets()->done()
                ->backendModel(\Some\Model::class)
                ->backendTable('backend_table')
                ->note('This is a demo attribute')
                ->source()->options()
                    ->addOption("Option 1")
                    ->addOption("Option 2")
                    ->done()
                ->frontendModel(\Some\Model::class)
                ->done()
            ->create()
        ;

        // It's important to distinguish updating an attribute:
        $this->eavCatalog()->attribute('attribute_code')->forProduct()
            ->requiredSettings()
                // ...
                ->done()
            ->optionalSettings()
                // ...
                ->done()
            ->update()
        ;


        // want to create an attribute for category ?
        $this->eavCatalog()->attribute('is_special_category')->forCategory()
            ->requiredSettings()
                ->scope()->scopeGlobal()
                ->label("Demo category attribute")
                ->isVisibleOnFront(false)
                ->isVisible(false)
                ->isUserDefined(true)
                ->isRequired(true)
                ->defaultValue('0')
                ->input()->boolean()
                ->isSearchable(false)
                ->isUnique(false)
                ->isSearchable(false)
                ->isUsedInProductListing(false)
                ->isUsedInFlatTables(false)
                ->done()
            ->done()
        ;


        // want to delete attribute ?
        $this->eavCatalog()->attribute('to_delete')->forProduct()->delete();
        $this->eavCatalog()->attribute('to_delete')->gotCategory()->delete();
    }

    private function demoEavCustomer()
    {
        $this->eavCustomer()->attribute('age')->forCustomer()
            ->requiredSettings()
                // ... see above examples
                ->done()
            ->optionalSettings()
                // ...
                ->done()
            ->create()
        ;

        $this->eavCustomer()->attribute('is_real_address')->forCustomerAddress()
            ->requiredSettings()
            // ... see above examples
                ->done()
            ->optionalSettings()
                // ...
                ->done()
            ->update()
        ;
    }

    final public function upgrade(ModuleDataSetupInterface $setup, CoreModuleContextInterface $context)
    {
        $this->run($setup, $context);
    }
}
