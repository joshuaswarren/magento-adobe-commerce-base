<?php

namespace Creatuity\Base\Model\Lock\Engine;

use Creatuity\Base\Model\Lock\Engine;
use Exception;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Zend_Db_Statement_Exception;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class Database implements Engine
{
    private ResourceConnection $resourceConnection;

	public function __construct(
		ResourceConnection $resourceConnection
	) {
		$this->resourceConnection = $resourceConnection;
	}

    /**
     * @throws Exception
     */
    public function lock(string $name): bool
    {
	    if (!$this->tryLock($name, -1)) {
	        throw new Exception('mysql lock cannot be obtained');
        }

        return true;
    }

    /**
     * @throws Zend_Db_Statement_Exception
     */
    public function tryLock(string $name, int $timeout = null): bool
    {
        return $this->getConnection()->query('SELECT GET_LOCK(?, ?)', [$name, $timeout])->fetchColumn() == '1';
    }

    public function unlock(string $name): bool
    {
        $this->getConnection()->query('SELECT RELEASE_LOCK(?)', [$name]);

        return true;
    }

    private function getConnection(): AdapterInterface
    {
        return $this->resourceConnection->getConnection();
    }

}
