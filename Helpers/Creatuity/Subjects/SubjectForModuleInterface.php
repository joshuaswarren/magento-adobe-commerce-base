<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
interface SubjectForModuleInterface
{
    /**
     * @param string $moduleName
     * @return SubjectAbstract
     */
    public function forModule($moduleName);
}