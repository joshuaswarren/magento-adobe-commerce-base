<?php

namespace Creatuity\Base\Model\CsvParser;

/**
 * @category Creatuity
 * @package intcb
 * @copyright Copyright (c) 2008-2017 Joshua Warren (https://warrenappliedlabs.com)
 * @license https://warrenappliedlabs.com/license
 */
interface UtilityInterface
{
    /**
     * @return int
     */
    public function rowCount();

    public function stop();

    /**
     * @return bool
     */
    public function isFirst();

    /**
     * @return bool
     */
    public function isLast();
}
