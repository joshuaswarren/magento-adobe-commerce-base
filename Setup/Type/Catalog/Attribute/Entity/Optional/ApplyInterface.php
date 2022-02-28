<?php
namespace Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\Optional;

use Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\OptionalInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
interface ApplyInterface
{

    /**
     * This attribute will be displayed for all product types
     *
     * @return ApplyInterface
     */
    public function addAll();

    /**
     * This attribute will be displayed for simple products
     *
     * @return ApplyInterface
     */
    public function addSimple();

    /**
     * This attribute will be displayed for configurable products
     *
     * @return ApplyInterface
     */
    public function addConfigurable();

    /**
     * This attribute will be displayed for bundle products
     *
     * @return ApplyInterface
     */
    public function addBundle();

    /**
     * This attribute will be displayed for virtual products
     *
     * @return ApplyInterface
     */
    public function addVirtual();

    /**
     * This attribute will be displayed for grouped products
     *
     * @return ApplyInterface
     */
    public function addGrouped();

    /**
     * This attribute will be displayed for products of given product type
     *
     * @param $customType
     * @return ApplyInterface
     */
    public function addCustomType( $customType );

    /**
     * @return OptionalInterface
     */
    public function done();

}
