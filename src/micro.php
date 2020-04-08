<?php

return [
    'app'   => [
        'id'        => 2,
        'secret'    => 'xxx',
        'redirect_uri'=> '',
        'auth_path' => null
    ],
    'url'   => [
        'auth'  => 'http://localhost:8001'
    ],
    'endpoint'=> [
        'profile'   => '/api/v1/profile',
        'app_check' => '/api/v1/app-check'
    ]
];