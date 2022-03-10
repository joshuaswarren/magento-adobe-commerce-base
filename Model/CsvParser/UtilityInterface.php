<?php

namespace Creatuity\Base\Model\CsvParser;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
interface UtilityInterface
{
    public function rowCount(): int;

    public function stop(): void;

    public function isFirst(): bool;

    public function isLast(): bool;
}
