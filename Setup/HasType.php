<?php

namespace Creatuity\Base\Setup;

use Creatuity\Base\Setup\Type\CatalogInterface;
use Creatuity\Base\Setup\Type\CustomerInterface;


/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
interface HasType {    

    /**
     * @return CatalogInterface
     */
    public function eavCatalog();

    /**
     * @return CustomerInterface
     */
    public function eavCustomer();
    
}
