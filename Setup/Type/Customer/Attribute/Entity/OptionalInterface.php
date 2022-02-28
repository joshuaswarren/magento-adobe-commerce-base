<?php
/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
namespace Creatuity\Base\Setup\Type\Customer\Attribute\Entity;


use Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Optional\AttributeSetsInterface;
use Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Optional\SourceInterface;
use Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Optional\UsedInFormsInterface;
use Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Optional\ValidationRulesInterface;
use Creatuity\Base\Setup\Type\Customer\Attribute\EntityInterface;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\OptionalInterface as EavOptionalInterface;

interface OptionalInterface
    extends EavOptionalInterface
{

    /**
     * Indicate the model class to define how the attribute data should be handlesd when the entity object is saved or deleted.
     *
     * @param string $model
     * @return OptionalInterface
     */
    public function backendModel( $model );

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
     * @return EavOptionalInterface
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
     * Determine the filter for the attribute value.
     * Available filters: date, datetime, escapehtml, striptags.
     *
     * @return OptionalInterface
     */
    public function inputFilter( $filter );

    /**
     * Determine the count of lines of the input.
     *
     * @return OptionalInterface
     */
    public function multilineCount( $count );

    /**
     * Manage validation rules for the attribute
     *
     * @return ValidationRulesInterface
     */
    public function validateRules();

    /**
     * Manage forms where the attribute should be displayed
     *
     * @return UsedInFormsInterface
     */
    public function usedInForms();

    /**
     * @return Optional
     */
    public function defaultAttributeSetId();

    /**
     * @param int $id
     * @return Optional
     */
    public function attributeSetId($id);

    /**
     * @return Optional
     */
    public function defaultAttributeGroupId();

    /**
     * @param int $id
     * @return Optional
     */
    public function attributeGroupId($id);

    /**
     * Indicate the model that handles attribute data operations, e.g. validation.
     *
     * @return OptionalInterface
     */
    public function dataModel( $model );

    /**
     * Determine the sort order of the attribute
     *
     * @return OptionalInterface
     */
    public function position( $position );

    /**
     * Finish editing optional settings
     *
     * @return EntityInterface
     */
    public function done();

}