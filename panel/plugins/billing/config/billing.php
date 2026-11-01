<?php

return [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),

    'currency' => env('BILLING_CURRENCY', 'USD'),

    'deployment_tags' => env('BILLING_DEPLOYMENT_TAGS'),
];
