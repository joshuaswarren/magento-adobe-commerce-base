<?php

namespace Creatuity\Base\Model\Lock\Engine;

use Creatuity\Base\Model\Lock\Engine;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class File implements Engine
{
    private DirectoryList $dir;

    /**
     * @var false|resource
     */
    private $lockp = null;

    public function __construct(
		DirectoryList $dir
	) {
		$this->dir = $dir;
	}

    /**
     * @throws FileSystemException
     */
    public function lock(string $name): bool
    {
        $fp = $this->getLockFp($name);

        flock($fp, LOCK_EX);

        return true;
    }

    /**
     * @throws FileSystemException
     */
    public function tryLock(string $name, ?int $timeout = null): bool
    {
        $fp = $this->getLockFp($name);

        return flock($fp, LOCK_EX | LOCK_NB);
    }

    /**
     * @throws FileSystemException
     */
    public function unlock(string $name): bool
    {
        flock($this->getLockFp($name), LOCK_UN);

        return true;
    }

    /**
     * @throws FileSystemException
     */
    private function getLockFp(string $name)
    {
        if (!isset($this->lockp)) {
            $path = $this->dir->getPath(DirectoryList::VAR_DIR) . DIRECTORY_SEPARATOR . 'lock_' . $name . '.pid';
            $this->lockp = fopen($path, 'w+');
            fwrite($this->lockp, 'PID:' . getmypid());
        }

        return $this->lockp;
    }

}
