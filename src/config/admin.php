<?php
return [

    /*
    |--------------------------------------------------------------------------
    | Laravel-admin route settings
    |--------------------------------------------------------------------------
    |
    | The routing configuration of the admin page, including the path prefix,
    | the controller namespace, and the default middleware. If you want to
    | access through the root path, just set the prefix to empty string.
    |
    */
    'route' => [

        'path' => base_path('routes'),

        'prefix' => env('ADMIN_ROUTE_PREFIX', 'admin'),

        //'namespace' => 'App\\Http\\Controllers\\Admin',

        //'middleware' => ['web', 'admin'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel-admin install directory
    |--------------------------------------------------------------------------
    |
    | The installation directory of the controller and routing configuration
    | files of the administration page. The default is `app/Admin`, which must
    | be set before running `artisan admin::install` to take effect.
    |
    */
    'directory' => app_path('Http/Controllers/Admin'),

    /*
    |--------------------------------------------------------------------------
    | Geauth database settings
    |--------------------------------------------------------------------------
    |
    | Here are database settings for laravel-admin builtin model & tables.
    |
    */
    'database' => [
        // user tables and model
        'users_table' => 'users',
        'users_model' => Bennent\Geauth\Models\Manager::class,

        // menu table
        'menu_table' => 'permissions',
    ],
];
