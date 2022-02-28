<?php
namespace Creatuity\Base\Setup\Type\Eav\Attribute\Entity\Required;

use Creatuity\Base\Setup\AbstractType;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\RequiredInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class Type extends AbstractType implements TypeInterface
{

    const ATTRIBUTE_TYPES = [
        "TYPE_DATETIME" => 'datetime',
        "TYPE_DECIMAL" => 'decimal',
        "TYPE_INT" => 'int',
        "TYPE_STATIC" => 'static',
        "TYPE_TEXT" => 'text',
        "TYPE_VARCHAR" => 'varchar',
    ];


    /**
     *
     * @return RequiredInterface
     */
    public function varchar()
    {
        return $this->_setType( self::ATTRIBUTE_TYPES[ "TYPE_VARCHAR" ] );
    }

    /**
     *
     * @return RequiredInterface
     */
    public function int()
    {
        return $this->_setType( self::ATTRIBUTE_TYPES[ "TYPE_INT" ] );
    }

    /**
     *
     * @return RequiredInterface
     */
    public function text()
    {
        return $this->_setType( self::ATTRIBUTE_TYPES[ "TYPE_TEXT" ] );
    }

    /**
     *
     * @return RequiredInterface
     */
    public function decimal()
    {
        return $this->_setType( self::ATTRIBUTE_TYPES[ "TYPE_DECIMAL" ] );
    }

    /**
     *
     * @return RequiredInterface
     */
    public function datetime()
    {
        return $this->_setType( self::ATTRIBUTE_TYPES[ "TYPE_DATETIME" ] );
    }

    /**
     *
     * @param string $type
     * @return  RequiredInterface
     */
    protected function _setType( $type )
    {
        $this->getParent()->getParent()->setAttributeCreateProperty( 'type', $type );
        return $this->getParent();
    }
}
