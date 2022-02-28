<?php

namespace Creatuity\Base\Model\CsvParser\Logic\Row;

use Creatuity\Base\Model\CsvParser\LogicInterface;
use Creatuity\Base\Model\CsvParser\UtilityInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
class RowClosureLogic implements LogicInterface
{
    /** @var \Closure */
    protected $closure;

    public function __construct(\Closure $closure)
    {
        $this->closure = $closure;
    }


    public function beforeProcess(UtilityInterface $utility)
    {
    }

    public function processRow(array $rowData, UtilityInterface $utility)
    {
        call_user_func($this->closure, $rowData, $utility);
    }

    public function afterProcess(UtilityInterface $utility)
    {
        // TODO: Implement afterProcess() method.
    }
}