<?php


namespace Creatuity\Base\Model\Catalog\CategoriesModifier\Examples;


class InstallSimpleTreeExample implements ExampleInterface
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
                'this' => 'food',
                'parent' => '/',
                'name' => 'Food',
                'color' => 'yellow',
                'description' => 'Are You hungry? Try some of our food',
                'is_anchor' => false,
            ],
            [
                'this' => 'bread',
                'parent' => '/food',
                'name' => 'Bread',
                'description' => 'Healthy bread',
            ],
            [
                'this' => 'sweets',
                'parent' => '/food',
                'name' => 'Sweets',
                'description' => 'All kinds of sweetness',
            ],
            [
                'this' => 'electronics',
                'parent' => '/',
                'name' => 'Electronics',
                'color' => 'blue',
                'description' => 'You can find plenty of electronic devices here',
                'is_anchor' => true,
            ],
            [
                'this' => 'computers',
                'parent' => '/electronics',
                'name' => 'Computers',
                'description' => 'Everything you need for your computer',
            ],
            [
                'this' => 'dj',
                'parent' => '/electronics',
                'name' => 'DJ',
                'description' => 'Electronic DJ items, like CDJ players, mixers...',
            ],
            [
                'this' => 'vinyl-adapter',
                'parent' => 'dj',
                'name' => 'Vinyl Adapters',
                'description' => 'Vinyl Adapters',
            ],
        ];
    }
}