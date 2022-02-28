<?php
namespace Creatuity\Base\Setup\Type\Catalog;

use Creatuity\Base\Setup\Type\Catalog\Attribute\EntityInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
interface AttributeInterface
{
    /**
     * @return EntityInterface
     */
    public function forProduct();

    /**
     * @return EntityInterface
     */
    public function forCategory();

}
