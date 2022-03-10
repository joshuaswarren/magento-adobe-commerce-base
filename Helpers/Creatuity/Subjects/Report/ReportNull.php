<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects\Report;

use Creatuity\Base\Helpers\Creatuity;
use Creatuity\Base\Helpers\Creatuity\Subjects\Report;
use Exception;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class ReportNull extends Report
{
    public function __construct()
    {
    }

    public function ensureNextOutputWillBeSeparated($numOfLines = 1): void
    {
    }

    public function registerObserver(ReportObserverInterface $observer): void
    {
    }

    public function unregisterObserver(ReportObserverInterface $observer): void
    {
    }

    public function printProgressIndicator(): void
    {
    }

    public function printMessage(string $txt): void
    {
    }

    public function printSuccess(string $txt): void
    {
    }

    public function printWarning(string $txt): void
    {
    }

    public function printError(string $txt, Exception $e = null): void
    {
    }

    public function printLine(string $char = '-', bool $doNotStackLines = true): void
    {
    }

    public function printEmptySeparator(int $numOf = 1): void
    {
    }
}
