<?php

namespace Creatuity\Base\Model\Lock;

interface Engine
{
    public function lock($name);

    public function tryLock($name, $timeout = null);

    public function unlock($name);
}