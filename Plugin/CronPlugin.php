<?php

namespace Creatuity\Base\Plugin;

use Magento\Cron\Console\Command\CronCommand;
use Magento\Framework\App\MaintenanceMode;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class CronPlugin
{
    private MaintenanceMode $maintenanceMode;

    public function __construct(
        MaintenanceMode $maintenanceMode
    ) {
        $this->maintenanceMode = $maintenanceMode;
    }

    /**
     * @return void|mixed
     */
    public function aroundRun(CronCommand $subject, callable $proceed, InputInterface $input, OutputInterface $output)
    {
        if (!$this->maintenanceMode->isOn()) {
            return $proceed($input, $output);
        }

        $output->writeln('<info>' . __('Cron is disabled in Maintenance mode') . '</info>');
    }
}
