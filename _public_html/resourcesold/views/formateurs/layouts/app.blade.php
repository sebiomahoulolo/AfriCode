<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @laravelPWA
    <title>@yield('title', 'AfriCode - Espace Formateur')</title>
    <!-- Bootstrap CSS -->
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Styles AfriCode Formateur -->

    <!-- Styles AfriCode Formateur -->
<link rel="stylesheet" href="{{ asset('build/assets/formateur-C6hCGEUP.css') }}">

    @livewireStyles

    {{-- ── Responsive booster (supplément au build compilé) ── --}}
    <style>
    /* Header : flex-wrap pour que les actions ne débordent pas */
    .header-content { flex-wrap: wrap; gap: 10px; }
    .header-content > div:nth-child(2) { flex: 1; min-width: 0; }
    .page-title {
        font-size: clamp(1rem, 4vw, 1.75rem);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        max-width: 100%;
    }
    .page-subtitle { font-size: clamp(.78rem, 2vw, .9rem); }
    .header-actions { flex-wrap: wrap; gap: 8px; }

    /* Tables : scroll horizontal sur mobile */
    .content-body table {
        min-width: 540px;
    }
    .table-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; border-radius: 8px; }

    /* Input-group sur petits écrans */
    @media (max-width: 576px) {
        .input-group { flex-wrap: wrap; }
        .input-group .form-select { border-radius: 8px !important; margin-top: 6px; }
        .input-group .form-control { border-radius: 8px !important; }
    }

    /* Amélioration course-grid */
    @media (max-width: 640px) {
        .course-grid { grid-template-columns: 1fr !important; }
    }

    /* Mobile ≤ 768px */
    @media (max-width: 768px) {
        .header-content { padding: 0; }
        .page-title { font-size: 1.2rem; }
        .page-subtitle { font-size: .8rem; }
        .header-actions { width: 100%; justify-content: flex-end; }
        .content-body table { display: block; overflow-x: auto; white-space: nowrap; }
        .card-body-modern { padding: 1rem !important; }
        .filters-section { padding: 1rem; }
        /* Forcer les grids à s'empiler */
        .row > [class*="col-md-"] { width: 100%; }
        .admin-grid-3, .admin-grid-4 { grid-template-columns: repeat(2, 1fr) !important; }
    }

    /* Mobile ≤ 480px */
    @media (max-width: 480px) {
        .content-body { padding: .75rem !important; }
        .admin-grid-3, .admin-grid-4 { grid-template-columns: 1fr !important; }
        .course-grid { grid-template-columns: 1fr !important; }
        .page-title { font-size: 1rem; }
        .btn-primary-africode, .btn-secondary-africode {
            font-size: .8rem;
            padding: .5rem .875rem;
        }
        .content-header { padding: .75rem 1rem; }
    }

    /* Notification bell — compact sur mobile */
    @media (max-width: 400px) {
        .nb-dropdown { right: -50px !important; width: 300px !important; }
    }
    </style>

    @yield('styles')
