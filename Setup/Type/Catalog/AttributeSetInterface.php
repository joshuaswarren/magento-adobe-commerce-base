<?php
namespace Creatuity\Base\Setup\Type\Catalog;

use Creatuity\Base\Setup\Type\CatalogInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
interface AttributeSetInterface
{
    /**
     * @param string $name
     * @param string $entityCode
     * @param string $attributeSetNameToCloneFrom
     * @return CatalogInterface
     */
    public function createSet( $name, $entityCode, $attributeSetNameToCloneFrom = null );

}
