<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Creatuity\Base\Helpers\Creatuity as CreatuityHelper;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
abstract class SubjectAbstract
{
    protected CreatuityHelper $creatuity;

    public function __construct(CreatuityHelper $creatuity)
    {
        $this->creatuity = $creatuity;
    }

    protected function creatuity(): CreatuityHelper
    {
        return $this->creatuity;
    }
}
