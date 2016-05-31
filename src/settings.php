<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production

        // View settings
        'view' => [
            'template_path' => __DIR__ ,
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
        ],
    ],
];
