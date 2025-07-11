<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#000000">
    <title>AfriCode : apprenez la cybersécurité, Python et plus encore</title>
    
    <meta name="title" content="AfriCode : apprenez la cybersécurité, Python et plus encore">
    <meta name="description" content="AfriCode est un programme de formation professionnelle qui façonne la main-d'œuvre de demain. Depuis 2024, nous avons eu un impact sur des milliers d'apprenants en Afrique.">
    
    <!-- Open Graph -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="AfriCode : apprenez la cybersécurité, Python et plus encore">
    <meta property="og:description" content="AfriCode est un programme de formation professionnelle qui façonne la main-d'œuvre de demain. Depuis 2024, nous avons eu un impact sur des milliers d'apprenants en Afrique.">
    <meta property="og:image" content="{{ asset('assets/images/landingBannerImage.png') }}">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <style>
        :root {
            --sfa-theme-primary-color: #6abf4b;
            --sfa-theme-primary-color-dark: #5cad3d;
            --sfa-color-green: #6abf4b;
            --sfa-color-dark-green: #487b32;
            --sfa-color-green-light: #8dce76;
            --sfa-color-red: #e2231a;
            --sfa-color-white: #ffffff;
            --sfa-color-gray-dark: #333333;
            --sfa-color-gray-light-1: #eaeaea;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 0;
        }

        .btn--primary {
            background-color: var(--sfa-color-green) !important;
            border-color: var(--sfa-color-green) !important;
            border-width: 2px !important;
            color: var(--sfa-color-gray-dark) !important;
            font-weight: 700 !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 5px;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn--primary:hover {
            background-color: var(--sfa-color-green-light) !important;
            border-color: var(--sfa-color-green-light) !important;
            color: var(--sfa-color-gray-dark) !important;
        }

        .btn--ghost {
            background-color: var(--sfa-color-white) !important;
            border-color: var(--sfa-color-green) !important;
            border-width: 2px !important;
            color: var(--sfa-color-gray-dark) !important;
            font-weight: 700 !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 5px;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn--ghost:hover {
            background-color: var(--sfa-color-green) !important;
            border-color: var(--sfa-color-green) !important;
            color: var(--sfa-color-gray-dark) !important;
        }

        .mastHead {
            background-color: #fff;
            border-bottom: 1px solid #e0e0e0;
            padding: 10px 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .header-panels {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header__logo img {
            height: 40px;
        }

        .header-panel--right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .sectionBannerBg {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 80px 0;
        }

        .sectionBannerImg {
            max-width: 100%;
            height: auto;
        }

        .marketBanner {
            background-color: #fff3cd;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #ffc107;
        }

        .fireIcon {
            width: 24px;
            height: 24px;
            margin-right: 10px;
        }

        .mBtitle {
            font-size: 16px;
            font-weight: 600;
            color: #856404;
            margin-bottom: 5px;
        }

        .mBdesc {
            font-size: 14px;
            color: #856404;
            margin: 0;
        }

        .sectionTitle {
            font-size: 3rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .sectionDesc {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .bannerCtaBlcok {
            margin-right: 15px;
            margin-bottom: 15px;
        }

        .sfa_stats_banner {
            background-color: #f8f9fa;
            padding: 40px 0;
        }

        .sfa_stats_bannerRow {
            display: flex;
            justify-content: space-around;
            align-items: center;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .stats_item {
            text-align: center;
            margin: 10px;
        }

        .stats_title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--sfa-color-green);
            margin-bottom: 5px;
        }

        .stats_desc {
            font-size: 0.9rem;
            color: #666;
        }

        .stats_separator {
            width: 1px;
            height: 40px;
            background-color: #ddd;
            margin: 0 20px;
        }

        .sectionQuote {
            padding: 60px 0;
            background-color: #fff;
            text-align: center;
        }

        .quoteTitle {
            font-size: 2rem;
            font-weight: 600;
            color: #333;
            max-width: 800px;
            margin: 0 auto 20px;
        }

        .horizontalSeparator {
            width: 100px;
            height: 3px;
            background-color: var(--sfa-color-green);
            margin: 20px auto;
        }

        .sectionSubjectArea {
            padding: 60px 0;
            background-color: #f8f9fa;
            text-align: center;
        }

        .subjectAreaBlock {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 40px 0;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .subjectAreaCard {
            background-color: white;
            padding: 30px 20px;
            border-radius: 10px;
            text-decoration: none;
            color: #333;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #e0e0e0;
        }

        .subjectAreaCard:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            color: #333;
            text-decoration: none;
        }

        .subjectAreaIcon {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px;
            display: block;
        }

        .subjectAreaTitle {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
        }

        .footer {
            background-color: #292929;
            color: white;
            padding: 40px 0 20px;
        }

        .footer a {
            color: #ccc;
            text-decoration: none;
        }

        .footer a:hover {
            color: white;
        }

        @media (max-width: 768px) {
            .sectionTitle {
                font-size: 2rem;
            }
            
            .sfa_stats_bannerRow {
                flex-direction: column;
            }
            
            .stats_separator {
                display: none;
            }
            
            .subjectAreaBlock {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }
    </style>
</head>

<body class="cui" id="sfa-marketplace-container" data-theme="light" dir="ltr">
    <div id="marketplace-container">
        <div class="tenant">
            <!-- Header -->
            <header class="mastHead header" id="styleguideheader" role="banner">
                <div class="container">
                    <div class="header-panels">
                        <div class="header-panel">
                            <a class="header__logo" href="{{ url('/') }}">
                                <img src="{{ asset('assets/images/logo-dark.png') }}" alt="AfriCode Home" title="AfriCode Home">
                            </a>
                        </div>
                        <div class="header-panel header-panel--right">
                            <a href="{{ route('courses.index') }}" class="header-item">
                                <span>Rechercher dans le catalogue</span>
                            </a>
                            @auth
                                <a href="{{ route('dashboard') }}" class="header-item">
                                    <span>Dashboard</span>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="header-item">
                                    <span>Connexion</span>
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="content" id="main-content">
                <!-- Hero Section -->
                <div class="sectionBannerBg">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-6 order-lg-2 d-flex justify-content-center">
                                <img src="{{ asset('assets/images/landingBannerImage.png') }}" class="sectionBannerImg" alt="Formation technologique en Afrique" loading="lazy">
                            </div>
                            <div class="col-lg-6 order-lg-1">
                                <div class="marketBanner">
                                    <div class="d-flex align-items-center">
                                        <span class="fireIcon">🔥</span>
                                        <div>
                                            <div class="mBtitle">Le marché de l'emploi de la tech recrute à tour de bras !</div>
                                            <div class="mBdesc">Découvrez pourquoi des milliers de personnes se sont inscrites à nos <a href="{{ route('courses.index') }}">cours gratuits de programmation</a></div>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="sectionTitle">Gagnez des compétences <br> et décrochez l'emploi de vos rêves.</h1>
                                <p class="sectionDesc">Cours en ligne gratuits. Formations en présentiel. Parcours menant à des certifications sur des sujets tels que la cybersécurité, les réseaux et le langage Python.</p>
                                <p class="sectionDesc">Tout est là. Êtes-vous prêt à démarrer, à réorienter ou à relancer votre carrière ?</p>
                                <div class="d-flex flex-wrap">
                                    <div class="bannerCtaBlcok">
                                        <a class="btn btn--primary" href="{{ route('courses.index') }}">Démarrer votre formation</a>
                                    </div>
                                    <div class="bannerCtaBlcok">
                                        <a class="btn btn--ghost" href="#domaines">Explorer les thèmes</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Banner -->
                <div class="sfa_stats_banner">
                    <div class="sfa_stats_bannerRow">
                        <div class="stats_item">
                            <div class="stats_title">5 000+</div>
                            <div class="stats_desc">étudiants depuis nos débuts en 2024</div>
                        </div>
                        <span class="stats_separator d-none d-md-block"></span>
                        <div class="stats_item">
                            <div class="stats_title">50+</div>
                            <div class="stats_desc">formateurs dans toute l'Afrique</div>
                        </div>
                        <span class="stats_separator d-none d-md-block"></span>
                        <div class="stats_item">
                            <div class="stats_title">25+</div>
                            <div class="stats_desc">entreprises partenaires</div>
                        </div>
                        <span class="stats_separator d-none d-md-block"></span>
                        <div class="stats_item">
                            <div class="stats_title">15</div>
                            <div class="stats_desc">pays africains où nous formons</div>
                        </div>
                        <span class="stats_separator d-none d-md-block"></span>
                        <div class="stats_item">
                            <div class="stats_title">92%</div>
                            <div class="stats_desc">des étudiants ont obtenu une opportunité de carrière</div>
                        </div>
                    </div>
                </div>

                <!-- Quote Section -->
                <section class="sectionQuote">
                    <div class="container">
                        <div class="quoteIconBorder">
                            <span style="font-size: 48px; color: var(--sfa-color-green);">"</span>
                        </div>
                        <h2 class="quoteTitle">Des milliers d'étudiants déclarent qu'AfriCode les a aidés à décrocher un emploi dans la tech.</h2>
                        <span class="horizontalSeparator"></span>
                        <p class="text-center" style="font-size: 16px; color: #666;">Parcourez les domaines ci-dessous pour vous ouvrir de nouveaux horizons.</p>
                    </div>
                </section>

                <!-- Subject Areas Section -->
                <section class="sectionSubjectArea" id="domaines">
                    <div class="container">
                        <h2 class="sectionTitle text-center mb-5">Domaines de formation</h2>
                        <div class="subjectAreaBlock">
                            <a class="subjectAreaCard" href="{{ route('courses.index', ['category' => 'cybersecurity']) }}">
                                <div class="subjectAreaIcon">🛡️</div>
                                <div class="subjectAreaTitle">Cybersécurité</div>
                            </a>
                            <a class="subjectAreaCard" href="{{ route('courses.index', ['category' => 'networking']) }}">
                                <div class="subjectAreaIcon">🌐</div>
                                <div class="subjectAreaTitle">Réseau</div>
                            </a>
                            <a class="subjectAreaCard" href="{{ route('courses.index', ['category' => 'ai']) }}">
                                <div class="subjectAreaIcon">🤖</div>
                                <div class="subjectAreaTitle">IA et science des données</div>
                            </a>
                            <a class="subjectAreaCard" href="{{ route('courses.index', ['category' => 'programming']) }}">
                                <div class="subjectAreaIcon">💻</div>
                                <div class="subjectAreaTitle">Programmation</div>
                            </a>
                            <a class="subjectAreaCard" href="{{ route('courses.index', ['category' => 'it']) }}">
                                <div class="subjectAreaIcon">⚙️</div>
                                <div class="subjectAreaTitle">Technologies de l'information</div>
                            </a>
                            <a class="subjectAreaCard" href="{{ route('courses.index', ['category' => 'digital']) }}">
                                <div class="subjectAreaIcon">📱</div>
                                <div class="subjectAreaTitle">Compétences numériques</div>
                            </a>
                            <a class="subjectAreaCard" href="{{ route('courses.index', ['category' => 'professional']) }}">
                                <div class="subjectAreaIcon">👔</div>
                                <div class="subjectAreaTitle">Compétences professionnelles</div>
                            </a>
                            <a class="subjectAreaCard" href="{{ route('courses.index', ['category' => 'mobile']) }}">
                                <div class="subjectAreaIcon">📱</div>
                                <div class="subjectAreaTitle">Développement mobile</div>
                            </a>
                        </div>
                        <div class="text-center mt-4">
                            <a class="btn btn--ghost" href="{{ route('courses.index') }}">Découvrir le catalogue complet</a>
                        </div>
                    </div>
                </section>
            </main>

            <!-- Footer -->
            <footer class="footer">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3 mb-4">
                            <img src="{{ asset('assets/images/logo-light.png') }}" alt="AfriCode" style="height: 40px; margin-bottom: 20px;">
                            <p style="color: #ccc; font-size: 14px;">
                                AfriCode est un programme de formation professionnelle qui façonne la main-d'œuvre technologique africaine de demain.
                            </p>
                        </div>
                        <div class="col-md-3 mb-4">
                            <h5 style="color: white; margin-bottom: 15px;">Formation</h5>
                            <ul style="list-style: none; padding: 0;">
                                <li style="margin-bottom: 8px;"><a href="{{ route('courses.index') }}">Catalogue de cours</a></li>
                                <li style="margin-bottom: 8px;"><a href="{{ route('instructors.index') }}">Nos instructeurs</a></li>
                                <li style="margin-bottom: 8px;"><a href="#">Certifications</a></li>
                            </ul>
                        </div>
                        <div class="col-md-3 mb-4">
                            <h5 style="color: white; margin-bottom: 15px;">Entreprises</h5>
                            <ul style="list-style: none; padding: 0;">
                                <li style="margin-bottom: 8px;"><a href="#">Formations en entreprise</a></li>
                                <li style="margin-bottom: 8px;"><a href="#">Partenariats</a></li>
                                <li style="margin-bottom: 8px;"><a href="#">Recrutement</a></li>
                            </ul>
                        </div>
                        <div class="col-md-3 mb-4">
                            <h5 style="color: white; margin-bottom: 15px;">À propos</h5>
                            <ul style="list-style: none; padding: 0;">
                                <li style="margin-bottom: 8px;"><a href="{{ route('about') }}">Notre mission</a></li>
                                <li style="margin-bottom: 8px;"><a href="{{ route('contact') }}">Contact</a></li>
                                <li style="margin-bottom: 8px;"><a href="#">Blog</a></li>
                            </ul>
                        </div>
                    </div>
                    <hr style="border-color: #555; margin: 30px 0 20px;">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <p style="color: #ccc; margin: 0; font-size: 14px;">
                                © {{ date('Y') }} AfriCode. Tous droits réservés.
                            </p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div style="font-size: 24px;">
                                <a href="#" style="color: #ccc; margin: 0 10px;">📧</a>
                                <a href="#" style="color: #ccc; margin: 0 10px;">🐦</a>
                                <a href="#" style="color: #ccc; margin: 0 10px;">📘</a>
                                <a href="#" style="color: #ccc; margin: 0 10px;">💼</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
    <!-- Barre de navigation supérieure -->
    <div class="top-nav-bar">
        <div class="container-fluid">
            <div class="row justify-content-between align-items-center">
                <div class="col-auto">
                    <div class="nav-links d-flex">
                        <a href="{{ route('login') }}" class="nav-link active">AfriCode</a>
                        <a href="#" class="nav-link inactive">Partenaires</a>
                        <a href="#" class="nav-link inactive">Entreprises</a>
                    </div>
                </div>
                <div class="col-auto d-none d-md-block">
                    <div class="support-links d-flex align-items-center">
                        <a href="#" class="support-link">Support</a>
                        <a href="#" class="support-link">Contact</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hero Section Principal -->
    <section class="hero-main">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1 class="hero-title">
                            Façonnez l'avenir numérique de l'<span class="text-highlight">Afrique</span>
                        </h1>
                        <p class="hero-subtitle">
                            AfriCode est un programme de formation professionnelle qui façonne la main-d'œuvre de demain. 
                            Depuis 2024, nous avons pour mission de démocratiser l'accès à l'éducation technologique en Afrique.
                        </p>
                        <div class="hero-actions">
                            <a href="{{ route('register') }}" class="btn btn-primary btn-hero">
                                Commencer maintenant
                                <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                            <a href="#courses" class="btn btn-outline-secondary btn-hero">
                                Explorer les cours
                            </a>
                        </div>
                        
                        <!-- Impact Stats -->
                        <div class="impact-stats mt-5">
                            <div class="row">
                                <div class="col-4">
                                    <div class="stat-item">
                                        <h3 class="stat-number">500+</h3>
                                        <p class="stat-label">Étudiants formés</p>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="stat-item">
                                        <h3 class="stat-number">15</h3>
                                        <p class="stat-label">Pays couverts</p>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="stat-item">
                                        <h3 class="stat-number">95%</h3>
                                        <p class="stat-label">Taux de réussite</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-visual">
                        <div class="visual-container">
                            <!-- Illustration principale -->
                            <div class="main-visual">
                                <svg width="500" height="400" viewBox="0 0 500 400" class="hero-svg">
                                    <!-- Fond gradiant -->
                                    <defs>
                                        <linearGradient id="bgGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" style="stop-color:var(--africode-primary);stop-opacity:0.1"/>
                                            <stop offset="100%" style="stop-color:var(--africode-secondary);stop-opacity:0.1"/>
                                        </linearGradient>
                                        <linearGradient id="primaryGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" style="stop-color:var(--africode-primary)"/>
                                            <stop offset="100%" style="stop-color:var(--africode-highlight-green)"/>
                                        </linearGradient>
                                    </defs>
                                    
                                    <!-- Formes géométriques abstraites -->
                                    <circle cx="100" cy="100" r="50" fill="url(#bgGradient)" opacity="0.6"/>
                                    <rect x="350" y="50" width="80" height="80" rx="12" fill="var(--africode-secondary)" opacity="0.3"/>
                                    <polygon points="200,300 250,200 300,300" fill="var(--africode-accent-red)" opacity="0.2"/>
                                    
                                    <!-- Code elements -->
                                    <rect x="150" y="150" width="200" height="120" rx="8" fill="var(--africode-white)" stroke="var(--africode-gray-medium)" stroke-width="2"/>
                                    <rect x="160" y="160" width="180" height="8" rx="4" fill="url(#primaryGradient)"/>
                                    <rect x="160" y="180" width="120" height="6" rx="3" fill="var(--africode-gray-medium)"/>
                                    <rect x="160" y="195" width="150" height="6" rx="3" fill="var(--africode-gray-medium)"/>
                                    <rect x="160" y="210" width="100" height="6" rx="3" fill="var(--africode-gray-medium)"/>
                                    
                                    <!-- Afrique silhouette stylisée -->
                                    <path d="M380 200 C 385 180, 400 175, 415 185 C 425 195, 430 210, 425 225 C 420 240, 410 250, 400 255 C 390 250, 385 235, 380 220 Z" 
                                          fill="url(#primaryGradient)" opacity="0.8"/>
                                </svg>
                            </div>
                            
                            <!-- Badges flottants -->
                            <div class="floating-badges">
                                <div class="badge-item badge-1">
                                    <i class="fab fa-python"></i>
                                    <span>Python</span>
                                </div>
                                <div class="badge-item badge-2">
                                    <i class="fab fa-js-square"></i>
                                    <span>JavaScript</span>
                                </div>
                                <div class="badge-item badge-3">
                                    <i class="fas fa-shield-alt"></i>
                                    <span>Cybersécurité</span>
                                </div>
                                <div class="badge-item badge-4">
                                    <i class="fas fa-robot"></i>
                                    <span>IA</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Programmes Principaux -->
    <section class="main-programs animate-on-scroll" id="courses">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Nos programmes phares</h2>
                <p class="section-subtitle">
                    Développez des compétences recherchées avec nos parcours certifiants
                </p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="program-card animate-on-scroll">
                        <div class="program-icon">
                            <i class="fab fa-python"></i>
                        </div>
                        <h3 class="program-title">Python pour l'analyse de données</h3>
                        <p class="program-description">
                            Maîtrisez Python et ses librairies pour devenir un expert en analyse de données et machine learning.
                        </p>
                        <div class="program-meta">
                            <span class="duration"><i class="far fa-clock"></i> 12 semaines</span>
                            <span class="level"><i class="fas fa-signal"></i> Débutant</span>
                        </div>
                        <a href="#" class="btn btn-outline-primary w-100 mt-3">En savoir plus</a>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="program-card featured animate-on-scroll">
                        <div class="featured-badge">
                            <i class="fas fa-star"></i>
                            Populaire
                        </div>
                        <div class="program-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="program-title">Cybersécurité essentiels</h3>
                        <p class="program-description">
                            Protégez les infrastructures numériques avec nos formations en cybersécurité adaptées au contexte africain.
                        </p>
                        <div class="program-meta">
                            <span class="duration"><i class="far fa-clock"></i> 16 semaines</span>
                            <span class="level"><i class="fas fa-signal"></i> Intermédiaire</span>
                        </div>
                        <a href="#" class="btn btn-primary w-100 mt-3">Commencer</a>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="program-card animate-on-scroll">
                        <div class="program-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3 class="program-title">Développement mobile</h3>
                        <p class="program-description">
                            Créez des applications mobiles innovantes avec React Native et Flutter pour l'écosystème africain.
                        </p>
                        <div class="program-meta">
                            <span class="duration"><i class="far fa-clock"></i> 20 semaines</span>
                            <span class="level"><i class="fas fa-signal"></i> Avancé</span>
                        </div>
                        <a href="#" class="btn btn-outline-primary w-100 mt-3">En savoir plus</a>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5">
                <a href="#" class="btn btn-secondary btn-lg">
                    Voir tous les programmes
                    <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Section Pourquoi AfriCode -->
    <section class="why-africode animate-on-scroll">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="why-content">
                        <h2 class="section-title">Pourquoi choisir AfriCode ?</h2>
                        <div class="feature-list">
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div class="feature-content">
                                    <h4>Formation pratique</h4>
                                    <p>Apprenez par la pratique avec des projets réels et des cas d'usage africains.</p>
                                </div>
                            </div>
                            
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="feature-content">
                                    <h4>Communauté active</h4>
                                    <p>Rejoignez une communauté de développeurs passionnés à travers l'Afrique.</p>
                                </div>
                            </div>
                            
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-certificate"></i>
                                </div>
                                <div class="feature-content">
                                    <h4>Certifications reconnues</h4>
                                    <p>Obtenez des certifications valorisées par les entreprises africaines et internationales.</p>
                                </div>
                            </div>
                            
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div class="feature-content">
                                    <h4>Accompagnement carrière</h4>
                                    <p>Bénéficiez d'un accompagnement personnalisé pour votre insertion professionnelle.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="why-visual">
                        <div class="placeholder-image">
                            <svg width="100%" height="400" viewBox="0 0 600 400" class="img-fluid rounded-lg">
                                <defs>
                                    <linearGradient id="placeholderGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:var(--africode-primary);stop-opacity:0.8"/>
                                        <stop offset="100%" style="stop-color:var(--africode-secondary);stop-opacity:0.8"/>
                                    </linearGradient>
                                </defs>
                                <rect width="100%" height="100%" fill="url(#placeholderGradient)" rx="12"/>
                                <g transform="translate(300,200)">
                                    <circle r="50" fill="white" opacity="0.2"/>
                                    <path d="M-30,-10 L-10,-30 L30,10 L10,30 Z" fill="white" opacity="0.3"/>
                                    <circle r="20" fill="white" opacity="0.4"/>
                                    <text x="0" y="100" text-anchor="middle" fill="white" font-size="18" font-weight="600">Étudiants AfriCode</text>
                                </g>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Témoignages -->
    <section class="testimonials animate-on-scroll">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Ce que disent nos étudiants</h2>
                <p class="section-subtitle">
                    Découvrez les témoignages de ceux qui ont transformé leur carrière avec AfriCode
                </p>
            </div>
            
            <div class="testimonials-slider">
                <div class="row g-4">
                    @php
                        $testimonials = [
                            [
                                'name' => 'Aminata Koné',
                                'role' => 'Développeuse Full Stack',
                                'company' => 'Tech Abidjan',
                                'image' => 'https://randomuser.me/api/portraits/women/44.jpg',
                                'text' => "Grâce à AfriCode, j'ai pu maîtriser React et Node.js en 6 mois. Aujourd'hui, je travaille dans une startup prometteuse à Abidjan.",
                                'rating' => 5
                            ],
                            [
                                'name' => 'Kwame Asante',
                                'role' => 'Expert Cybersécurité',
                                'company' => 'SecureNet Ghana',
                                'image' => 'https://randomuser.me/api/portraits/men/32.jpg',
                                'text' => "Le programme de cybersécurité d'AfriCode m'a donné les compétences nécessaires pour protéger les infrastructures de mon pays.",
                                'rating' => 5
                            ],
                            [
                                'name' => 'Fatou Diop',
                                'role' => 'Data Scientist',
                                'company' => 'Analytics Dakar',
                                'image' => 'https://randomuser.me/api/portraits/women/68.jpg',
                                'text' => "L'approche pratique d'AfriCode m'a permis de devenir data scientist et d'analyser des données pour l'agriculture sénégalaise.",
                                'rating' => 5
                            ]
                        ];
                    @endphp

                    @foreach($testimonials as $testimonial)
                    <div class="col-lg-4">
                        <div class="testimonial-card">
                            <div class="testimonial-header">
                                <img src="{{ $testimonial['image'] }}" alt="{{ $testimonial['name'] }}" class="testimonial-avatar">
                                <div class="testimonial-info">
                                    <h5 class="testimonial-name">{{ $testimonial['name'] }}</h5>
                                    <p class="testimonial-role">{{ $testimonial['role'] }}</p>
                                    <p class="testimonial-company">{{ $testimonial['company'] }}</p>
                                </div>
                            </div>
                            <div class="testimonial-content">
                                <div class="rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= $testimonial['rating'] ? '' : ' opacity-25' }}"></i>
                                    @endfor
                                </div>
                                <p class="testimonial-text">"{{ $testimonial['text'] }}"</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Section Call to Action -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-card">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="cta-title">Prêt à façonner votre avenir numérique ?</h2>
                        <p class="cta-subtitle">
                            Rejoignez des milliers d'étudiants qui transforment leur carrière avec AfriCode. 
                            Commencez votre parcours dès aujourd'hui.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <div class="cta-actions">
                            <a href="{{ route('register') }}" class="btn btn-white btn-lg mb-2">
                                Inscription gratuite
                            </a>
                            <br>
                            <a href="#" class="cta-link">
                                Parler à un conseiller
                                <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('styles')
@vite('resources/css/homepage.css')
<style>
    /* Variables globales AfriCode */
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

    /* Reset et base */
    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        line-height: 1.6;
        color: var(--africode-dark-text);
    }

    /* Barre de navigation supérieure */
    .top-nav-bar {
        background-color: #434343;
        padding: 8px 0;
        font-size: 12px;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1030;
    }

    .nav-links {
        gap: 0;
    }

    .nav-link {
        padding: 6px 16px;
        text-decoration: none;
        font-weight: 700;
        font-size: 11px;
        transition: var(--africode-transition);
        border-radius: 0;
    }

    .nav-link.active {
        background-color: var(--africode-white);
        color: #000;
    }

    .nav-link.inactive {
        background-color: #e3e3e3;
        color: #333;
    }

    .nav-link.inactive:hover {
        background-color: #d0d0d0;
    }

    .support-links {
        gap: 20px;
    }

    .support-link {
        color: var(--africode-white);
        text-decoration: none;
        font-size: 11px;
        font-weight: 500;
    }

    .support-link:hover {
        color: var(--africode-gray-medium);
    }

    /* Hero Section */
    .hero-main {
        background: linear-gradient(135deg, 
            rgba(30, 163, 139, 0.05) 0%, 
            rgba(255, 142, 42, 0.05) 50%, 
            rgba(39, 179, 113, 0.05) 100%);
        padding-top: 80px;
        position: relative;
        overflow: hidden;
    }

    .hero-main::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: var(--africode-bg-pattern);
        opacity: 0.3;
        pointer-events: none;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-title {
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 800;
        line-height: 1.1;
        margin-bottom: 2rem;
        color: var(--africode-dark-text);
    }

    .text-highlight {
        background: var(--africode-gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-subtitle {
        font-size: 1.25rem;
        line-height: 1.6;
        margin-bottom: 2.5rem;
        color: var(--africode-gray-dark);
        max-width: 600px;
    }

    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 3rem;
    }

    .btn-hero {
        padding: 1rem 2rem;
        font-weight: 600;
        border-radius: var(--africode-border-radius);
        transition: var(--africode-transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .btn-primary {
        background: var(--africode-gradient-primary);
        border: none;
        color: var(--africode-white);
        box-shadow: var(--africode-shadow-md);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--africode-shadow-lg);
    }

    .btn-outline-secondary {
        border: 2px solid var(--africode-gray-medium);
        color: var(--africode-dark-text);
        background: transparent;
    }

    .btn-outline-secondary:hover {
        background: var(--africode-gray-light);
        border-color: var(--africode-primary);
        color: var(--africode-primary);
    }

    /* Impact Stats */
    .impact-stats {
        border-top: 1px solid var(--africode-gray-medium);
        padding-top: 2rem;
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--africode-primary);
        margin-bottom: 0.5rem;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.9rem;
        color: var(--africode-gray-dark);
        margin: 0;
    }

    /* Hero Visual */
    .hero-visual {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 500px;
    }

    .visual-container {
        position: relative;
        width: 100%;
        height: 100%;
    }

    .hero-svg {
        width: 100%;
        height: 100%;
        max-width: 500px;
    }

    .floating-badges {
        position: absolute;
        inset: 0;
        pointer-events: none;
    }

    .badge-item {
        position: absolute;
        background: var(--africode-white);
        border-radius: var(--africode-border-radius);
        padding: 0.75rem 1rem;
        box-shadow: var(--africode-shadow-md);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        animation: float 3s ease-in-out infinite;
    }

    .badge-1 {
        top: 10%;
        left: 10%;
        animation-delay: 0s;
        color: #3776ab;
    }

    .badge-2 {
        top: 20%;
        right: 15%;
        animation-delay: 1s;
        color: #f7df1e;
        background: #333;
    }

    .badge-3 {
        bottom: 30%;
        left: 5%;
        animation-delay: 2s;
        color: var(--africode-accent-red);
    }

    .badge-4 {
        bottom: 15%;
        right: 10%;
        animation-delay: 1.5s;
        color: var(--africode-primary);
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    /* Sections */
    .main-programs {
        padding: 5rem 0;
        background: var(--africode-gray-light);
    }

    .section-header {
        margin-bottom: 4rem;
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--africode-dark-text);
        margin-bottom: 1rem;
    }

    .section-subtitle {
        font-size: 1.125rem;
        color: var(--africode-gray-dark);
        max-width: 600px;
        margin: 0 auto;
    }

    /* Program Cards */
    .program-card {
        background: var(--africode-white);
        border-radius: var(--africode-border-radius);
        padding: 2rem;
        height: 100%;
        box-shadow: var(--africode-shadow-sm);
        transition: var(--africode-transition);
        position: relative;
        overflow: hidden;
    }

    .program-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--africode-shadow-lg);
    }

    .program-card.featured {
        border: 2px solid var(--africode-primary);
        transform: scale(1.05);
    }

    .featured-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: var(--africode-gradient-primary);
        color: var(--africode-white);
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .program-icon {
        width: 60px;
        height: 60px;
        background: var(--africode-gradient-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--africode-white);
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .program-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--africode-dark-text);
    }

    .program-description {
        color: var(--africode-gray-dark);
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }

    .program-meta {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        font-size: 0.875rem;
        color: var(--africode-gray-dark);
    }

    .duration, .level {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* Why AfriCode */
    .why-africode {
        padding: 5rem 0;
        background: var(--africode-white);
    }

    .feature-list {
        margin-top: 2rem;
    }

    .feature-item {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        padding: 1.5rem;
        border-radius: var(--africode-border-radius);
        background: var(--africode-gray-light);
        transition: var(--africode-transition);
    }

    .feature-item:hover {
        background: rgba(30, 163, 139, 0.05);
        transform: translateX(5px);
    }

    .feature-icon {
        width: 50px;
        height: 50px;
        background: var(--africode-gradient-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--africode-white);
        flex-shrink: 0;
    }

    .feature-content h4 {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--africode-dark-text);
    }

    .feature-content p {
        color: var(--africode-gray-dark);
        margin: 0;
    }

    .why-visual img {
        border-radius: var(--africode-border-radius);
        box-shadow: var(--africode-shadow-md);
    }

    /* Testimonials */
    .testimonials {
        padding: 5rem 0;
        background: var(--africode-gray-light);
    }

    .testimonial-card {
        background: var(--africode-white);
        border-radius: var(--africode-border-radius);
        padding: 2rem;
        height: 100%;
        box-shadow: var(--africode-shadow-sm);
        transition: var(--africode-transition);
    }

    .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--africode-shadow-lg);
    }

    .testimonial-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .testimonial-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
    }

    .testimonial-info h5 {
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: var(--africode-dark-text);
    }

    .testimonial-role {
        font-size: 0.875rem;
        color: var(--africode-primary);
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .testimonial-company {
        font-size: 0.75rem;
        color: var(--africode-gray-dark);
        margin: 0;
    }

    .rating {
        color: #FFD700;
        margin-bottom: 1rem;
    }

    .testimonial-text {
        color: var(--africode-gray-dark);
        line-height: 1.6;
        font-style: italic;
    }

    /* CTA Section */
    .cta-section {
        padding: 5rem 0;
        background: var(--africode-gradient-primary);
    }

    .cta-card {
        background: rgba(255, 255, 255, 0.1);
        border-radius: var(--africode-border-radius);
        padding: 3rem;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .cta-title {
        color: var(--africode-white);
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .cta-subtitle {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.125rem;
        line-height: 1.6;
        margin-bottom: 0;
    }

    .btn-white {
        background: var(--africode-white);
        color: var(--africode-primary);
        border: none;
        padding: 1rem 2rem;
        border-radius: var(--africode-border-radius);
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: var(--africode-transition);
        box-shadow: var(--africode-shadow-md);
    }

    .btn-white:hover {
        transform: translateY(-2px);
        box-shadow: var(--africode-shadow-lg);
        color: var(--africode-primary);
    }

    .cta-link {
        color: var(--africode-white);
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        transition: var(--africode-transition);
    }

    .cta-link:hover {
        color: rgba(255, 255, 255, 0.8);
        transform: translateX(5px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .top-nav-bar {
            padding: 4px 0;
        }
        
        .nav-link {
            padding: 4px 8px;
            font-size: 10px;
        }
        
        .support-links {
            display: none !important;
        }
        
        .hero-main {
            padding-top: 60px;
        }
        
        .hero-actions {
            flex-direction: column;
        }
        
        .btn-hero {
            justify-content: center;
        }
        
        .impact-stats {
            margin-top: 2rem;
        }
        
        .stat-number {
            font-size: 2rem;
        }
        
        .hero-visual {
            height: 300px;
            margin-top: 2rem;
        }
        
        .floating-badges {
            display: none;
        }
        
        .program-card.featured {
            transform: none;
        }
        
        .section-title {
            font-size: 2rem;
        }
        
        .cta-title {
            font-size: 2rem;
        }
        
        .cta-actions {
            text-align: center;
            margin-top: 2rem;
        }
    }

    @media (max-width: 576px) {
        .hero-title {
            font-size: 2rem;
        }
        
        .hero-subtitle {
            font-size: 1rem;
        }
        
        .program-card {
            padding: 1.5rem;
        }
        
        .feature-item {
            padding: 1rem;
        }
        
        .testimonial-card {
            padding: 1.5rem;
        }
        
        .cta-card {
            padding: 2rem;
        }
    }
</style>
@endpush

@push('scripts')
@vite('resources/js/homepage.js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Animation des compteurs
        const statNumbers = document.querySelectorAll(".stat-number");
        
        const animateCounter = (element, target) => {
            let current = 0;
            const increment = target / 100;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target + (element.textContent.includes('%') ? '%' : '+');
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current) + (element.textContent.includes('%') ? '%' : '+');
                }
            }, 20);
        };

        // Observer pour détecter quand les éléments entrent dans la vue
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const target = entry.target;
                    const value = parseInt(target.textContent);
                    animateCounter(target, value);
                    observer.unobserve(target);
                }
            });
        }, {
            threshold: 0.5
        });

        statNumbers.forEach(stat => {
            observer.observe(stat);
        });

        // Animation d'apparition progressive
        const animateOnScroll = () => {
            const elements = document.querySelectorAll('.program-card, .feature-item, .testimonial-card');
            
            elements.forEach((element, index) => {
                const elementTop = element.getBoundingClientRect().top;
                const elementVisible = 150;
                
                if (elementTop < window.innerHeight - elementVisible) {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }
            });
        };

        // Initialiser les éléments avec opacity 0
        document.querySelectorAll('.program-card, .feature-item, .testimonial-card').forEach(element => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px)';
            element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        });

        // Écouter le scroll
        window.addEventListener('scroll', animateOnScroll);
        animateOnScroll(); // Appeler une fois au chargement

        // Smooth scroll pour les liens d'ancrage
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Effet parallax léger pour le hero
        const hero = document.querySelector('.hero-main');
        if (hero) {
            window.addEventListener('scroll', () => {
                const scrolled = window.pageYOffset;
                const parallax = scrolled * 0.5;
                hero.style.transform = `translateY(${parallax}px)`;
            });
        }
    });
</script>
@endpush