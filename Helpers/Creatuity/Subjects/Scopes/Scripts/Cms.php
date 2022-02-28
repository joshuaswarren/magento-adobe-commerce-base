<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects\Scopes\Scripts;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2021 Joshua Warren (https://warrenappliedlabs.com)
 */
class Cms extends \Creatuity\Base\Helpers\Creatuity\Subjects\Cms
{
    protected function pagePathPattern(): string
    {
        return 'data' . DIRECTORY_SEPARATOR . 'cms' . DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR;
    }

    protected function blockPathPattern(): string
    {
        return 'data' . DIRECTORY_SEPARATOR . 'cms' . DIRECTORY_SEPARATOR . 'blocks' . DIRECTORY_SEPARATOR;
    }
}
