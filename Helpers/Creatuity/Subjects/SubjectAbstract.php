<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Creatuity\Base\Helpers\Creatuity;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
abstract class SubjectAbstract
{
    /**
     * @var Creatuity
     */
    protected $creatuity;

    public function __construct(Creatuity $creatuity)
    {
        $this->creatuity = $creatuity;
    }

    /**
     * @return Creatuity
     */
    protected function creatuity()
    {
        return $this->creatuity;
    }
}