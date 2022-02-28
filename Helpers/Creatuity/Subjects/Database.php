<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Creatuity\Base\Helpers\Creatuity;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\App\ResourceConnection;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
class Database extends SubjectAbstract
{
    /**
     * @var ResourceConnection
     */
    protected $connectionsPool;

    public function __construct(Creatuity $creatuity, ResourceConnection $connectionsPool)
    {
        parent::__construct($creatuity);
        $this->connectionsPool = $connectionsPool;
    }


    public function runSqlFile($sqlIdentifier)
    {
        $sqlQuery = $this->creatuity()->resources()->fileRead("data/sql/{$sqlIdentifier}.sql");

        $this->runSql($sqlQuery);

        $this->creatuity()->report()->printSuccess("Executed sql file from 'data/sql/{$sqlIdentifier}.sql'");
    }

    public function runSql($sqlQuery)
    {
        // Hack for segmentation fault PCRE bug for preg_replace()
        // see http://stackoverflow.com/questions/20750757/php-segmentation-fault-during-preg-replace
        ini_set('pcre.recursion_limit', 10000);

        $this->dbConnection()->multiQuery($sqlQuery);
    }

    public function runInTransaction($callback, array $args = [])
    {
        try {
            $this->dbConnection()->beginTransaction();

            $return = call_user_func_array($callback, $args);

            $this->dbConnection()->commit();

            return $return;
        } catch (\Exception $e) {
            $this->dbConnection()->rollBack();
            throw $e;
        }
    }

    public function runWithForeignKeysDisabled($callback, array $args = [])
    {
        $foreignKeysStatus = (int)$this->dbConnection()->query('SELECT @@SESSION.FOREIGN_KEY_CHECKS;')->fetchColumn(0);
        try {
            $this->dbConnection()->query('SET FOREIGN_KEY_CHECKS=0;');

            return call_user_func_array($callback, $args);
        } finally {
            $this->dbConnection()->query('SET FOREIGN_KEY_CHECKS=' . $foreignKeysStatus . ';');
        }
    }

    /**
     * @param string $table
     * @return string
     */
    public function tableName($table)
    {
        return $this->connectionsPool->getTableName($table);
    }

    /**
     * @return AdapterInterface
     */
    public function dbConnection()
    {
        return $this->connectionsPool->getConnection();
    }

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