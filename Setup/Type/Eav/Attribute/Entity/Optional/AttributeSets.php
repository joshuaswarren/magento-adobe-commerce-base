<?php namespace Creatuity\Base\Setup\Type\Eav\Attribute\Entity\Optional;

use Creatuity\Base\Setup\AbstractType;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\OptionalInterface;
use Creatuity\Base\Setup\TypeInterface as SetupTypeInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class AttributeSets extends AbstractType implements AttributeSetsInterface, SetupTypeInterface
{
    /**
     * Add attribute to attribute set
     *
     * @param int $attributeSetId
     * @param int $attributeGroupId if null attribute will be assigned to first group from this set
     * @param int $sortOrder
     * @return AttributeSetsInterface
     */
    public function addToAttributeSet( $attributeSetId, $attributeGroupId = null, $sortOrder = 100)
    {
        $this->getParent()->getParent()->addToAttributeSet( $attributeSetId, $attributeGroupId, $sortOrder );
        return $this;
    }

    /**
     * Add attribute to first group for all attribute sets
     * @return AttributeSetsInterface
     */
    public function addToAllSets()
    {
        $this->getParent()->getParent()->addToAllSets();
        return $this;
    }

    /**
     * Finish adding attribute to attribute sets
     *
     * @return OptionalInterface
     */
    public function done()
    {
        return $this->getParent();
    }


}
