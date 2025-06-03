<?php

namespace Creatuity\Base\Model\CsvParser;

use Closure;
use Creatuity\Base\Model\CsvParser\Logic\Row\RowLogicAdapterFactory;
use Exception;
use Generator;
use Traversable;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class Parser implements CsvParserInterface, UtilityInterface
{
    private const MINIMUM_CHUNK_SIZE = 1;
    private const DEFAULT_PROGRESS_STEP = 1;

    private string $separator = ',';
    private bool $withTrimmingValues = true;
    private string $enclosure = '"';
    private string $escapeChar = '\\';
    private bool $calculateTotalRowsNumBeforeProcessing = true;
    private string $progressMessage = 'Processed rows: %s from %s (%.2f %%)';
    private int $chunkSize = 100;
    private int $rowCount = 0;
    private bool $withHeader = true;
    private string $filePath;
    private bool $isParsingEnabled;
    private bool $isFirst;
    private bool $isLast;

    /** @var Closure|string */
    private $logicModelInstanceOrName;

    private OutputInterface $output;

    private OutputFactory $outputFactory;
    private ChunkLogicFactory $chunkLogicFactory;
    private int $showProgressOnEveryChunk;
    private CsvFileFactory $csvFileFactory;
    private RowLogicFactory $rowLogicFactory;
    private RowLogicAdapterFactory $rowLogicAdapterFactory;

    public function __construct(
        ChunkLogicFactory $chunkLogicFactory,
        RowLogicFactory $rowLogicFactory,
        RowLogicAdapterFactory $rowLogicAdapterFactory,
        OutputFactory $outputFactory,
        CsvFileFactory $csvReadFactory
    ) {
        $this->outputFactory = $outputFactory;
        $this->chunkLogicFactory = $chunkLogicFactory;
        $this->showProgressOnEveryChunk = static::DEFAULT_PROGRESS_STEP;
        $this->csvFileFactory = $csvReadFactory;
        $this->rowLogicFactory = $rowLogicFactory;
        $this->rowLogicAdapterFactory = $rowLogicAdapterFactory;
    }

    #[\ReturnTypeWillChange]
    public function getIterator(): Traversable
    {
        foreach($this->process() as $rows) {
            foreach($rows as $row) {
                yield $row;
            }
        }
    }

    /**
     * @throws Exception
     */
    public function run(): ?array
    {
        $wholeRows = [];

        foreach($this->process() as $rows) {
            if (!$this->logicModelInstanceOrName) {
                $wholeRows = array_merge($wholeRows, $rows);
            }
        }

        return !$this->logicModelInstanceOrName ? $wholeRows : null;
    }

    /**
     * @throws ParserException
     */
    private function process(): Generator
    {
        $logicModelInstance = $this->chunkLogicFactory->create($this->logicModelInstanceOrName);

        if ($this->calculateTotalRowsNumBeforeProcessing || $this->hasActiveOutput()) {
            $this->countRowsNumber();
        }

        try {
            $fileRead = $this->csvFileFactory->create(['filePath' => $this->filePath]);
            $this->isParsingEnabled = true;

            $logicModelInstance->beforeProcess($this);
            $rows = [];

            if ($this->withHeader) {
                $headerRow = $this->loadCsvLineFromFile($fileRead);
            }

            $this->setIsFirst(true);
            $this->setIsLast(false);
            $processedRowNumber = 0;

            $rowData = $this->loadCsvLineFromFile($fileRead);
            while ($rowData && $this->isParsingEnabled) {
                $processedRowNumber++;
                if ($this->withHeader) {
                    try {
                        $rowData = array_combine($headerRow, $rowData);
                    } catch ( Exception $e ) {
                        $msg  = sprintf('PHP array_combine error: Header and data row columns count mismatch. Header has %s columns, data row has %s columns', count($headerRow), count($rowData)) . PHP_EOL;
                        $msg .= sprintf('Line number: %s', $processedRowNumber + 1) . PHP_EOL;
                        $msg .= 'Header: ' . print_r($headerRow, true) . PHP_EOL;
                        $msg .= 'Row: ' . print_r($rowData, true);
                        throw new Exception($msg);
                    }
                }

                if ($this->withTrimmingValues) {
                    $rowData = array_map('trim', $rowData);
                }

                $rows[] = $rowData;

                $nextRow = $this->loadCsvLineFromFile($fileRead);

                if ($nextRow && $processedRowNumber % $this->chunkSize > 0) {
                    $rowData = $nextRow;
                    continue;
                }

                if (!$nextRow) {
                    $this->setIsLast(true);
                }

                $logicModelInstance->processChunk($rows, $this);
                yield $rows;

                $this->setIsFirst(false);

                $rowData = $nextRow;

                $rows = [];

                $this->displayProgress($processedRowNumber, $this->isParsingEnabled);
            }

            $logicModelInstance->afterProcess($this);
        } finally {
            if ($fileRead) {
                $fileRead->close();
            }
        }
    }

    private function countRowsNumber(): void
    {
        $fileRead = null;
        try {
            $fileRead = $this->csvFileFactory->create(['filePath' => $this->filePath]);
            $count = 0;
            while ( $this->loadCsvLineFromFile($fileRead) ) {
                $count++;
            }
            $this->rowCount = $this->withHeader ? $count - 1 : $count;
        } finally {
            if ( $fileRead ) {
                $fileRead->close();
            }
        }
    }

    /**
     * @throws Exception
     */
    private function loadCsvLineFromFile(CsvFile $fileRead): array
    {
        return $fileRead->readCsv($this->separator, $this->enclosure, $this->escapeChar);
    }

    private function displayProgress(int $processedRowNumber, bool $additionalConditionResult = true): void
    {
        if ( $additionalConditionResult
            && $this->logicModelInstanceOrName && $this->hasActiveOutput()
            && ceil($processedRowNumber / $this->chunkSize) % $this->showProgressOnEveryChunk == 0
        ) {
            $this->output->writeln(sprintf($this->progressMessage, $processedRowNumber, $this->rowCount, ($processedRowNumber / $this->rowCount) * 100));
        }
    }

    public function columnSeparator(string $separator): self
    {
        $this->separator = $separator;
        return $this;
    }

    /**
     * @param bool $withTrimmingValues
     *
     * @return $this
     */
    public function withTrimmingValues($withTrimmingValues): self
    {
        $this->withTrimmingValues = $withTrimmingValues;
        return $this;
    }

    public function enclosure(string $enclosure): self
    {
        $this->enclosure = $enclosure;
        return $this;
    }

    public function escapeChar(string $escapeChar): self
    {
        $this->escapeChar = $escapeChar;
        return $this;
    }

    public function calculateTotalRowsNumBeforeProcessing(bool $calculateTotalRowsNumBeforeProcessing): self
    {
        $this->calculateTotalRowsNumBeforeProcessing = $calculateTotalRowsNumBeforeProcessing;
        return $this;
    }

    public function withHeader(bool $withHeader): self
    {
        $this->withHeader = $withHeader;
        return $this;
    }

    public function showProgress(?OutputInterface $output = null, ?int $showProgressOnEveryChunk = null, ?string $progressMessage = null): self
    {
        $this->output = is_null($output) ? $this->outputFactory->create() : $output;
        $this->showProgressOnEveryChunk = !is_null($showProgressOnEveryChunk) ? $showProgressOnEveryChunk : static::DEFAULT_PROGRESS_STEP;
        $this->progressMessage = !is_null($progressMessage) ? $progressMessage : $this->progressMessage;

        return $this;
    }

    /**
     * @param Closure|string $logicModelInstanceOrName
     * @return self
     * @throws ParserException
     */
    public function applyLogic($logicModelInstanceOrName): self
    {
        $rowLogicInstance = $this->rowLogicFactory->create($logicModelInstanceOrName);

        $this->logicModelInstanceOrName = $this->rowLogicAdapterFactory->create(['rowLogic' => $rowLogicInstance, 'parser' => $this]);
        return $this;
    }

    /**
     * @param Closure|string $logicModelInstanceOrName
     * @return self
     */
    public function applyChunkLogic($logicModelInstanceOrName): self
    {
        $this->logicModelInstanceOrName = $logicModelInstanceOrName;
        return $this;
    }

    public function parse(string $fileName): self
    {
        $this->filePath = $fileName;
        return $this;
    }

    private function hasActiveOutput(): bool
    {
        return !empty($this->progressMessage) && $this->output instanceof OutputInterface;
    }

    public function rowCount(): int
    {
        return $this->rowCount;
    }

    /**
     * @throws ParserException
     */
    public function chunkSize(int $rowsInChunkCount): self
    {
        if ($rowsInChunkCount < static::MINIMUM_CHUNK_SIZE) {
            throw new ParserException(sprintf('Invalid chunk size: %s - Chunk size must be equal %s or more', $rowsInChunkCount, static::MINIMUM_CHUNK_SIZE));
        }

        $this->chunkSize = $rowsInChunkCount;
        return $this;
    }

    public function stop(): void
    {
        $this->isParsingEnabled = false;
    }

    public function isRunning(): bool
    {
        return $this->isParsingEnabled;
    }

    public function setIsFirst(bool $isFirst = true): self
    {
        $this->isFirst = $isFirst;
        return $this;
    }

    public function setIsLast(bool $isLast = true): self
    {
        $this->isLast = $isLast;
        return $this;
    }

    public function isFirst(): bool
    {
        return $this->isFirst;
    }

    public function isLast(): bool
    {
        return $this->isLast;
    }
}
