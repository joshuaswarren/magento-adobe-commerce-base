<?php
namespace Creatuity\Base\Setup\Type\Eav\Attribute\Entity\Optional;

use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\OptionalInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
interface AttributeSetsInterface
{
    /**
     * Add attribute to attribute set
     *
     * @param int $attributeSetId
     * @param int $attributeGroupId if null attribute will be assigned to first group from this set
     * @param int $sortOrder
     * @return AttributeSetsInterface
     */
    public function addToAttributeSet( $attributeSetId, $attributeGroupId = null, $sortOrder = 100);

    /**
     * Add attribute to first group for all attribute sets
     * @return AttributeSetsInterface
     */
    public function addToAllSets();

    /**
     * Finish adding attribute to attribute sets
     *
     * @return OptionalInterface
     */
    public function done();

}
