<?php
namespace Creatuity\Base\Helpers;

/**
 * @package ygy
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 *
 * @deprecated use Creatuity::database()->normalizeDataSetForMultipleInsert(array $dataSet);
 */
class Database
{
    /**
     * @return array
     *
     * @deprecated use Creatuity::database()->normalizeDataSetForMultipleInsert(array $dataSet);
     */
    public function normalizeDataSetForMultipleInsert(array $dataSet)
    {
        $columnsOfEntitiesToCreate = [];
        foreach ($dataSet as $row) {
            $columnsOfEntitiesToCreate = array_unique(array_merge($columnsOfEntitiesToCreate, array_keys($row)));
        }

        $emptyRecord = array_combine($columnsOfEntitiesToCreate, array_fill(0, count($columnsOfEntitiesToCreate), null));

        foreach ($dataSet as &$row) {
            $row += $emptyRecord;
        }

        return $dataSet;
    }

}