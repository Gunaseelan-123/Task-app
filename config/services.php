<?php

return [
    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'razorpay' => [
        'key' => env('RAZORPAY_KEY'),
        'secret' => env('RAZORPAY_SECRET'),
    ],
];
