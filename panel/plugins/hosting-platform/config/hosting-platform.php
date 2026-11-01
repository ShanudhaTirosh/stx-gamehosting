<?php

return [
    'currency' => [
        'code' => 'LKR',
        'symbol' => 'Rs.',
        'name' => 'Sri Lankan Rupee',
    ],

    'proof_uploads' => [
        'max_file_size_kb' => env('HOSTING_PROOF_MAX_SIZE_KB', 10240), // 10 MB
        'allowed_mimes' => [
            'image/jpeg',
            'image/png',
            'image/webp',
            'application/pdf',
        ],
        'disk' => env('HOSTING_PROOF_DISK', 'local'),
    ],

    'orders' => [
        'expiration_days' => env('HOSTING_ORDER_EXPIRATION_DAYS', 3),
        'auto_deploy' => env('HOSTING_AUTO_DEPLOY_ON_APPROVAL', true),
    ],

    'storefront' => [
        'enabled' => env('HOSTING_STOREFRONT_ENABLED', true),
        'allow_guest_checkout' => env('HOSTING_ALLOW_GUEST_CHECKOUT', true),
    ],
];