</head>
<body>
    <div class="app-layout">
        <!-- Sidebar -->
        <aside class="formateur-sidebar" id="sidebar">
            <!-- Toggle Button -->
            <button class="sidebar-toggle" onclick="toggleSidebar()">
                <i class="fas fa-chevron-left" id="toggleIcon"></i>
            </button>
            
            <!-- Logo Section -->
            <div class="sidebar-brand">
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 50 25'%3E%3Cg transform='translate(10 2)' fill='white'%3E%3Cpath d='M0 0 H8 V20 H0 Z' fill='%23fea837' transform='skewX(-15)'/%3E%3Cpath d='M10 0 H18 V20 H10 Z' fill='%23fa1c39' transform='skewX(-15)'/%3E%3Cpath d='M20 0 H28 V20 H20 Z' fill='%2311c06d' stroke='white' stroke-width='0.5' transform='skewX(-15)'/%3E%3C/g%3E%3C/svg%3E" alt="AfriCode Logo" class="africode-logo-img" style="height: 35px;">
                <span class="logo-text">AFRICODE</span>
            </div>
            
            <!-- Navigation -->
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Principal</div>
                    <div class="nav-item">
                        <a href="{{ route('formateur.dashboard') }}" class="nav-link {{ request()->routeIs('formateur.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i>
                            <span class="nav-link-text">Tableau de bord</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('formateur.courses.index') }}" class="nav-link {{ request()->routeIs('formateur.courses.index') ? 'active' : '' }}">
                            <i class="fas fa-book-open"></i>
                            <span class="nav-link-text">Mes cours</span>
                            @if(isset($totalCourses) && $totalCourses > 0)
                                <span class="nav-badge">{{ $totalCourses }}</span>
                            @endif
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('formateur.courses.create') }}" class="nav-link {{ request()->routeIs('formateur.courses.create') ? 'active' : '' }}">
                            <i class="fas fa-plus-circle"></i>
                            <span class="nav-link-text">Créer un cours</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('formateur.earnings.index') }}" class="nav-link {{ request()->routeIs('formateur.earnings.*') ? 'active' : '' }}">
                            <i class="fas fa-money-bill-wave"></i>
                            <span class="nav-link-text">Mes revenus</span>
                        </a>
                    </div>
                     <div class="nav-item">
                        <a href="{{ route('formateur.guide') }}" class="nav-link {{ request()->routeIs('formateur.guide') ? 'active' : '' }}">
                         
                            <i class="fas fa-question-circle"></i>
                            <span class="nav-link-text">MON GUIDE</span>
                        </a>
                    </div>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Mentorat</div>
                    <div class="nav-item">
                        <a href="{{ route('mentorship.index') }}" class="nav-link {{ request()->routeIs('mentorship.index') ? 'active' : '' }}">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <span class="nav-link-text">Mes Mentorats</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('mentorship.requests') }}" class="nav-link {{ request()->routeIs('mentorship.requests') ? 'active' : '' }}">
                            <i class="fas fa-bell"></i>
                            <span class="nav-link-text">Demandes</span>
                            @php
                                $pendingRequests = \App\Models\Mentorship::where('mentor_id', Auth::id())
                                    ->where('status', 'pending_mentor_approval')
                                    ->count();
                            @endphp
                            @if($pendingRequests > 0)
                                <span class="nav-badge">{{ $pendingRequests }}</span>
                            @endif
                        </a>
                    </div>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Tutorat</div>
                    <div class="nav-item">
                        <a href="{{ route('tutoring.sessions.index') }}" class="nav-link {{ request()->routeIs('tutoring.sessions.*') ? 'active' : '' }}">
                            <i class="fas fa-video"></i>
                            <span class="nav-link-text">Mes Sessions</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('tutoring.sessions.create') }}" class="nav-link {{ request()->routeIs('tutoring.sessions.create') ? 'active' : '' }}">
                            <i class="fas fa-plus-circle"></i>
                            <span class="nav-link-text">Créer Session</span>
                        </a>
                    </div>
                </div>
            </nav>
            
            <!-- Footer avec profil utilisateur -->
            <div class="sidebar-footer">
                <a href="{{ route('profile.edit') }}" class="user-profile">
                    <img src="{{ Auth::user()->profile_image_path ? asset(Auth::user()->profile_image_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->first_name . ' ' . Auth::user()->last_name) . '&background=11c06d&color=fff&size=40' }}" 
                         alt="Profile" class="user-avatar">
                    <div class="user-info">
                        <h6>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h6>
                        <small>Formateur</small>
                    </div>
                </a>
                <div class="nav-item mt-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link border-0 bg-transparent w-100">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="nav-link-text">Déconnexion</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>
        
        <!-- Mobile Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
        
        <!-- Contenu principal -->
        <main class="main-content">
            <!-- Header -->
            <header class="content-header">
                <div class="header-content">
                    <button class="mobile-toggle" onclick="openSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div>
                        <h1 class="page-title">@yield('page-title', 'Tableau de bord')</h1>
                        <p class="page-subtitle">@yield('page-subtitle', 'Bienvenue dans votre espace formateur')</p>
                    </div>
                    <div class="header-actions" style="display:flex;align-items:center;gap:10px;">
                        <x-notification-bell />
                        @yield('header-actions')
                    </div>
                </div>
            </header>
            
            <!-- Indicateur de connexion -->
            <div id="connection-status" class="alert alert-warning d-none" role="alert">
                <i class="fas fa-wifi me-2"></i>
                <span>Vérification de la connexion...</span>
            </div>
            
            <!-- Messages flash -->
            @if(session('success'))
                <div class="content-body">
                    <div class="alert alert-success-modern alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="content-body">
                    <div class="alert alert-danger-modern alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif
            
            <!-- Contenu de la page -->
            <div class="content-body slide-in-right">
                @yield('content')
            </div>
        </main>
    </div>
    
    {{-- AfriCode AI Assistant - Popup flottant --}}
    {{-- @livewire('ai-assistant-chat') --}}
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- Scripts AfriCode Formateur -->
<script type="module" src="{{ asset('build/assets/formateur-DK9Oby62.js') }}"></script>

@livewireScripts
@stack('scripts')
@yield('scripts')

<!-- Alpine.js pour l'AI Assistant - Chargé après les scripts pour que aiAssistant() soit disponible -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
{{-- ═══ PROTECTION GLOBALE (clic droit + DevTools + raccourcis) ══════ --}}
@include('partials.security-lock')
</body>
</html>
