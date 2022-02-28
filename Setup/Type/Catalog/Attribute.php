<?php namespace Creatuity\Base\Setup\Type\Catalog;

use Creatuity\Base\Setup\Type\Catalog\Attribute\EntityInterface;
use Creatuity\Base\Setup\Type\Eav\Attribute as EavAttribute;
use Creatuity\Base\Setup\TypeInterface as SetupTypeInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;


/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class Attribute extends EavAttribute implements AttributeInterface, SetupTypeInterface
{

    /**
     * @return EntityInterface
     */
    public function forCategory()
    {
        $this->entityType = Category::ENTITY;
        return $this->getContext()->getTypeFactory()->create( 'catalog_attribute_entity', $this );
    }

    /**
     * @return EntityInterface
     */
    public function forProduct()
    {
        $this->entityType = Product::ENTITY;
        return $this->getContext()->getTypeFactory()->create( 'catalog_attribute_entity', $this );
    }

}
