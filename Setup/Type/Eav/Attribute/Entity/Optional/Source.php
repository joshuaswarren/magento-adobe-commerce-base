<?php namespace Creatuity\Base\Setup\Type\Eav\Attribute\Entity\Optional;

use Creatuity\Base\Setup\AbstractType;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\Optional\Source\OptionsInterface;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\OptionalInterface;
use Creatuity\Base\Setup\TypeInterface as SetupTypeInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class Source extends AbstractType implements SourceInterface, SetupTypeInterface
{
    /**
     * Define the model which will provide the list of available options for the attribute
     *
     * @return OptionalInterface
     */
    public function model( $model )
    {
        $this->getParent()->getParent()->setAttributeCreateProperty( 'source', $model );
        return $this->getParent();
    }

    /**
     * Manage attribute options
     *
     * @return OptionsInterface
     */
    public function options()
    {
        return $this->getContext()->getTypeFactory()->create( 'eav_attribute_entity_optional_source_options', $this );
    }


}
