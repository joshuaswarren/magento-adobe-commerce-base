<?php

namespace Creatuity\Base\Model\Lock\Engine;

use Creatuity\Base\Model\Lock\Engine;
use Magento\Framework\App\Filesystem\DirectoryList;

class File implements Engine
{

    /** @var DirectoryList */
    protected $dir;


	public function __construct(
		DirectoryList $dir
	) {
		$this->dir = $dir;
	}

    public function lock($name)
    {
        $fp = $this->getLockFp($name);
        flock($fp, LOCK_EX);
    }

    public function tryLock($name, $timeout = null)
    {
        $fp = $this->getLockFp($name);
        return flock($fp, LOCK_EX | LOCK_NB);
    }

    public function unlock($name)
    {
        return (bool)flock($this->getLockFp($name), LOCK_UN);
    }

    protected function getLockFp($name)
    {
        if (!isset($this->lockp)) {
            $path = $this->dir->getPath(DirectoryList::VAR_DIR) . DIRECTORY_SEPARATOR . 'lock_' . $name . '.pid';
            $this->lockp = fopen($path, 'w+');
            fwrite($this->lockp, 'PID:' . getmypid());
        }
        return $this->lockp;
    }

}