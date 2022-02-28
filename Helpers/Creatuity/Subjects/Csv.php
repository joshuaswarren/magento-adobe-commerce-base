<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Creatuity\Base\Helpers\Creatuity;
use Creatuity\Base\Model\CsvParser\CsvParserInterface;
use Creatuity\Base\Model\CsvParser\CsvParserInterfaceFactory;
use Creatuity\Base\Model\CsvParser\OutputInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
class Csv extends SubjectAbstract implements CsvParserInterface, SubjectForModuleInterface
{
    /**
     * @var CsvParserInterface
     */
    protected $csvParser;

    /**
     * @var string
     */
    protected $moduleName;

    public function __construct(CsvParserInterfaceFactory $csvParserInterfaceFactory, Creatuity $creatuity)
    {
        parent::__construct($creatuity);
        $this->csvParser = $csvParserInterfaceFactory->create();
    }


    /**
     * @param string $fileName
     */
    public function parse($fileName)
    {
        $this->csvParser->parse(
            $this->creatuity()->resources($this->moduleName)->fileAbsPath($this->csvFilesPath() . DIRECTORY_SEPARATOR . $fileName)
        );
        return $this;
    }

    /**
     * @return string
     */
    protected function csvFilesPath()
    {
        return '';
    }

    /**
     * @return array|null
     */
    public function run()
    {
        return $this->csvParser->run();
    }

    /**
     * @return \Traversable
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return $this->csvParser->getIterator();
    }

    /**
     * @param \Closure|string $logicModelInstanceOrName
     * @return $this
     */
    public function applyLogic($logicModelInstanceOrName)
    {
        $this->csvParser->applyLogic($logicModelInstanceOrName);
        return $this;
    }

    public function applyChunkLogic($logicModelInstanceOrName)
    {
        $this->csvParser->applyChunkLogic($logicModelInstanceOrName);
        return $this;
    }

    /**
     * @param int $showProgressOnEveryChunk
     * @param string $progressMessage
     * @return $this
     */
    public function showProgress(OutputInterface $output = null, $showProgressOnEveryChunk = null, $progressMessage = null)
    {
        $this->csvParser->showProgress($output, $showProgressOnEveryChunk, $progressMessage);
        return $this;
    }

    /**
     * @param string $separator
     * @return $this
     */
    public function columnSeparator($separator)
    {
        $this->csvParser->columnSeparator($separator);
        return $this;
    }

    /**
     * @param bool $withTrimmingValues
     * @return $this
     */
    public function withTrimmingValues($withTrimmingValues)
    {
        $this->csvParser->withTrimmingValues($withTrimmingValues);
        return $this;
    }

    /**
     * @param string $enclosure
     * @return $this
     */
    public function enclosure($enclosure)
    {
        $this->csvParser->enclosure($enclosure);
        return $this;
    }

    /**
     * @param string $escapeChar
     * @return $this
     */
    public function escapeChar($escapeChar)
    {
        $this->csvParser->escapeChar($escapeChar);
        return $this;
    }

    /**
     * @param bool $calculateTotalRowsNumBeforeProcessing
     * @return $this
     */
    public function calculateTotalRowsNumBeforeProcessing($calculateTotalRowsNumBeforeProcessing)
    {
        $this->csvParser->calculateTotalRowsNumBeforeProcessing($calculateTotalRowsNumBeforeProcessing);
        return $this;
    }

    /**
     * @param bool $withHeader
     * @return $this
     */
    public function withHeader($withHeader)
    {
        $this->csvParser->withHeader($withHeader);
        return $this;
    }

    /**
     * @param int $rowsInChunkCount
     * @return $this
     */
    public function chunkSize($rowsInChunkCount)
    {
        $this->csvParser->chunkSize($rowsInChunkCount);
        return $this;
    }

    /**
     * @param string $moduleName
     * @return SubjectAbstract
     */
    public function forModule($moduleName)
    {
        $this->moduleName = $moduleName;
        return $this;
    }
}
