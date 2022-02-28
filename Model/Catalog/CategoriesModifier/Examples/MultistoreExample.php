<?php


namespace Creatuity\Base\Model\Catalog\CategoriesModifier\Examples;


class MultistoreExample implements ExampleInterface
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
                    'tree_delimiter' => '/',
                ],
            ],

            [
                'this' => 'abc-store',
                'parent' => '/',
                'name' => 'ABC Store',
                'description' => 'Root category for ABC store',
                'is_root_category' => true,
                'is_root_category_for_store_groups' => ['abc_group'],
            ],
            [
                'this' => 'food',
                'parent' => '/abc-store',
                'name' => 'Food',
                'color' => 'yellow',
                'description' => 'Are You hungry? Try some of your food',
                'is_anchor' => false,
                'stores' => [
                    'abc_pl' => [
                        'name' => 'Jedzenie',
                        'url_key' => 'jedzenie',
                        'description' => 'Jesteś głodny? Spróbuj naszego jedzenia',
                    ],
                ],
            ],
            [
                'this' => 'bread',
                'parent' => '/abc-store/food',
                'name' => 'Bread',
                'description' => 'Healthy bread',
                'stores' => [
                    'abc_pl' => [
                        'name' => 'Pieczywo',
                        'url_key' => 'pieczywo',
                        'description' => 'Zdrowiuśkie pieczywko',
                    ],
                ],
            ],
            [
                'this' => 'sweets',
                'parent' => '/abc-store/food',
                'name' => 'Sweets',
                'description' => 'All kinds of sweetness',
                'stores' => [
                    'abc_pl' => [
                        'name' => 'Słodycze',
                        'url_key' => 'slodycze',
                        'description' => 'Wszystkie rodzaje słodkości',
                    ],
                ],
            ],
            [
                'this' => 'xyz-store',
                'parent' => '/',
                'name' => 'XYZ Store',
                'description' => 'Root category for XYZ store',
                'is_root_category' => true,
                'is_root_category_for_store_groups' => [
                    'xyz_group',
                ],
            ],
            [
                'this' => 'electronics',
                'parent' => '/xyz-store',
                'name' => 'Electronics',
                'color' => 'blue',
                'description' => 'You can find plenty of electronic devices here',
                'is_anchor' => true,
                'stores' => [
                    'xyz_pl' => [
                        'name' => 'Elektronika',
                        'url_key' => 'elektronika',
                        'description' => 'Możesz znaleźć u nas mnóstwo elektronicznych urządzeń',
                    ],
                ],
            ],
            [
                'this' => 'computers',
                'parent' => '/xyz-store/electronics',
                'name' => 'Computers',
                'description' => 'Everything you need for your computer',
                'stores' => [
                    'xyz_pl' => [
                        'name' => 'Komputery',
                        'url_key' => 'komputery',
                        'description' => 'Wszystko dla Twojego komputera',
                    ],
                ],
            ],
            [
                'this' => 'dj',
                'parent' => '/xyz-store/electronics',
                'name' => 'DJ',
                'description' => 'Electronic DJ items, like CDJ players, mixers...',
                'is_anchor' => true,
                'stores' => [
                    'xyz_pl' => [
                        'description' => 'Wszystko dla DJa...',
                    ],
                ],
            ],
            [
                'this' => 'vinyl-adapters',
                'parent' => '/xyz-store/electronics/dj',
                'name' => 'Vinyl Adapters',
                'description' => 'DJ Vinyl Adapters...',
                'stores' => [
                    'xyz_pl' => [
                        'name' => 'Adaptery płyt winylowych',
                        'url_key' => 'adaptery-plyt-winylowych',
                        'description' => 'Znajdź swój ulubiony adapter',
                    ],
                ],
            ],
        ];
    }
}