<?php namespace Creatuity\Base\Setup\Type\Eav\Attribute\Entity;

use Creatuity\Base\Setup\AbstractType;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\Required\InputInterface;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\Required\TypeInterface;
use Creatuity\Base\Setup\Type\Eav\Attribute\EntityInterface;
use Creatuity\Base\Setup\TypeInterface as SetupTypeInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class Required extends AbstractType implements RequiredInterface, SetupTypeInterface
{
    /**
     * Define the label of the attribute
     *
     * @param string $label
     * @return RequiredInterface
     */
    public function label( $label )
    {
        $this->getParent()->setAttributeCreateProperty( 'label', $label );
        return $this;
    }

    /**
     * Define the default value of the attribute
     *
     * @param $value
     * @return RequiredInterface
     */
    public function defaultValue( $value )
    {
        $this->getParent()->setAttributeCreateProperty( 'default', $value );
        return $this;
    }

    /**
     * Determine whether the attribute must have a value
     *
     * @param bool $trueOrFalse
     * @return RequiredInterface
     */
    public function isRequired( $trueOrFalse )
    {
        $this->getParent()->setAttributeCreateProperty( 'required', $trueOrFalse );
        return $this;
    }

    /**
     * Determine whether two or more entities can have the same value for the attribute
     *
     * @param bool $trueOrFalse
     * @return RequiredInterface
     */
    public function isUnique( $trueOrFalse )
    {
        $this->getParent()->setAttributeCreateProperty( 'unique', $trueOrFalse );
        return $this;
    }

    /**
     * Determine whether the attribute is user defined, i.e. it can be deleted via Admin Panel
     *
     * @param bool $trueOrFalse
     * @return RequiredInterface
     */
    public function isUserDefined( $trueOrFalse )
    {
        $this->getParent()->setAttributeCreateProperty( 'user_defined', $trueOrFalse );
        return $this;
    }

    /**
     * Define the type of attribute
     *
     * @return TypeInterface
     */
    public function attributeType()
    {
        return $this->getContext()->getTypeFactory()->create( 'eav_attribute_entity_required_type', $this );
    }

    /**
     * Define what kind of input element should be used for the attribute in forms
     *
     * @return InputInterface
     */
    public function input()
    {
        return $this->getContext()->getTypeFactory()->create( 'eav_attribute_entity_required_input', $this );
    }

    /**
     * Finish editing required settings
     *
     * @return EntityInterface
     */
    public function done()
    {
        return $this->getParent();
    }
}
