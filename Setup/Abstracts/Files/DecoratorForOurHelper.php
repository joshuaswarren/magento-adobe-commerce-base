<?php

namespace Creatuity\Base\Setup\Abstracts\Files;

use Creatuity\Base\Helpers\Creatuity;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
class DecoratorForOurHelper extends Creatuity
{
    /**
     * @var Creatuity
     */
    protected $creatuity;

    public function __construct(Creatuity $creatuity)
    {
        $this->creatuity = $creatuity;
    }


    /**
     * @inheritDoc
     */
    public function database()
    {
        $this->notSupported();
    }

    /**
     * @inheritDoc
     */
    public function catalog($forModule = '')
    {
        $this->notSupported();
    }

    /**
     * @inheritDoc
     */
    public function creatuityLogo()
    {
        $this->notSupported();
    }

    /**
     * @inheritDoc
     */
    public function cms($forModule = '')
    {
        $this->notSupported();
    }

    /**
     * @inheritDoc
     */
    public function config()
    {
        $this->notSupported();
    }

    /**
     * @inheritDoc
     */
    public function csv($forModule = '')
    {
        $this->notSupported();
    }

    /**
     * @inheritDoc
     */
    public function emulate()
    {
        $this->notSupported();
    }

    /**
     * @inheritDoc
     */
    public function indexer()
    {
        $this->notSupported();
    }

    /**
     * @inheritDoc
     */
    public function report()
    {
        return $this->creatuity->report();
    }

    /**
     * @inheritDoc
     */
    public function resources($forModule = '')
    {
        $this->notSupported();
    }

    /**
     * @inheritDoc
     */
    public function setting($scope = 0, $scopeType = 'default')
    {
        $this->notSupported();
    }

    /**
     * @inheritDoc
     */
    public function store()
    {
        $this->notSupported();
    }

    /**
     * @inheritDoc
     */
    public function theme()
    {
        $this->notSupported();
    }

    protected function notSupported()
    {
        throw new DecoratorForOurHelperException('Subject is not supported..');
    }
}

class DecoratorForOurHelperException extends \Exception {}
