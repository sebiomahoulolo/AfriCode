<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AfriCode - Espace Formateur')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2C73D2;
            --secondary-color: #FF8B34;
            --light-bg: #f8f9fa;
            --border-radius: 10px;
        }
        
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            background: linear-gradient(180deg, #2C73D2 0%, #1E5AA6 100%);
            color: white;
            min-height: 100vh;
            padding-top: 2rem;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 0.5rem;
            padding: 0.75rem 1rem;
            border-radius: var(--border-radius);
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .main-content {
            padding: 2rem;
        }
        
        .card {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: var(--border-radius);
        }
        
        .stat-card h3 {
            font-weight: 600;
            margin-bottom: 0;
        }
        
        .stat-card p {
            color: #6c757d;
            margin-bottom: 0;
        }
        
        .course-card img {
            border-top-left-radius: var(--border-radius);
            border-top-right-radius: var(--border-radius);
            height: 180px;
            object-fit: cover;
        }
        
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .profile-pic {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 1rem;
        }
        
        .welcome-text h4 {
            margin-bottom: 0;
            font-weight: 600;
        }
        
        .welcome-text p {
            margin-bottom: 0;
            color: #6c757d;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #215daa;
            border-color: #215daa;
        }

        .btn-warning {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .bg-primary {
            background-color: var(--primary-color) !important;
        }

        .bg-warning {
            background-color: var(--secondary-color) !important;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="d-flex flex-column p-3">
                    <h4 class="text-center mb-4">AfriCode</h4>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('formateur.dashboard') ? 'active' : '' }}" href="{{ route('formateur.dashboard') }}">
                                <i class="fas fa-home"></i> Tableau de bord
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('formateur.courses.*') || request()->routeIs('formateur.manage.course') ? 'active' : '' }}" href="{{ route('formateur.dashboard') }}#mes-cours">
                                <i class="fas fa-book"></i> Mes cours
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('formateur.courses.create') ? 'active' : '' }}" href="{{ route('formateur.courses.create') }}">
                                <i class="fas fa-plus-circle"></i> Créer un cours
                            </a>
                        </li>
                        <li class="nav-item mt-5">
                            <a class="nav-link" href="{{ route('profile.edit') }}">
                                <i class="fas fa-cog"></i> Paramètres
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link border-0 bg-transparent">
                                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Profile Header -->
                <div class="profile-header">
                    <img src="{{ Auth::user()->profile_image_path ? asset(Auth::user()->profile_image_path) : 'https://via.placeholder.com/50' }}" alt="Profile" class="profile-pic">
                    <div class="welcome-text">
                        <h4>@yield('page-heading', 'Bonjour, ' . Auth::user()->first_name . ' ' . Auth::user()->last_name)</h4>
                        <p>@yield('page-subheading', 'Bienvenue sur votre tableau de bord formateur')</p>
                    </div>
                </div>

                <!-- Session Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <!-- Main Content -->
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS et Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
