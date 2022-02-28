<?php

namespace Creatuity\Base\Setup;

use Magento\Framework\ObjectManagerInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class TypeFactory
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
     * @param $code
     * @param TypeInterface $parent
     * @param array $arguments
     * @return mixed
     * @throws \Exception
     */
    public function create( $code, TypeInterface $parent, array $arguments = [] )
    {
        $classPart = str_replace( '_', '\\', ucwords( $code, "_ \t\r\n\f\v" ) );

        $type = "\\Creatuity\\Base\\Setup\\Type\\{$classPart}";

        $ret = $this->objectManager->create( $type, [
                'parent' => $parent,
            ] + $arguments );

        if ( !$ret instanceof AbstractType ) {
            throw new \Exception( "Expected object to be " . AbstractHelper::class );
        }

        return $ret;
    }

}
