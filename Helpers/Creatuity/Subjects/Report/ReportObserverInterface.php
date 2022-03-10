<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects\Report;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
interface ReportObserverInterface
{
    public function handleReportEvent(string $name, array $args): void;
}
