<?php
/**
 * Created by PhpStorm.
 * User: jbiesiada
 * Date: 12.12.16
 * Time: 07:46
 */

namespace Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Optional;


use Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Optional\Source\OptionsInterface;
use Creatuity\Base\Setup\Type\Customer\Attribute\Entity\OptionalInterface;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\Optional\SourceInterface as EavSourceInterface;

interface SourceInterface
    extends EavSourceInterface
{

    /**
     * Define the model which will provide the list of available options for the attribute
     *
     * @param $model
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