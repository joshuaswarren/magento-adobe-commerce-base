<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
interface SubjectForModuleInterface
{
    public function forModule(string $moduleName): SubjectForModuleInterface;
}
