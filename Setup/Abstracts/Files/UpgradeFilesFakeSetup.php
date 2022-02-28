<?php
namespace Creatuity\Base\Setup\Abstracts\Files;

use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 *
 * Magento Setup module runs out of ordinary ObjectManager.
 * that's why we need that fake class, so we can use run setup scripts also from inside of the Magento
 *
 * @package waltwo
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2017 Joshua Warren (https://warrenappliedlabs.com)
 */
class UpgradeFilesFakeSetup implements ModuleDataSetupInterface
{

    public function getTableRow($table, $idField, $rowId, $field = null, $parentField = null, $parentId = 0)
    {
        throw new \Exception("Invalid usage. This is class is fake");
    }

    public function deleteTableRow($table, $idField, $rowId, $parentField = null, $parentId = 0)
    {
        throw new \Exception("Invalid usage. This is class is fake");
    }

    public function updateTableRow($table, $idField, $rowId, $field, $value = null, $parentField = null, $parentId = 0)
    {
        throw new \Exception("Invalid usage. This is class is fake");
    }

    public function getEventManager()
    {
        throw new \Exception("Invalid usage. This is class is fake");
    }

    public function getFilesystem()
    {
        throw new \Exception("Invalid usage. This is class is fake");
    }

    public function createMigrationSetup(array $data = [])
    {
        throw new \Exception("Invalid usage. This is class is fake");
    }

    public function getSetupCache()
    {
        throw new \Exception("Invalid usage. This is class is fake");
    }

    public function getConnection()
    {
        throw new \Exception("Invalid usage. This is class is fake");
    }

    public function setTable($tableName, $realTableName)
    {
        throw new \Exception("Invalid usage. This is class is fake");
    }

    public function getTable($tableName)
    {
        throw new \Exception("Invalid usage. This is class is fake");
    }

    public function getTablePlaceholder($tableName)
    {
        throw new \Exception("Invalid usage. This is class is fake");
    }

    public function tableExists($table)
    {
        throw new \Exception("Invalid usage. This is class is fake");
    }

    public function run($sql)
    {
        throw new \Exception("Invalid usage. This is class is fake");
    }

    public function startSetup()
    {
        // we run that from our code
    }

    public function endSetup()
    {
        // we run that from our code
    }
}