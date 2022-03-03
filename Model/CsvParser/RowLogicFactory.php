<?php

namespace Creatuity\Base\Model\CsvParser;

use Creatuity\Base\Model\CsvParser\Logic\Row\EmptyRowLogic;
use Creatuity\Base\Model\CsvParser\Logic\Row\RowClosureLogic;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class RowLogicFactory extends LogicAbstractFactory
{
    protected function emptyLogicClassName(): string
    {
        return EmptyRowLogic::class;
    }

    protected function closureLogicClassName(): string
    {
        return RowClosureLogic::class;
    }

    protected function logicInterfaceName(): string
    {
        return LogicInterface::class;
    }
}
