<?php
namespace Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\Required;

use Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\RequiredInterface;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\Required\TypeInterface as EavTypeInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
interface TypeInterface
    extends EavTypeInterface
{
    /**
     *
     * @return RequiredInterface
     */
    public function varchar();

    /**
     *
     * @return RequiredInterface
     */
    public function int();

    /**
     *
     * @return RequiredInterface
     */
    public function text();

    /**
     *
     * @return RequiredInterface
     */
    public function decimal();

    /**
     *
     * @return RequiredInterface
     */
    public function datetime();
}
