<?php

return [

    //disk. Currently available: s3. Will be available in the future: do || local
    'storage' => 's3',

    'pull_url' => env('VE_EDITOR_URL') . '/ve-editor/api/pull',

    'api_token' => env('VE_EDITOR_API_TOKEN'),

    'config' => [
        'filesystems.disks.s3.options' => ['CacheControl' => 'max-age=315360000, no-transform, public'],
    ],

    'main' => env('VE_EDITOR_MAIN', false),
        
    'allowed-users' => [
        'admin@mail.io',
    ]
];
