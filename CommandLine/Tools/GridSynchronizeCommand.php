<?php
/**
 * Created by PhpStorm.
 * User: mderlatka
 * Date: 2/8/19
 * Time: 8:08 AM
 */

namespace Creatuity\Base\CommandLine\Tools;


use Creatuity\Base\Helpers\Tools\GridSynchronizer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GridSynchronizeCommand extends Command
{
    /**
     * @var GridSynchronizer
     */
    private $gridSynchronizer;

    public function __construct(GridSynchronizer $gridSynchronizer, $name = null)
    {
        parent::__construct($name);

        $this->gridSynchronizer = $gridSynchronizer;
    }

    protected function configure()
    {
        $this
            ->setName('creatuity:tools:grid-sync')
            ->setDescription('Synchornize sales grid');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try{
            $this->gridSynchronizer->synchronize($output);
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }
    }
}