<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AfriCode')</title>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">

    {{-- Google Fonts (optionnel, Udemy utilise des polices propriétaires) --}}
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    {{-- Si vous voulez vous rapprocher des polices Udemy, utilisez une pile de polices système comme dans le CSS --}}

    {{-- CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <!-- <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}"> -->
    <!-- <script src="{{ asset('js/bootstrap.bundle.min.js') }}" defer></script> -->
    @stack('styles')
    
    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body>
    @include('components.navbar')

    <div>
        @yield('content')
    </div>

    @include('components.footer') <!-- Inclure le footer -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    
    <!-- Alpine.js (nécessaire pour les interactions dynamiques) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Livewire Scripts -->
    @livewireScripts
    
    <script>
        AOS.init({
            once: true,
            duration: 800,
            offset: 100
        });
    </script>
    
    @stack('scripts')
</body>
</html>
