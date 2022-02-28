<?php namespace Creatuity\Base\Setup\Type\Catalog;

use Creatuity\Base\Setup\AbstractType;
use Creatuity\Base\Setup\Type\CatalogInterface;
use Creatuity\Base\Setup\TypeInterface as SetupTypeInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class AttributeGroup extends AbstractType implements AttributeGroupInterface, SetupTypeInterface
{

    /**
     * @param string $name
     * @param string $attributeSetName
     * @param string $entityName
     * @param string $afterName
     * @return CatalogInterface
     */
    public function createGroup( $name, $attributeSetName, $entityName, $afterName = null )
    {

    }

}
