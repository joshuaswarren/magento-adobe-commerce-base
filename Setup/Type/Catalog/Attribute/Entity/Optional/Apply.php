<?php
namespace Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\Optional;

use Creatuity\Base\Setup\AbstractType;
use Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\OptionalInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class Apply extends AbstractType implements ApplyInterface
{
    protected $_types;

    /**
     * This attribute will be displayed for all product types
     *
     * @return ApplyInterface
     */
    public function addAll()
    {
        $this->_types = null;
        return $this;
    }

    /**
     * This attribute will be displayed for simple products
     *
     * @return ApplyInterface
     */
    public function addSimple()
    {
        return $this->_addProductType( 'simple' );
    }

    /**
     * This attribute will be displayed for configurable products
     *
     * @return ApplyInterface
     */
    public function addConfigurable()
    {
        return $this->_addProductType( 'configurable' );
    }

    /**
     * This attribute will be displayed for bundle products
     *
     * @return ApplyInterface
     */
    public function addBundle()
    {
        return $this->_addProductType( 'bundle' );
    }

    /**
     * This attribute will be displayed for virtual products
     *
     * @return ApplyInterface
     */
    public function addVirtual()
    {
        return $this->_addProductType( 'virtual' );
    }

    /**
     * This attribute will be displayed for grouped products
     *
     * @return ApplyInterface
     */
    public function addGrouped()
    {
        return $this->_addProductType( 'grouped' );
    }

    /**
     * This attribute will be displayed for products of given product type
     *
     * @param $customType
     * @return \Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\Optional\ApplyInterface
     */
    public function addCustomType( $customType )
    {
        return $this->_addProductType( $customType );
    }

    /**
     * @return OptionalInterface
     */
    public function done()
    {
        $this->getParent()->getParent()->setAttributeCreateProperty( 'apply_to', implode( ',', $this->_types ) );
        return $this->getParent();
    }

    /**
     *
     * @param string $type
     * @return ApplyInterface
     */
    protected function _addProductType( $type )
    {
        if ( $this->_types === null ) {
            $this->_types = array();
        }

        if ( !in_array( $type, $this->_types ) ) {
            $this->_types[] = $type;
        }
        return $this;
    }

}
