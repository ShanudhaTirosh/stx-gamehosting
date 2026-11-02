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

    'provisioning' => [
        // Nodes must carry one of these tags (Admin > Nodes > Tags) to receive new servers.
        'default_node_tags' => array_filter(explode(',', (string) env('HOSTING_NODE_TAGS', 'game-node'))),

        // Checkout region value => node tag(s). Tag your nodes with the matching tag.
        'regions' => [
            'sg' => ['sg'],
            'de' => ['de'],
        ],

        // Port range(s) searched for a free allocation when a product defines no ports.
        'default_ports' => array_filter(explode(',', (string) env('HOSTING_DEFAULT_PORTS', '25565-25999'))),

        // Host the automatic database accepts connections from. '%' is Pelican's default;
        // use the game node's IP (or a subnet like '10.0.%') and firewall the DB host.
        'database_remote' => env('HOSTING_DATABASE_REMOTE', '%'),

        // Values used for required egg variables that have no default. Anything not listed
        // here makes provisioning fail with a clear message instead of guessing.
        'env_fallbacks' => [
            'USER_AGENT' => 'STX-Pelican-Agent/1.0',
        ],
    ],

    'storefront' => [
        'enabled' => env('HOSTING_STOREFRONT_ENABLED', true),
        'allow_guest_checkout' => env('HOSTING_ALLOW_GUEST_CHECKOUT', true),
    ],
];
