<?php

return [
    'site-domain' => 'dsi.localhost',
    'site-relative-path' => '',
    'env' => 'prod',

    'must-use-https' => false,
    'can-create-projects' => true,
    'waiting-approval-email-address' => 'alecs@inoveb.co.uk',
    'apc-keys' => [
        'organisations' => 'digitalSocial:organisations',
        'projects' => 'digitalSocial:projects',
    ],
    'mysql' => [
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'db' => 'dsi4eu',
    ],
    'api ' => [
        'facebook' => [
            'clientId' => '{facebook-app-id}',
            'clientSecret' => '{facebook-app-secret}',
            'redirectUri' => '{https://example.com}/facebook-login',
            'graphApiVersion' => 'v2.6',
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