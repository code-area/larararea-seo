<?php

return [
    'web' => [
        'route' => [
            'as' => 'admin.',
            'middleware' => ['web', 'auth'],
            'prefix' => 'admin/'
        ],
        'view' => [
            'path' => 'admin'
        ]
    ]
];
