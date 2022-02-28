<?php

namespace Creatuity\Base\Helpers\Tools;

use Psr\Log\LoggerInterface;
use Magento\Sales\Model\ResourceModel\Grid;
use Symfony\Component\Console\Output\ConsoleOutput;


/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com/)
 */

class GridSynchronizer
{

    /**
     * @var Grid[]
     */
    protected $gridUpdaters = [];

    /**
     * @var LoggerInterface
     */
    protected $logger;

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


    public function synchronize(ConsoleOutput $out, ...$types)
    {
        foreach($this->provideGrids($types) as $type => $grid) {
            $out->writeln("Synchronizing '$type' grid...");
            $this->synchronizeGrid($grid);
        }
        $out->writeln("Synchronization of adminhtml sales grids is done.");
    }

    protected function synchronizeGrid(Grid $grid )
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
    protected function provideGrids($types)
    {
        if (empty($types)) {
            return $this->gridUpdaters;
        }
        return array_intersect_key($this->gridUpdaters,
            array_flip((array)$types));
    }

}