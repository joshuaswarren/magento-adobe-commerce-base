<?php

namespace Creatuity\Base\Model\CsvParser;

use Closure;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
interface CsvParserInterface extends \IteratorAggregate
{
    public function parse(string $fileName): self;

    /**
     * @param Closure|string $logicModelInstanceOrName
     * @return self
     */
    public function applyLogic($logicModelInstanceOrName): self;

    /**
     * @param Closure|string $logicModelInstanceOrName
     * @return self
     */
    public function applyChunkLogic($logicModelInstanceOrName): self;

    public function run(): ?array;

    public function showProgress(OutputInterface $output = null, int $showProgressOnEveryChunk = null, string $progressMessage = null): self;

    public function columnSeparator(string $separator): self;

    public function withTrimmingValues(bool $withTrimmingValues): self;

    public function enclosure(string $enclosure): self;

    public function escapeChar(string $escapeChar): self;

    public function calculateTotalRowsNumBeforeProcessing(bool $calculateTotalRowsNumBeforeProcessing): self;

    public function withHeader(bool $withHeader): self;

    public function chunkSize(int $rowsInChunkCount): self;
}
