<?php namespace Creatuity\Base\Setup\Type;

use Creatuity\Base\Setup\AbstractType;
use Creatuity\Base\Setup\Type\Eav\AttributeInterface;
use Creatuity\Base\Setup\TypeInterface as SetupTypeInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class Eav extends AbstractType implements EavInterface, SetupTypeInterface
{


    /**
     * @param string $code
     * @return AttributeInterface
     */
    public function attribute( $code )
    {
        return $this->getContext()->getTypeFactory()->create( 'eav_attribute', $this, array( 'code' => $code ) );
    }


}
