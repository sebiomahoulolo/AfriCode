<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AfriCode</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --africode-primary: #1EA38B; /* Vert Principal (Navbar BG) */
            --africode-white: #FFFFFF;    /* Texte Navbar, Dropdown Hover Texte */
            --africode-accent-orange: #FF8E2A; /* Hover, Soulignement Liens Navbar, Hover/BG Bouton */
            --africode-accent-red: #E32D31;    /* Dropdown item soulignement */
            --africode-highlight-green: #27B371; /* Dropdown hover BG */
            --africode-dark-text: #333333;      /* Texte Dropdown */
            --africode-light-bg: #f8f9fa;      /* Body BG / Copyright */
            --africode-border-light: rgba(255, 255, 255, 0.5); /* Bordure boutons outline */
            --africode-navbar-text: rgba(255, 255, 255, 0.9); /* Texte liens navbar standard */
        }

        body {
            padding-top: 70px; /* Ajuster si la hauteur de la navbar change */
            background-color: var(--africode-light-bg);
        }

        .navbar {
            background-color: var(--africode-primary) !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* --- Logo --- */
        .navbar-brand {
            color: var(--africode-white) !important;
            font-weight: bold;
            display: flex;
            align-items: center;
        }
        /* Styles pour simuler le logo - À REMPLACER par une image réelle */
        .navbar-brand .logo-placeholder-icon {
            margin-right: 8px;
            display: inline-block;
            width: 20px;
            height: 20px;
            position: relative;
        }
         .navbar-brand .logo-placeholder-icon::before,
         .navbar-brand .logo-placeholder-icon::after {
            content: ''; position: absolute; width: 50%; height: 100%;
            background-color: var(--africode-accent-orange); /* Orange */
            transform: skewX(-15deg); left: 0;
        }
         .navbar-brand .logo-placeholder-icon::after {
             background-color: var(--africode-highlight-green); /* Vert clair */
             left: 50%;
         }
          .navbar-brand .logo-placeholder-icon .red-line {
              position: absolute; bottom: 2px; left: 15%; right: 15%; height: 3px;
              background-color: var(--africode-accent-red); /* Rouge */
               transform: skewX(-15deg);
          }
         /* --- Fin Styles Logo --- */

        /* --- Liens Navbar --- */
        .navbar-nav .nav-link {
            color: var(--africode-navbar-text) !important;
            font-weight: 500;
            padding-top: 0.8rem; padding-bottom: 0.8rem;
            transition: color 0.2s ease;
        }
        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: var(--africode-white) !important;
        }

        /* Effet de soulignement des liens principaux */
        .nav-item { position: relative; }
        .nav-item::after {
            content: ''; position: absolute; width: 0; height: 3px; display: block;
            bottom: 0; left: 50%; transform: translateX(-50%);
            background-color: var(--africode-accent-orange); /* Soulignement Orange */
            transition: width 0.3s ease;
        }
        .nav-item:hover::after,
        .nav-item .nav-link.active::after { width: 60%; }

        /* --- Bouton Toggler (Mobile) --- */
         .navbar-toggler { border-color: var(--africode-border-light) !important; }
         .navbar-toggler-icon {
             background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.9%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
         }

        /* --- Recherche & Boutons Droite --- */
        .btn-outline-light {
            color: var(--africode-white) !important;
            border-color: var(--africode-border-light) !important;
            transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
        }
        .btn-outline-light:hover {
            background-color: var(--africode-accent-orange) !important;
            border-color: var(--africode-accent-orange) !important;
            color: var(--africode-white) !important;
        }
        .navbar .d-flex .form-control {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--africode-border-light);
            color: white; border-radius: 20px 0 0 20px;
        }
         .navbar .d-flex .form-control::placeholder { color: rgba(255, 255, 255, 0.6); }
         .navbar .d-flex .btn { border-radius: 0 20px 20px 0; }
         .form-control:focus { /* Style focus recherche */
            background-color: rgba(255, 255, 255, 0.2);
            border-color: var(--africode-white);
            box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.25);
            color: white;
         }

        /* --- Menu Déroulant --- */
        .dropdown-menu {
            border-radius: 8px; border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            margin-top: 0.5rem !important; padding: 0.5rem 0;
            max-width: 95vw;
            background-color: var(--africode-white); /* Fond blanc par défaut */
            display: none; /* Caché par défaut, géré par :hover */
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        /* Affichage au survol du parent .dropdown */
         .dropdown:hover > .dropdown-menu { /* Utiliser > pour cibler le menu direct */
            display: block; /* ou flex pour dropdown-menu-cours */
            opacity: 1;
        }
        /* Spécifique au menu Cours */
         .dropdown-menu-cours {
            min-width: 550px;
            padding: 1.25rem 1rem;
         }
         .dropdown:hover > .dropdown-menu-cours {
             display: flex; /* S'assurer qu'il utilise flex */
         }
          .dropdown-menu-cours .row {
              margin-left: -0.5rem; margin-right: -0.5rem;
              width: calc(100% + 1rem);
          }
          .dropdown-column { padding: 0 0.5rem; }
          .dropdown-column h6 {
              color: var(--africode-primary); font-weight: bold; font-size: 0.9em;
              margin-bottom: 0.75rem; padding-left: 0.75rem;
              text-transform: uppercase; letter-spacing: 0.5px;
          }

        .dropdown-item {
            color: var(--africode-dark-text) !important;
            padding: 0.6rem 1rem; font-size: 0.95em;
            transition: background-color 0.2s ease, color 0.2s ease;
            white-space: nowrap;
            position: relative; /* Pour le ::after */
        }
        .dropdown-item:hover, .dropdown-item:focus {
            background-color: var(--africode-highlight-green) !important; /* Vert clair */
            color: var(--africode-white) !important; /* Texte blanc */
            border-radius: 4px;
        }
        /* Effet soulignement rouge sur dropdown items (facultatif) */
        .dropdown-item::after {
            content: ''; position: absolute; width: 0; height: 2px; display: block;
            bottom: 0; right: 0; /* Commence à droite */
            background-color: var(--africode-accent-red); /* Rouge */
            transition: width 0.3s ease, right 0.3s ease;
        }
        .dropdown-item:hover::after { width: 100%; right: 0; }

         /* Style spécifique pour le dropdown user */
         .dropdown-menu-end { min-width: auto; }

        /* Responsivité */
        @media (max-width: 991.98px) {
            .navbar-nav { margin-top: 10px; }
            .nav-item::after { display: none; }
            .dropdown-menu {
                box-shadow: none; margin-top: 0 !important; border-radius: 0;
                padding: 0; background-color: transparent; /* Fond transparent pour voir navbar */
                 max-width: none; display: block; opacity: 1; /* Toujours visible si ouvert */
            }
             .dropdown-menu-cours { min-width: 100%; padding: 0.5rem; }
             .dropdown-menu-cours .row { margin: 0; width: 100%;}
             .dropdown-menu-cours .col-md-6 { width: 100%; padding: 0; margin-bottom: 0.5rem; }
             .dropdown-menu-cours .col-md-6:last-child { margin-bottom: 0; }
             .dropdown-column h6 {
                padding-left: 1rem; margin-bottom: 0.5rem;
                color: var(--africode-white); /* Titres blancs sur mobile */
                background-color: rgba(0,0,0,0.1); /* Léger fond pour titres */
                padding-top: 0.3rem; padding-bottom: 0.3rem; border-radius: 3px;
              }
             .dropdown-item {
                color: var(--africode-white) !important; padding: 0.75rem 1rem;
                border-radius: 0; white-space: normal;
                background-color: rgba(0,0,0,0.1); /* Fond item mobile */
                margin-bottom: 2px; /* Petit espace entre items */
             }
             .dropdown-item::after { display: none; } /* Pas de soulignement mobile */
             .dropdown-item:hover, .dropdown-item:focus {
                background-color: var(--africode-accent-orange) !important; /* Fond orange mobile */
             }
             .dropdown-menu-cours hr { display: none; }
             .navbar .d-flex { margin-top: 10px; }
             .navbar .dropdown { margin-top: 10px; width: 100%; }
             .navbar .dropdown .btn { width: 100%; text-align: left; padding-left: 1rem;} /* Bouton User prend toute largeur */
             .navbar .dropdown-menu-end { background-color: transparent; width: 100%; box-shadow: none; border-top: 1px solid var(--africode-border-light); }
             .navbar .dropdown-menu-end .dropdown-item { background-color: rgba(0,0,0,0.1);}
             .navbar .dropdown-menu-end .dropdown-item:hover { background-color: var(--africode-accent-orange) !important; }
        }

    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
        <!-- LOGO: Remplacez ceci par <img src="/path/to/your/logo.svg" alt="AfriCode Logo"> -->
        <a class="navbar-brand" href="{{ url('/') }}">
            <span class="logo-placeholder-icon"><span class="red-line"></span></span>
            AfriCode
        </a>
        <!-- FIN LOGO -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ url('/') }}"><i class="fas fa-home fa-fw me-1"></i> Accueil</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownCours" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-book fa-fw me-1"></i> Formations
                    </a>
                    <!-- Menu déroulant pour les cours -->
                    <div class="dropdown-menu dropdown-menu-cours" aria-labelledby="navbarDropdownCours">
                        <div class="row">
                            <div class="col-md-6 dropdown-column">
                                <h6>Par Niveau</h6>
                                <a class="dropdown-item" href="{{ route('pages.coursD') }}">Débutant</a>
                                <a class="dropdown-item" href="{{ route('pages.coursT') }}">Intermédiaire</a>
                                <a class="dropdown-item" href="{{ route('pages.coursE') }}">Avancé / Expert</a>
                                <hr class="d-none d-md-block my-2"> <!-- Séparateur visible sur desktop -->
                                <h6>Types</h6>
                                <a class="dropdown-item" href="#">Cours Gratuits</a>
                                <a class="dropdown-item" href="#">Cours Certifiants</a>
                            </div>
                            <div class="col-md-6 dropdown-column">
                                <h6>Communauté & Outils</h6>
                                <a class="dropdown-item" href="{{ route('pages.compdisp') }}">Compétitions</a>
                                <a class="dropdown-item" href="{{ route('pages.test') }}">Test de niveau</a>
                                <a class="dropdown-item" href="{{ route('pages.forumexp') }}">Forum des Experts</a>
                                <a class="dropdown-item" href="{{ route('pages.forumapp') }}">Forum des Apprenants</a>
                                <hr class="d-none d-md-block my-2">
                                <a class="dropdown-item" href="{{ route('pages.verifier') }}">Vérifier un Certificat</a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('pages.apropos') }}"><i class="fas fa-info-circle fa-fw me-1"></i> À Propos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('pages.contact') }}"><i class="fas fa-envelope fa-fw me-1"></i> Contact</a>
                </li>
            </ul>
            <form class="d-flex me-lg-3 mb-3 mb-lg-0" role="search">
                <input class="form-control me-0" type="search" placeholder="Rechercher cours..." aria-label="Recherche">
                <button class="btn btn-outline-light" type="submit"><i class="fas fa-search"></i></button>
            </form>
            <div class="dropdown">
                <a href="#" class="btn btn-outline-light dropdown-toggle" id="identificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user me-1"></i> S'identifier
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="identificationDropdown">
                    <li><a class="dropdown-item" href="{{ route('login') }}">Connexion</a></li>
                    <li><a class="dropdown-item" href="{{ route('register') }}">Créer un compte</a></li>
                    <!-- Ajouter lien Profil/Déconnexion si connecté -->
                </ul>
            </div>
        </div>
    </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Script pour gérer le lien actif (exemple simple)
    // Dans une vraie application (Laravel/React), cela serait géré différemment.
    document.addEventListener('DOMContentLoaded', function() {
        const currentLocation = window.location.href;
        const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

        navLinks.forEach(link => {
            // Comparaison plus robuste pour 'active' (ignore query string/hash)
            const linkLocation = link.href.split('?')[0].split('#')[0];
            const currentBaseLocation = currentLocation.split('?')[0].split('#')[0];

            link.classList.remove('active'); // Nettoyer d'abord
             if (link.closest('.nav-item')?.classList.contains('dropdown')) {
                 // Pour les dropdown toggles, vérifier si un enfant est actif
                 const dropdownItems = link.closest('.nav-item.dropdown').querySelectorAll('.dropdown-item');
                 let dropdownIsActive = false;
                 dropdownItems.forEach(item => {
                    const itemLocation = item.href.split('?')[0].split('#')[0];
                     if (itemLocation === currentBaseLocation) {
                        dropdownIsActive = true;
                     }
                 });
                 if (dropdownIsActive) {
                    link.classList.add('active');
                 }
             } else if (linkLocation === currentBaseLocation) {
                 // Pour les liens simples
                 link.classList.add('active');
             }
        });
    });
</script>
</body>
</html>