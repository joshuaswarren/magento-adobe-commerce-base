<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Creatuity\Base\Helpers\Creatuity;
use Creatuity\Base\Helpers\Creatuity\Subjects\Report\ReportObserverInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
class Report extends SubjectAbstract
{
    /**
     * @var Creatuity
     */
    protected $helper;

    /**
     * @var int
     */
    protected $nextOutputNeedsNewlines = 0;

    /**
     * @var bool
     */
    protected $printedAtLeastOnce = false;

    /**
     * @var string
     */
    protected $lastThing;

    /**
     * @var ReportObserverInterface[]
     */
    protected $observers = [];

    /**
     * @var OutputInterface
     */
    protected $output;

    public function __construct(OutputInterface $output, LoggerInterface $logger, Creatuity $creatuity)
    {
        parent::__construct($creatuity);
        $this->logger = $logger;
        $this->output = $output;
    }

    /**
     * @return OutputInterface
     */
    public function output()
    {
        return $this->output;
    }

    public function ensureNextOutputWillBeSeparated($numOfLines = 1)
    {
        $this->nextOutputNeedsNewlines = $numOfLines;
    }

    public function registerObserver(ReportObserverInterface $observer)
    {
        $this->observers[spl_object_hash($observer)] = $observer;
    }

    public function unregisterObserver(ReportObserverInterface $observer)
    {
        unset($this->observers[spl_object_hash($observer)]);
    }

    public function printProgressIndicator()
    {
        $this->printCreatuityHeader();
        $this->output->write('.');
        $this->ensureNextOutputWillBeSeparated();
        $this->lastThing = 'progress';
    }

    public function printMessage($txt)
    {
        $this->newlineIfNeeded();
        $this->printCreatuityHeader();
        $this->output->writeln($txt);
        $this->lastThing = 'message';
        $this->callObservers('printMessage', [
            'txt' => $txt,
        ]);
    }

    public function printSuccess($txt)
    {
        $this->newlineIfNeeded();
        $this->printCreatuityHeader();
        $this->output->writeln('<fg=green>' . $txt . '</>');
        $this->lastThing = 'success';
        $this->callObservers('printSuccess', [
            'txt' => $txt,
        ]);
    }

    public function printWarning($txt)
    {
        $this->newlineIfNeeded();
        $this->printCreatuityHeader();
        $this->output->writeln('<fg=yellow>WARNING: ' . $txt . '</>');
        $this->lastThing = 'warning';
        $this->callObservers('printWarning', [
            'txt' => $txt,
        ]);
    }

    public function printError($txt, \Exception $e = null)
    {
        $this->newlineIfNeeded();
        $this->printCreatuityHeader();
        $this->output->writeln('<fg=red>ERROR: ' . $txt . '</>');
        if ($this->output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $this->output->writeln($e->getTraceAsString());
        }
        if ($e) {
            $this->logger->critical($e);
        }
        $this->lastThing = 'error';
        $this->callObservers('printErrror', [
            'txt' => $txt,
            'exception' => $e,
        ]);
    }

    public function printLine($char = '-', $doNotStackLines = true)
    {
        if ($doNotStackLines && $this->lastThing == 'line') {
            return;
        }

        $this->newlineIfNeeded();
        $this->printCreatuityHeader();

        $this->output->write(str_repeat($char, 130));

        $this->output->writeln('');
        $this->lastThing = 'line';
        $this->callObservers('printLine', [
            'txt' => $char,
        ]);
    }

    public function printEmptySeparator($numOf = 1)
    {
        for ($i = 0; $i < $numOf; ++$i) {
            $this->output->writeln("");
        }
        $this->lastThing = 'empty_separator';
        $this->callObservers('printEmptySeparator', [
            'num_of' => $numOf,
        ]);
    }

    protected function printCreatuityHeader()
    {
        if ($this->printedAtLeastOnce) {
            return;
        }

        $this->output->write('~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~  ');
        $this->creatuity()->creatuityLogo()->writeCreatuityLogo($this->output);
        $this->output->writeln(' UPGRADE SCRIPTS  ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~');
        $this->output->writeln("");

        $this->printedAtLeastOnce = true;
        $this->lastThing = 'creatuity_header';
    }

    protected function newlineIfNeeded()
    {
        if (!$this->nextOutputNeedsNewlines) {
            return;
        }

        $hungProtector = 1000;
        do {
            if (!--$hungProtector) {
                throw new \Exception("Bad programmer!");
            }

            $this->output->writeln("");
        } while (--$this->nextOutputNeedsNewlines);
        $this->lastThing = 'needed_newline';
    }

    protected function callObservers($eventName, array $args = [])
    {
        foreach($this->observers as $observer) {
            $observer->handleReportEvent($eventName, $args);
        }
    }
}