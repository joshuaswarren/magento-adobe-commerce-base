<?php
namespace Creatuity\Base\Setup\Type\Eav\Attribute\Entity\Optional\Source;

use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\OptionalInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
interface OptionsInterface
{
    /**
     * Add option for the attribute.
     *
     * @param string $label
     * @param int $order
     * @return OptionsInterface
     */
    public function addOption( $label, $order = 0 );

    /**
     * Finish editing attribute's options.
     *
     * @return OptionalInterface
     */
    public function done();

}
