<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    
    'paypal' => [
        'client_id' => 'ARcxbe33etZKO7MhTZqdy9DZzIx8UQbyDOZqrudRlUJZUHGAjOyDZEzocwP9tvIIEP8bz16kcedOO7cW',
        'secret' => 'EESB60_GfDY-1eVnelqKWe96_w5x3Ow1P7EU-cD2Cty82yn-V984p7RB5vcA58klYkQwBc66VUbW1rLa'
    ],
];
