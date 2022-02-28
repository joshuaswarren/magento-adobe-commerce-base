<?php namespace Creatuity\Base\Setup\Type\Eav;

use Creatuity\Base\Setup\AbstractUpgradeDataContext;
use Creatuity\Base\Setup\AbstractType;
use Creatuity\Base\Setup\Type\Eav\Attribute\EntityInterface;
use Creatuity\Base\Setup\TypeInterface as SetupTypeInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class Attribute extends AbstractType implements AttributeInterface, SetupTypeInterface
{

    /**
     * @var string
     */
    protected $code;
    /**
     * @var string
     */
    protected $entityType;

    /**
     * Attribute constructor.
     * @param SetupTypeInterface $parent
     * @param AbstractUpgradeDataContext $context
     * @param $code
     */
    public function __construct(SetupTypeInterface $parent, AbstractUpgradeDataContext $context, $code )
    {
        $this->code = $code;
        parent::__construct( $parent, $context );
    }

    /**
     * @param $entityType
     * @return EntityInterface
     */
    public function forEntity( $entityType )
    {
        $this->entityType = $entityType;
        return $this->getContext()->getTypeFactory()->create( 'eav_attribute_entity', $this );
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getEntityType()
    {
        return $this->entityType;
    }

}
