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
    
    <style>
        /* Variables de couleur AfriCode */
        :root {
            --africode-primary: #1EA38B;
            --africode-secondary: #FF8E2A;
            --africode-accent-red: #E32D31;
            --africode-highlight-green: #27B371;
            --africode-white: #FFFFFF;
            --africode-dark-text: #333333;
            --africode-gray-light: #F8F9FA;
            --africode-gray-medium: #E9ECEF;
            --africode-gray-dark: #6C757D;
            --africode-gradient-primary: linear-gradient(135deg, var(--africode-primary) 0%, var(--africode-highlight-green) 100%);
            --africode-gradient-accent: linear-gradient(135deg, var(--africode-secondary) 0%, #FFB366 100%);
            --africode-shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
            --africode-shadow-md: 0 4px 8px rgba(0, 0, 0, 0.15);
            --africode-shadow-lg: 0 8px 25px rgba(0, 0, 0, 0.15);
            --africode-border-radius: 12px;
            --africode-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #F8FFFE 0%, #F0F9F8 100%);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: var(--africode-dark-text);
            line-height: 1.6;
        }
        
        /* Layout principal */
        .app-layout {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar moderne */
        .formateur-sidebar {
            width: 280px;
            background: var(--africode-gradient-primary);
            color: white;
            box-shadow: var(--africode-shadow-lg);
            position: relative;
            z-index: 1000;
            transition: var(--africode-transition);
        }
        
        .formateur-sidebar.collapsed {
            width: 80px;
        }
        
        /* Logo section */
        .sidebar-brand {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .africode-logo-img {
            transition: transform 0.2s ease;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
            margin-bottom: 0.5rem;
        }
        
        .sidebar-brand:hover .africode-logo-img {
            transform: scale(1.05);
        }
        
        .logo-text {
            color: white;
            font-weight: 800;
            font-size: 1rem;
            letter-spacing: 1px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            transition: var(--africode-transition);
        }
        
        .sidebar-brand:hover .logo-text {
            color: var(--africode-secondary);
        }
        
        /* Mode collapsed - Logo */
        .formateur-sidebar.collapsed .sidebar-brand {
            padding: 1.5rem 0.75rem;
        }
        
        .formateur-sidebar.collapsed .africode-logo-img {
            height: 28px !important;
        }
        
        .formateur-sidebar.collapsed .logo-text {
            display: none;
        }
        
        /* Navigation */
        .sidebar-nav {
            flex: 1;
            padding: 1.5rem 0;
            overflow-y: auto;
        }
        
        .nav-section {
            margin-bottom: 2rem;
        }
        
        .nav-section-title {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 1.5rem 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .nav-item {
            margin: 0 1rem;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            border-radius: var(--africode-border-radius);
            margin-bottom: 0.25rem;
            transition: var(--africode-transition);
            position: relative;
            overflow: hidden;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--africode-secondary);
            opacity: 0;
            transition: var(--africode-transition);
        }
        
        .nav-link:hover, .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(4px);
        }
        
        .nav-link:hover::before, .nav-link.active::before {
            opacity: 1;
        }
        
        .nav-link i {
            width: 20px;
            margin-right: 12px;
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .nav-link-text {
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        /* Badge de notification */
        .nav-badge {
            background: var(--africode-secondary);
            color: white;
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 12px;
            margin-left: auto;
            font-weight: 600;
        }
        
        /* Mode collapsed - Navigation */
        .formateur-sidebar.collapsed .nav-section-title {
            display: none;
        }
        
        .formateur-sidebar.collapsed .nav-item {
            margin: 0 0.5rem;
        }
        
        .formateur-sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 1rem 0.5rem;
        }
        
        .formateur-sidebar.collapsed .nav-link-text {
            display: none;
        }
        
        .formateur-sidebar.collapsed .nav-badge {
            display: none;
        }
        
        .formateur-sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.25rem;
        }
        
        /* Sidebar footer */
        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            padding: 0.75rem;
            border-radius: var(--africode-border-radius);
            transition: var(--africode-transition);
        }
        
        .user-profile:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 12px;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .user-info h6 {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .user-info small {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.75rem;
        }
        
        /* Mode collapsed - Footer */
        .formateur-sidebar.collapsed .sidebar-footer {
            padding: 1rem 0.75rem;
            text-align: center;
        }
        
        .formateur-sidebar.collapsed .user-info {
            display: none;
        }
        
        .formateur-sidebar.collapsed .user-avatar {
            margin-right: 0;
            width: 36px;
            height: 36px;
        }
        
        /* Toggle button */
        .sidebar-toggle {
            position: absolute;
            top: 50%;
            right: -15px;
            width: 30px;
            height: 30px;
            background: var(--africode-secondary);
            border: none;
            border-radius: 50%;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--africode-shadow-md);
            transition: var(--africode-transition);
            z-index: 1001;
        }
        
        .sidebar-toggle:hover {
            background: #FF6B00;
            transform: scale(1.1);
        }
        
        /* Contenu principal */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        /* Header moderne */
        .content-header {
            background: white;
            padding: 1.5rem 2rem;
            box-shadow: var(--africode-shadow-sm);
            border-bottom: 1px solid var(--africode-gray-medium);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--africode-dark-text);
            margin: 0;
            background: var(--africode-gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .page-subtitle {
            color: var(--africode-gray-dark);
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .btn-primary-africode {
            background: var(--africode-gradient-primary);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: var(--africode-border-radius);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--africode-transition);
            box-shadow: var(--africode-shadow-sm);
        }
        
        .btn-primary-africode:hover {
            transform: translateY(-2px);
            box-shadow: var(--africode-shadow-md);
            color: white;
        }
        
        .btn-secondary-africode {
            background: var(--africode-gradient-accent);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: var(--africode-border-radius);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--africode-transition);
            box-shadow: var(--africode-shadow-sm);
        }
        
        .btn-secondary-africode:hover {
            transform: translateY(-2px);
            box-shadow: var(--africode-shadow-md);
            color: white;
        }
        
        /* Content area */
        .content-body {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }
        
        /* Cards modernes */
        .card-modern {
            background: white;
            border: none;
            border-radius: var(--africode-border-radius);
            box-shadow: var(--africode-shadow-sm);
            transition: var(--africode-transition);
            overflow: hidden;
        }
        
        .card-modern:hover {
            transform: translateY(-4px);
            box-shadow: var(--africode-shadow-md);
        }
        
        .card-header-modern {
            background: var(--africode-gray-light);
            border-bottom: 1px solid var(--africode-gray-medium);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            color: var(--africode-dark-text);
        }
        
        .card-body-modern {
            padding: 1.5rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .formateur-sidebar {
                position: fixed;
                left: -280px;
                z-index: 1050;
                height: 100vh;
            }
            
            .formateur-sidebar.show {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .content-header {
                padding: 1rem;
            }
            
            .content-body {
                padding: 1rem;
            }
            
            .sidebar-toggle {
                display: none;
            }
        }
        
        /* Mobile overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            display: none;
        }
        
        .sidebar-overlay.show {
            display: block;
        }
        
        /* Mobile toggle button */
        .mobile-toggle {
            display: none;
            background: var(--africode-primary);
            border: none;
            color: white;
            padding: 0.5rem;
            border-radius: 8px;
            margin-right: 1rem;
        }
        
        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }
        }
        
        /* Animations et effets */
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .slide-in-right {
            animation: slideInRight 0.5s ease-out;
        }
        
        /* États et messages */
        .alert-modern {
            border: none;
            border-radius: var(--africode-border-radius);
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--africode-shadow-sm);
        }
        
        .alert-success-modern {
            background: linear-gradient(135deg, #D4F7ED 0%, #E8FDF8 100%);
            color: var(--africode-highlight-green);
            border-left: 4px solid var(--africode-highlight-green);
        }
        
        .alert-warning-modern {
            background: linear-gradient(135deg, #FFF4E6 0%, #FFFBF0 100%);
            color: #B45309;
            border-left: 4px solid var(--africode-secondary);
        }
        
        .alert-danger-modern {
            background: linear-gradient(135deg, #FEE2E2 0%, #FEF2F2 100%);
            color: var(--africode-accent-red);
            border-left: 4px solid var(--africode-accent-red);
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
                        <a href="{{ route('formateur.dashboard') }}#mes-cours" class="nav-link {{ request()->routeIs('formateur.courses.*') || request()->routeIs('formateur.manage.course') ? 'active' : '' }}">
                            <i class="fas fa-book-open"></i>
                            <span class="nav-link-text">Mes cours</span>
                            @if(isset($totalCourses) && $totalCourses > 0)
                                <span class="nav-badge">{{ $totalCourses }}</span>
                            @endif
                        </a>
                    </div>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Gestion</div>
                    <div class="nav-item">
                        <a href="{{ route('formateur.courses.create') }}" class="nav-link {{ request()->routeIs('formateur.courses.create') ? 'active' : '' }}">
                            <i class="fas fa-plus-circle"></i>
                            <span class="nav-link-text">Créer un cours</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-users"></i>
                            <span class="nav-link-text">Étudiants</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-chart-bar"></i>
                            <span class="nav-link-text">Analytics</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-star"></i>
                            <span class="nav-link-text">Évaluations</span>
                        </a>
                    </div>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Outils</div>
                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-comments"></i>
                            <span class="nav-link-text">Messages</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-calendar-alt"></i>
                            <span class="nav-link-text">Calendrier</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-file-alt"></i>
                            <span class="nav-link-text">Ressources</span>
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
    
    <script>
        // Initialisation AOS
        AOS.init({
            duration: 600,
            easing: 'ease-out-cubic',
            once: true
        });
        
        // Gestion sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const toggleIcon = document.getElementById('toggleIcon');
            
            sidebar.classList.toggle('collapsed');
            
            if (sidebar.classList.contains('collapsed')) {
                toggleIcon.classList.remove('fa-chevron-left');
                toggleIcon.classList.add('fa-chevron-right');
            } else {
                toggleIcon.classList.remove('fa-chevron-right');
                toggleIcon.classList.add('fa-chevron-left');
            }
        }
        
        function openSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.add('show');
            overlay.classList.add('show');
        }
        
        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        }
        
        // Fermer sidebar au clic sur un lien (mobile)
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    closeSidebar();
                }
            });
        });
        
        // Auto-close sidebar on window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                closeSidebar();
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>
