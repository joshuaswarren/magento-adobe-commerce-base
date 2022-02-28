<?php

namespace Creatuity\Base\Model\Lock;

class Lock
{

    /** @var $name */
    protected $name;

    /** @var Engine */
    protected $engine;

    public function __construct(
        Engine $engine,
        $name
    )
    {
        $this->engine = $engine;
        $this->name = $name;
    }

    public function lock()
    {
        return $this->engine->lock($this->name);
    }

    public function tryLock($timeout = null)
    {
        return $this->engine->tryLock($this->name, $timeout);
    }

    public function unlock()
    {
        return $this->engine->unlock($this->name);
    }

    public function runInLock($callback)
    {
        try {
            $this->lock();
            return call_user_func($callback);
        } finally {
            $this->unlock();
        }
    }

}