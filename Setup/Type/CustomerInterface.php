<?php
namespace Creatuity\Base\Setup\Type;

use Creatuity\Base\Setup\Type\Customer\AttributeInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
interface CustomerInterface extends EavInterface
{
    /**
     * @param string $code
     * @return AttributeInterface
     */
    public function attribute( $code );

}
