<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Tracking activé
    |--------------------------------------------------------------------------
    | Désactiver tout le tracking (pixels + JS) en production si besoin.
    */
    'enabled' => env('TRACKING_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Google Tag Manager (GTM)
    |--------------------------------------------------------------------------
    | Un seul conteneur GTM peut gérer GA4, Meta, LinkedIn, etc. Si GTM_ID
    | est défini, le composant peut n'injecter que GTM et vous gérez le reste
    | dans l'interface GTM. Sinon, chaque pixel est injecté individuellement.
    */
    'gtm_id' => env('GTM_ID', null),

    /*
    |--------------------------------------------------------------------------
    | Meta (Facebook / Instagram) Pixel
    |--------------------------------------------------------------------------
    */
    'meta' => [
        'enabled' => env('META_PIXEL_ENABLED', true),
        'pixel_id' => env('META_PIXEL_ID', '9650375495040301'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Analytics 4
    |--------------------------------------------------------------------------
    */
    'ga4' => [
        'enabled' => env('GA4_ENABLED', false),
        'measurement_id' => env('GA4_MEASUREMENT_ID', null), // ex: G-XXXXXXXXXX
    ],

    /*
    |--------------------------------------------------------------------------
    | LinkedIn Insight Tag
    |--------------------------------------------------------------------------
    */
    'linkedin' => [
        'enabled' => env('LINKEDIN_PIXEL_ENABLED', false),
        'partner_id' => env('LINKEDIN_PARTNER_ID', null), // ex: 1234567
    ],

    /*
    |--------------------------------------------------------------------------
    | TikTok Pixel
    |--------------------------------------------------------------------------
    */
    'tiktok' => [
        'enabled' => env('TIKTOK_PIXEL_ENABLED', false),
        'pixel_id' => env('TIKTOK_PIXEL_ID', null),
    ],

    /*
    |--------------------------------------------------------------------------
    | Twitter / X Pixel (Conversions)
    |--------------------------------------------------------------------------
    */
    'twitter' => [
        'enabled' => env('TWITTER_PIXEL_ENABLED', false),
        'pixel_id' => env('TWITTER_PIXEL_ID', null),
    ],

    /*
    |--------------------------------------------------------------------------
    | Nom du site (pour dataLayer)
    |--------------------------------------------------------------------------
    */
    'site_name' => env('APP_NAME', 'AfriCode'),

];
