<?php

namespace Creatuity\Base\Model\CsvParser\Logic\Row;

use Closure;
use Creatuity\Base\Model\CsvParser\LogicInterface;
use Creatuity\Base\Model\CsvParser\UtilityInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class RowClosureLogic implements LogicInterface
{
    private Closure $closure;

    public function __construct(
        Closure $closure
    ) {
        $this->closure = $closure;
    }

    public function beforeProcess(UtilityInterface $utility): void
    {
    }

    public function processRow(array $rowData, UtilityInterface $utility): void
    {
        call_user_func($this->closure, $rowData, $utility);
    }

    public function afterProcess(UtilityInterface $utility): void
    {
    }
}
