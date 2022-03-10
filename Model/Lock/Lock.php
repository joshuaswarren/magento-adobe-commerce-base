<?php

namespace Creatuity\Base\Model\Lock;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class Lock
{
    private Engine $engine;
    private string $name;

    public function __construct(
        Engine $engine,
        string $name
    ) {
        $this->engine = $engine;
        $this->name = $name;
    }

    public function lock(): bool
    {
        return $this->engine->lock($this->name);
    }

    public function tryLock($timeout = null): bool
    {
        return $this->engine->tryLock($this->name, $timeout);
    }

    public function unlock(): bool
    {
        return $this->engine->unlock($this->name);
    }

    /**
     * @param callable $callback
     * @return mixed
     */
    public function runInLock(callable $callback)
    {
        try {
            $this->lock();
            return call_user_func($callback);
        } finally {
            $this->unlock();
        }
    }

}
