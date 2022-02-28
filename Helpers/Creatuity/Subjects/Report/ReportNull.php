<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects\Report;

use Creatuity\Base\Helpers\Creatuity;
use Creatuity\Base\Helpers\Creatuity\Subjects\Report;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
class ReportNull extends Report
{
    public function __construct()
    {
    }


    public function ensureNextOutputWillBeSeparated($numOfLines = 1)
    {
        return null;
    }

    public function registerObserver(ReportObserverInterface $observer)
    {
        return null;
    }

    public function unregisterObserver(ReportObserverInterface $observer)
    {
        return null;
    }

    public function printProgressIndicator()
    {
        return null;
    }

    public function printMessage($txt)
    {
        return null;
    }

    public function printSuccess($txt)
    {
        return null;
    }

    public function printWarning($txt)
    {
        return null;
    }

    public function printError($txt, \Exception $e = null)
    {
        return null;
    }

    public function printLine($char = '-', $doNotStackLines = true)
    {
        return null;
    }

    public function printEmptySeparator($numOf = 1)
    {
        return null;
    }
}