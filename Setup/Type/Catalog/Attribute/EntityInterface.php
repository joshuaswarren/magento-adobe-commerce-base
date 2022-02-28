<?php
namespace Creatuity\Base\Setup\Type\Catalog\Attribute;

use Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\OptionalInterface;
use Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\RequiredInterface;
use Creatuity\Base\Setup\Type\Eav\Attribute\EntityInterface as EavEntityInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
interface EntityInterface
    extends EavEntityInterface
{

    /**
     * Manage required settings
     *
     * @return RequiredInterface
     */
    public function requiredSettings();

    /**
     * Manage optional settings
     *
     * @return OptionalInterface
     */
    public function optionalSettings();

    /**
     * @return EntityInterface
     */
    public function create();

    /**
     * @return EntityInterface
     */
    public function update();

    /**
     * @return EntityInterface
     */
    public function delete();

}
