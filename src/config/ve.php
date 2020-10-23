<?php

return [

    //disk. Currently available: s3. Will be available in the future: do || local
    'storage' => 's3',

    'pull_url' => env('VE_EDITOR_URL') . '/ve-editor/api/pull',

    'api_username' => env('VE_EDITOR_USERNAME'),
    'api_password' => env('VE_EDITOR_PASSWORD'),

    'config' => [
        'filesystems.disks.s3.options' => ['CacheControl' => 'max-age=315360000, no-transform, public'],
    ],

    'main' => env('VE_EDITOR_MAIN', false),
    
    'restrict-usage' => false,
    
    'allowed-users' => [
        'admin@mail.io',
    ],

    'guard' => [
        // 'driver' => 'session',
        // 'provider' => 'users',
    ]
];
