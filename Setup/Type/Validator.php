<?php

namespace Creatuity\Base\Setup\Type;

class Validator
{


    protected $firstParam;

    public function ensure( $param )
    {
        $this->firstParam = isset( $param ) ? $param : null;
        return $this;
    }

    /**
     * @param $index
     * @param null $message
     * @return $this
     */
    public function hasIndex( $index, $message = null )
    {
        if ( !is_array( $this->firstParam ) ) {
            $this->throwError( json_encode( $this->firstParam ) . " is not an array." );
        }
        if ( isset( $this->firstParam[ $index ] ) ) {
            return $this;
        }
        $this->throwError( $message ? : "There is no " . json_encode( $index ) . " index in " . json_encode( $this->firstParam ) );
    }

    /**
     * @param $array
     * @param null $message
     * @return $this
     */
    public function isOneOf( $array, $message = null )
    {
        $this->checkFirstParam();
        if ( !is_array( $array ) ) {
            $this->throwError( json_encode( $array ) . "Is not array" );
        }
        if ( in_array( $this->firstParam, $array ) ) {
            return $this;
        }
        $this->throwError( $message ? : $this->firstParam . " is not one of " . json_encode( $array ) );
    }

    /**
     * @param $param
     * @param null $message
     * @return $this
     */
    public function isEqualTo( $param, $message = null )
    {
        $this->checkFirstParam();
        if ( $this->firstParam == $param ) {
            return $this;
        }
        $this->throwError( $message ? : $this->firstParam . " is not equal to " . $param );
    }

    /**
     * @param $type
     * @param null $message
     * @return $this
     */
    public function isType( $type, $message = null )
    {
        if ( gettype( $this->firstParam ) == $type ) {
            return $this;
        }
        if ( is_object( $this->firstParam ) && get_class( $this->firstParam ) == $type ) {
            return $this;
        }
        $this->throwError( $message ? : json_encode( $this->firstParam ) . " is not " . $type );
    }


    /**
     * @param null $message
     * @return Validator
     */
    public function isNotEmpty( $message = null )
    {
        return $this->checkFirstParam( $message );
    }

    /**
     * @param null $message
     * @return $this
     */
    protected function checkFirstParam( $message = null )
    {
        if ( empty( $this->firstParam ) ) {
            $this->throwError( $message ? : "param is empty" );
        }
        return $this;
    }

    /**
     * @param null $message
     * @throws \Exception
     */
    protected function throwError( $message = null )
    {
        throw new \Exception( $message );
    }
}