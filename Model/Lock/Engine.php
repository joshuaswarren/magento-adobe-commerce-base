<?php

namespace Creatuity\Base\Model\Lock;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
interface Engine
{
    public function lock(string $name): bool;

    public function tryLock(string $name, int $timeout = null): bool;

    public function unlock(string $name): bool;
}
