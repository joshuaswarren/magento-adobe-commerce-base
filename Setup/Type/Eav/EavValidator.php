<?php


namespace Creatuity\Base\Setup\Type\Eav;


use Creatuity\Base\Setup\Type\AttributeValidatorInterface;
use Creatuity\Base\Setup\Type\Eav\Attribute\EntityInterface;
use Creatuity\Base\Setup\Type\PropertyValidatorFactory;
use Creatuity\Base\Setup\Type\Validator;

class EavValidator implements AttributeValidatorInterface
{
    /**
     * @var PropertyValidatorFactory
     */
    protected $propertyFactory;
    /**
     * @var array
     */
    protected $properties;
    /**
     * @var Validator
     */
    protected $validator;

    /**
     * EavValidator constructor.
     * @param PropertyValidatorFactory $propertyFactory
     * @param array $properties
     * @param Validator $validator
     */
    public function __construct( PropertyValidatorFactory $propertyFactory, array $properties, Validator $validator )
    {
        $this->propertyFactory = $propertyFactory;
        $this->properties = $properties;
        $this->validator = $validator;
    }

    public function validateCreate( EntityInterface $entity )
    {
        $this->checkProperties( $entity, "validate" );
    }

    public function validateUpdate( EntityInterface $entity )
    {
        $this->checkCode( $entity );
        $this->checkProperties( $entity, "validateIfNotEmpty" );
    }

    public function validateDelete( EntityInterface $entity )
    {
        $this->checkCode( $entity );
    }

    protected function checkProperties( EntityInterface $entity, $method )
    {
        foreach ( $this->properties as $property ) {
            $validationProperty = $this->propertyFactory->create( $property, $entity->getAttributeProperties() );
            $validationProperty->{$method}();
        }
    }

    protected function checkCode( EntityInterface $entity )
    {
        $this->validator->ensure( $entity->getCode() )->isNotEmpty();
    }
}