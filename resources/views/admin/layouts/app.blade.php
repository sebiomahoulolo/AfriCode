<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AfriCode') }} - Administration</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Admin CSS -->
    @vite(['resources/css/admin.css', 'resources/js/components/admin.js'])
    <!-- Custom styles for charts and notifications -->
    <style>
        .admin-notifications-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            width: 320px;
            z-index: 1000;
            margin-top: 0.5rem;
        }
        
        .admin-notifications-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #E9ECEF;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .admin-notifications-header h6 {
            margin: 0;
            font-weight: 600;
        }
        
        .admin-notifications-count {
            background: #E32D31;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 10px;
            font-size: 0.8rem;
        }
        
        .admin-notifications-list {
            max-height: 300px;
            overflow-y: auto;
        }
        
        .admin-notification-item {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #F8F9FA;
            transition: background-color 0.2s;
        }
        
        .admin-notification-item:hover {
            background: #F8F9FA;
        }
        
        .admin-notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1EA38B, #27B371);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 1rem;
            font-size: 0.9rem;
        }
        
        .admin-notification-content {
            flex: 1;
        }
        
        .admin-notification-content p {
            margin: 0;
            font-size: 0.9rem;
            color: #333;
        }
        
        .admin-notification-content small {
            color: #6C757D;
            font-size: 0.8rem;
        }
        
        .admin-notifications-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #E9ECEF;
            text-align: center;
        }
        
        .admin-notifications-footer a {
            color: #1EA38B;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .admin-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 1rem 1.5rem;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-width: 300px;
            animation: slideInRight 0.3s ease;
        }
        
        .admin-toast-success {
            border-left: 4px solid #27B371;
        }
        
        .admin-toast-error {
            border-left: 4px solid #E32D31;
        }
        
        .admin-toast-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .admin-toast-content i {
            font-size: 1.1rem;
        }
        
        .admin-toast-success .admin-toast-content i {
            color: #27B371;
        }
        
        .admin-toast-error .admin-toast-content i {
            color: #E32D31;
        }
        
        .admin-toast-close {
            background: none;
            border: none;
            color: #6C757D;
            cursor: pointer;
            font-size: 0.9rem;
        }
        
        .admin-confirm-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .admin-confirm-backdrop {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }
        
        .admin-confirm-dialog {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
            width: 90%;
            max-width: 400px;
            position: relative;
        }
        
        .admin-confirm-header {
            padding: 1.5rem 1.5rem 1rem;
            border-bottom: 1px solid #E9ECEF;
        }
        
        .admin-confirm-header h6 {
            margin: 0;
            font-weight: 600;
        }
        
        .admin-confirm-body {
            padding: 1rem 1.5rem;
        }
        
        .admin-confirm-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #E9ECEF;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }
        
        .admin-btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .admin-btn-primary {
            background: #1EA38B;
            color: white;
        }
        
        .admin-btn-primary:hover {
            background: #178A73;
        }
        
        .admin-btn-secondary {
            background: #6C757D;
            color: white;
        }
        
        .admin-btn-secondary:hover {
            background: #5A6268;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="admin-sidebar-brand">
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 50 25'%3E%3Cg transform='translate(10 2)' fill='white'%3E%3Cpath d='M0 0 H8 V20 H0 Z' fill='%23FF8E2A' transform='skewX(-15)'/%3E%3Cpath d='M10 0 H18 V20 H10 Z' fill='%23E32D31' transform='skewX(-15)'/%3E%3Cpath d='M20 0 H28 V20 H20 Z' fill='%2327B371' stroke='white' stroke-width='0.5' transform='skewX(-15)'/%3E%3C/g%3E%3C/svg%3E" alt="AfriCode Logo" class="admin-logo-img">
                <span class="admin-logo-text">AFRICODE</span>
            </div>

            <!-- Toggle Button -->
            <button class="admin-sidebar-toggle">
                <i class="fas fa-chevron-left" id="adminToggleIcon"></i>
            </button>
            
            <nav class="admin-sidebar-menu">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt admin-nav-icon"></i>
                    <span class="admin-nav-text">Tableau de bord</span>
                </a>
                
                <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users admin-nav-icon"></i>
                    <span class="admin-nav-text">Utilisateurs</span>
                </a>
                
                <a href="{{ route('admin.courses.index') }}" class="admin-nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                    <i class="fas fa-book admin-nav-icon"></i>
                    <span class="admin-nav-text">Cours</span>
                </a>
                
                <a href="{{ route('admin.certifications.index') }}" class="admin-nav-link {{ request()->routeIs('admin.certifications.*') ? 'active' : '' }}">
                    <i class="fas fa-certificate admin-nav-icon"></i>
                    <span class="admin-nav-text">Certifications</span>
                </a>
                
                <a href="{{ route('admin.payments.index') }}" class="admin-nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <i class="fas fa-credit-card admin-nav-icon"></i>
                    <span class="admin-nav-text">Paiements</span>
                </a>
                
                <a href="{{ route('admin.statistics.index') }}" class="admin-nav-link {{ request()->routeIs('admin.statistics.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar admin-nav-icon"></i>
                    <span class="admin-nav-text">Statistiques</span>
                </a>
                
                <a href="{{ route('admin.messages.index') }}" class="admin-nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                    <i class="fas fa-envelope admin-nav-icon"></i>
                    <span class="admin-nav-text">Messages</span>
                </a>
                
                <a href="{{ route('admin.events.index') }}" class="admin-nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar admin-nav-icon"></i>
                    <span class="admin-nav-text">Événements</span>
                </a>
                
                <a href="{{ route('admin.challenges.index') }}" class="admin-nav-link {{ request()->routeIs('admin.challenges.create') ? 'active' : '' }}">
                    <i class="fas fa-bolt admin-nav-icon"></i>
                    <span class="admin-nav-text">Créer Défi/Compétition</span>
                </a>
                
                <a href="{{ route('admin.settings.edit') }}" class="admin-nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cog admin-nav-icon"></i>
                    <span class="admin-nav-text">Paramètres</span>
                </a>
                
                <a href="{{ route('home') }}" class="admin-nav-link">
                    <i class="fas fa-home admin-nav-icon"></i>
                    <span class="admin-nav-text">Retour au site</span>
                </a>
                
                <a href="#" class="admin-nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt admin-nav-icon"></i>
                    <span class="admin-nav-text">Déconnexion</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </nav>
        </aside>

        <!-- Mobile Overlay -->
        <div class="admin-sidebar-overlay" id="adminSidebarOverlay"></div>

        <!-- Main Content -->
        <main class="admin-main-content">
            <!-- Header -->
            <header class="admin-header">
                <div class="admin-header-left">
                    <button class="admin-mobile-toggle" onclick="openAdminSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="admin-breadcrumb">
                        <div class="admin-breadcrumb-item">
                            <i class="fas fa-home"></i>
                            <span>Admin</span>
                        </div>
                        <div class="admin-breadcrumb-separator">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                        <div class="admin-breadcrumb-item">
                            <span>@yield('breadcrumb', 'Tableau de bord')</span>
                        </div>
                    </div>
                </div>
                
                <div class="admin-header-right">
                    <div class="admin-search-box">
                        <input type="text" class="admin-search-input" placeholder="Rechercher...">
                        <i class="fas fa-search admin-search-icon"></i>
                    </div>
                    
                    <button class="admin-notifications">
                        <i class="fas fa-bell"></i>
                        <span class="admin-notification-badge">3</span>
                    </button>
                    
                    <div class="admin-user-menu">
                        <button class="admin-user-button">
                            <div class="admin-user-avatar">
                                {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
                            </div>
                            <div class="admin-user-info">
                                <div class="admin-user-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                                <div class="admin-user-role">Administrateur</div>
                            </div>
                            <i class="fas fa-chevron-down admin-user-chevron"></i>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="admin-content-body">
                @if (session('success'))
                    <div class="admin-toast admin-toast-success" style="position: relative; top: 0; right: 0; margin-bottom: 1rem;">
                        <div class="admin-toast-content">
                            <i class="fas fa-check-circle"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button class="admin-toast-close" onclick="this.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="admin-toast admin-toast-error" style="position: relative; top: 0; right: 0; margin-bottom: 1rem;">
                        <div class="admin-toast-content">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button class="admin-toast-close" onclick="this.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Overlay for mobile -->
    <div class="admin-sidebar-overlay" id="adminSidebarOverlay"></div>

    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Chart.js for admin charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @stack('scripts')
</body>
</html>
