<?php
/**
 * Created by PhpStorm.
 * User: jbiesiada
 * Date: 19.12.16
 * Time: 11:12
 */

namespace Creatuity\Base\Setup\Type\Catalog;


use Creatuity\Base\Setup\AbstractType;
use Creatuity\Base\Setup\AbstractUpgradeDataContext;
use Creatuity\Base\Setup\TypeInterface;
use Magento\Eav\Model\AttributeSetManagement;
use Magento\Eav\Model\Entity\Attribute\SetFactory;
use Magento\Eav\Model\Entity\TypeFactory;

class AttributeSet
    extends AbstractType
    implements AttributeSetInterface
{

    protected $_coreSetupClass = 'catalog/setup';

    /**
     * @var SetFactory
     */
    protected $attributeSetFactory;

    /**
     * @var AttributeSetManagement
     */
    protected $attributeSetManagement;

    /**
     * @var TypeFactory
     */
    protected $eavTypeFactory;

    public function __construct(
        TypeInterface $parent,
        AbstractUpgradeDataContext $context,
        SetFactory $attributeSetFactory,
        AttributeSetManagement $attributeSetManagement,
        TypeFactory $eavTypeFactory
    ) {
        parent::__construct($parent, $context);
        $this->attributeSetFactory = $attributeSetFactory;
        $this->attributeSetManagement = $attributeSetManagement;
        $this->eavTypeFactory = $eavTypeFactory;
    }

    public function createSet($name, $entityCode, $attributeSetNameToCloneFrom = null)
    {
        $attributeSet = $this->attributeSetFactory->create();
        $attributeSet->setEntityTypeId($this->entityIdByCode($entityCode));
        $attributeSet->setAttributeSetName($name);

        $this->attributeSetManagement->create($entityCode, $attributeSet, $this->entityTypeDefaultSetId($entityCode));
        return $this->catalog();
    }

    protected function entityIdByCode($entityCode)
    {
        return $this->entityType($entityCode)->getId();
    }

    protected function entityTypeDefaultSetId($entityCode)
    {
        return $this->entityType($entityCode)->getDefaultAttributeSetId();
    }

    protected function entityType($entityCode)
    {
        return $this->eavTypeFactory->create()->loadByCode($entityCode);
    }

}
