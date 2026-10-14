<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AfriCode')</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}" defer></script>
</head>
<body>
    @include('components.navbar')

    <div class="container mt-4">
        @yield('content')
    </div>

    @include('components.footer') <!-- Inclure le footer -->
</body>
</html>
