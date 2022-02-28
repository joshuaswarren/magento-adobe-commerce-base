<?php

namespace Creatuity\Base\Model\Lock;

class Factory
{
    /** @var LockFactory */
    protected $lockFactory;


	public function __construct(
		LockFactory $lockFactory
	) {
		$this->lockFactory = $lockFactory;
	}

    /**
     * @param $name
     * @return Lock
     */
    public function create($name)
    {
        return $this->lockFactory->create(['name' => $name]);
    }
}