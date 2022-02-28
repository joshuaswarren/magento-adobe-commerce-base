<?php

namespace Creatuity\Base\Model\Lock\Engine;

use Creatuity\Base\Model\Lock\Engine;
use Magento\Framework\App\ResourceConnection;

class Database implements Engine
{

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

	public function __construct(
		ResourceConnection $resourceConnection
	) {
		$this->resourceConnection = $resourceConnection;
	}

    public function lock($name) {
	    if (!$this->tryLock($name, -1)) {
	        throw new \Exception('mysql lock filed');
        }
    }

    public function tryLock($name, $timeout = null) {
        return $this->getConnection()->query('SELECT GET_LOCK(?, ?)', [$name, $timeout])->fetchColumn() == '1';
    }

    public function unlock($name) {
        $this->getConnection()->query('SELECT RELEASE_LOCK(?)', [$name]);
    }

    protected function getConnection()
    {
        return $this->resourceConnection->getConnection();
    }

}