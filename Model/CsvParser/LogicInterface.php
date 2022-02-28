<?php

namespace Creatuity\Base\Model\CsvParser;

interface LogicInterface
{
    public function beforeProcess(UtilityInterface $utility);

    public function processRow(array $rowData, UtilityInterface $utility);

    public function afterProcess(UtilityInterface $utility);
}
