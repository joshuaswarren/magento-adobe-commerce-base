<?php

namespace Creatuity\Base\Model\Lock;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class Factory
{
    /** @var LockFactory */
    private LockFactory $lockFactory;

	public function __construct(
		LockFactory $lockFactory
	) {
		$this->lockFactory = $lockFactory;
	}

    public function create(string $name): Lock
    {
        return $this->lockFactory->create(['name' => $name]);
    }
}
