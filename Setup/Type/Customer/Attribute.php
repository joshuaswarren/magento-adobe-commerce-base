<?php
namespace Creatuity\Base\Setup\Type\Customer;


use Creatuity\Base\Setup\Type\Eav\Attribute as EavAttribute;
use Magento\Customer\Model\Customer;
use Creatuity\Base\Setup\TypeInterface as SetupTypeInterface;

class Attribute
    extends EavAttribute
    implements AttributeInterface, SetupTypeInterface
{
    /**
     *
     * @return \Creatuity\Base\Setup\Type\Customer\Attribute\Entity
     */
    public function forCustomer()
    {
        $this->entityType = Customer::ENTITY;
        return $this->getContext()->getTypeFactory()->create( 'customer_attribute_entity', $this );
    }

    /**
     *
     * @return \Creatuity\Base\Setup\Type\Customer\Attribute\Entity
     */
    public function forCustomerAddress()
    {
        $this->entityType = "customer_address";
        return $this->getContext()->getTypeFactory()->create( 'customer_attribute_entity', $this );
    }

}