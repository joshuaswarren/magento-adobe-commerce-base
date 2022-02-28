<?php


namespace Creatuity\Base\Model\Catalog\CategoriesModifier\Examples;


class AutoDocumentingExample implements ExampleInterface
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
                    'replace_current_categories' => true,
                ],
            ],


            [
                'this' => 'parent_of_parent_of_bread',
                'entity_id' => 3,
                'parent' => 2,
            ],
            [
                'this' => 'parent_of_bread',
                'entity_id' => 5,
                'parent' => 'parent_of_parent_of_bread',
            ],
            [
                // Provide unique id of the entity.
                // It's just to determine tree structure.
                // It won't be stored in database.
                'this' => 'food',

                // you can modify existing category by providing it's id.
                // Otherwise, a new category will be created.
                // 'entity_id' => 1313,

                // Provide parent key (the one parent has in 'this')
                // You can also provide existing root_category_id here
                'parent' => 'parent_of_parent_of_bread/parent_of_bread',
//                'is_root_category' => true,
                'name' => 'Food',
                'color' => 'yellow',
                'description' => 'qqAre You hungry? Try some of our food',
                'is_anchor' => false,
                'is_root_category_for_store_groups' =>
                    [
                    ],
            ],
            [
                'this' => 'bread',
                'parent' => '/food',
                'name' => 'qqBread',
                'description' => 'Healthy bread',
            ],
            [
                'this' => 'sweets',
                'parent' => '/food',
                'name' => 'qqSweets',
                'description' => 'All kinds of sweetness',
            ],
            [
                'this' => 'electronics',
                'parent' => '/',
//                'is_root_category' => true,
                'name' => 'Electronics',
                'color' => 'blue',
                'description' => 'You can find plenty of electronic devices here',
                'is_anchor' => true,
                'is_root_category_for_store_groups' =>
                    [
                        'uk_group',
                    ],
                'is_root_category_for_stores' =>
                    [
                    ],
            ],
            [
                'this' => 'computers',
                'parent' => '/electronics',
                'name' => 'Computers',
                'description' => 'Everything you need for your computer',
                'is_root_category_for_store_groups' =>
                    [
                        'th_group'
                    ],
            ],
            [
                'this' => 'dj',
                'parent' => '/electronics',
                'name' => 'DJ',
                'description' => 'Electronic DJ items, like CDJ players, mixers...',
                'is_root_category_for_stores' =>
                    [
                    ],
            ],
        ];
    }
}