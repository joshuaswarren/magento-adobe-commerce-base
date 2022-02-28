<?php

namespace Creatuity\Base\Plugin;

use Magento\Cron\Console\Command\CronCommand;
use Magento\Framework\App\MaintenanceMode;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CronPlugin
{
    /**
     * @var MaintenanceMode
     */
    private $maintenanceMode;

    public function __construct(
        MaintenanceMode $maintenanceMode
    )
    {
        $this->maintenanceMode = $maintenanceMode;
    }

    /**
     * @param CronCommand $subject
     * @param callable $proceed
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
     */
    public function aroundRun(CronCommand $subject, callable $proceed, InputInterface $input, OutputInterface $output)
    {
        if ( !$this->maintenanceMode->isOn() ) {
            return $proceed($input, $output);
        }
        $output->writeln('<info>' . __('Cron is disabled in Maintenance mode') . '</info>');
    }
}