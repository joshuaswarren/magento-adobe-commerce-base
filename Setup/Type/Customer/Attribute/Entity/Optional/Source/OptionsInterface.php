<?php
namespace Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Optional\Source;


use Creatuity\Base\Setup\Type\Customer\Attribute\Entity\OptionalInterface;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\Optional\Source\OptionsInterface as EavOptionsInterface;

interface OptionsInterface
    extends EavOptionsInterface
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