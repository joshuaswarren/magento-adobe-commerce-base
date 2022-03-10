<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Creatuity\Base\Helpers\Creatuity\Subjects\Exception\ModuleNotSetException;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
interface SubjectForModuleInterface
{
    public function forModule(string $moduleName): SubjectForModuleInterface;

    /**
     * @throws ModuleNotSetException
     */
    public function ensureModuleIsSet(): void;
}
