<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- Important pour les formulaires POST --}}
    <title>AfriCode Admin - @yield('title', 'Tableau de Bord')</title>

    {{-- Liens CDN (ou mieux, via Vite) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- Votre CSS Admin compilé via Vite --}}
    @vite(['resources/css/admin.css', 'resources/js/admin.js']) {{-- Adaptez les chemins --}}

    {{-- Styles spécifiques à la page --}}
    @stack('styles')
</head>
<body>
    <div class="dashboard-container">
        {{-- Inclure la Sidebar --}}
        @include('admin.partials.sidebar') {{-- Créez ce fichier --}}

        <!-- Main Content -->
        <div class="main-content">
            {{-- Inclure le Header --}}
            @include('admin.partials.header') {{-- Créez ce fichier --}}

            {{-- Contenu spécifique de la page --}}
            @yield('content')

            {{-- Inclure le Footer si nécessaire --}}
            {{-- @include('admin.partials.footer') --}}
        </div>
    </div>

    {{-- Scripts CDN (ou mieux, via Vite/NPM) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

    {{-- Scripts spécifiques à la page --}}
    @stack('scripts')
</body>
</html>