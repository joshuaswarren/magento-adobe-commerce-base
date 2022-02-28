<?php


namespace Creatuity\Base\Model\Catalog\CategoriesModifier;


use Creatuity\Base\Model\Catalog\CategoriesModifier\Examples\ExampleInterface;

interface ExamplesFactoryInterface
{

    /**
     * @return ExampleInterface
     */
    public function create($type, array $args = []);

}