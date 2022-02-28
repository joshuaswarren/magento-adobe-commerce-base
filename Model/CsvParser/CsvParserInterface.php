<?php

namespace Creatuity\Base\Model\CsvParser;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
interface CsvParserInterface extends \IteratorAggregate
{
    /**
     * @param string $fileName
     * @return $this
     */
    public function parse($fileName);

    /**
     * @param \Closure|string $logicModelInstanceOrName
     * @return $this
     */
    public function applyLogic($logicModelInstanceOrName);

    /**
     * @param \Closure|string $logicModelInstanceOrName
     * @return $this
     */
    public function applyChunkLogic($logicModelInstanceOrName);

    /**
     * @return array|null
     */
    public function run();

    /**
     * @param int $showProgressOnEveryChunk
     * @param string $progressMessage
     * @return $this
     */
    public function showProgress(OutputInterface $output = null, $showProgressOnEveryChunk = null, $progressMessage = null);

    /**
     * @param string $separator
     * @return $this
     */
    public function columnSeparator($separator);

    /**
     * @param bool $withTrimmingValues
     * @return $this
     */
    public function withTrimmingValues($withTrimmingValues);

    /**
     * @param string $enclosure
     * @return $this
     */
    public function enclosure($enclosure);

    /**
     * @param string $escapeChar
     * @return $this
     */
    public function escapeChar($escapeChar);

    /**
     * @param bool $calculateTotalRowsNumBeforeProcessing
     * @return $this
     */
    public function calculateTotalRowsNumBeforeProcessing($calculateTotalRowsNumBeforeProcessing);

    /**
     * @param bool $withHeader
     * @return $this
     */
    public function withHeader($withHeader);

    /**
     * @param int $rowsInChunkCount
     * @return $this
     */
    public function chunkSize($rowsInChunkCount);
}