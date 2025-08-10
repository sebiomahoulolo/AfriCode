<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('APP_URL') . '/auth/google/callback',

        
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('APP_URL') . '/auth/facebook/callback',
    ],

    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => env('APP_URL') . '/auth/github/callback',
    ],
    'linkedin' => [
    'client_id' => env('LINKEDIN_CLIENT_ID'),
    'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
    'redirect' => env('LINKEDIN_REDIRECT'),
],


    'stripe' => [
        'key' => env('STRIPE_PUBLISHABLE_KEY'),
        'secret' => env('STRIPE_SECRET_KEY'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    'fedapay' => [
        'api_key' => env('FEDAPAY_API_KEY'),
        'public_key' => env('FEDAPAY_PUBLIC_KEY'),
        'secret' => env('FEDAPAY_SECRET'),
        'environment' => env('FEDAPAY_ENVIRONMENT', 'live'), // 'live' or 'sandbox'
        'base_url' => env('FEDAPAY_BASE_URL', 'https://api.fedapay.com'),
        'checkout_url' => env('FEDAPAY_CHECKOUT_URL', 'https://cdn.fedapay.com/checkout.js?v=1.1.7'),
        'currency' => env('FEDAPAY_CURRENCY', 'XOF'),
        'webhook_secret' => env('FEDAPAY_WEBHOOK_SECRET'),
        'webhook_url' => env('FEDAPAY_WEBHOOK_URL'),
        'default_currency' => env('FEDAPAY_DEFAULT_CURRENCY', 'EUR'),
        'eur_to_xof_rate' => env('FEDAPAY_EUR_TO_XOF_RATE', 655.957),
    ],

];
