<?php

declare(strict_types=1);

use App\Models\User;
use Kaster\Cms\Filament\Resources\Menus\RelationManagers\ItemsRelationManager;
use Kaster\Cms\Models\Menu;
use Kaster\Cms\Models\MenuItem;
use Kaster\Cms\Models\Page;

return [
    /*
     |--------------------------------------------------------------------------
     | Models
     |--------------------------------------------------------------------------
     */

    'models' => [
        'user' => User::class,
        'page' => Page::class,
        'menu' => Menu::class,
        'menu_item' => MenuItem::class,
    ],

    /*
     |--------------------------------------------------------------------------
     | Filament Navigation
     |--------------------------------------------------------------------------
     | Configure where resources appear in Filament's sidebar.
     | Set 'group' to null for no grouping, or a string like 'CMS' or 'Content'.
     */

    'navigation' => [
        'page' => [
            'group' => null,
            'icon' => 'heroicon-o-document-text',
            'sort' => null,
        ],
        'menu' => [
            'group' => null,
            'icon' => 'heroicon-o-rectangle-stack',
            'sort' => null,
        ],
    ],

    /*
     |--------------------------------------------------------------------------
     | Menu
     |--------------------------------------------------------------------------
     */

    'enable_menu_module' => true,
    'menu' => [
        'menu_items_relation_manager' => ItemsRelationManager::class,
    ],

    /*
     |--------------------------------------------------------------------------
     | Page
     |--------------------------------------------------------------------------
     */

    'enable_page_module' => false,

    /*
     |--------------------------------------------------------------------------
     | Multilingual feature
     |--------------------------------------------------------------------------
     */
    'enable_multilingual_feature' => false,
    'locales' => [
        'en' => [
            'label' => 'English',
        ],
        'fr' => [
            'label' => 'French',
        ],
        'de' => [
            'label' => 'German',
        ],
    ],
    'default_locale' => 'en',

    /*
     |--------------------------------------------------------------------------
     | SEO
     |--------------------------------------------------------------------------
     */
    'disable_robots_follow' => env('DISABLE_ROBOTS_FOLLOW', false),

    /*
     |--------------------------------------------------------------------------
     | Components
     |--------------------------------------------------------------------------
     | Manually register component classes here. These are registered in
     | addition to auto-discovered components from the CMS module.
     |--------------------------------------------------------------------------
     */
    'components' => [
        // \App\Cms\Components\MyCustomBlock::class,
    ],
];
