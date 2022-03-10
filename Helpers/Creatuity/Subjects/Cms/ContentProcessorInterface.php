<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects\Cms;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
interface ContentProcessorInterface
{
    public function process(string $content): string;
}
