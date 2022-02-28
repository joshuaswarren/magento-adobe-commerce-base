<?php
namespace Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\Optional;

use Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\Optional\Source\OptionsInterface;
use Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\OptionalInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
interface SourceInterface
{
    /**
     * Define the model which will provide the list of available options for the attribute
     *
     * @return OptionalInterface
     */
    public function model( $model );

    /**
     * Manage attribute options
     *
     * @return OptionsInterface
     */
    public function options();

}
