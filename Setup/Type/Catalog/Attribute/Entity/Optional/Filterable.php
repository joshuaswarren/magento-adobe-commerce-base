<?php
namespace Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\Optional;

use Creatuity\Base\Setup\AbstractType;
use Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\OptionalInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class Filterable extends AbstractType implements FilterableInterface
{
    const FILTERABLE_NONE = 0;
    const FILTERABLE_WITH_RESULTS = 1;
    const FILTERABLE_NO_RESULTS = 2;

    /**
     * The attribute won't be available as filter in layered navigation, but it will be filterable in search.
     *
     * @return OptionalInterface
     */
    public function notInLayeredNavigationButInSearch()
    {
        $this->_setNotFilterableInLayeredNavigation();
        $this->_setFilterableInSearch( false );
        return $this->getParent();
    }

    /**
     * This attribute won't be available as filter in layered navigation. It also won't be filterable in search.
     *
     * @return OptionalInterface
     */
    public function notInLayeredNavigationAndNotInSearch()
    {
        $this->_setNotFilterableInLayeredNavigation();
        $this->_setFilterableInSearch( true );
        return $this->getParent();
    }

    /**
     * This attribute will be available as filter in layered navigation,
     * but only attribute's values that are associated to specific products in a given category page
     * will be listed in the Layered Navigation menu.
     * This attribute will be also filterable in search.
     *
     * @return OptionalInterface
     */
    public function yesInLayeredNavigationWithResultsAndInSearch()
    {
        $this->_setFilterableInLayeredNavigationWithResults();
        $this->_setFilterableInSearch( true );
        return $this->getParent();
    }

    /**
     * This attribute will be available as filter in layered navigation,
     * but only attribute's values that are associated to specific products in a given category page
     * will be listed in the Layered Navigation menu.
     * However this attribute won't be filterable in search.
     *
     * @return OptionalInterface
     */
    public function yesInLayeredNavigationWithResultsButNotInSearch()
    {
        $this->_setFilterableInLayeredNavigationWithResults();
        $this->_setFilterableInSearch( false );
        return $this->getParent();
    }

    /**
     * This attribute will be available as filter in layered navigation and all existing values for this attribute
     * will be displayed in the Layered Navigation menu, even if using them as filter will produce no results.
     * However this attribute won't be filterable in search.
     *
     * @return OptionalInterface
     */
    public function yesInLayeredNavigationWithoutResultsButNotInSearch()
    {
        $this->_setFilterableInLayeredNavigationWithoutResults();
        $this->_setFilterableInSearch( true );
        return $this->getParent();
    }

    /**
     * This attribute will be available as filter in layered navigation and all existing values for this attribute
     * will be displayed in the Layered Navigation menu, even if using them as filter will produce no results.
     * This attribute will be also filterable in search.
     *
     * @return OptionalInterface
     */
    public function yesInLayeredNavigationWithoutResultsAndInSearch()
    {
        $this->_setFilterableInLayeredNavigationWithoutResults();
        $this->_setFilterableInSearch( false );
        return $this->getParent();
    }


    /**
     * @param bool $trueOrFalse
     */
    protected function _setFilterableInSearch( $trueOrFalse )
    {
        $this->getParent()->getParent()->setAttributeCreateProperty( 'is_filterable_in_search', $trueOrFalse );
    }

    /**
     * @param int $optionNumber
     */
    protected function _setFilterableInLayeredNavigation( $optionNumber )
    {
        $this->getParent()->getParent()->setAttributeCreateProperty( 'is_filterable', $optionNumber );
    }

    protected function _setNotFilterableInLayeredNavigation()
    {
        $this->_setFilterableInLayeredNavigation( self::FILTERABLE_NONE );
    }

    protected function _setFilterableInLayeredNavigationWithResults()
    {
        $this->_setFilterableInLayeredNavigation( self::FILTERABLE_WITH_RESULTS );
    }

    public function _setFilterableInLayeredNavigationWithoutResults()
    {
        $this->_setFilterableInLayeredNavigation( self::FILTERABLE_NO_RESULTS );
    }
}
