<?php

namespace Creatuity\Base\Setup\Tools;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
class ModuleNameResolver
{
    /**
     * @param object $object
     * @return string
     */
    public function byObject($object)
    {
        list($package, $module) = explode('\\', get_class($object));

        return "{$package}_{$module}";
    }
}