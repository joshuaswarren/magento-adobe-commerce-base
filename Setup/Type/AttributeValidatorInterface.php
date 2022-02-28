<?php
namespace Creatuity\Base\Setup\Type;


use Creatuity\Base\Setup\Type\Eav\Attribute\EntityInterface;

interface AttributeValidatorInterface
{
    public function validateCreate( EntityInterface $entity );

    public function validateUpdate( EntityInterface $entity );

    public function validateDelete( EntityInterface $entity );
}