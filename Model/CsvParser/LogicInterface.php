<?php

namespace Creatuity\Base\Model\CsvParser;

interface LogicInterface
{
    public function beforeProcess(UtilityInterface $utility): void;

    public function processRow(array $rowData, UtilityInterface $utility): void;

    public function afterProcess(UtilityInterface $utility): void;
}
