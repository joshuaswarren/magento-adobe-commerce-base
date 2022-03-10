<?php

namespace Creatuity\Base\Model\CsvParser;

use Creatuity\Base\Model\CsvParser\Logic\ClosureChunkLogic;
use Creatuity\Base\Model\CsvParser\Logic\EmptyChunkLogic;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class ChunkLogicFactory extends LogicAbstractFactory
{
    protected function emptyLogicClassName(): string
    {
        return EmptyChunkLogic::class;
    }

    protected function closureLogicClassName(): string
    {
        return ClosureChunkLogic::class;
    }

    protected function logicInterfaceName(): string
    {
        return ChunkLogicInterface::class;
    }
}
