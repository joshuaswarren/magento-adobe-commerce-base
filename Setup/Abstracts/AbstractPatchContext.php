<?php

namespace Creatuity\Base\Setup\Abstracts;

use Creatuity\Base\Helpers\Creatuity;
use Creatuity\Base\Helpers\OurHelperForScripts;
use Creatuity\Base\Setup\Tools\ModuleNameResolver;
use Creatuity\Base\Setup\Tools\SelfExplainTester;
use Creatuity\Base\Setup\TypeFactory;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
class AbstractPatchContext
{
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

    /**
     * @var TypeFactory
     */
    protected $typeFactory;

    public function __construct(OurHelperForScripts $creatuity, SelfExplainTester $selfExplainTester, ModuleNameResolver $moduleNameResolver, TypeFactory $typeFactory)
    {
        $this->creatuity = $creatuity;
        $this->selfExplainTester = $selfExplainTester;
        $this->moduleNameResolver = $moduleNameResolver;
        $this->typeFactory = $typeFactory;
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

    /**
     * @return TypeFactory
     */
    public function getTypeFactory()
    {
        return $this->typeFactory;
    }
}