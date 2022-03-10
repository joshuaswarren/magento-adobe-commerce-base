<?php

namespace Creatuity\Base\Model\CsvParser;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
interface ChunkLogicInterface
{
    public function beforeProcess(UtilityInterface $utility): void;

    public function processChunk(array $chunkRows, UtilityInterface $utility): void;

    public function afterProcess(UtilityInterface $utility): void;
}
