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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Admin CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f8fa;
        }
        .sidebar {
            min-height: 100vh;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            position: fixed;
            width: 250px;
            z-index: 100;
            transition: all 0.3s;
        }
        .sidebar-brand {
            padding: 20px 15px;
            background-color: #4F46E5;
            color: white;
            font-weight: 700;
        }
        .sidebar-menu {
            padding: 0;
        }
        .sidebar-menu a {
            display: block;
            padding: 12px 15px;
            color: #4B5563;
            text-decoration: none;
            transition: all 0.3s;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background-color: #EFF6FF;
            color: #4F46E5;
            border-right: 4px solid #4F46E5;
        }
        .sidebar-menu i {
            width: 20px;
            text-align: center;
            margin-right: 12px;
        }
        main {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
        }
        .navbar {
            background-color: #fff;
            border-bottom: 1px solid #E5E7EB;
            margin-bottom: 20px;
        }
        .page-header {
            padding-bottom: 10px;
            margin-bottom: 20px;
            border-bottom: 1px solid #E5E7EB;
        }
        .dashboard-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }
        .dashboard-card:hover {
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            font-weight: 600;
            margin-bottom: 15px;
        }
        .stats-number {
            font-size: 24px;
            font-weight: 700;
            color: #4F46E5;
        }
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            main {
                margin-left: 0;
            }
            .sidebar.show {
                margin-left: 0;
            }
            main.shift {
                margin-left: 250px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand d-flex align-items-center">
            <i class="fas fa-code me-2"></i>
            <span>AfriCode Admin</span>
        </div>
        
        <ul class="sidebar-menu list-unstyled">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Tableau de bord
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Utilisateurs
                </a>
            </li>
            <li>
                <a href="{{ route('admin.courses.index') }}" class="{{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                    <i class="fas fa-book"></i> Cours
                </a>
            </li>
            <li>
                <a href="{{ route('admin.certifications.index') }}" class="{{ request()->routeIs('admin.certifications.*') ? 'active' : '' }}">
                    <i class="fas fa-certificate"></i> Certifications
                </a>
            </li>
            <li>
                <a href="{{ route('admin.payments.index') }}" class="{{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <i class="fas fa-credit-card"></i> Paiements
                </a>
            </li>
            <li>
                <a href="{{ route('admin.statistics.index') }}" class="{{ request()->routeIs('admin.statistics.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i> Statistiques
                </a>
            </li>
            <li>
                <a href="{{ route('admin.messages.index') }}" class="{{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                    <i class="fas fa-envelope"></i> Messages
                </a>
            </li>
            <li>
                <a href="{{ route('admin.events.index') }}" class="{{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar"></i> Événements
                </a>
            </li>
            <li>
                <a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i> Paramètres
                </a>
            </li>
            <li>
                <a href="{{ route('home') }}">
                    <i class="fas fa-home"></i> Retour au site
                </a>
            </li>
            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>

    <main>
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <button class="btn btn-light d-md-none" id="toggleSidebar">
                    <i class="fas fa-bars"></i>
                </button>
                
                <div class="ms-auto d-flex align-items-center">
                    <!-- Notifications Component -->
                    <div class="me-3">
                        <x-admin-notifications />
                    </div>
                    
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Mon profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form-2').submit();">
                                    Déconnexion
                                </a>
                                <form id="logout-form-2" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chart.js for admin charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
            document.querySelector('main').classList.toggle('shift');
        });
    </script>

    @stack('scripts')
</body>
</html>
