<?php
namespace Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\Optional;

use Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\OptionalInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
interface FilterableInterface
{
    /**
     * The attribute won't be available as filter in layered navigation, but it will be filterable in search.
     *
     * @return OptionalInterface
     */
    public function notInLayeredNavigationButInSearch();

    /**
     * This attribute won't be available as filter in layered navigation. It also won't be filterable in search.
     *
     * @return OptionalInterface
     */
    public function notInLayeredNavigationAndNotInSearch();

    /**
     * This attribute will be available as filter in layered navigation,
     * but only attribute's values that are associated to specific products in a given category page
     * will be listed in the Layered Navigation menu.
     * This attribute will be also filterable in search.
     *
     * @return OptionalInterface
     */
    public function yesInLayeredNavigationWithResultsAndInSearch();

    /**
     * This attribute will be available as filter in layered navigation,
     * but only attribute's values that are associated to specific products in a given category page
     * will be listed in the Layered Navigation menu.
     * However this attribute won't be filterable in search.
     *
     * @return OptionalInterface
     */
    public function yesInLayeredNavigationWithResultsButNotInSearch();

    /**
     * This attribute will be available as filter in layered navigation and all existing values for this attribute
     * will be displayed in the Layered Navigation menu, even if using them as filter will produce no results.
     * However this attribute won't be filterable in search.
     *
     * @return OptionalInterface
     */
    public function yesInLayeredNavigationWithoutResultsButNotInSearch();

    /**
     * This attribute will be available as filter in layered navigation and all existing values for this attribute
     * will be displayed in the Layered Navigation menu, even if using them as filter will produce no results.
     * This attribute will be also filterable in search.
     *
     * @return OptionalInterface
     */
    public function yesInLayeredNavigationWithoutResultsAndInSearch();
}
