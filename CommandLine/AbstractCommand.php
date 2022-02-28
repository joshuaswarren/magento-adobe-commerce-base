<?php

namespace Creatuity\Base\CommandLine;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @package m2newbuild
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
abstract class AbstractCommand extends Command
{
    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var string
     */
    protected $name = '';


    public function __construct()
    {
        if (!$this->name) {
            throw new \InvalidArgumentException('Missing name property');
        }
        if (!$this->description) {
            throw new \InvalidArgumentException('Missing description property');
        }

        $this->setDescription($this->description);

        parent::__construct($this->name);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->runCommand($input, $output);
    }

    abstract protected function runCommand(InputInterface $input, OutputInterface $output);

}