<?php

namespace Creatuity\Base\Model\CsvParser;

use Creatuity\Base\Model\CsvParser\Logic\Row\EmptyRowLogic;
use Creatuity\Base\Model\CsvParser\Logic\Row\RowClosureLogic;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
class RowLogicFactory extends LogicAbstractFactory
{
    /**
     * @return string
     */
    protected function emptyLogicClassName()
    {
        return EmptyRowLogic::class;
    }

    /**
     * @return string
     */
    protected function closureLogicClassName()
    {
        return RowClosureLogic::class;
    }

    /**
     * @return string
     */
    protected function logicInterfaceName()
    {
        return LogicInterface::class;
    }
}