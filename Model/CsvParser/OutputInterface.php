<?php

namespace Creatuity\Base\Model\CsvParser;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
interface OutputInterface
{
    public function writeln(string $text): void;
}
