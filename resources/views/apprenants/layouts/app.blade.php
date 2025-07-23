<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AfriCode - Plateforme d\'apprentissage')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- AfriCode Theme CSS -->
    <link href="{{ asset('css/africode-learner-theme.css') }}" rel="stylesheet">
    
    <!-- AfriCode Visibility Fixes - Corrections critiques -->
    <link href="{{ asset('css/africode-fixes.css') }}" rel="stylesheet">
    
    <style>
        :root {
            --africode-background: #F8F9FA;
        }
        /* Application équilibrée de la charte AfriCode aux pages apprenants */
        
        body {
            background: #F8F9FA !important;
            color: #333 !important;
            font-family: 'Segoe UI', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 1rem;
            font-weight: 400;
        }
        h1, h2, h3, h4, h5, h6 {
            color: #1EA38B;
            font-weight: 700;
        }
        a, .link {
            color: #1EA38B;
            text-decoration: none;
            transition: color 0.2s;
        }
        a:hover, .link:hover {
            color: #27B371;
        }
        .fa, .fas, .far, .fal, .fab {
            color: #1EA38B;
            font-size: 1.1em;
            vertical-align: middle;
            margin-right: 0.5em;
        }

        /* Sidebar avec charte AfriCode équilibrée */
        .app-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(180deg, var(--africode-primary) 0%, var(--africode-primary-dark) 100%);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 1000;
            transition: var(--africode-transition);
            transform: translateX(0);
            display: flex;
            flex-direction: column;
            box-shadow: var(--africode-shadow-lg);
        }

        .app-sidebar.collapsed {
            width: 80px;
        }

        /* Sidebar brand et logo */
        .sidebar-brand {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }

        .sidebar-brand h3 {
            color: white;
            font-weight: 800;
            font-size: 1.25rem;
            margin: 0;
            display: flex;
            align-items: center;
            transition: var(--africode-transition);
        }

        /* Logo AfriCode - Normal */
        .africode-logo {
            transition: var(--africode-transition);
            flex-shrink: 0;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }
        
        /* Styles pour le nouveau logo avec les trois bandes */
        .africode-logo-img {
            transition: transform 0.2s ease;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }
        
        .sidebar-brand:hover .africode-logo-img {
            transform: scale(1.05);
        }

        .logo-text {
            background: linear-gradient(135deg, #FFFFFF 0%, rgba(255, 255, 255, 0.9) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            font-size: 1.2rem;
            transition: var(--africode-transition);
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .sidebar-brand:hover .logo-text {
            color: var(--africode-secondary) !important;
            -webkit-text-fill-color: var(--africode-secondary);
        }

        /* Mode réduit */
        .app-sidebar.collapsed .sidebar-brand {
            padding: 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .app-sidebar.collapsed .sidebar-brand > div {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .app-sidebar.collapsed .logo-text {
            display: none;
        }

        .app-sidebar.collapsed .africode-logo-img {
            height: 24px !important;
            width: auto;
        }

        /* Navigation */
        .sidebar-nav {
            flex: 1;
            padding: 1rem 0;
            overflow-y: auto;
        }

        .nav-item {
            margin: 0.25rem 0.75rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            border-radius: var(--africode-border-radius-sm);
            transition: var(--africode-transition);
            position: relative;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .nav-link i {
            width: 20px;
            margin-right: 0.875rem;
            font-size: 1.1rem;
            text-align: center;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(4px);
            box-shadow: var(--africode-shadow-sm);
        }

        .nav-link.active {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.25) 0%, rgba(255, 255, 255, 0.35) 100%) !important;
            color: white !important;
            font-weight: 700 !important;
            box-shadow: var(--africode-shadow-md) !important;
            border-left: 4px solid var(--africode-secondary) !important;
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--africode-secondary);
            border-radius: 0 4px 4px 0;
        }

        /* Mode réduit - navigation */
        .app-sidebar.collapsed .nav-item {
            margin: 0.25rem 0.5rem;
        }

        .app-sidebar.collapsed .nav-link {
            justify-content: center;
            position: relative;
            padding: 0.875rem 0.5rem;
            border-radius: var(--africode-border-radius-sm);
        }

        .app-sidebar.collapsed .nav-link span {
            opacity: 0;
            visibility: hidden;
            position: absolute;
        }

        .app-sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.25rem;
            width: auto;
            text-align: center;
        }

        /* Header et toggle button avec charte AfriCode */
        .app-header {
            background: var(--africode-surface);
            border-bottom: 1px solid var(--africode-border);
            box-shadow: var(--africode-shadow-sm);
            backdrop-filter: blur(20px);
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .sidebar-toggle-btn {
            background: var(--africode-surface);
            border: 2px solid var(--africode-border);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--africode-text-secondary);
            transition: var(--africode-transition);
            box-shadow: var(--africode-shadow-sm);
            margin-right: 1rem;
            position: relative;
            overflow: hidden;
        }

        .sidebar-toggle-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--africode-gradient-primary);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-toggle-btn:hover::before {
            opacity: 1;
        }

        .sidebar-toggle-btn:hover {
            color: white;
            transform: scale(1.05);
            box-shadow: var(--africode-shadow-md);
            border-color: var(--africode-primary);
        }

        .sidebar-toggle-btn i {
            position: relative;
            z-index: 1;
            transition: transform 0.3s ease;
        }

        .sidebar-toggle-btn:hover i {
            transform: rotate(180deg);
        }

        /* Tooltip styles */
        .nav-link[data-bs-toggle="tooltip"] {
            position: relative;
        }

        /* Animations for collapsed state */
        .nav-link span {
            transition: all 0.3s ease;
        }

        .app-sidebar.collapsed .nav-link:hover::after {
            content: attr(data-title);
            position: absolute;
            left: 70px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--gray-900);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: var(--border-radius-sm);
            font-size: 0.875rem;
            white-space: nowrap;
            z-index: 1001;
            opacity: 1;
            visibility: visible;
            pointer-events: none;
        }

        .app-sidebar.collapsed .nav-link:hover::before {
            content: '';
            position: absolute;
            left: 60px;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
            border-right-color: var(--gray-900);
            z-index: 1001;
        }

        /* Sidebar footer adjustments */
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-footer .btn-outline-modern {
            background: rgba(255, 255, 255, 0.1) !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 600 !important;
            padding: 0.75rem 1rem !important;
            transition: var(--africode-transition) !important;
        }

        .sidebar-footer .btn-outline-modern:hover {
            background: rgba(227, 45, 49, 0.9) !important;
            border-color: rgba(227, 45, 49, 1) !important;
            color: white !important;
            transform: translateY(-2px) !important;
            box-shadow: var(--africode-shadow-md) !important;
        }

        .app-sidebar.collapsed .sidebar-footer .btn {
            padding: 0.5rem;
            font-size: 0.875rem;
        }

        .app-sidebar.collapsed .sidebar-footer .btn span {
            display: none;
        }

        .app-sidebar.collapsed .sidebar-footer .btn i {
            margin: 0;
        }

        .sidebar-brand {
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .app-sidebar.collapsed .sidebar-brand {
            padding: 1rem 0.5rem;
        }

        .sidebar-brand h3 {
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .sidebar-nav {
            padding: 1rem 0;
            flex: 1;
            overflow-y: auto;
        }

        .nav-item {
            margin: 0.25rem 1rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.25rem;
            color: var(--gray-700);
            text-decoration: none;
            border-radius: var(--border-radius-sm);
            transition: all 0.2s ease;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .nav-link:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            color: var(--primary-color);
            transform: translateX(4px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            box-shadow: var(--shadow);
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), transparent);
            pointer-events: none;
        }

        .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }

        /* Main Content avec charte AfriCode */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--africode-background);
        }

        .main-content.sidebar-collapsed {
            margin-left: 80px;
        }

        /* Top Header avec charte AfriCode */
        .top-header {
            background: var(--africode-surface);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--africode-border);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: var(--africode-shadow-sm);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--africode-text-primary);
            margin: 0;
            background: var(--africode-gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .notification-btn, .profile-btn {
            position: relative;
            background: var(--africode-surface);
            border: 1px solid var(--africode-border);
            padding: 0.75rem;
            border-radius: var(--africode-border-radius-sm);
            transition: var(--africode-transition);
            color: var(--africode-text-secondary);
            box-shadow: var(--africode-shadow-sm);
        }

        .notification-btn:hover, .profile-btn:hover {
            background: var(--africode-hover-bg);
            color: var(--africode-primary);
            transform: translateY(-2px);
            box-shadow: var(--africode-shadow-md);
            border-color: var(--africode-primary);
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-color);
            margin-left: 0.5rem;
        }

        /* Content Area */
        .content-wrapper {
            padding: 2rem;
        }

        /* Cards */
        .modern-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--gray-200);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .modern-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .modern-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        /* Buttons */
        .btn-modern {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius-sm);
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-modern:hover::before {
            left: 100%;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .btn-outline-modern {
            background: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius-sm);
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-modern:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            .app-sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            .app-sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0 !important;
            }

            .content-wrapper {
                padding: 1rem;
            }

            .top-header {
                padding: 1rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .sidebar-toggle-btn {
                display: flex;
            }
        }

        @media (min-width: 769px) {
            .mobile-toggle {
                display: none;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Progress bars */
        .progress-modern {
            height: 8px;
            border-radius: 4px;
            background: var(--gray-200);
            overflow: hidden;
        }

        .progress-bar-modern {
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 4px;
            transition: width 0.6s ease;
        }

        /* Badges */
        .badge-modern {
            padding: 0.375rem 0.75rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .badge-success {
            background: var(--success-color);
            color: white;
        }

        .badge-warning {
            background: var(--warning-color);
            color: white;
        }

        .badge-danger {
            background: var(--danger-color);
            color: white;
        }

        /* Overlay for mobile sidebar */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Scrollbar customization */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-100);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gray-400);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--gray-500);
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="app-sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="d-flex flex-column align-items-center">
                <!-- Logo AfriCode avec les trois bandes -->
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 50 25'%3E%3Cg transform='translate(10 2)' fill='white'%3E%3Cpath d='M0 0 H8 V20 H0 Z' fill='%23FF8E2A' transform='skewX(-15)'/%3E%3Cpath d='M10 0 H18 V20 H10 Z' fill='%23E32D31' transform='skewX(-15)'/%3E%3Cpath d='M20 0 H28 V20 H20 Z' fill='%2327B371' stroke='white' stroke-width='0.5' transform='skewX(-15)'/%3E%3C/g%3E%3C/svg%3E" alt="AfriCode Logo" class="africode-logo-img mb-2" style="height: 35px;">
                <!-- Texte AFRICODE en dessous -->
                <span class="logo-text text-white fw-bold text-uppercase" style="font-size: 0.9rem; letter-spacing: 1px;">AFRICODE</span>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="{{ route('apprenant.dashboard') }}" 
                   class="nav-link {{ request()->routeIs('apprenant.dashboard') ? 'active' : '' }}"
                   data-title="Tableau de bord">
                    <i class="fas fa-home"></i>
                    <span>Tableau de bord</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('apprenant.courses') }}" 
                   class="nav-link {{ request()->routeIs('apprenant.courses') ? 'active' : '' }}"
                   data-title="Mes Cours">
                    <i class="fas fa-book-open"></i>
                    <span>Mes Cours</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('badges.index') }}" 
                   class="nav-link"
                   data-title="Progression">
                    <i class="fas fa-chart-line"></i>
                    <span>Progression</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('apprenant.certifications') }}" 
                   class="nav-link {{ request()->routeIs('apprenant.certifications') ? 'active' : '' }}"
                   data-title="Certifications">
                    <i class="fas fa-certificate"></i>
                    <span>Certifications</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('pages.forumapp') }}" 
                   class="nav-link {{ request()->routeIs('pages.forumapp') ? 'active' : '' }}"
                   data-title="Communauté">
                    <i class="fas fa-users"></i>
                    <span>Communauté</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('pages.compdisp') }}" 
                   class="nav-link"
                   data-title="Défis">
                    <i class="fas fa-trophy"></i>
                    <span>Défis</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('apprenant.profile') }}" 
                   class="nav-link {{ request()->routeIs('apprenant.profile') ? 'active' : '' }}"
                   data-title="Paramètres">
                    <i class="fas fa-user-cog"></i>
                    <span>Paramètres</span>
                </a>
            </div>
        </nav>
        
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-modern w-100" title="Déconnexion">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="ms-2">Déconnexion</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-content">
                <div class="d-flex align-items-center">
                    <button class="sidebar-toggle-btn d-none d-md-flex" id="sidebarToggleDesktop"
                            title="Basculer la sidebar">
                        <i class="fas fa-bars"></i>
                    </button>

                    <h1 class="page-title">@yield('page-title', 'Tableau de bord')</h1>
                </div>
                
                <div class="header-actions">
                    <button class="notification-btn" data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        @if(isset($notifications) && $notifications->count() > 0)
                            <span class="notification-badge">{{ $notifications->count() }}</span>
                        @endif
                    </button>
                    
                    <div class="dropdown">
                        <button class="profile-btn dropdown-toggle" data-bs-toggle="dropdown">
                            <span class="d-none d-md-inline me-2">{{ Auth::user()->first_name }}</span>
                            <img src="{{ Auth::user()->profile_image_path ? asset(Auth::user()->profile_image_path) : asset('assets/images/default-avatar.svg') }}" 
                                 alt="Avatar" class="user-avatar">
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('apprenant.profile') }}">
                                <i class="fas fa-user me-2"></i>Mon Profil
                            </a></li>
                            <li><a class="dropdown-item" href="#">
                                <i class="fas fa-cog me-2"></i>Paramètres
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 600,
            easing: 'ease-out-cubic',
            once: true
        });

        // Sidebar Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggleDesktop = document.getElementById('sidebarToggleDesktop');
            const sidebarToggleMobile = document.getElementById('sidebarToggleMobile');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const mainContent = document.getElementById('mainContent');

            // Restore sidebar state from localStorage
            const savedSidebarState = localStorage.getItem('sidebarCollapsed');
            if (savedSidebarState === 'true' && window.innerWidth > 768) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('sidebar-collapsed');
                if (sidebarToggleDesktop) {
                    const icon = sidebarToggleDesktop.querySelector('i');
                    icon.className = 'fas fa-chevron-right';
                }
            }

            function toggleSidebarDesktop() {
                const isCollapsed = sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('sidebar-collapsed');
                
                // Save state to localStorage
                localStorage.setItem('sidebarCollapsed', isCollapsed);
                
                // Update button icon with animation
                const icon = sidebarToggleDesktop.querySelector('i');
                icon.style.transform = 'rotate(180deg)';
                setTimeout(() => {
                    if (isCollapsed) {
                        icon.className = 'fas fa-chevron-right';
                    } else {
                        icon.className = 'fas fa-bars';
                    }
                    icon.style.transform = '';
                }, 150);
                
                // Trigger resize event for any charts or responsive components
                setTimeout(() => {
                    window.dispatchEvent(new Event('resize'));
                }, 300);
            }

            function toggleSidebarMobile() {
                sidebar.classList.toggle('mobile-open');
                sidebarOverlay.classList.toggle('active');
                document.body.style.overflow = sidebar.classList.contains('mobile-open') ? 'hidden' : '';
            }

            function closeSidebar() {
                sidebar.classList.remove('mobile-open');
                sidebarOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }

            // Desktop toggle
            if (sidebarToggleDesktop) {
                sidebarToggleDesktop.addEventListener('click', toggleSidebarDesktop);
            }

            // Mobile toggle
            if (sidebarToggleMobile) {
                sidebarToggleMobile.addEventListener('click', toggleSidebarMobile);
            }

            // Close sidebar when clicking overlay
            sidebarOverlay.addEventListener('click', closeSidebar);

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('mobile-open');
                    sidebarOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                } else {
                    // Reset desktop collapsed state on mobile
                    sidebar.classList.remove('collapsed');
                    mainContent.classList.remove('sidebar-collapsed');
                    if (sidebarToggleDesktop) {
                        const icon = sidebarToggleDesktop.querySelector('i');
                        icon.className = 'fas fa-bars';
                    }
                }
            });

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + B to toggle sidebar
                if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
                    e.preventDefault();
                    if (window.innerWidth > 768) {
                        toggleSidebarDesktop();
                    } else {
                        toggleSidebarMobile();
                    }
                }
                
                // Escape to close mobile sidebar
                if (e.key === 'Escape' && window.innerWidth <= 768) {
                    closeSidebar();
                }
            });

            // Add smooth hover effects to nav links
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('mouseenter', function() {
                    if (!this.classList.contains('active')) {
                        this.style.transform = 'translateX(8px)';
                    }
                });
                
                link.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('active')) {
                        this.style.transform = '';
                    }
                });
            });

            // Add loading state to navigation
            document.querySelectorAll('.nav-link[href]:not([href="#"])').forEach(link => {
                link.addEventListener('click', function(e) {
                    if (!this.classList.contains('active')) {
                        // Add loading animation
                        const icon = this.querySelector('i');
                        const originalIcon = icon.className;
                        icon.className = 'fas fa-spinner fa-spin';
                        
                        // Restore original icon after a short delay if navigation is cancelled
                        setTimeout(() => {
                            if (icon.className.includes('fa-spinner')) {
                                icon.className = originalIcon;
                            }
                        }, 3000);
                    }
                });
            });
        });

        // Smooth transitions for nav links
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                if (!this.classList.contains('active')) {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
