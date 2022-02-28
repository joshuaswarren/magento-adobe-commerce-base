<?php namespace Creatuity\Base\Setup\Type;


use Creatuity\Base\Setup\AbstractType;
use Creatuity\Base\Setup\Type\Catalog\AttributeGroupInterface;
use Creatuity\Base\Setup\Type\Catalog\AttributeInterface;
use Creatuity\Base\Setup\Type\Catalog\AttributeSetInterface;
use Creatuity\Base\Setup\TypeInterface as SetupTypeInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class Catalog extends AbstractType implements CatalogInterface, SetupTypeInterface
{


    /**
     * @param string $code
     * @return AttributeInterface
     */
    public function attribute( $code )
    {
        return $this->getContext()->getTypeFactory()->create( 'catalog_attribute', $this, array( 'code' => $code ) );
    }

    /**
     * @return AttributeGroupInterface
     */
    public function attributeGroup()
    {
        return $this->getContext()->getTypeFactory()->create( 'catalog_attributeGroup', $this );
    }

    /**
     * @return AttributeSetInterface
     */
    public function attributeSet()
    {
        return $this->getContext()->getTypeFactory()->create( 'catalog_attributeSet', $this );
    }

}
