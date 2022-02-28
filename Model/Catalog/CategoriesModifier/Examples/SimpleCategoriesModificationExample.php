<?php

namespace Creatuity\Base\Model\Catalog\CategoriesModifier\Examples;

class SimpleCategoriesModificationExample implements ExampleInterface
{

    /**
     * @return array[]
     */
    public function demo()
    {
        return [
            [
                'is_config_node' => true,
                'config' => [
                    'replace_current_categories' => false,
                ],
            ],

            [
                'entity_id' => 4,
                'name' => 'BEST Electronics Ever!',
            ],

            [
                'entity_id' => 3,
                'name' => 'BEST Food Ever!',
            ],
        ];
    }
}