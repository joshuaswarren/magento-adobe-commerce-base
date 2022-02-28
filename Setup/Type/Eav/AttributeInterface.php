<?php
namespace Creatuity\Base\Setup\Type\Eav;

use Creatuity\Base\Setup\Type\Eav\Attribute\EntityInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
interface AttributeInterface
{
    /**
     * Manage attribute of custom entity type
     *
     * @param $entityType
     * @return EntityInterface
     */
    public function forEntity( $entityType );

}
