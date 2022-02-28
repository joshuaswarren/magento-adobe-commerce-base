<?php

namespace Creatuity\Base\Model\CsvParser;

use Creatuity\Base\Model\CsvParser\Logic\Row\RowLogicAdapterFactory;

class Parser implements CsvParserInterface, UtilityInterface
{
    const MINIMUM_CHUNK_SIZE = 1;

    const DEFAULT_CHUNK_SIZE = 100;

    const DEFAULT_PROGRESS_STEP = 1;

    const DEFAULT_PROGRESS_MESSAGE = 'Processed rows: %s from %s (%.2f %%)';

    /** @var string */
    protected $separator = ',';

    /** @var bool */
    protected $withTrimmingValues = true;

    /** @var string */
    protected $enclosure = '"';

    /** @var string */
    protected $escapeChar = '\\';

    /** @var bool */
    protected $calculateTotalRowsNumBeforeProcessing = true;

    /** @var string */
    protected $progressMessage;

    /** @var int */
    protected $rowCount = 0;

    /** @var bool */
    protected $withHeader = true;

    /** @var string */
    protected $filePath;

    /** @var \Closure|string */
    protected $logicModelInstanceOrName;

    /** @var OutputInterface */
    protected $output;

    /** @var OutputFactory */
    protected $outputFactory;

    /** @var ChunkLogicFactory */
    protected $chunkLogicFactory;

    /** @var int */
    protected $chunkSize;

    /** @var int */
    protected $showProgressOnEveryChunk;

    /** @var bool */
    protected $isParsingEnabled;

    /** @var CsvFileFactory */
    protected $csvFileFactory;

    /** @var RowLogicFactory */
    protected $rowLogicFactory;

    /** @var RowLogicAdapterFactory */
    protected $rowLogicAdapterFactory;

    /** @var bool */
    protected $isFirst;

    /** @var bool */
    protected $isLast;

    public function __construct(
        ChunkLogicFactory $chunkLogicFactory,
        RowLogicFactory $rowLogicFactory,
        RowLogicAdapterFactory $rowLogicAdapterFactory,
        OutputFactory $outputFactory,
        CsvFileFactory $csvReadFactory
    )
    {
        $this->chunkLogicFactory = $chunkLogicFactory;
        $this->outputFactory = $outputFactory;
        $this->csvFileFactory = $csvReadFactory;
        $this->chunkSize = static::DEFAULT_CHUNK_SIZE;
        $this->progressMessage = static::DEFAULT_PROGRESS_MESSAGE;
        $this->showProgressOnEveryChunk = static::DEFAULT_PROGRESS_STEP;
        $this->rowLogicFactory = $rowLogicFactory;
        $this->rowLogicAdapterFactory = $rowLogicAdapterFactory;
    }

    /**
     * @return \Traversable
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        foreach($this->process() as $rows) {
            foreach($rows as $row) {
                yield $row;
            }
        }
    }

    /**
     * @return array|null
     */
    public function run()
    {
        $wholeRows = [];

        foreach($this->process() as $rows) {

            if (!$this->logicModelInstanceOrName) {
                $wholeRows = array_merge($wholeRows, $rows);
            }
        }

        return !$this->logicModelInstanceOrName ? $wholeRows : null;
    }

