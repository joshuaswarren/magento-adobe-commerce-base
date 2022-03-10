<?php

namespace Creatuity\Base\Setup\Tools;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class ModuleNameResolver
{
    public function byObject(object $object): string
    {
        list($package, $module) = explode('\\', get_class($object));

        return "{$package}_{$module}";
    }
}
