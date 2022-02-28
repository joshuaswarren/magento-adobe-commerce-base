<?php

namespace Creatuity\Base\Setup\Type;

use Magento\Framework\ObjectManagerInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class PropertyValidatorFactory
{

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    public function __construct( ObjectManagerInterface $objectManager )
    {
        $this->objectManager = $objectManager;
    }


    /**
     * @param array $arguments
     * @param $container
     * @return PropertyValidatorInterface
     */
    public function create( array $arguments, $container )
    {
        $args = [
            'name' => '',
            'isRequired' => false,
            'isNotEmpty' => false,
            'isOneOf' => [],
            'isType' => "",
            'container' => $container
        ];
        $arguments = array_merge( $args, $arguments );
        $ret = $this->objectManager->create( PropertyValidator::class, $arguments );

        if ( !$ret instanceof PropertyValidatorInterface ) {
            throw new \Exception( "Expected object to be " . PropertyValidatorInterface::class );
        }

        return $ret;
    }

}
