<?php
/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
namespace Creatuity\Base\Setup\Type\Customer;


use Creatuity\Base\Setup\Type\Customer\Attribute\EntityInterface;

interface AttributeInterface
{
    /**
     *
     * @return EntityInterface
     */
    public function forCustomer();

    /**
     *
     * @return EntityInterface
     */
    public function forCustomerAddress();
}