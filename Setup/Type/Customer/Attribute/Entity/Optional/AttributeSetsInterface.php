<?php
/**
 * Created by PhpStorm.
 * User: jbiesiada
 * Date: 12.12.16
 * Time: 08:20
 */

namespace Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Optional;


use Creatuity\Base\Setup\Type\Customer\Attribute\Entity\OptionalInterface;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\Optional\AttributeSetsInterface as EavAttributeSetsInterface;

interface AttributeSetsInterface
    extends EavAttributeSetsInterface
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