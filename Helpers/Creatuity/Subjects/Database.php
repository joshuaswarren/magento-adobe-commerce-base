<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Creatuity\Base\Helpers\Creatuity;
use Exception;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\App\ResourceConnection;
use Zend_Db_Statement_Exception;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class Database extends SubjectAbstract
{
    private ResourceConnection $connectionsPool;

    public function __construct(
        Creatuity $creatuity,
        ResourceConnection $connectionsPool
    ) {
        parent::__construct($creatuity);
        $this->connectionsPool = $connectionsPool;
    }

    public function runSql(string $sqlQuery): void
    {
        // Hack for segmentation fault PCRE bug for preg_replace()
        // see http://stackoverflow.com/questions/20750757/php-segmentation-fault-during-preg-replace
        ini_set('pcre.recursion_limit', 10000);

        $this->dbConnection()->multiQuery($sqlQuery);
    }

    /**
     * @param callable $callback
     * @param array $args
     * @return mixed
     * @throws Exception
     */
    public function runInTransaction(callable $callback, array $args = [])
    {
        try {
            $this->dbConnection()->beginTransaction();

            $return = call_user_func_array($callback, $args);

            $this->dbConnection()->commit();

            return $return;
        } catch (Exception $e) {
            $this->dbConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param callable $callback
     * @param array $args
     * @return mixed
     * @throws Zend_Db_Statement_Exception
     */
    public function runWithForeignKeysDisabled(callable $callback, array $args = [])
    {
        $foreignKeysStatus = (int)$this->dbConnection()->query('SELECT @@SESSION.FOREIGN_KEY_CHECKS;')->fetchColumn(0);
        try {
            $this->dbConnection()->query('SET FOREIGN_KEY_CHECKS=0;');

            return call_user_func_array($callback, $args);
        } finally {
            $this->dbConnection()->query('SET FOREIGN_KEY_CHECKS=' . $foreignKeysStatus . ';');
        }
    }

    public function tableName(string $table): string
    {
        return $this->connectionsPool->getTableName($table);
    }

    public function dbConnection(): AdapterInterface
    {
        return $this->connectionsPool->getConnection();
    }

    public function normalizeDataSetForMultipleInsert(array $dataSet): array
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
