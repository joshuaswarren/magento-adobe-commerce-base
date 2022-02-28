<?php
namespace Creatuity\Base\Setup\Type\Catalog\Attribute\Entity;

use Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\Optional\ApplyInterface;
use Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\Optional\FilterableInterface;
use Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\Optional\SourceInterface;
use Creatuity\Base\Setup\Type\Catalog\Attribute\EntityInterface;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\Optional\AttributeSetsInterface;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\OptionalInterface as EavOptionalInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
interface OptionalInterface
    extends EavOptionalInterface
{

    /**
     * Define the block class for customized input element
     *
     * @param string $block
     * @return OptionalInterface
     */
    public function inputRenderer( $block );

    /**
     * Indicate the model class to define how the attribute data should be handlesd when the entity object is saved or deleted.
     *
     * @param string $model
     * @return OptionalInterface
     */
    public function backendModel( $model );

    /**
     * Define the database table where attributes should be stored.
     * If it's not set, the database table is retrieved dynamically based on attribute type (e.g. entitytypename\entity\varchar for varchar)
     *
     * @param string $table
     * @return OptionalInterface
     */
    public function backendTable( $table );

    /**
     * Indicate the class that is responsible for the attribute's presentation to the user.
     *
     * @param string $model
     * @return OptionalInterface
     */
    public function frontendModel( $model );

    /**
     * Define a tip which can be displayed next to the attribute's field in Admin Panel.
     *
     * @param string $note
     * @return OptionalInterface
     */
    public function note( $note );

    /**
     * Define a group for the attribute
     *
     * @param string $groupName
     * @return OptionalInterface
     */
    public function group( $groupName );

    /**
     * /**
     * Manage the list of available options for the attribute.
     * It can be used only for attributes of type 'select' or 'multiselect'.
     *
     * @return SourceInterface
     */
    public function source();

    /**
     * Manage attribute to attribute sets relations
     *
     * @return AttributeSetsInterface
     */
    public function attributeSets();

    /**
     * @return FilterableInterface
     */
    public function filterable();

    /**
     * Determine whether Wysiwyg editor should be displayed when editing attribute's value
     *
     * @param bool $trueOrFalse
     * @return OptionalInterface
     */
    public function wysiwygEnabled( $trueOrFalse = true );

    /**
     * Determine whether to allow HTML tags on front-end
     *
     * @param bool $trueOrFalse
     * @return OptionalInterface
     */
    public function htmlAllowedOnFront( $trueOrFalse = true );

    /**
     * Determine whether the attribute's data should be searchable in the front-end in the advanced search
     *
     * @param bool $trueOrFalse
     * @return OptionalInterface
     */
    public function visibleInAdvancedSearch( $trueOrFalse = true );

    /**
     * Determine whether attribute is available in product listing
     *
     * @param bool $trueOrFalse
     * @return OptionalInterface
     */
    public function usedInProductListingCollection( $trueOrFalse = true );

    /**
     * Determine whether the attribute could be used for promo rule conditions
     *
     * @param bool $trueOrFalse
     * @return OptionalInterface
     */
    public function usedForPromoRules( $trueOrFalse = true );

    /**
     * Determine whether attribute should be used for sorting in product listing
     *
     * @param bool $trueOrFalse
     * @return OptionalInterface
     */
    public function usedForSortBy( $trueOrFalse = true );

    /**
     * Determine which Product Types this attribute should be displayed for
     *
     * @return ApplyInterface
     */
    public function applyToProductTypes();

    /**
     * Determine the position of the attribute in the Layered Navigation
     *
     * @param int $position
     * @return OptionalInterface
     */
    public function position( $position );

    /**
     * Finish editing optional settings
     *
     * @return EntityInterface
     */
    public function done();
}
