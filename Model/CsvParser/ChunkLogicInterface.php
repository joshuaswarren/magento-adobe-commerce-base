<?php

namespace Creatuity\Base\Model\CsvParser;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
interface ChunkLogicInterface
{
    public function beforeProcess(UtilityInterface $utility);

    /**
     * @param array[] $chunkRows
     * @return mixed
     */
    public function processChunk(array $chunkRows, UtilityInterface $utility);

    public function afterProcess(UtilityInterface $utility);
}