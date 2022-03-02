<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects\Scopes\Scripts;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class Csv extends \Creatuity\Base\Helpers\Creatuity\Subjects\Csv
{
    protected function csvFilesPath(): string
    {
        return 'data' . DIRECTORY_SEPARATOR . 'csv';
    }
}
