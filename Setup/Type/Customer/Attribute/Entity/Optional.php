<?php
namespace Creatuity\Base\Setup\Type\Customer\Attribute\Entity;


use Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Optional\UsedInForms;
use Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Optional\ValidationRules;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\Optional as EavOptional;
use Magento\Eav\Model\Config;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory;

class Optional
    extends EavOptional
    implements OptionalInterface
{


    /** @var Config */
    private $eavConfig;

    /**
     * @var SetFactory
     */
    private $attributeSetFactory;



	public function __construct(
		Config $eavConfig,
		SetFactory $attributeSetFactory,
		\Creatuity\Base\Setup\TypeInterface $parent,
		\Creatuity\Base\Setup\AbstractUpgradeDataContext $context
	) {
		parent::__construct($parent, $context);
		$this->attributeSetFactory = $attributeSetFactory;
		$this->eavConfig = $eavConfig;
	}

    /**
     *
     * @return UsedInForms
     */
    public function usedInForms()
    {
        return $this->getContext()->getTypeFactory()->create( 'customer_attribute_entity_optional_usedInForms', $this );
    }

    /**
     * @return Optional
     */
    public function defaultAttributeSetId()
    {
        $entity = $this->eavConfig->getEntityType($this->getParent()->getEntityType());
        $attributeSetId = $entity->getDefaultAttributeSetId();
        return $this->attributeSetId($attributeSetId);
    }

    /**
     *
     * @param int $id
     * @return Optional
     */
    public function attributeSetId($id)
    {
        $this->getParent()->setAttributeUpdateProperty( 'attribute_set_id', $id );
        return $this;
    }

    /**
     * @return Optional
     */
    public function defaultAttributeGroupId()
    {
        $entity = $this->eavConfig->getEntityType($this->getParent()->getEntityType());
        $attributeSetId = $entity->getDefaultAttributeSetId();
        /** @var $attributeSet AttributeSet */
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
        return $this->attributeGroupId($attributeGroupId);
    }

    /**
     * @param int $id
     * @return Optional
     */
    public function attributeGroupId($id)
    {
        $this->getParent()->setAttributeUpdateProperty( 'attribute_group_id', $id );
        return $this;
    }

    /**
     *
     * @return ValidationRules
     */
    public function validateRules()
    {
        return $this->getContext()->getTypeFactory()->create( 'customer_attribute_entity_optional_validateRules', $this );
    }

    /**
     *
     * @param string $model
     * @return Optional
     */
    public function dataModel( $model )
    {
        $this->getParent()->setAttributeCreateProperty( 'data_model', $model );
        return $this;
    }

    /**
     *
     * @param string $filter
     * @return Optional
     */
    public function inputFilter( $filter )
    {
        $this->getParent()->setAttributeCreateProperty( 'input_filter', $filter );
        return $this;
    }

    /**
     *
     * @param int $count
     * @return Optional
     */
    public function multilineCount( $count )
    {
        $this->getParent()->setAttributeCreateProperty( 'multiline_count', $count );
        return $this;
    }

    /**
     *
     * @param int $position
     * @return Optional
     */
    public function position( $position )
    {
        $this->getParent()->setAttributeCreateProperty( 'position', $position );
        return $this;
    }

}