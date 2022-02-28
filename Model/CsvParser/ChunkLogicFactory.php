<?php

namespace Creatuity\Base\Model\CsvParser;

use Creatuity\Base\Model\CsvParser\Logic\ClosureChunkLogic;
use Creatuity\Base\Model\CsvParser\Logic\EmptyChunkLogic;

class ChunkLogicFactory extends LogicAbstractFactory
{
    /**
     * @return string
     */
    protected function emptyLogicClassName()
    {
        return EmptyChunkLogic::class;
    }

    /**
     * @return string
     */
    protected function closureLogicClassName()
    {
        return ClosureChunkLogic::class;
    }

    /**
     * @return string
     */
    protected function logicInterfaceName()
    {
        return ChunkLogicInterface::class;
    }
}
