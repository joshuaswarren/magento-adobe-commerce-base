<?php

namespace Creatuity\Base\Helpers\Tools;

use Psr\Log\LoggerInterface;
use Magento\Sales\Model\ResourceModel\Grid;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class GridSynchronizer
{
    private array $gridUpdaters = [];

    private LoggerInterface $logger;

    public function __construct(
        Grid $orderGridUpdater,
        Grid $invoiceGridUpdater,
        Grid $shipmentGridUpdater,
        Grid $creditmemoGridUpdater,
        LoggerInterface $logger
    )
    {
        $this->gridUpdaters['order'] = $orderGridUpdater;
        $this->gridUpdaters['invoice'] = $invoiceGridUpdater;
        $this->gridUpdaters['shipment'] = $shipmentGridUpdater;
        $this->gridUpdaters['creditmemo'] = $creditmemoGridUpdater;
    }


    public function synchronize(OutputInterface $out, ...$types): void
    {
        foreach($this->provideGrids($types) as $type => $grid) {
            $out->writeln("Synchronizing '$type' grid...");
            $this->synchronizeGrid($grid);
        }
        $out->writeln("Synchronization of adminhtml sales grids is done.");
    }

    protected function synchronizeGrid(Grid $grid): void
    {
        try {
            $grid->refreshBySchedule();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $e);
        }
    }

    /**
     * @return Grid[]
     */
    protected function provideGrids(array $types): array
    {
        if (empty($types)) {
            return $this->gridUpdaters;
        }
        return array_intersect_key($this->gridUpdaters,
            array_flip((array)$types));
    }

}
