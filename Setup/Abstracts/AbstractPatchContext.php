<?php

namespace Creatuity\Base\Setup\Abstracts;

use Creatuity\Base\Helpers\Creatuity;
use Creatuity\Base\Setup\Tools\ModuleNameResolver;
use Creatuity\Base\Setup\Tools\SelfExplainTester;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 *
 * @deprecated Use core DataPatchInterface, include desired pieces via constructor
 */
class AbstractPatchContext
{
    private Creatuity $creatuity;
    private SelfExplainTester $selfExplainTester;
    private ModuleNameResolver $moduleNameResolver;

    public function __construct(Creatuity $creatuity, SelfExplainTester $selfExplainTester, ModuleNameResolver $moduleNameResolver)
    {
        $this->creatuity = $creatuity;
        $this->selfExplainTester = $selfExplainTester;
        $this->moduleNameResolver = $moduleNameResolver;
    }

    public function getCreatuity(): Creatuity
    {
        return $this->creatuity;
    }

    public function getSelfExplainTester(): SelfExplainTester
    {
        return $this->selfExplainTester;
    }

    public function getModuleNameResolver(): ModuleNameResolver
    {
        return $this->moduleNameResolver;
    }
}
