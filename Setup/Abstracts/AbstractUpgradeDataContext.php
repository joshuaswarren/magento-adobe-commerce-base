<?php

namespace Creatuity\Base\Setup\Abstracts;

use Creatuity\Base\Helpers\OurHelperForScripts;
use Creatuity\Base\Setup\Tools\ModuleNameResolver;
use Creatuity\Base\Setup\Tools\SelfExplainTester;
use Magento\Framework\ObjectManager\ContextInterface;
use Creatuity\Base\Setup\Abstracts\ModuleContext\ModuleContextInterfaceImplFactory;
use Creatuity\Base\Setup\TypeFactory;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class AbstractUpgradeDataContext implements ContextInterface
{
    /**
     * @var ModuleContextInterfaceImplFactory
     */
    protected $contextFactory;

    /**
     * @var TypeFactory
     */
    protected $typeFactory;

    /**
     * @var OurHelperForScripts
     */
    protected $creatuity;

    /**
     * @var SelfExplainTester
     */
    protected $selfExplainTester;

    /**
     * @var ModuleNameResolver
     */
    protected $moduleNameResolver;

    public function __construct(
        ModuleContextInterfaceImplFactory $contextFactory,
        TypeFactory $typeFactory,
        OurHelperForScripts $creatuity,
        SelfExplainTester $selfExplainTester,
        ModuleNameResolver $moduleNameResolver
    ) {
        $this->contextFactory = $contextFactory;
        $this->typeFactory = $typeFactory;
        $this->creatuity = $creatuity;
        $this->selfExplainTester = $selfExplainTester;
        $this->moduleNameResolver = $moduleNameResolver;
    }

    /**
     * @return TypeFactory
     */
    public function getTypeFactory()
    {
        return $this->typeFactory;
    }
    
    /**
     * @return ModuleContextInterfaceImplFactory
     */
    public function getContextFactory()
    {
        return $this->contextFactory;
    }

    /**
     * @return OurHelperForScripts
     */
    public function getCreatuity()
    {
        return $this->creatuity;
    }

    /**
     * @return SelfExplainTester
     */
    public function getSelfExplainTester()
    {
        return $this->selfExplainTester;
    }

    /**
     * @return ModuleNameResolver
     */
    public function getModuleNameResolver()
    {
        return $this->moduleNameResolver;
    }
}
