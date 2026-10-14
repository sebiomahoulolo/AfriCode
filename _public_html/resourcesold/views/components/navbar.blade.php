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
                aria-expanded="false" aria-label="Toggle navigation"  style="color:  #fea837" >
            <span class="navbar-toggler-icon" ></span>
        </button>

        <!-- Contenu principal navbar -->
        <div class="collapse navbar-collapse" id="africode-navbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') || request()->routeIs('home') ? 'active' : '' }}" href="{{ url('/') }}">
                        <i class="fas fa-home me-1" style="color: #fea837;"></i> Accueil
                    </a>
                </li>

                <!-- Menu Formations -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs(['courses.*', 'pages.compdisp', 'pages.test', 'pages.forumexp', 'pages.forumapp']) ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-book me-1" style="color: #fea837;"></i> Formations
                    </a>
                    <ul class="dropdown-menu" >
                        <li><h6 class="dropdown-header"style="color:  #fea837;">Par Niveau</h6></li>
                        <li><a class="dropdown-item" href="{{ route('courses.index', ['level' => 'debutant']) }}">Débutant</a></li>
                        <li><a class="dropdown-item" href="{{ route('courses.index', ['level' => 'intermediaire']) }}">Intermédiaire</a></li>
                        <li><a class="dropdown-item" href="{{ route('courses.index', ['level' => 'avance']) }}">Avancé / Expert</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header" style="color: #fea837;">Par Prix</h6></li>
                        <li><a class="dropdown-item" href="{{ route('courses.index', ['price' => 'free']) }}">Cours gratuits</a></li>
                        <li><a class="dropdown-item" href="{{ route('courses.index', ['price' => 'paid']) }}">Cours payants</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header" style="color: #fea837;">Par Certification</h6></li>
                        <li><a class="dropdown-item" href="{{ route('courses.index', ['certification' => 'certified']) }}">Formations certifiées</a></li>

                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header" style="color: #fea837;">Communauté & Outils</h6></li>
                        <li><a class="dropdown-item" href="{{ route('pages.compdisp') }}">Compétitions</a></li>
                        <li><a class="dropdown-item" href="{{ route('pages.test') }}">Test de niveau</a></li>
                        @auth
                        <li><a class="dropdown-item" href="{{ url('/le-forum-des-experts') }}">Forum</a></li>
                        @else
                        <li><a class="dropdown-item text-muted" href="{{ route('login') }}"><i class="fas fa-lock me-2"></i>Forum (connexion requise)</a></li>
                        @endauth
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('certificate.verification') }}">
                            <i class="fas fa-shield-check me-2 text-success"></i>
                            <strong>Vérifier un Certificat</strong>
                        </a></li>
                    </ul>
                </li>


                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pages.services') ? 'active' : '' }}" href="{{ route('pages.services') }}">
                        <i class="fas fa-layer-group me-1" style="color: #fea837;"></i> Nos Services
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pages.apropos') ? 'active' : '' }}" href="{{ route('pages.apropos') }}">
                        <i class="fas fa-info-circle me-1" style="color: #fea837;"></i> À Propos
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pages.contact') ? 'active' : '' }}" href="{{ route('pages.contact') }}">
                        <i class="fas fa-envelope me-1" style="color: #fea837;"></i> Contact
                    </a>
                </li>
            </ul>

            <!-- Section de droite -->
            <div class="navbar-nav ms-auto d-flex align-items-center">
                <!-- Sélecteur de langue -->
                <form class="d-flex me-2 search-form" action="{{ route('courses.index') }}" method="GET">
                    <div class="input-group">
                        <input type="search" name="search" class="form-control form-control-sm" placeholder="Rechercher un cours..." aria-label="Recherche" value="{{ request('search') }}">
                        <button class="btn btn-outline-light btn-sm" type="submit">
                            <i class="fas fa-search" style="color: #fea837;"></i>
                        </button>
                    </div>
                </form>
                @guest
                <div class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('login') }}">
                        <i class="fas fa-user me-1" style="color: #fea837;"></i> S'identifier
                    </a>
                </div>
                @else
                <div class="nav-item dropdown user-dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1" style="color: #fea837; font-size: 1.2rem;"></i>
                        <span>{{ auth()->user()->first_name ?? auth()->user()->email }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header text-muted small">Connecté</h6></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2" style="color: #11c06d;"></i> Mon tableau de bord
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger border-0 bg-transparent w-100 text-start">
                                    <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endguest
            </div>
        </div>
    </div>
</nav>

<style>
    /* Variables de couleur AfriCode */
    :root {
        --africode-primary: #11c06d;
        --africode-white: #FFFFFF;
        --africode-accent-orange: #fea837;
        --africode-accent-red: #fa1c39;
        --africode-highlight-green:  #11c06d;
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
        background: rgba(254, 168, 55, 0.1);
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
        background: rgba(254, 168, 55, 0.15);
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
