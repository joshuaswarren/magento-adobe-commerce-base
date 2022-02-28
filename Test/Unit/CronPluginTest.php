<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Cron\Test\Unit\Console\Command;

use Creatuity\Base\Plugin\CronPlugin;
use Magento\Cron\Console\Command\CronCommand;
use Magento\Framework\App\MaintenanceMode;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CronPluginTest extends TestCase
{

    public function testMaintenanceIsOn()
    {
        $maintenanceMock = $this->createMock(MaintenanceMode::class );
        $maintenanceMock->expects(self::once())
            ->method('isOn')
            ->willReturn(true);

        $cronMock = $this->createMock(CronCommand::class);
        $inputMock = $this->createMock(InputInterface::class);
        $outputMock = $this->createMock(OutputInterface::class);

        $outputMock->expects(self::once())
            ->method('writeln');

        $proceedMock = $this->getMockBuilder(\stdClass::class)
            ->addMethods([ '__invoke' ])
            ->getMock();

        $proceedMock
            ->expects(self::never())
            ->method('__invoke');

        $proceedCallback = function(...$args) use ($proceedMock) {
            return $proceedMock->__invoke(...$args);
        };

        $plugin = new CronPlugin($maintenanceMock);
        $plugin->aroundRun($cronMock, $proceedCallback, $inputMock, $outputMock);
    }

    public function testMaintenanceIsOff()
    {
        $maintenanceMock = $this->createMock(MaintenanceMode::class );
        $maintenanceMock->expects(self::once())
            ->method('isOn')
            ->willReturn(false);

        $cronMock = $this->createMock(CronCommand::class);
        $inputMock = $this->createMock(InputInterface::class);
        $outputMock = $this->createMock(OutputInterface::class);

        $outputMock->expects(self::never())
            ->method('writeln');

        $proceedMock = $this->getMockBuilder(\stdClass::class)
            ->addMethods([ '__invoke' ])
            ->getMock();

        $proceedMock
            ->expects(self::once())
            ->method('__invoke')
            ->with( $inputMock, $outputMock )
        ;

        $proceedCallback = function(...$args) use ($proceedMock) {
            return $proceedMock->__invoke(...$args);
        };

        $plugin = new CronPlugin($maintenanceMock);
        $plugin->aroundRun($cronMock, $proceedCallback, $inputMock, $outputMock);
    }

}