    protected function process()
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
                    } catch ( \Exception $e ) {
                        $msg  = sprintf('PHP array_combine error: Header and data row columns count mismatch. Header has %s columns, data row has %s columns', count($headerRow), count($rowData)) . PHP_EOL;
                        $msg .= sprintf('Line number: %s', $processedRowNumber + 1) . PHP_EOL;
                        $msg .= 'Header: ' . print_r($headerRow, true) . PHP_EOL;
                        $msg .= 'Row: ' . print_r($rowData, true);
                        throw new \Exception($msg);
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

    protected function countRowsNumber()
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
     * @return array
     */
    protected function loadCsvLineFromFile(CsvFile $fileRead)
    {
        return $fileRead->readCsv($this->separator, $this->enclosure, $this->escapeChar);
    }

    /**
     * @param int $processedRowNumber
     */
    protected function displayProgress($processedRowNumber, $additionalConditionResult = true)
    {
        if ( $additionalConditionResult
            && $this->logicModelInstanceOrName && $this->hasActiveOutput()
            && ceil($processedRowNumber / $this->chunkSize) % $this->showProgressOnEveryChunk == 0
        ) {
            $this->output->writeln(sprintf($this->progressMessage, $processedRowNumber, $this->rowCount, ($processedRowNumber / $this->rowCount) * 100));
        }
    }

    /**
     * @param string $separator
     *
     * @return $this
     */
    public function columnSeparator($separator)
    {
        $this->separator = $separator;
        return $this;
    }

    /**
     * @param bool $withTrimmingValues
     *
     * @return $this
     */
    public function withTrimmingValues($withTrimmingValues)
    {
        $this->withTrimmingValues = $withTrimmingValues;
        return $this;
    }

    /**
     * @param string $enclosure
     *
     * @return $this
     */
    public function enclosure($enclosure)
    {
        $this->enclosure = $enclosure;
        return $this;
    }

    /**
     * @param string $escapeChar
     *
     * @return $this
     */
    public function escapeChar($escapeChar)
    {
        $this->escapeChar = $escapeChar;
        return $this;
    }

    /**
     * @param bool $calculateTotalRowsNumBeforeProcessing
     *
     * @return $this
     */
    public function calculateTotalRowsNumBeforeProcessing($calculateTotalRowsNumBeforeProcessing)
    {
        $this->calculateTotalRowsNumBeforeProcessing = $calculateTotalRowsNumBeforeProcessing;
        return $this;
    }

    /**
     * @param bool $withHeader
     *
     * @return $this
     */
    public function withHeader($withHeader)
    {
        $this->withHeader = $withHeader;
        return $this;
    }

    /**
     * @param int $showProgressOnEveryChunk
     * @param string $progressMessage
     *
     * @return $this
     */
    public function showProgress(OutputInterface $output = null, $showProgressOnEveryChunk = null, $progressMessage = null)
    {
        $this->output = is_null($output) ? $this->outputFactory->create() : $output;
        $this->showProgressOnEveryChunk = !is_null($showProgressOnEveryChunk) ? $showProgressOnEveryChunk : static::DEFAULT_PROGRESS_STEP;
        $this->progressMessage = !is_null($progressMessage) ? $progressMessage : static::DEFAULT_PROGRESS_MESSAGE;

        return $this;
    }

    /**
     * @param \Closure|string $logicModelInstanceOrName
     *
     * @return $this
     */
    public function applyLogic($logicModelInstanceOrName)
    {
        $rowLogicInstance = $this->rowLogicFactory->create($logicModelInstanceOrName);

        $this->logicModelInstanceOrName = $this->rowLogicAdapterFactory->create(['rowLogic' => $rowLogicInstance, 'parser' => $this]);
        return $this;
    }

    /**
     * @param int $chunkSize
     * @param \Closure|string $logicModelInstanceOrName
     * @return $this
     */
    public function applyChunkLogic($logicModelInstanceOrName)
    {
        $this->logicModelInstanceOrName = $logicModelInstanceOrName;
        return $this;
    }

    /**
     * @param string $fileName
     *
     * @return $this
     */
    public function parse($fileName)
    {
        $this->filePath = $fileName;
        return $this;
    }

    /**
     * @return bool
     */
    protected function hasActiveOutput()
    {
        return !empty($this->progressMessage) && $this->output instanceof OutputInterface;
    }

    /**
     * @return int
     */
    public function rowCount()
    {
        return $this->rowCount;
    }

    /**
     * @param int $rowsInChunkCount
     * @return $this
     */
    public function chunkSize($rowsInChunkCount)
    {
        if ( $rowsInChunkCount < static::MINIMUM_CHUNK_SIZE ) {
            throw new ParserException(sprintf('Invalid chunk size: %s - Chunk size must be equal %s or more', $rowsInChunkCount, static::MINIMUM_CHUNK_SIZE));
        }

        $this->chunkSize = $rowsInChunkCount;
        return $this;
    }

    public function stop()
    {
        $this->isParsingEnabled = false;
    }

    /**
     * @return bool
     */
    public function isRunning()
    {
        return $this->isParsingEnabled;
    }

    public function setIsFirst($isFirst = true)
    {
        $this->isFirst = $isFirst;
        return $this;
    }

    public function setIsLast($isLast = true)
    {
        $this->isLast = $isLast;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFirst()
    {
        return $this->isFirst;
    }

    /**
     * @return bool
     */
    public function isLast()
    {
        return $this->isLast;
    }
}
