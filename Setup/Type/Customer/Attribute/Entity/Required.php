<?php

namespace Creatuity\Base\Setup\Type\Customer\Attribute\Entity;

use Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Required\InputInterface;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\Required as EavRequired;

class Required extends EavRequired implements RequiredInterface
{

    /**
     *
     * @param bool $trueOrFalse
     * @return Required
     */
    public function isUserDefined( $trueOrFalse )
    {
        parent::isUserDefined( $trueOrFalse );
        $this->getParent()->setAttributeCreateProperty( 'is_user_defined', !$trueOrFalse );
        return $this;
    }

    /**
     *
     * @param bool $trueOrFalse
     * @return Required
     */
    public function isVisible( $trueOrFalse )
    {
        $this->getParent()->setAttributeCreateProperty( 'is_visible', $trueOrFalse );
        return $this;
    }

    /**
     *
     * @return InputInterface
     */
    public function input()
    {
        return $this->getContext()->getTypeFactory()->create( 'customer_attribute_entity_required_input', $this );
    }

}