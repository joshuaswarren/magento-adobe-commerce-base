<?php namespace Creatuity\Base\Setup\Type\Eav\Attribute\Entity\Optional\Source;

use Creatuity\Base\Setup\AbstractType;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\OptionalInterface;
use Creatuity\Base\Setup\TypeInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class Options extends AbstractType implements OptionsInterface, TypeInterface
{
    protected $options = array();

    /**
     * Add option for the attribute.
     *
     * @param string $label
     * @param int $order
     * @return OptionsInterface
     */
    public function addOption( $label, $order = 0 )
    {
        $this->options[ 'value' ][ $label ] = array( 0 => $label );
        $this->options[ 'order' ][ $label ] = $order;
        return $this;
    }

    /**
     * Finish editing attribute's options.
     *
     * @return OptionalInterface
     */
    public function done()
    {
        $this->getParent()->getParent()->getParent()->setOptions( $this->options );
        return $this->getParent()->getParent();
    }


}
