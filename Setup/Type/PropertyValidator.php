<?php


namespace Creatuity\Base\Setup\Type;


class PropertyValidator extends Validator implements PropertyValidatorInterface
{

    protected $container;
    protected $name;
    protected $isRequired;
    protected $isNotEmpty;
    protected $isOneOf;
    protected $isType;
    /**
     * @var Validator
     */
    private $validator;

    /**
     * PropertyValidator constructor.
     * @param $name
     * @param bool $isRequired
     * @param bool $isNotEmpty
     * @param array $isOneOf
     * @param string $isType
     * @param $container
     * @param Validator $validator
     */
    public function __construct( $name, $isRequired, $isNotEmpty, $isOneOf, $isType, $container, Validator $validator )
    {
        $this->name = $name;
        $this->isRequired = $isRequired;
        $this->isNotEmpty = $isNotEmpty;
        $this->isOneOf = $isOneOf;
        $this->isType = $isType;
        $this->container = $container;
        $this->validator = $validator;
    }

    public function validate()
    {
        if ( $this->getIsRequired() ) {
            $this->validator->ensure( $this->container )->hasIndex( $this->getName(), "There is no \"" . $this->getName() . "\" selected" );
        }
        $this->validateIfNotEmpty();
    }

    public function validateIfNotEmpty()
    {
        if ( isset ( $this->container[ $this->getName() ] ) ) {
            if ( $this->getIsNotEmpty() ) {
                $this->validator->ensure( $this->container[ $this->getName() ] )->isNotEmpty( "There is no \"" . $this->getName() . "\" selected" );
            }
            if ( !empty( $this->getIsOneOf() ) ) {
                $this->validator->ensure( $this->container[ $this->getName() ] )->isOneOf( $this->getIsOneOf(), "Wrong \"" . $this->getName() . "\" type" );
            }
            if ( !empty( $this->getIsType() ) ) {
                $this->validator->ensure( $this->container[ $this->getName() ] )->isType( $this->getIsType(), "\"" . $this->getName() . "\" has to be true or false" );
            }
        }
    }

    /**
     * @return string
     */
    protected function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    protected function getIsRequired()
    {
        return $this->isRequired;
    }

    /**
     * @return bool
     */
    protected function getIsNotEmpty()
    {
        return $this->isNotEmpty;
    }

    /**
     * @return array
     */
    protected function getIsOneOf()
    {
        return $this->isOneOf;
    }

    /**
     * @return string
     */
    protected function getIsType()
    {
        return $this->isType;
    }


}
