<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AfriCode - Espace Formateur')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Styles AfriCode Formateur -->
    @vite(['resources/css/formateur.css'])
    
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
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 50 25'%3E%3Cg transform='translate(10 2)' fill='white'%3E%3Cpath d='M0 0 H8 V20 H0 Z' fill='%23FF8E2A' transform='skewX(-15)'/%3E%3Cpath d='M10 0 H18 V20 H10 Z' fill='%23E32D31' transform='skewX(-15)'/%3E%3Cpath d='M20 0 H28 V20 H20 Z' fill='%2327B371' stroke='white' stroke-width='0.5' transform='skewX(-15)'/%3E%3C/g%3E%3C/svg%3E" alt="AfriCode Logo" class="africode-logo-img" style="height: 35px;">
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
                </div>
            </nav>
            
            <!-- Footer avec profil utilisateur -->
            <div class="sidebar-footer">
                <a href="{{ route('profile.edit') }}" class="user-profile">
                    <img src="{{ Auth::user()->profile_image_path ? asset(Auth::user()->profile_image_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->first_name . ' ' . Auth::user()->last_name) . '&background=1EA38B&color=fff&size=40' }}" 
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
                    <div class="header-actions">
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
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Scripts AfriCode Formateur -->
    @vite(['resources/js/components/formateur.js'])
    
    @yield('scripts')
</body>
</html>
