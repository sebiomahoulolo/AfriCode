<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AfriCode — Mentorat')</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    @vite(['resources/css/homepage.css', 'resources/css/mentorship.css'])

    @livewireStyles
    @stack('styles')

    <style>
    /* ── Remap Bootstrap → AfriCode ── */
    :root {
        --bs-primary:     #1EA38B; --bs-primary-rgb:  30,163,139;
        --bs-success:     #27B371; --bs-success-rgb:  39,179,113;
        --bs-info:        #1EA38B; --bs-info-rgb:     30,163,139;
        --bs-warning:     #FF8E2A; --bs-warning-rgb:  255,142,42;
        --bs-danger:      #E32D31; --bs-danger-rgb:   227,45,49;
        --bs-link-color:  #1EA38B; --bs-link-hover-color:#27B371;
    }
    body { font-family:'Inter',sans-serif; background:#f8fafc; min-height:100vh; }
    .btn-primary  { background-color:#1EA38B !important; border-color:#1EA38B !important; color:#fff !important; }
    .btn-primary:hover { background-color:#27B371 !important; border-color:#27B371 !important; }
    .btn-outline-primary { color:#1EA38B !important; border-color:#1EA38B !important; }
    .btn-outline-primary:hover { background-color:#1EA38B !important; color:#fff !important; }
    .btn-success  { background-color:#27B371 !important; border-color:#27B371 !important; color:#fff !important; }
    .btn-outline-success { color:#27B371 !important; border-color:#27B371 !important; }
    .btn-outline-success:hover { background-color:#27B371 !important; color:#fff !important; }
    .btn-info     { background-color:#1EA38B !important; border-color:#1EA38B !important; color:#fff !important; }
    .btn-outline-info { color:#1EA38B !important; border-color:#1EA38B !important; }
    .btn-outline-info:hover { background-color:#1EA38B !important; color:#fff !important; }
    .btn-warning  { background-color:#FF8E2A !important; border-color:#FF8E2A !important; color:#fff !important; }
    .btn-outline-warning { color:#FF8E2A !important; border-color:#FF8E2A !important; }
    .btn-outline-warning:hover { background-color:#FF8E2A !important; color:#fff !important; }
    .text-primary { color:#1EA38B !important; } .text-success { color:#27B371 !important; }
    .text-info    { color:#1EA38B !important; } .text-warning { color:#FF8E2A !important; }
    .text-danger  { color:#E32D31 !important; }
    .bg-primary   { background-color:#1EA38B !important; } .bg-success { background-color:#27B371 !important; }
    .bg-info      { background-color:#1EA38B !important; } .bg-warning { background-color:#FF8E2A !important; }
    .bg-danger    { background-color:#E32D31 !important; }
    .badge.bg-primary,.badge.bg-info { background-color:#1EA38B !important; }
    .badge.bg-success { background-color:#27B371 !important; }
    .progress-bar { background-color:#1EA38B !important; }
    a:not(.btn):not(.nav-link):not(.navbar-brand) { color:#1EA38B; }
    a:not(.btn):not(.nav-link):not(.navbar-brand):hover { color:#27B371; }

    /* ── Mentorship page header ── */
    .ms-page-hero {
        background: linear-gradient(135deg, #1EA38B 0%, #0d7a63 50%, #27B371 100%);
        padding: 2.5rem 0 2rem;
        color: #fff;
        margin-bottom: 0;
    }
    .ms-hero-title { font-size: clamp(1.4rem,4vw,2rem); font-weight: 800; margin:0 0 .3rem; }
    .ms-hero-sub   { font-size: .9rem; opacity: .85; margin:0; }
    .ms-hero-badge {
        display: inline-flex; align-items: center; gap:.5rem;
        background: rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25);
        border-radius: 50px; padding: .35rem 1rem; font-size: .78rem; font-weight: 600; color:#fff;
    }

    /* ── Cards ── */
    .card { border:none; border-radius:14px; box-shadow:0 2px 14px rgba(0,0,0,.07); }
    .card-header { background:#fff; border-bottom:1px solid #f0f2f5; border-radius:14px 14px 0 0 !important; }

    /* ── Flash messages ── */
    .ms-flash { border-radius:10px; border:none; font-size:.88rem; margin-bottom:1rem; }
    </style>
</head>
<body>

    {{-- Navbar principale AfriCode --}}
    @include('components.navbar')

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success ms-flash alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="fas fa-check-circle text-success"></i>
                {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger ms-flash alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif
    @if(session('warning'))
        <div class="container mt-3">
            <div class="alert alert-warning ms-flash alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="fas fa-exclamation-triangle"></i>
                {{ session('warning') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    {{-- Contenu --}}
    <div>
        @yield('content')
    </div>

    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @livewireScripts

    <script>
        AOS.init({ once:true, duration:700, offset:80 });
        document.querySelectorAll('.alert').forEach(function(el) {
            setTimeout(function(){ bootstrap.Alert.getOrCreateInstance(el).close(); }, 5000);
        });
    </script>

    @stack('scripts')
</body>
</html>
