<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @laravelPWA

        <title>{{ config('app.name', 'Laravel') }}</title>
<link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
        <!-- Fonts -->
        <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @livewireStyles
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <!-- Skeleton Loader global -->
        <link rel="stylesheet" href="{{ asset('css/skeleton.css') }}">
        @stack('styles')
        {{-- Pixels unifiés : Meta, GA4, LinkedIn, TikTok, Twitter, UTM, dataLayer --}}
        <x-tracking-pixels />

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
<meta name="facebook-domain-verification" content="u57iy063pazf4alikzjmppuwkxtqhz" />

    <!-- CSS et JS compilés (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
    <body class="font-sans antialiased">
        <x-tracking-pixels-body />
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')
            @include('components.language-switcher')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @yield('content')
                @livewire('advanced-search')
                @if(\Illuminate\Support\Facades\Schema::hasTable('ai_conversations'))
                    @livewire('ai-assistant-chat')
                @endif
            </main>
        </div>
        @livewireScripts
        @stack('scripts')
    </body>
</html>
