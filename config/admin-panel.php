<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sidebar Menu Items
    |--------------------------------------------------------------------------
    |
    | Here you can define the menu items that will be displayed in the sidebar.
    | You can add your own items to this array to extend the sidebar.
    |
    */
    'sidebar' => [
        [
            'title' => 'Dashboard',
            'route' => 'dashboard', // রুট এর নাম
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">...</svg>', // SVG আইকন
            'active_on' => 'dashboard*' // কোন রুটে active থাকবে
        ],
        [
            'title' => 'Users',
            'route' => 'users.index',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">...</svg>',
            'active_on' => 'users.*'
        ],
        [
            'title' => 'Settings',
            'route' => 'settings.index',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">...</svg>',
            'active_on' => 'settings.*'
        ],
        [
            'title' => 'User Logs',
            'route' => 'user-logs.index',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24">...</svg>',
            'active_on' => 'user-logs.*'
        ],
    ]
];