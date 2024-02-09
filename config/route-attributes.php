<?php

return [
    /*
     *  Automatic registration of routes will only happen if this setting is `true`
     */
    'enabled' => true,

    /*
     * Controllers in these directories that have routing attributes
     * will automatically be registered.
     *
     * Optionally, you can specify group configuration by using key/values
     */
    'directories' => [
        // app_path('Http/Controllers'),
        app_path('Http/Controllers/Admin') => [
            'prefix' => 'api/admin',
            'middleware' => ['api', 'auth:sanctum'],
            // only register routes in files that match the patterns
            'patterns' => ['*Controller.php'],
            // do not register routes in files that match the patterns
            'not_patterns' => [],
        ],
    ],

    /**
     * This middleware will be applied to all routes.
     */
    'middleware' => [
        \Illuminate\Routing\Middleware\SubstituteBindings::class
    ]
];
