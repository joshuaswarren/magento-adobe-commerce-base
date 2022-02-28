<?php

namespace Creatuity\Base\Model\CsvParser;

/**
 * @category Creatuity
 * @package intgshop
 * @copyright Copyright (c) 2008-2017 Joshua Warren (https://warrenappliedlabs.com)
 * @license https://warrenappliedlabs.com/license
 */
interface OutputInterface
{
    /**
     * @param string $text
     * @return $this
     */
    public function writeln($text);
}