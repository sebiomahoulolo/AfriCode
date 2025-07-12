{{-- Composant Navbar AfriCode - Simplifié et optimisé pour résoudre les problèmes --}}

<nav class="navbar navbar-expand-lg fixed-top navbar-dark">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <div class="d-flex flex-column align-items-center">
                <!-- Logo SVG sans bande horizontale -->
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 50 25'%3E%3Cg transform='translate(10 2)' fill='white'%3E%3Cpath d='M0 0 H8 V20 H0 Z' fill='%23FF8E2A' transform='skewX(-15)'/%3E%3Cpath d='M10 0 H18 V20 H10 Z' fill='%23E32D31' transform='skewX(-15)'/%3E%3Cpath d='M20 0 H28 V20 H20 Z' fill='%2327B371' stroke='white' stroke-width='0.5' transform='skewX(-15)'/%3E%3C/g%3E%3C/svg%3E" alt="AfriCode Logo" class="africode-logo-img" style="height: 30px;">
                <!-- Texte AFRICODE en dessous -->
                <span class="africode-text text-white fw-bold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px; margin-top: 2px;">AFRICODE</span>
            </div>
        </a>
        
        <!-- Toggler pour mobile -->
        <button class="navbar-toggler border-light" type="button" data-bs-toggle="collapse" 
                data-bs-target="#africode-navbar" aria-controls="africode-navbar" 
                aria-expanded="false" aria-label="Toggle navigation"  style="color:  #FF8E2A" >
            <span class="navbar-toggler-icon" ></span>
        </button>
        
        <!-- Contenu principal navbar -->
        <div class="collapse navbar-collapse" id="africode-navbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') || request()->routeIs('home') ? 'active' : '' }}" href="{{ url('/') }}">
                        <i class="fas fa-home me-1" style="color: #FF8E2A;"></i> Accueil
                    </a>
                </li>
                
                <!-- Menu Formations -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs(['courses.*', 'pages.compdisp', 'pages.test', 'pages.forumexp', 'pages.forumapp']) ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-book me-1" style="color: #FF8E2A;"></i> Formations
                    </a>
                    <ul class="dropdown-menu">
                        <li><h6 class="dropdown-header"style="color:  #FF8E2A;">Par Niveau</h6></li>
                        <li><a class="dropdown-item" href="{{ route('courses.index', ['level' => 'debutant']) }}">Débutant</a></li>
                        <li><a class="dropdown-item" href="{{ route('courses.index', ['level' => 'intermediaire']) }}">Intermédiaire</a></li>
                        <li><a class="dropdown-item" href="{{ route('courses.index', ['level' => 'avance']) }}">Avancé / Expert</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header" style="color: #FF8E2A;">Par Prix</h6></li>
                        <li><a class="dropdown-item" href="{{ route('courses.index', ['price' => 'free']) }}">Cours gratuits</a></li>
                        <li><a class="dropdown-item" href="{{ route('courses.index', ['price' => 'paid']) }}">Cours payants</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header" style="color: #FF8E2A;">Par Certification</h6></li>
                        <li><a class="dropdown-item" href="{{ route('courses.index', ['certification' => 'certified']) }}">Formations certifiées</a></li>
                        
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header" style="color: #FF8E2A;">Communauté & Outils</h6></li>
                        <li><a class="dropdown-item" href="{{ route('pages.compdisp') }}">Compétitions</a></li>
                        <li><a class="dropdown-item" href="{{ route('pages.test') }}">Test de niveau</a></li>
                        <li><a class="dropdown-item" href="{{ route('pages.forumexp') }}">Forum des Experts</a></li>
                        <li><a class="dropdown-item" href="{{ route('pages.forumapp') }}">Forum des Apprenants</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('certificate.verification') }}">
                            <i class="fas fa-shield-check me-2 text-success"></i>
                            <strong>Vérifier un Certificat</strong>
                        </a></li>
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('certificate.verification') ? 'active' : '' }}" href="{{ route('certificate.verification') }}">
                        <i class="fas fa-shield-check me-1" style="color: #FF8E2A;"></i> Vérifier Certificat
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pages.apropos') ? 'active' : '' }}" href="{{ route('pages.apropos') }}">
                        <i class="fas fa-info-circle me-1" style="color: #FF8E2A;"></i> À Propos
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pages.contact') ? 'active' : '' }}" href="{{ route('pages.contact') }}">
                        <i class="fas fa-envelope me-1" style="color: #FF8E2A;"></i> Contact
                    </a>
                </li>
            </ul>
            
            <!-- Section de droite -->
            <div class="navbar-nav ms-auto d-flex align-items-center">
                <!-- Sélecteur de langue -->
                <x-language-selector  />
                <form class="d-flex me-2 search-form">
                    <div class="input-group">
                        <input type="search" class="form-control form-control-sm" placeholder="Rechercher..." aria-label="Recherche">
                        <button class="btn btn-outline-light btn-sm" type="submit">
                            <i class="fas fa-search" style="color: #FF8E2A;"></i>
                        </button>
                    </div>
                </form>
                <div class="nav-item dropdown user-dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user me-1" style="color: #FF8E2A;"></i> S'identifier
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('login') }}">Connexion</a></li>
                        <li><a class="dropdown-item" href="{{ route('register') }}">Créer un compte</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<style>
    /* Variables de couleur AfriCode */
    :root {
        --africode-primary: #1EA38B;
        --africode-white: #FFFFFF;
        --africode-accent-orange: #FF8E2A;
        --africode-accent-red: #E32D31;
        --africode-highlight-green:  #1EA38B;
        --africode-dark-text: #333333;
    }
    
    /* Ajustement pour fixed navbar */
    body {
        padding-top: 60px;
    }
    
    /* Styles navbar */
    .navbar {
        background-color: var(--africode-primary) !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .navbar-brand {
        font-weight: 600;
        padding: 0.5rem 0;
        white-space: nowrap;
    }
    
    /* Styles pour le logo AfriCode */
    .africode-logo-img {
        transition: transform 0.2s ease;
    }
    
    .navbar-brand:hover .africode-logo-img {
        transform: scale(1.05);
    }
    
    .africode-text {
        transition: color 0.2s ease;
    }
    
    .navbar-brand:hover .africode-text {
        color: var(--africode-accent-orange) !important;
    }
    
    /* Styles liens navbar avec indicateur actif amélioré */
    .navbar .nav-link {
        padding: 0.5rem 0.8rem;
        position: relative;
        color: rgba(255, 255, 255, 0.9) !important;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .navbar .nav-link:after {
        content: '';
        position: absolute;
        width: 0;
        height: 3px;
        background: linear-gradient(135deg, var(--africode-accent-orange), #FFB366);
        left: 50%;
        bottom: 0;
        transform: translateX(-50%);
        transition: width 0.3s ease;
        border-radius: 2px;
    }
    
    .navbar .nav-link:hover:after, 
    .navbar .nav-link.active:after {
        width: 60%;
    }
    
    .navbar .nav-link:hover, 
    .navbar .nav-link.active {
        color: var(--africode-white) !important;
        background: rgba(255, 142, 42, 0.1);
        border-radius: 8px;
    }
    
    .navbar .nav-link.active {
        font-weight: 600;
        position: relative;
    }
    
    .navbar .nav-link.active::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 142, 42, 0.15);
        border-radius: 8px;
        z-index: -1;
    }
    
    /* Styles dropdown */
    .dropdown-menu {
        margin-top: 0.5rem;
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .dropdown-header {
        color: var(--africode-primary);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        padding: 0.5rem 1rem;
    }
    
    .dropdown-item {
        padding: 0.5rem 1rem;
        color: var(--africode-dark-text);
        transition: all 0.2s;
        white-space: normal;
        word-wrap: break-word;
    }
    
    .dropdown-item:hover, .dropdown-item:focus {
        background-color: var(--africode-highlight-green);
        color: var(--africode-white);
    }
    
    /* Styles recherche */
    .search-form .form-control {
        background-color: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.3);
        color: white;
    }
    
    .search-form .form-control::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }
    
    .search-form .form-control:focus {
        background-color: rgba(255, 255, 255, 0.2);
        box-shadow: none;
        border-color: white;
    }
    
    .search-form .btn {
        border-color: rgba(255, 255, 255, 0.3);
    }
    
    .search-form .btn:hover {
        background-color: var(--africode-accent-orange);
        border-color: var(--africode-accent-orange);
    }
    
    /* Styles dropdown utilisateur */
    .user-dropdown .nav-link {
        color: var(--africode-white) !important;
        display: inline-flex;
        align-items: center;
    }
    
    /* Media queries */
    @media (min-width: 992px) {
        .search-form {
            width: 200px;
        }
    }
    
    @media (max-width: 991.98px) {
        .navbar-collapse {
            padding: 1rem 0;
        }
        
        .navbar .nav-link:after {
            display: none;
        }
        
        .dropdown-menu {
            border: none;
            background-color: rgba(255, 255, 255, 0.05);
            box-shadow: none;
        }
        
        .dropdown-header {
            color: white;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .dropdown-item {
            color: rgba(255, 255, 255, 0.9) !important;
        }
        
        .dropdown-item:hover, .dropdown-item:focus {
            background-color: var(--africode-accent-orange);
        }
        
        .search-form {
            margin-bottom: 0.5rem;
            width: 100%;
        }
        
        .user-dropdown {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 0.5rem;
            padding-top: 0.5rem;
        }
    }
</style>