<?php

namespace Creatuity\Base\Model\CsvParser\Logic\Row;

use Creatuity\Base\Model\CsvParser\LogicInterface;
use Creatuity\Base\Model\CsvParser\UtilityInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class EmptyRowLogic implements LogicInterface
{
    public function beforeProcess(UtilityInterface $utility): void
    {
    }

    public function processRow(array $rowData, UtilityInterface $utility): void
    {
    }

    public function afterProcess(UtilityInterface $utility): void
    {
    }
}
