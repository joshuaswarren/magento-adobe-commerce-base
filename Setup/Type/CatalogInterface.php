<?php
namespace Creatuity\Base\Setup\Type;

use Creatuity\Base\Setup\Type\Catalog\AttributeGroupInterface;
use Creatuity\Base\Setup\Type\Catalog\AttributeInterface;
use Creatuity\Base\Setup\Type\Catalog\AttributeSetInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
interface CatalogInterface extends EavInterface
{
    /**
     * @param string $code
     * @return AttributeInterface
     */
    public function attribute( $code );

    /**
     * @return AttributeGroupInterface
     */
    public function attributeGroup();

    /**
     * @return AttributeSetInterface
     */
    public function attributeSet();

}
