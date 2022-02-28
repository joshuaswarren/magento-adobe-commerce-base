<?php

namespace Creatuity\Base\Model\CsvParser\Logic;

use Creatuity\Base\Model\CsvParser\ChunkLogicInterface;
use Creatuity\Base\Model\CsvParser\UtilityInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
class EmptyChunkLogic implements ChunkLogicInterface
{
    public function beforeProcess(UtilityInterface $utility)
    {
    }

    public function processChunk(array $chunkRows, UtilityInterface $utility)
    {
    }

    public function afterProcess(UtilityInterface $utility)
    {
    }
}