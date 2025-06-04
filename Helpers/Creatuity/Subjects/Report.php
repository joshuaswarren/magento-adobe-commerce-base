<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Creatuity\Base\Helpers\Creatuity;
use Creatuity\Base\Helpers\Creatuity\Subjects\Report\ReportObserverInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class Report extends SubjectAbstract
{
    private int $nextOutputNeedsNewlines = 0;
    private bool $printedAtLeastOnce = false;
    private string $lastThing;
    private OutputInterface $output;
    private LoggerInterface $logger;

    /**
     * @var ReportObserverInterface[]
     */
    private array $observers = [];

    public function __construct(OutputInterface $output, LoggerInterface $logger, Creatuity $creatuity)
    {
        parent::__construct($creatuity);
        $this->logger = $logger;
        $this->output = $output;
    }

    public function output(): OutputInterface
    {
        return $this->output;
    }

    public function ensureNextOutputWillBeSeparated(int $numOfLines = 1): void
    {
        $this->nextOutputNeedsNewlines = $numOfLines;
    }

    public function registerObserver(ReportObserverInterface $observer): void
    {
        $this->observers[spl_object_hash($observer)] = $observer;
    }

    public function unregisterObserver(ReportObserverInterface $observer): void
    {
        unset($this->observers[spl_object_hash($observer)]);
    }

    public function printProgressIndicator(): void
    {
        $this->printCreatuityHeader();
        $this->output->write('.');
        $this->ensureNextOutputWillBeSeparated();
        $this->lastThing = 'progress';
    }

    public function printMessage(string $txt): void
    {
        $this->newlineIfNeeded();
        $this->printCreatuityHeader();
        $this->output->writeln($txt);
        $this->lastThing = 'message';
        $this->callObservers('printMessage', [
            'txt' => $txt,
        ]);
    }

    public function printSuccess(string $txt): void
    {
        $this->newlineIfNeeded();
        $this->printCreatuityHeader();
        $this->output->writeln('<fg=green>' . $txt . '</>');
        $this->lastThing = 'success';
        $this->callObservers('printSuccess', [
            'txt' => $txt,
        ]);
    }

    public function printWarning(string $txt): void
    {
        $this->newlineIfNeeded();
        $this->printCreatuityHeader();
        $this->output->writeln('<fg=yellow>WARNING: ' . $txt . '</>');
        $this->lastThing = 'warning';
        $this->callObservers('printWarning', [
            'txt' => $txt,
        ]);
    }

    public function printError(string $txt, ?\Exception $e = null): void
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

    public function printLine(string $char = '-', bool $doNotStackLines = true): void
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

    public function printEmptySeparator(int $numOf = 1): void
    {
        for ($i = 0; $i < $numOf; ++$i) {
            $this->output->writeln("");
        }
        $this->lastThing = 'empty_separator';
        $this->callObservers('printEmptySeparator', [
            'num_of' => $numOf,
        ]);
    }

    private function printCreatuityHeader(): void
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

    private function newlineIfNeeded(): void
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

    private function callObservers(string $eventName, array $args = []): void
    {
        foreach($this->observers as $observer) {
            $observer->handleReportEvent($eventName, $args);
        }
    }
}
