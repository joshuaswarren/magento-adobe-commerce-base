<?php

namespace Creatuity\Base\Model\CsvParser\Logic;

use Closure;
use Creatuity\Base\Model\CsvParser\ChunkLogicInterface;
use Creatuity\Base\Model\CsvParser\UtilityInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class ClosureChunkLogic implements ChunkLogicInterface
{
    private Closure $closure;

    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    public function beforeProcess(UtilityInterface $utility): void
    {
    }

    public function processChunk(array $chunkRows, UtilityInterface $utility): void
    {
        call_user_func($this->closure, $chunkRows, $utility);
    }

    public function afterProcess(UtilityInterface $utility): void
    {
    }
}
