<?php

namespace Creatuity\Base\Model\CsvParser\Logic;

use Creatuity\Base\Model\CsvParser\ChunkLogicInterface;
use Creatuity\Base\Model\CsvParser\UtilityInterface;

class ClosureChunkLogic implements ChunkLogicInterface
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

    public function processChunk(array $chunkRows, UtilityInterface $utility)
    {
        call_user_func($this->closure, $chunkRows, $utility);
    }

    public function afterProcess(UtilityInterface $utility)
    {
    }
}
