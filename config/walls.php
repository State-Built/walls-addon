<?php


return [
    'checkout' => [
        'url' => '/checkout',
        'redirect_url' => '/my-stuff'
    ],
    'orders' => [
        'collection' => 'orders',
    ],

    'payment_provider' => 'stripe',

    'payments' => [
        'stripe' => [
            'secret_key'      => env('STRIPE_SECRET_KEY'),
            'publishable_key' => env('STRIPE_PUBLISHABLE_KEY')
        ]
    ],
];