<?php
namespace Creatuity\Base\Setup\Type\Catalog\Attribute\Entity;

use Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\Required\InputInterface;
use Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\Required\ScopeInterface;
use Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\Required\TypeInterface;
use Creatuity\Base\Setup\Type\Catalog\Attribute\EntityInterface;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\RequiredInterface as EavRequiredInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
interface RequiredInterface
    extends EavRequiredInterface
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
     * Determine whether the attribute should be visible (both in backend and frontend)
     *
     * @param bool $trueOrFalse
     * @return RequiredInterface
     */
    public function isVisible( $trueOrFalse );

    /**
     * Determine whether data saved in this attribute will be searchable in the front-end in the quick search.
     *
     * @param bool $trueOrFalse
     * @return RequiredInterface
     */
    public function isSearchable( $trueOrFalse );

    /**
     * Determine whether attribute's value is listed while comparing products
     *
     * @param bool $trueOrFalse
     * @return RequiredInterface
     */
    public function isComparable( $trueOrFalse );

    /**
     * Determine whether attribute's data should be visible on product pages on front-end
     *
     * @param bool $trueOrFalse
     * @return RequiredInterface
     */
    public function isVisibleOnFront( $trueOrFalse );

    /**
     * Determine whether attribute's data should be visible on product pages on front-end
     *
     * @param bool $trueOrFalse
     * @return RequiredInterface
     */
    public function isUsedInFlatTables( $trueOrFalse );

    /**
     * @param $trueOrFalse
     * @return RequiredInterface
     */
    public function isUsedInProductListing ($trueOrFalse);

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
     * Determine the store level at which the attribute will be saved for all products
     *
     * @return ScopeInterface
     */
    public function scope();

    /**
     * Finish editing required settings
     *
     * @return EntityInterface
     */
    public function done();
}
