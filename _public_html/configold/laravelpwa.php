<?php

return [
    'name' => 'AfriCode PWA',
    'manifest' => [
        'name' => env('APP_NAME', 'AfriCode - Plateforme d\'apprentissage'),
        'short_name' => 'AfriCode',
        'start_url' => '/',
        'background_color' => '#ffffff',
        'theme_color' => '#0d9488',
        'display' => 'standalone',
        'orientation'=> 'any',
        'status_bar'=> 'black-translucent',
        // Icônes PWA : africode-192.png et africode-512.png dans public/images/icons/
        'icons' => [
            '72x72'   => ['path' => '/images/icons/africode-192.png', 'purpose' => 'any'],
            '96x96'   => ['path' => '/images/icons/africode-192.png', 'purpose' => 'any'],
            '128x128' => ['path' => '/images/icons/africode-192.png', 'purpose' => 'any'],
            '152x152' => ['path' => '/images/icons/africode-192.png', 'purpose' => 'any'],
            '192x192' => ['path' => '/images/icons/africode-192.png', 'purpose' => 'any'],
            '384x384' => ['path' => '/images/icons/africode-512.png', 'purpose' => 'any'],
            '512x512' => ['path' => '/images/icons/africode-512.png', 'purpose' => 'any maskable'],
        ],
        'splash' => [
            '640x1136'  => '/images/icons/africode-512.png',
            '750x1334'  => '/images/icons/africode-512.png',
            '828x1792'  => '/images/icons/africode-512.png',
            '1125x2436' => '/images/icons/africode-512.png',
            '1242x2208' => '/images/icons/africode-512.png',
            '1242x2688' => '/images/icons/africode-512.png',
            '1536x2048' => '/images/icons/africode-512.png',
            '1668x2224' => '/images/icons/africode-512.png',
            '1668x2388' => '/images/icons/africode-512.png',
            '2048x2732' => '/images/icons/africode-512.png',
        ],
        'shortcuts' => [
            [
                'name' => 'Formations',
                'description' => 'Parcourir tous les cours AfriCode',
                'url' => '/courses',
                'icons' => ['src' => '/images/icons/africode-192.png', 'purpose' => 'any']
            ],
            [
                'name' => 'Mon Espace',
                'description' => 'Mon tableau de bord apprenant',
                'url' => '/dashboard',
                'icons' => ['src' => '/images/icons/africode-192.png', 'purpose' => 'any']
            ],
            [
                'name' => 'Nos Services',
                'description' => 'Découvrir les services AfriCode',
                'url' => '/nos-services',
                'icons' => ['src' => '/images/icons/africode-192.png', 'purpose' => 'any']
            ],
            [
                'name' => 'Nous Contacter',
                'description' => 'Contacter l\'équipe AfriCode',
                'url' => '/Contactez-nous',
                'icons' => ['src' => '/images/icons/africode-192.png', 'purpose' => 'any']
            ]
        ],
        'custom' => [
            'scope' => '/',
            'id' => '/',
        ]
    ]
];
