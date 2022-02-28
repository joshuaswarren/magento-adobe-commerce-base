<?php namespace Creatuity\Base\Setup\Type\Catalog\Attribute\Entity;

use Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\Required\ScopeInterface;
use Creatuity\Base\Setup\Type\Catalog\Attribute\EntityInterface;
use Creatuity\Base\Setup\TypeInterface as SetupTypeInterface;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\Required as EavRequired;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class Required extends EavRequired implements RequiredInterface, SetupTypeInterface
{


    /**
     * Determine whether the attribute should be visible (both in backend and frontend)
     *
     * @param bool $trueOrFalse
     * @return RequiredInterface
     */
    public function isVisible( $trueOrFalse )
    {
        $this->getParent()->setAttributeCreateProperty( 'is_visible', $trueOrFalse );
        return $this;
    }

    /**
     * Determine whether data saved in this attribute will be searchable in the front-end in the quick search.
     *
     * @param bool $trueOrFalse
     * @return RequiredInterface
     */
    public function isSearchable( $trueOrFalse )
    {
        $this->getParent()->setAttributeCreateProperty( 'is_searchable', $trueOrFalse );
        return $this;
    }

    /**
     * Determine whether attribute's value is listed while comparing products
     *
     * @param bool $trueOrFalse
     * @return RequiredInterface
     */
    public function isComparable( $trueOrFalse )
    {
        $this->getParent()->setAttributeCreateProperty( 'is_comparable', $trueOrFalse );
        return $this;
    }

    /**
     * Determine whether attribute's data should be visible on product pages on front-end
     *
     * @param bool $trueOrFalse
     * @return RequiredInterface
     */
    public function isVisibleOnFront( $trueOrFalse )
    {
        $this->getParent()->setAttributeCreateProperty( 'visible_on_front', $trueOrFalse );
        return $this;
    }

    /**
     * Determine whether attribute's data should be visible on product pages on front-end
     *
     * @param bool $trueOrFalse
     * @return RequiredInterface
     * @deprecated
     */
    public function isUsedInFlatTables( $trueOrFalse )
    {
        $this->getParent()->setAttributeCreateProperty( 'is_used_in_flat_tables', $trueOrFalse );
        return $this;
    }

    /**
     * Determine whether attribute's data should be visible on product pages on front-end
     *
     * @param bool $trueOrFalse
     * @return RequiredInterface
     */
    public function isUsedInProductListing( $trueOrFalse )
    {
        $this->getParent()->setAttributeCreateProperty( 'used_in_product_listing', $trueOrFalse );
        return $this;
    }


    /**
     * Determine the store level at which the attribute will be saved for all products
     *
     * @return ScopeInterface
     */
    public function scope()
    {
        return $this->getContext()->getTypeFactory()->create( 'catalog_attribute_entity_required_scope', $this );
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
