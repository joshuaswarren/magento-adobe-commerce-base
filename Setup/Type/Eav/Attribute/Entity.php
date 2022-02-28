<?php namespace Creatuity\Base\Setup\Type\Eav\Attribute;

use Creatuity\Base\Setup\AbstractType;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\OptionalInterface;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\RequiredInterface;
use Creatuity\Base\Setup\Type\AttributeValidatorInterface;
use Magento\Eav\Model\AttributeManagement;
use Magento\Eav\Model\Config;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\Collection;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Creatuity\Base\Setup\TypeInterface;
use Creatuity\Base\Setup\AbstractUpgradeDataContext;
use Creatuity\Base\Setup\TypeInterface as SetupTypeInterface;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class Entity extends AbstractType implements EntityInterface, SetupTypeInterface
{
    protected $attributeCreateProperties = [];
    protected $attributeUpdateProperties = [];
    protected $options = [];
    protected $applyToAllAttributeSets = false;
    protected $attributeSets = [];
    protected $attributeGroup = 'General';
    protected $code;
    protected $entityType;
    protected $scope;
    /**
     * @var AttributeValidatorInterface
     */
    protected $validator;

    /** @var array $propertyMap */
    protected $propertyMap;

    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;


    /** @var Config */
    private $eavConfig;

    /**
     * @var AttributeManagement
     */
    protected $attributeManagement;

    /**
     * @var CollectionFactory
     */
    protected $setCollectionFactory;

    /**
     * @var Set
     */
    protected $attributeSet;

    /**
     * Init
     *
     * @param SetupTypeInterface $parent
     * @param AbstractUpgradeDataContext $context
     * @param EavSetupFactory $eavSetupFactory
     * @param AttributeValidatorInterface $validator
     */
    public function __construct(
        Set $attributeSet,
        CollectionFactory $setCollectionFactory,
        AttributeManagement $attributeManagement,
        Config $eavConfig,
        TypeInterface $parent,
        AbstractUpgradeDataContext $context,
        EavSetupFactory $eavSetupFactory,
        AttributeValidatorInterface $validator,
        array $propertyMap
    )
    {
        parent::__construct( $parent, $context );
        $this->code = $parent->getCode();
        $this->entityType = $parent->getEntityType();
        $this->eavSetupFactory = $eavSetupFactory;
        $this->validator = $validator;
    	$this->eavConfig = $eavConfig;
		$this->attributeManagement = $attributeManagement;
		$this->setCollectionFactory = $setCollectionFactory;
		$this->attributeSet = $attributeSet;
		$this->propertyMap = $propertyMap;
	}

    /**
     * @return EntityInterface
     */
    public function create()
    {
        $this->validator->validateCreate( $this );
        /** @var EavSetup $eavSetup */
        $createProperties = $this->getAttributeCreateProperties();
        $eavSetup = $this->eavSetupFactory->create();
        $eavSetup->addAttribute(
            $this->getEntityType(),
            $this->getCode(),
            $createProperties
        );

        $created = $this->eavConfig->getAttribute($this->getEntityType(), $this->getCode());

        $updateProperties = $this->getAttributeUpdateProperties();
        if (!empty($updateProperties)) {
            //some properties will work only when setting after creation (probably id is required)
            $created->addData($updateProperties)->save();
        }

        foreach ($this->getAllAttributeSetsToAssign() as $attributeSetId => $data) {
            $this->attributeManagement->assign(
                $this->getEntityType(),
                $attributeSetId,
                ($data['group_id'] == null) ? $this->attributeSet->getDefaultGroupId($attributeSetId) : $data['group_id'],
                $this->getCode(),
                $data['sort_order']
            );
        }

        return $this;
    }

    /**
     * @return EntityInterface
     */
    public function update()
    {
        $this->validator->validateUpdate( $this );
        $eavSetup = $this->eavSetupFactory->create();
        foreach ($this->getAttributeProperties() as $propertyName => $value) {
            if(!empty($this->propertyMap[$propertyName])) {
                $propertyName = $this->propertyMap[$propertyName];
            }
            $eavSetup->updateAttribute(
                $this->getEntityType(),
                $this->getCode(),
                $propertyName,
                $value
            );
        }
        return $this;
    }

    /**
     * @return EntityInterface
     */
    public function delete()
    {
        $this->validator->validateDelete( $this );
        $eavSetup = $this->eavSetupFactory->create();
        $eavSetup->removeAttribute( $this->getEntityType(), $this->getCode() );
        return $this;
    }

    /**
     * Manage required settings
     *
     * @return RequiredInterface
     */

    public function requiredSettings()
    {
        return $this->getContext()->getTypeFactory()->create( 'eav_attribute_entity_required', $this );
    }

    /**
     * Manage optional settings
     *
     * @return OptionalInterface
     */
    public function optionalSettings()
    {
        return $this->getContext()->getTypeFactory()->create( 'eav_attribute_entity_optional', $this );
    }

    /**
     * @param $propertyName
     * @param $value
     */
    public function setAttributeCreateProperty($propertyName, $value )
    {
        $this->attributeCreateProperties[ $propertyName ] = $value;
    }

    /**
     * @param $propertyName
     * @param $value
     */
    public function setAttributeUpdateProperty($propertyName, $value )
    {
        $this->attributeUpdateProperties[ $propertyName ] = $value;
    }

    /**
     *
     * @param array $options
     * @return EntityInterface
     */
    public function setOptions( array $options )
    {
        $this->options = $options;
        $this->setAttributeCreateProperty( 'option', $options );
        return $this;
    }

    /**
     *
     */
    protected function getAllAttributeSetsToAssign()
    {
        if ($this->applyToAllAttributeSets) {
            /** @var Collection $collection */
            $collection = $this->setCollectionFactory->create();
            $entityId = $this->eavConfig->getEntityType($this->getEntityType())->getId();
            foreach ($collection->setEntityTypeFilter($entityId)->toOptionArray() as $row) {
                if (!isset($this->attributeSets[$row['value']])) {
                    $this->attributeSets[$row['value']] = [
                        'group_id' => null,
                        'sort_order' =>100
                    ];
                }
            }
        }
        return $this->attributeSets;
    }

    /**
     *
     * @return EntityInterface
     */
    public function addToAllSets()
    {
        $this->applyToAllAttributeSets = true;
        return $this;
    }

    /**
     * @return EntityInterface
     */
    public function addToAttributeSet( $attributeSetId, $attributeGroupId, $sortOrder = 100 )
    {
        $this->attributeSets[$attributeSetId] = [
            'group_id' => $attributeGroupId,
            'sort_order' => $sortOrder,
        ];
        return $this;
    }

    /**
     * @param $scope
     * @return $this
     */
    public function setScope( $scope )
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getScope()
    {
        return $this->scope;
    }


    /**
     * @return array
     */
    public function getAttributeProperties()
    {
        return array_merge($this->attributeCreateProperties, $this->attributeUpdateProperties);
    }

    /**
     * @return array
     */
    public function getAttributeUpdateProperties()
    {
        return $this->attributeUpdateProperties;
    }

    /**
     * @return array
     */
    public function getAttributeCreateProperties()
    {
        return $this->attributeCreateProperties;
    }

    /**
     * @return array
     */
    public function getAttributeOptions()
    {
        return $this->options;
    }

    public function getCode()
    {
        return $this->code;
    }
}
