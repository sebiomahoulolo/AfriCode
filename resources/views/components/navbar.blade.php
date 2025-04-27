<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AfriCode</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .navbar {
            background-color: #1e3a8a !important;
        }
        .navbar-brand, .nav-link, .btn-outline-light {
            color: white !important;
        }
        .nav-link:hover {
            color: #facc15 !important;
        }

        /* Effet de soulignement du li au survol et au clic */
.nav-item {
    position: relative;
}

.nav-item::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    display: block;
    bottom: 0;
    right: 0;
    background-color: #00FF00; /* Couleur verte */
    transition: width 0.3s ease, right 0.3s ease;
}

.nav-item:hover::after,
.nav-item:focus-within::after {
    width: 100%;
    right: 0;
}



.nav-item .nav-link::after {
    content: '';
    position: absolute;
    width: 2px;
    height: 3px;
    display: block;
    bottom: 0;
    left: 0;
    transition: width 0.3s ease, left 0.3s ease;
}

        .btn-outline-light:hover {
            background-color: #facc15 !important;
            color: #1e3a8a !important;
        }
        body {
            padding-top: 80px;
        }
        .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        .dropdown:hover .dropdown-menu {
            display: block;
            opacity: 1;
        }
        .dropdown-item:hover {
            background-color: #facc15;
            color: #1e3a8a;
            border-radius: 5px;
        }
        @media (max-width: 767.98px) {
            .dropdown-menu {
                display: block;
                opacity: 1;
                position: relative;
            }
        }

        /* Style général du menu déroulant */
.nav-item.dropdown:hover .dropdown-menu {
    display: flex;
    animation: fadeIn 0.3s ease-in-out; /* Animation de l'apparition */
}

/* Style pour les colonnes du menu */
.dropdown-column {
    display: flex;
    flex-direction: column;
    padding: 10px;
}

/* Ajout d'un fond clair au survol des éléments du menu */
.dropdown-item:hover {
    background-color: #facc15; /* Jaune doré */
    color: #1e3a8a; /* Bleu foncé */
    border-radius: 5px; /* Coins arrondis */
}

/* Effet de transition pour les éléments du menu */
.dropdown-item {
    transition: background-color 0.3s ease, color 0.3s ease, transform 0.2s ease;
    position: relative;
}

.dropdown-item::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    display: block;
    bottom: 0;
    right: 0;
    background-color: #1e3a8a; /* Couleur de soulignement */
    transition: width 0.3s ease, right 0.3s ease;
}

.dropdown-item:hover::after {
    width: 100%; /* Soulignement complet */
    right: 0; /* Déplacement de la droite vers la gauche */
}

/* Ajout d'une ombre douce pour le menu */
.dropdown-menu {
    border-radius: 8px; /* Coins arrondis */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Ombre */
    display: none;
    opacity: 0;
    transition: opacity 0.3s ease;
}

/* Animation d'apparition pour le menu */
@keyframes fadeIn {
    0% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}

/* Transition fluide pour le menu déroulant */
.navbar-nav .dropdown-menu {
    display: none; /* Initialement caché */
    opacity: 0;
    transition: opacity 0.3s ease;
}

.navbar-nav .dropdown:hover .dropdown-menu {
    display: flex;
    opacity: 1;
    min-width: 450px; /* Largeur minimale pour le cadre */
    min-height: 200px; /* Hauteur minimale pour le cadre */
}

/* Media queries pour les petits écrans */
@media (max-width: 767.98px) {
    .dropdown-menu {
        flex-direction: column;
        min-width: 50%; /* Largeur minimale pour petits écrans */
    }
}


    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#"><i class="fas fa-code"></i> AfriCode</a>
        <button class="navbar-toggler" type="button" style="color: white;" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"style="color: white;"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ url('/') }}"><i class="fas fa-home"></i> Accueil</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button">
                        <i class="fas fa-book"></i> Cours
</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <div class="row">
                            <div class="col-md-6 dropdown-column">
                            <a class="dropdown-item" href="{{ route('pages.coursD') }}">Cours débutant</a>
    <a class="dropdown-item" href="{{ route('pages.coursT') }}">Cours intermédiaire</a>
    <a class="dropdown-item" href="{{ route('pages.coursE') }}">Cours expert</a>
    <a class="dropdown-item" href="">Cours gratuit</a>
    <a class="dropdown-item" href="">Cours certifiant</a>
</div>

                            <div class="col-md-6 dropdown-column">
                                <a class="dropdown-item" href="{{ route('pages.compdisp') }}">Compétitions disponibles</a>
                                <a class="dropdown-item" href="{{ route('pages.test') }}">Test de niveau</a>
                                <a class="dropdown-item" href="{{ route('pages.verifier') }}">Vérifier un certificat</a>
                                <a class="dropdown-item" href="{{ route('pages.forumexp') }}">Forum des experts</a>
                                <a class="dropdown-item" href="{{ route('pages.forumapp') }}">Forum des apprenants</a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('pages.apropos') }}"><i class="fas fa-info-circle"></i> À propos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('pages.contact') }}"><i class="fas fa-envelope"></i> Contact</a>
                </li>
            </ul>
            <form class="d-flex me-3" role="search">
                <input class="form-control me-2" type="search" placeholder="Recherche" aria-label="Search">
                <button class="btn btn-outline-light" type="submit"><i class="fas fa-search"></i></button>
            </form>
            <div class="dropdown">
                <a href="#" class="btn btn-outline-light dropdown-toggle" id="identificationDropdown" data-bs-toggle="dropdown">
                    <i class="fas fa-user"></i> S'identifier
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('login') }}">Connexion</a></li>
                    <li><a class="dropdown-item" href="{{ route('register') }}">Créer un compte</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
