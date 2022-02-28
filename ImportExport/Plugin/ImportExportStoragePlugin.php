<?php namespace Creatuity\Base\ImportExport\Plugin;
use Magento\ImportExport\Model\ResourceModel\Import\Data;

/**
 *
 * @package Project
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class ImportExportStoragePlugin
{

    protected $counter = 0;
    protected $bunchesCounter = 0;
    protected $printingEnabled = false;
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    private $output;

    public function __construct(
        \Symfony\Component\Console\Output\OutputInterface $output
    ) {
        $this->resetCounter();
        $this->output = $output;
    }

    public function openDumping()
    {
        $this->resetCounter();
        $this->enablePrintingProgress();
    }

    public function closeDumping()
    {
        $this->disablePrintingProgress();
        $this->resetCounter();
    }

    protected function resetCounter()
    {
        $this->counter = 0;
        $this->bunchesCounter = 0;
    }

    protected function enablePrintingProgress()
    {
        $this->printingEnabled = true;
    }

    protected function disablePrintingProgress()
    {
        $this->printingEnabled = false;
    }

    public function beforeSaveBunch(Data $subject, $entity, $behavior, array $data)
    {
        $this->counter += sizeof($data);
        ++$this->bunchesCounter;
        if ($this->printingEnabled) {
            $this->output->writeln("Number of saved csv items in database: {$this->counter} in total {$this->bunchesCounter} bunches");
        }
    }

}
