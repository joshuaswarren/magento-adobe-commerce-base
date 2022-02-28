<?php
namespace Creatuity\Base\Setup\Type\Eav\Attribute\Entity;

use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\Optional\AttributeSetsInterface;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\Optional\SourceInterface;
use Creatuity\Base\Setup\Type\Eav\Attribute\EntityInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
interface OptionalInterface
{
    /**
     * Indicate the model class to define how the attribute data should be handlesd when the entity object is saved or deleted.
     *
     * @param string $model
     * @return OptionalInterface
     */
    public function backendModel( $model );

    /**
     * Select "1" to add this attribute to the list of column options in the product grid.
     *
     * @param int $value 0 or 1
     * @return OptionalInterface
     */
    public function isUsedInGrid($value);

    /**
     * Select "1" to add this attribute to the list of filter options in the product grid.
     *
     * @param int $value 0 or 1
     * @return OptionalInterface
     */
    public function isFilterableInGrid( $value );

    /**
     * Define the database table where attributes should be stored.
     * If it's not set, the database table is retrieved dynamically based on attribute type (e.g. entitytypename\entity\varchar for varchar)
     *
     * @param string $table
     * @return OptionalInterface
     */
    public function backendTable( $table );

    /**
     * Indicate the class that is responsible for the attribute's presentation to the user.
     *
     * @param string $model
     * @return OptionalInterface
     */
    public function frontendModel( $model );

    /**
     * Define a tip which can be displayed next to the attribute's field in Admin Panel.
     *
     * @param string $note
     * @return OptionalInterface
     */
    public function note( $note );

    /**
     * Define a group for the attribute
     *
     * @param string $groupName
     * @return OptionalInterface
     */
    public function group( $groupName );

    /**
     * Manage the list of available options for the attribute.
     * It can be used only for attributes of type 'select' or 'multiselect'.
     *
     * @return SourceInterface
     */
    public function source();

    /**
     * Manage attribute to attribute sets relations
     *
     * @return AttributeSetsInterface
     */
    public function attributeSets();

    /**
     * Finish editing optional settings
     *
     * @return EntityInterface
     */
    public function done();

}
