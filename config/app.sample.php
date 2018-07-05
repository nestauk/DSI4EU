<?php

return [
    'site-domain' => '',
    'site-relative-path' => '',
    'env' => 'prod',

    'must-use-https' => false,
    'can-create-projects' => true,
    'waiting-approval-email-address' => '',
    'apc-keys' => [
        'organisations' => 'digitalSocial:organisations',
        'projects' => 'digitalSocial:projects',
    ],
    'mysql' => [
        'host' => 'localhost',
        'username' => '',
        'password' => '',
        'db' => '',
    ],
    'mysql-test' => [
        'host' => 'localhost',
        'username' => '',
        'password' => '',
        'db' => '',
    ],
    'api' => [
        'facebook' => [
            'clientId' => '{facebook-app-id}',
            'clientSecret' => '{facebook-app-secret}',
            'redirectUri' => '{https://example.com}/facebook-login',
            'graphApiVersion' => 'v3.0',
        ],
        'github' => [
            'clientId' => '{github-client-id}',
            'clientSecret' => '{github-client-secret}',
            'redirectUri' => '{http://example.com}/github-login',
        ],
        'google' => [
            'clientId' => '{google-app-id}',
            'clientSecret' => '{google-app-secret}',
            'redirectUri' => '{http://example.com}/google-login',
            'hostedDomain' => '{http://example/com}',
        ],
        'twitter' => [
            'identifier' => '{your-identifier}',
            'secret' => '{your-secret}',
            'callback_uri' => "{http://example.com}/twitter-login",
        ]
    ]
];