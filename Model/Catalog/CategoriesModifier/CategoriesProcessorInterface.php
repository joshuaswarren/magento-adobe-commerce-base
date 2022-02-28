<?php


namespace Creatuity\Base\Model\Catalog\CategoriesModifier;


interface CategoriesProcessorInterface
{

    /**
     * @return void
     */
    public function process(Config $config, array $data);

}