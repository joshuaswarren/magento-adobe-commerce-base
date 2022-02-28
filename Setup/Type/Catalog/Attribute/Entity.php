<?php namespace Creatuity\Base\Setup\Type\Catalog\Attribute;

use Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\OptionalInterface;
use Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\RequiredInterface;
use Creatuity\Base\Setup\TypeInterface as SetupTypeInterface;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity as EavEntity;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class Entity extends EavEntity implements EntityInterface, SetupTypeInterface
{

    /**
     * Manage required settings
     *
     * @return RequiredInterface
     */
    public function requiredSettings()
    {
        return $this->getContext()->getTypeFactory()->create( 'catalog_attribute_entity_required', $this );
    }

    /**
     * Manage optional settings
     *
     * @return OptionalInterface
     */
    public function optionalSettings()
    {
        return $this->getContext()->getTypeFactory()->create( 'catalog_attribute_entity_optional', $this );
    }


}
