<?php

namespace Creatuity\Base\CommandLine\Tools;

use Creatuity\Base\Helpers\Tools\GridSynchronizer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class GridSynchronizeCommand extends Command
{
    private GridSynchronizer $gridSynchronizer;

    public function __construct(GridSynchronizer $gridSynchronizer, $name = null)
    {
        parent::__construct($name);
        $this->gridSynchronizer = $gridSynchronizer;
    }

    protected function configure(): void
    {
        $this->setName('creatuity:tools:grid-sync')
            ->setDescription('Synchronizes sales grid after async order operation');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        try{
            $this->gridSynchronizer->synchronize($output);
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }
    }
}
