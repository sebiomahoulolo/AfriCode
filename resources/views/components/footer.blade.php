<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AfriCode Footer Corrected</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Définition des couleurs AfriCode */
        :root {
            --africode-primary: #1EA38B;       /* Vert Principal */
            --africode-white: #FFFFFF;          /* Blanc */
            --africode-accent-orange: #FF8E2A;  /* Orange */
            --africode-accent-red: #E32D31;      /* Rouge */
            --africode-highlight-green: #27B371; /* Vert Clair */
            --africode-dark-text: #333333;        /* Texte Foncé */
            --africode-light-bg: #f8f9fa;        /* Fond très clair */
            --africode-border-light: rgba(255, 255, 255, 0.5); /* Bordure boutons/etc */
            --africode-footer-text: rgba(255, 255, 255, 0.9); /* Texte standard du footer */
        }

        /* Style général du footer */
        .footer {
            /* background-color est appliqué inline, mais on définit la couleur du texte ici */
            color: var(--africode-footer-text);
        }

        /* Titres */
        .footer h5 {
            color: var(--africode-white);
            font-weight: 600;
        }

        .footer-title {
            position: relative;
            display: inline-block;
            margin-bottom: 20px; /* Gardé de ton style original */
        }

        /* Soulignement des titres */
        .footer-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: var(--africode-accent-orange); /* Utilisation de l'orange */
        }

        /* Logo dans le titre */
        .footer-title img.footer-logo-img {
            height: 25px; /* Ajuster la taille si besoin */
            vertical-align: middle; /* Mieux aligner avec le texte */
            margin-right: 8px; /* Espace après le logo */
        }

        /* Paragraphe de description */
        .footer p {
            font-size: 0.9rem;
            line-height: 1.6;
            color: var(--africode-footer-text);
        }

        /* Liens */
        .footer a {
            color: var(--africode-footer-text);
            text-decoration: none;
        }

        /* Animation de soulignement */
        .link-animated {
            position: relative;
            display: inline-block;
            padding-bottom: 3px;
            transition: color 0.3s ease;
        }

        .link-animated::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0; /* Commence à gauche maintenant */
            width: 0;
            height: 2px;
            background-color: var(--africode-accent-orange); /* Soulignement Orange */
            transition: width 0.3s ease; /* Transition uniquement sur la largeur */
        }

        .link-animated:hover::after,
        .link-animated:focus::after {
            width: 100%;
        }

        /* Changement de couleur au survol */
        .link-animated:hover,
        .link-animated:focus {
            color: var(--africode-white) !important; /* Blanc vif au survol */
        }

        /* Icônes dans les listes de liens */
        .footer .list-unstyled li i.fa-fw, /* Cibler les icônes avec fa-fw si utilisées */
        .footer .list-unstyled li i.me-2 {   /* Ou cibler celles avec me-2 */
             color: var(--africode-accent-orange); /* Icônes en Orange */
             width: 1.2em; /* Assurer un espace constant */
        }

        /* Style pour les boutons des réseaux sociaux */
        .btn-floating {
            border-radius: 50%;
            width: 38px; /* Légèrement plus grand */
            height: 38px;
            padding: 0;
            display: inline-flex; /* Pour centrage parfait */
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border-color: var(--africode-border-light) !important; /* Bordure standard */
            color: var(--africode-white) !important;
        }

        .btn-floating:hover {
            background-color: var(--africode-accent-orange) !important; /* Fond orange */
            border-color: var(--africode-accent-orange) !important;
            color: var(--africode-white) !important;
            transform: translateY(-2px); /* Léger effet de soulèvement */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Disposition des liens dans la 2ème colonne */
        .footer-links {
            display: flex;
            gap: 10px 15px; /* Espacement vertical et horizontal */
            flex-wrap: wrap;
            padding-left: 0; /* Enlever padding par défaut si c'est une ul */
        }
         .footer-links li {
            list-style: none; /* S'assurer qu'il n'y a pas de puces */
         }

        /* Copyright */
        .footer-copyright {
            background-color: var(--africode-light-bg); /* Fond clair standard */
            color: var(--africode-dark-text); /* Texte foncé */
            font-size: 0.85rem;
        }
        .footer-copyright a { /* Styles pour lien dans copyright */
             color: var(--africode-primary); /* Lien en vert */
             font-weight: 600;
             text-decoration: none;
        }
         .footer-copyright a:hover {
             text-decoration: underline;
         }

    </style>
</head>
<body>

<footer class="footer text-light text-center text-lg-start" style="background-color: var(--africode-primary);">
    <div class="container py-4">
        <div class="row">
            <!-- Section Africode -->
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0"> <!-- Ajout mb-lg-0 -->
                <h5 class="text-uppercase fw-bold footer-title d-inline-flex align-items-center"> <!-- Utiliser d-inline-flex pour aligner logo et texte -->
                    <!-- Insertion du logo SVG -->
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 180 100'%3E%3Crect width='180' height='100' rx='5' fill='%231EA38B'/%3E%3Cg transform='translate(71 32)' fill='white'%3E%3Cpath d='M0 0 H8 V25 H0 Z' fill='%23FF8E2A' transform='skewX(-15)'/%3E%3Cpath d='M10 0 H18 V25 H10 Z' fill='%23E32D31' transform='skewX(-15)'/%3E%3Cpath d='M20 0 H28 V25 H20 Z' fill='%2327B371' transform='skewX(-15)'/%3E%3C/g%3E%3Ctext x='90' y='75' fill='white' font-size='20' font-family='Segoe UI, sans-serif' font-weight='bold' text-anchor='middle' letter-spacing='2'%3EAFRICODE%3C/text%3E%3Ctext x='90' y='90' fill='white' font-size='9' font-family='Segoe UI, sans-serif' text-anchor='middle' letter-spacing='1' opacity='0.9'%3ENO CODE, NO FUTURE%3C/text%3E%3C/svg%3E" alt="AfriCode Logo" class="footer-logo-img">
                    AfriCode
                </h5>
                <p>
                    Africode est votre plateforme de choix pour apprendre la programmation et devenir un expert en informatique.
                    Rejoignez notre communauté dès aujourd'hui !
                </p>
            </div>

            <!-- Liens utiles -->
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0"> <!-- Ajout mb-lg-0 -->
                <h5 class="text-uppercase fw-bold footer-title">Liens utiles</h5>
                <ul class="list-unstyled footer-links">
                    <!-- Répétez pour chaque lien -->
                    <li><a href="{{ route('pages.coursD') }}" class="text-light text-decoration-none link-animated"><i class="fas fa-chalkboard-teacher me-2"></i>Cours débutant</a></li>
                    <li><a href="{{ route('pages.coursT') }}" class="text-light text-decoration-none link-animated"><i class="fas fa-laptop-code me-2"></i>Cours intermédiaire</a></li>
                    <li><a href="{{ route('pages.coursE') }}" class="text-light text-decoration-none link-animated"><i class="fas fa-rocket me-2"></i>Cours expert</a></li>
                    <li><a href="#" class="text-light text-decoration-none link-animated"><i class="fas fa-gift me-2"></i>Cours gratuit</a></li>
                    <li><a href="#" class="text-light text-decoration-none link-animated"><i class="fas fa-award me-2"></i>Cours certifiant</a></li>
                    <li><a href="{{ route('pages.compdisp') }}" class="text-light text-decoration-none link-animated"><i class="fas fa-trophy me-2"></i>Compétitions</a></li>
                    <li><a href="{{ route('pages.test') }}" class="text-light text-decoration-none link-animated"><i class="fas fa-vial me-2"></i>Test de niveau</a></li>
                    <li><a href="{{ route('pages.verifier') }}" class="text-light text-decoration-none link-animated"><i class="fas fa-certificate me-2"></i>Vérifier certificat</a></li>
                    <li><a href="{{ route('pages.forumexp') }}" class="text-light text-decoration-none link-animated"><i class="fas fa-user-tie me-2"></i>Forum experts</a></li>
                    <li><a href="{{ route('pages.forumapp') }}" class="text-light text-decoration-none link-animated"><i class="fas fa-users me-2"></i>Forum apprenants</a></li>
                </ul>
            </div>

            <!-- Réseaux sociaux & Autres liens -->
            <div class="col-lg-4 col-md-12 mb-4 mb-lg-0"> <!-- mb-lg-0 pour éviter marge en bas sur grand écran -->
                <h5 class="text-uppercase fw-bold footer-title">Suivez-nous</h5>
                <div class="d-flex justify-content-center justify-content-lg-start mb-4"> <!-- mb-4 ajouté et alignement gauche sur lg -->
                    <a href="#" class="btn btn-outline-light btn-floating m-1" title="Facebook" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="btn btn-outline-light btn-floating m-1" title="Twitter" target="_blank" rel="noopener noreferrer"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="btn btn-outline-light btn-floating m-1" title="Instagram" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="btn btn-outline-light btn-floating m-1" title="LinkedIn" target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="btn btn-outline-light btn-floating m-1" title="YouTube" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="btn btn-outline-light btn-floating m-1" title="WhatsApp" target="_blank" rel="noopener noreferrer"><i class="fab fa-whatsapp"></i></a>
                </div>
                <!-- Autres liens -->
                 <ul class="list-unstyled d-flex flex-column align-items-center align-items-lg-start gap-2"> <!-- alignement gauche sur lg -->
                    <li>
                        <a href="{{ url('/') }}" class="text-light text-decoration-none link-animated">
                            <i class="fas fa-home me-2"></i>Accueil
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pages.apropos') }}" class="text-light text-decoration-none link-animated">
                            <i class="fas fa-info-circle me-2"></i>À propos
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pages.contact') }}" class="text-light text-decoration-none link-animated">
                            <i class="fas fa-envelope me-2"></i>Contact
                        </a>
                    </li>
                 </ul>
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="footer-copyright text-center p-3">
        © 2025 AfriCode : No code, No future - Tous droits réservés.
        <!-- Lien optionnel vers le créateur -->
         <!-- Développé par <a href="https://www.linkedin.com/in/mahoulolo-s%C3%A9bio-7a6a85285/" target="_blank" rel="noopener noreferrer">Mahoulolo SEBIO</a>. -->
    </div>
</footer>

<!-- Bootstrap JS Bundle (si nécessaire) -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->

</body>
</html>