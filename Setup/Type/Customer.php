<?php
namespace Creatuity\Base\Setup\Type;

use Creatuity\Base\Setup\Type\Customer\AttributeInterface;

class Customer extends Eav implements CustomerInterface
{
    /**
     * @param string $code
     * @return AttributeInterface
     */
    public function attribute( $code = '' )
    {
        return $this->getContext()->getTypeFactory()->create( 'customer_attribute', $this, array( 'code' => $code ) );
    }
}