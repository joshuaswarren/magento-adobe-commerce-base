<?php
namespace Creatuity\Base\Setup\Type\Eav\Attribute\Entity\Required;

use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\RequiredInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
interface TypeInterface
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
