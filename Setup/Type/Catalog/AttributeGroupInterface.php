<?php
namespace Creatuity\Base\Setup\Type\Catalog;

use Creatuity\Base\Setup\Type\CatalogInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
interface AttributeGroupInterface
{
    /**
     * @param string $name
     * @param string $attributeSetName
     * @param string $entityName
     * @param string $afterName
     * @return CatalogInterface
     */
    public function createGroup( $name, $attributeSetName, $entityName, $afterName = null );

}
