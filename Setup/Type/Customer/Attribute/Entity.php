<?php

namespace Creatuity\Base\Setup\Type\Customer\Attribute;


use Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Optional;
use Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Required;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity as EavEntity;

class Entity extends EavEntity implements EntityInterface
{
    /**
     * @return Required
     */
    public function requiredSettings()
    {
        return $this->getContext()->getTypeFactory()->create( 'customer_attribute_entity_required', $this );
    }

    /**
     *
     * @return Optional
     */

    public function optionalSettings()
    {
        return $this->getContext()->getTypeFactory()->create( 'customer_attribute_entity_optional', $this );
    }
}