<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Closure;
use Creatuity\Base\Helpers\Creatuity;
use Creatuity\Base\Model\CsvParser\CsvParserInterface;
use Creatuity\Base\Model\CsvParser\CsvParserInterfaceFactory;
use Creatuity\Base\Model\CsvParser\OutputInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class Csv extends SubjectAbstract implements CsvParserInterface, SubjectForModuleInterface
{
    private CsvParserInterface $csvParser;
    private string $moduleName;

    public function __construct(CsvParserInterfaceFactory $csvParserInterfaceFactory, Creatuity $creatuity)
    {
        parent::__construct($creatuity);
        $this->csvParser = $csvParserInterfaceFactory->create();
    }

    /**
     * @throws ResourcesHelperException
     * @throws Exception\ModuleNotSetException
     */
    public function parse(string $fileName): self
    {
        $this->ensureModuleIsSet();

        $this->csvParser->parse(
            $this->creatuity()->resources($this->moduleName)->fileAbsPath($this->csvFilesPath() . DIRECTORY_SEPARATOR . $fileName)
        );

        return $this;
    }

    protected function csvFilesPath(): string
    {
        return 'data' . DIRECTORY_SEPARATOR . 'csv';
    }

    public function run(): ?array
    {
        return $this->csvParser->run();
    }

    #[\ReturnTypeWillChange]
    public function getIterator(): \Traversable
    {
        return $this->csvParser->getIterator();
    }

    /**
     * @param Closure|string $logicModelInstanceOrName
     * @return self
     */
    public function applyLogic($logicModelInstanceOrName): self
    {
        $this->csvParser->applyLogic($logicModelInstanceOrName);
        return $this;
    }

    /**
     * @param Closure|string $logicModelInstanceOrName
     * @return self
     */
    public function applyChunkLogic($logicModelInstanceOrName): self
    {
        $this->csvParser->applyChunkLogic($logicModelInstanceOrName);
        return $this;
    }

    public function showProgress(OutputInterface $output = null, int $showProgressOnEveryChunk = null, string $progressMessage = null): self
    {
        $this->csvParser->showProgress($output, $showProgressOnEveryChunk, $progressMessage);
        return $this;
    }

    public function columnSeparator(string $separator): self
    {
        $this->csvParser->columnSeparator($separator);
        return $this;
    }

    public function withTrimmingValues(bool $withTrimmingValues): self
    {
        $this->csvParser->withTrimmingValues($withTrimmingValues);
        return $this;
    }

    public function enclosure(string $enclosure): self
    {
        $this->csvParser->enclosure($enclosure);
        return $this;
    }

    public function escapeChar(string $escapeChar): self
    {
        $this->csvParser->escapeChar($escapeChar);
        return $this;
    }

    public function calculateTotalRowsNumBeforeProcessing(bool $calculateTotalRowsNumBeforeProcessing): self
    {
        $this->csvParser->calculateTotalRowsNumBeforeProcessing($calculateTotalRowsNumBeforeProcessing);
        return $this;
    }

    public function withHeader(bool $withHeader): self
    {
        $this->csvParser->withHeader($withHeader);
        return $this;
    }

    public function chunkSize(int $rowsInChunkCount): self
    {
        $this->csvParser->chunkSize($rowsInChunkCount);
        return $this;
    }

    public function forModule(string $moduleName): self
    {
        $this->moduleName = $moduleName;
        return $this;
    }

    public function ensureModuleIsSet(): void
    {
        if (empty($this->moduleName)) {
            throw new Creatuity\Subjects\Exception\ModuleNotSetException();
        }
    }
}
