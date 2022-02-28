<?php

namespace Creatuity\Base\Setup\Abstracts;

use Creatuity\Base\Helpers\Creatuity;
use Creatuity\Base\Helpers\Creatuity\Subjects\CreatuityDemo;
use Creatuity\Base\Setup\Type\Catalog;
use Creatuity\Base\Setup\Type\Customer;
use Creatuity\Base\Setup\TypeInterface;
use Creatuity\Base\Setup\TypeFactory;

/**
 * @category Creatuity
 * @package phw
 * @copyright Copyright (c) 2008-2017 Joshua Warren (https://warrenappliedlabs.com)
 * @license https://warrenappliedlabs.com/license
 */
abstract class AbstractInstallUpgradeSchemaData extends BackwardCompatibility implements TypeInterface
{
    /**
     * @var TypeFactory
     */
    protected $typeFactory;

    /**
     * @var Creatuity
     *
     * @deprecated use $this->creatuity() method
     */
    protected $creatuity;


    public function __construct(AbstractUpgradeDataContext $context)
    {
        parent::__construct($context);
        $this->typeFactory = $context->getTypeFactory();
        $this->creatuity = $context->getCreatuity()->forModule($context->getModuleNameResolver()->byObject($this));
    }


    /**
     * @return CreatuityDemo
     */
    protected function creatuityDemo()
    {
        return $this->creatuity()->demo();
    }

    /**
     * @return Creatuity
     */
    protected function creatuity()
    {
        return $this->creatuity;
    }

    /**
     * @return Catalog
     */
    protected function eavCatalog()
    {
        return $this->typeFactory->create('catalog', $this);
    }
    /**
     * @return Customer
     */
    protected function eavCustomer()
    {
        return $this->typeFactory->create('customer', $this);
    }

    protected function runOnlyWhenCreatuityDevToolsAreAbsent($callback)
    {
        if (!$this->isCreatuityDevToolsAvailable()) {
            call_user_func($callback);
        }
    }

    protected function runOnlyWhenCreatuityDevToolsArePresent($callback)
    {
        if ($this->isCreatuityDevToolsAvailable()) {
            call_user_func($callback);
        }
    }

    /**
     * @return bool
     */
    private function isCreatuityDevToolsAvailable()
    {
        return $this->creatuity()->resources()->projectDirReader('dev/creatuity')->isExist('run.sh');
    }
}