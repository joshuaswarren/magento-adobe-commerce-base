<?php
namespace Creatuity\Base\Setup\Type\Eav\Attribute;

use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\OptionalInterface;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\RequiredInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
interface EntityInterface
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
     * @return array
     */
    public function getAttributeProperties();

    /**
     * @return array
     */
    public function getAttributeOptions();

    /**
     * @return string
     */
    public function getCode();

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
