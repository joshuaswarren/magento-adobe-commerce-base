<?php
/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
namespace Creatuity\Base\Setup\Type\Customer\Attribute\Entity;


use Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Required\InputInterface;
use Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Required\TypeInterface;
use Creatuity\Base\Setup\Type\Customer\Attribute\EntityInterface;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\RequiredInterface as EavRequiredInterface;

interface RequiredInterface extends EavRequiredInterface
{

    /**
     * Define the label of the attribute
     *
     * @param string $label
     * @return RequiredInterface
     */
    public function label( $label );

    /**
     * Define the default value of the attribute
     *
     * @param $value
     * @return RequiredInterface
     */
    public function defaultValue( $value );

    /**
     * Determine whether the attribute must have a value
     *
     * @param bool $trueOrFalse
     * @return RequiredInterface
     */
    public function isRequired( $trueOrFalse );

    /**
     * Determine whether two or more entities can have the same value for the attribute
     *
     * @param bool $trueOrFalse
     * @return RequiredInterface
     */
    public function isUnique( $trueOrFalse );

    /**
     * Determine whether the attribute is user defined, i.e. it can be deleted via Admin Panel
     *
     * @param bool $trueOrFalse
     * @return RequiredInterface
     */
    public function isUserDefined( $trueOrFalse );

    /**
     * Determines whether the attribute should be visible
     *
     * @param bool $trueOrFalse
     * @return RequiredInterface
     */
    public function isVisible( $trueOrFalse );

    /**
     * Define the type of attribute
     *
     * @return TypeInterface
     */
    public function attributeType();

    /**
     * Define what kind of input element should be used for the attribute in forms
     *
     * @return InputInterface
     */
    public function input();

    /**
     * Finish editing required settings
     *
     * @return EntityInterface
     */
    public function done();

}