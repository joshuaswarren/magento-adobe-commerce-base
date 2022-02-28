<?php
namespace Creatuity\Base\Setup\Type\Catalog\Attribute\Entity;

use Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\Optional\ApplyInterface;
use Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\Optional\AttributeSetsInterface;
use Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\Optional\SourceInterface;
use Creatuity\Base\Setup\Type\Catalog\Attribute\EntityInterface;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\Optional as EavOptional;
use Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\OptionalInterface as SetupOptionalInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class Optional extends EavOptional implements SetupOptionalInterface
{

    /**
     * Define the block class for customized input element
     *
     * @param string $block
     * @return OptionalInterface
     */
    public function inputRenderer( $block )
    {
        $this->getParent()->setAttributeCreateProperty( 'input_renderer', $block );
        return $this;
    }


    /**
     * @return \Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\Optional\FilterableInterface
     */
    public function filterable()
    {
        return $this->getContext()->getTypeFactory()->create( 'catalog_attribute_entity_optional_filterable', $this );
    }

    /**
     * Determine whether Wysiwyg editor should be displayed when editing attribute's value
     *
     * @param bool $trueOrFalse
     * @return OptionalInterface
     */
    public function wysiwygEnabled( $trueOrFalse = true )
    {
        $this->getParent()->setAttributeCreateProperty( 'is_wysiwyg_enabled', $trueOrFalse );
        $this->getParent()->setAttributeCreateProperty( 'wysiwyg_enabled', $trueOrFalse );
        return $this;
    }

    /**
     * Determine whether to allow HTML tags on front-end
     *
     * @param bool $trueOrFalse
     * @return OptionalInterface
     */
    public function htmlAllowedOnFront( $trueOrFalse = true )
    {
        $this->getParent()->setAttributeCreateProperty( 'is_html_allowed_on_front', $trueOrFalse );
        return $this;
    }

    /**
     * Determine whether the attribute's data should be searchable in the front-end in the advanced search
     *
     * @param bool $trueOrFalse
     * @return OptionalInterface
     */
    public function visibleInAdvancedSearch( $trueOrFalse = true )
    {
        $this->getParent()->setAttributeCreateProperty( 'is_visible_in_advanced_search', $trueOrFalse );
        return $this;
    }

    /**
     * Determine whether attribute is available in product listing
     *
     * @param bool $trueOrFalse
     * @return OptionalInterface
     */
    public function usedInProductListingCollection( $trueOrFalse = true )
    {
        $this->getParent()->setAttributeCreateProperty( 'used_in_product_listing', $trueOrFalse );
        return $this;
    }

    /**
     * Determine whether the attribute could be used for promo rule conditions
     *
     * @param bool $trueOrFalse
     * @return OptionalInterface
     */
    public function usedForPromoRules( $trueOrFalse = true )
    {
        $this->getParent()->setAttributeCreateProperty( 'is_used_for_promo_rules', $trueOrFalse );
        return $this;
    }

    /**
     * Determine whether attribute should be used for sorting in product listing
     *
     * @param bool $trueOrFalse
     * @return OptionalInterface
     */
    public function usedForSortBy( $trueOrFalse = true )
    {
        $this->getParent()->setAttributeCreateProperty( 'used_for_sort_by', $trueOrFalse );
        return $this;
    }

    /**
     * Determine which Product Types this attribute should be displayed for
     *
     * @return ApplyInterface
     */
    public function applyToProductTypes()
    {
        return $this->getContext()->getTypeFactory()->create( 'catalog_attribute_entity_optional_apply', $this );
    }

    /**
     * Determine the position of the attribute in the Layered Navigation
     *
     * @param int $position
     * @return OptionalInterface
     */
    public function position( $position )
    {
        $this->getParent()->setAttributeCreateProperty( 'position', $position );
        return $this;
    }

    /**
     * Finish editing required settings
     *
     * @return EntityInterface
     */
    public function done()
    {
        return parent::done();
    }

    /**
     * Manage the list of available options for the attribute.
     * It can be used only for attributes of type 'select' or 'multiselect'.
     *
     * @return SourceInterface
     */
    public function source()
    {
        return parent::source();
    }

    /**
     * Manage attribute to attribute sets relations
     *
     * @return AttributeSetsInterface
     */
    public function attributeSets()
    {
        return parent::attributeSets();
    }

    /**
     * Indicate the model class to define how the attribute data should be handlesd when the entity object is saved or deleted.
     *
     * @param string $model
     * @return OptionalInterface
     */
    public function backendModel( $model )
    {
        return parent::backendModel( $model );
    }

    /**
     * Define the database table where attributes should be stored.
     * If it's not set, the database table is retrieved dynamically based on attribute type (e.g. entitytypename\entity\varchar for varchar)
     *
     * @param string $table
     * @return OptionalInterface
     */
    public function backendTable( $table )
    {
        return parent::backendTable( $table );
    }

    /**
     * Indicate the class that is responsible for the attribute's presentation to the user.
     *
     * @param string $model
     * @return OptionalInterface
     */
    public function frontendModel( $model )
    {
        return parent::frontendModel( $model );
    }

    /**
     * Define a tip which can be displayed next to the attribute's field in Admin Panel.
     *
     * @param string $note
     * @return OptionalInterface
     */
    public function note( $note )
    {
        return parent::note( $note );
    }

    /**
     * Define a group for the attribute
     *
     * @param string $groupName
     * @return OptionalInterface
     */
    public function group( $groupName )
    {
        return parent::group( $groupName );
    }
}
