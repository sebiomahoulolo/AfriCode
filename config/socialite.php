<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Socialite Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Laravel Socialite OAuth providers
    |
    */

    'providers' => [
        'google' => [
            'client_id' => env('GOOGLE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_CLIENT_SECRET'),
            'redirect' => env('GOOGLE_REDIRECT_URI', 'https://africode.tech/auth/google/callback'),
        ],

        'github' => [
            'client_id' => env('GITHUB_CLIENT_ID'),
            'client_secret' => env('GITHUB_CLIENT_SECRET'),
            'redirect' => 'https://africode.tech/auth/github/callback',
        ],

        'linkedin' => [
            'client_id' => env('LINKEDIN_CLIENT_ID'),
            'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
            'redirect' => 'https://africode.tech/auth/linkedin/callback',
        ],

        'facebook' => [
            'client_id' => env('FACEBOOK_CLIENT_ID'),
            'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
            'redirect' => env('FACEBOOK_REDIRECT_URI', 'https://africode.tech/auth/facebook/callback'),
        ],
    ],
];
