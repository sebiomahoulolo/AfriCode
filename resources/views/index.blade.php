@extends('layouts.layout')

@section('title', 'AfriCode - Cours en ligne : Apprenez ce que vous voulez, à votre rythme')

{{-- Section pour les meta tags spécifiques à la page d'accueil --}}
@section('meta_tags')
    <meta name="description" content="AfriCode est une plateforme d'apprentissage en ligne avec plus de 500 cours. Apprenez la programmation, le développement web, mobile, l'IA et plus encore.">
    <meta name="keywords" content="cours en ligne, programmation, développement web, afrique, coder, apprendre à coder, formation informatique">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="AfriCode - Cours en ligne : Apprenez ce que vous voulez, à votre rythme">
    <meta property="og:description" content="AfriCode est une plateforme d'apprentissage en ligne avec plus de 500 cours. Apprenez la programmation, le développement web, mobile, l'IA et plus encore.">
    <meta property="og:image" content="{{ asset('assets/images/africode-og-image.jpg') }}"> {{-- Créez une image OG --}}

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/') }}">
    <meta property="twitter:title" content="AfriCode - Cours en ligne : Apprenez ce que vous voulez, à votre rythme">
    <meta property="twitter:description" content="AfriCode est une plateforme d'apprentissage en ligne avec plus de 500 cours. Apprenez la programmation, le développement web, mobile, l'IA et plus encore.">
    <meta property="twitter:image" content="{{ asset('assets/images/africode-og-image.jpg') }}">

    {{-- Schema.org JSON-LD --}}
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "url": "{{ url('/') }}",
      "name": "AfriCode",
      "description": "AfriCode est une plateforme d'apprentissage en ligne avec plus de 500 cours. Apprenez la programmation, le développement web, mobile, l'IA et plus encore.",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ url('/search?q={search_term_string}') }}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
@endsection

@section('content')
    <!-- Hero Section avec recherche (style Udemy) -->
    <div class="hero-section bg-dark position-relative overflow-hidden">
        <div class="container py-5">
            <div class="row min-vh-60 align-items-center"> {{-- min-vh-60 pour plus de hauteur --}}
                <div class="col-lg-7 col-md-9 py-5 text-white">
                    <h1 class="display-3 fw-bold mb-4 ud-heading-serif">Des compétences qui vous donnent confiance</h1>
                    
                    <!-- Barre de recherche style Udemy -->
                    <div class="search-container my-4 position-relative">
                        <form action="" method="GET" class="d-flex"> {{-- Ajoutez votre route de recherche --}}
                            <input class="form-control form-control-lg py-3 ps-4 pe-5 ud-text-input" type="search" name="q" placeholder="Que souhaitez-vous apprendre?" aria-label="Search">
                            <button class="btn btn-primary position-absolute end-0 top-0 h-100 px-4 d-flex align-items-center justify-content-center" type="submit" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
                {{-- Image de fond optionnelle pour le hero, gérée en CSS ou via un élément img si besoin --}}
            </div>
        </div>
        {{-- Image de droite, peut être gérée par un pseudo-élément ou un div positionné --}}
         <div class="hero-image-decoration position-absolute end-0 top-0 bottom-0 d-none d-lg-block" style="width: 35%; background-image: url('{{ asset('assets/images/hero-decoration.png') }}'); background-size: contain; background-repeat: no-repeat; background-position: center right; opacity: 0.8;"></div>

    </div>

    <!-- Barre de statistiques / Confiance (Trust Bar) -->
    <div class="trust-bar bg-light py-4 border-bottom">
        <div class="container">
            <div class="row text-center justify-content-around">
                <div class="col-6 col-md-auto mb-3 mb-md-0">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="fas fa-users fa-2x me-2 text-primary"></i>
                        <div>
                            <strong class="d-block fs-5 counter" data-min="90" data-max="325">325</strong>
                            <small class="text-muted">Apprenants actifs</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-auto mb-3 mb-md-0">
                     <div class="d-flex align-items-center justify-content-center">
                        <i class="fas fa-laptop-code fa-2x me-2 text-success"></i>
                        <div>
                            <strong class="d-block fs-5 counter" data-min="45" data-max="120">120</strong>
                            <small class="text-muted">Cours disponibles</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-auto">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="fas fa-certificate fa-2x me-2 text-warning"></i>
                        <div>
                            <strong class="d-block fs-5"><span class="counter" data-min="45" data-max="98">98</span>%</strong>
                            <small class="text-muted">Taux de satisfaction</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-auto">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="fas fa-chalkboard-teacher fa-2x me-2 text-info"></i>
                        <div>
                            <strong class="d-block fs-5 counter" data-min="2" data-max="32">32</strong>
                            <small class="text-muted">Instructeurs experts</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Catégories populaires -->
    <!-- <div class="container my-5">
        <h2 class="fw-bold mb-4 ud-heading-serif">Catégories populaires</h2>
        
        <div class="categories-slider">
            @if(isset($categories) && $categories->isNotEmpty())
                @foreach($categories as $category)
                <a href="{{ route('courses.index', ['category' => $category->slug]) }}" class="category-btn">
                    {{ $category->name }} <span class="course-count">{{ $category->courses_count }}</span>
                </a>
                @endforeach
            @else
                @php
                    $dummyCategories = [
                        'Développement Web', 'Mobile', 'Base de données', 'IA & ML', 
                        'UI/UX Design', 'Cloud Computing', 'Cybersécurité', 
                        'Data Science', 'Réseaux', 'DevOps', 'Blockchain'
                    ];
                @endphp
                @foreach($dummyCategories as $category)
                <a href="#" class="category-btn">
                    {{ $category }} <span class="course-count">{{ rand(10, 50) }}</span>
                </a>
                @endforeach
            @endif
        </div>
        <div class="slider-controls">
            <button class="control-btn prev-btn"><i class="fas fa-chevron-left"></i></button>
            <button class="control-btn next-btn"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div> -->

    <!-- Courses Populaires (style Udemy) -->
    <!-- <div class="bg-light py-5">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-3 mb-md-0 ud-heading-serif">Cours les plus consultés</h2>
                <a href="{{ route('courses.index') }}" class="btn btn-outline-primary">Voir tous les cours</a>
            </div>
            
            <div class="row g-4">
                @if(isset($popularCourses) && $popularCourses->isNotEmpty())
                    @foreach($popularCourses as $course)
                    <div class="col-12 col-sm-6 col-lg-3 d-flex">
                        <div class="card course-card flex-fill">
                            <a href="{{ route('courses.show', $course['slug']) }}" class="text-decoration-none text-dark">
                                <div class="course-img-container position-relative">
                                    <img src="{{ asset($course['img']) }}" class="card-img-top" alt="{{ $course['title'] }}">
                                    @if(isset($course['bestseller']) && $course['bestseller'])
                                    <span class="badge bg-warning text-dark position-absolute bottom-0 start-0 m-2 bestseller-badge">Meilleure vente</span>
                                    @elseif(isset($course['new']) && $course['new'])
                                    <span class="badge bg-info text-white position-absolute bottom-0 start-0 m-2">Nouveau</span>
                                    @endif
                                </div>
                                <div class="card-body d-flex flex-column p-3">
                                    <h5 class="card-title fw-bold ud-text-md mb-1 course-title-ellipsis">{{ $course['title'] }}</h5>
                                    <p class="card-text text-muted small mb-2 course-desc-ellipsis">{{ $course['desc'] }}</p>
                                    <div class="ud-text-xs text-muted mb-2">
                                        {{ $course['instructor'] }}
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="text-warning me-1 fw-bold ud-text-sm">{{ number_format($course['rating'], 1) }}</span>
                                        <div class="text-warning me-1 star-rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($course['rating']))
                                                    <i class="fas fa-star fa-xs"></i>
                                                @elseif ($i - 0.5 <= $course['rating'])
                                                    <i class="fas fa-star-half-alt fa-xs"></i>
                                                @else
                                                    <i class="far fa-star fa-xs"></i> {{-- Empty star --}}
                                                @endif
                                            @endfor
                                        </div>
                                        <small class="text-muted ud-text-xs">({{ $course['reviews'] }})</small>
                                    </div>
                                    <div class="mt-auto">
                                        <div class="d-flex align-items-center">
                                            <strong class="fs-5 text-dark me-2 ud-heading-md">{{ $course['price'] }} €</strong>
                                            @if(isset($course['old_price']))
                                            <span class="text-decoration-line-through text-muted ud-text-sm">{{ $course['old_price'] }} €</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endforeach
                @else
                    @php
                    $popularCourses = [
                        ['img' => 'assets/images/th.jpeg', 'title' => 'JavaScript Moderne de A à Z', 'desc' => 'Maîtrisez ES6+ et créez des applications interactives.', 'instructor' => 'Jean Dupont', 'rating' => 4.8, 'reviews' => 128, 'price' => 19.99, 'old_price' => 79.99, 'bestseller' => true, 'slug' => 'javascript-moderne-a-z'],
                        ['img' => 'assets/images/th (4).jpeg', 'title' => 'Python pour Débutants et Avancés', 'desc' => 'Apprenez Python, de la syntaxe aux projets complets.', 'instructor' => 'Marie Leclerc', 'rating' => 4.6, 'reviews' => 256, 'price' => 24.99, 'old_price' => 99.99, 'slug' => 'python-debutants-avances'],
                        ['img' => 'assets/images/télécharger.jpeg', 'title' => 'Laravel 10 : Le Guide Complet', 'desc' => 'Construisez des applications web robustes avec Laravel.', 'instructor' => 'Ahmed Bamba', 'rating' => 4.9, 'reviews' => 87, 'price' => 29.99, 'old_price' => 119.99, 'new' => true, 'slug' => 'laravel-10-guide-complet'],
                        ['img' => 'assets/images/th.jpeg', 'title' => 'React & Redux : Créez des UI Modernes', 'desc' => 'Développement d\'interfaces utilisateur dynamiques.', 'instructor' => 'Sarah Koné', 'rating' => 4.7, 'reviews' => 175, 'price' => 24.99, 'old_price' => 99.99, 'slug' => 'react-redux-ui-modernes'],
                    ];
                    @endphp

                    @foreach($popularCourses as $course)
                    <div class="col-12 col-sm-6 col-lg-3 d-flex">
                        <div class="card course-card flex-fill">
                            <a href="{{ route('courses.show', $course['slug']) }}" class="text-decoration-none text-dark">
                                <div class="course-img-container position-relative">
                                    <img src="{{ asset($course['img']) }}" class="card-img-top" alt="{{ $course['title'] }}">
                                    @if(isset($course['bestseller']) && $course['bestseller'])
                                    <span class="badge bg-warning text-dark position-absolute bottom-0 start-0 m-2 bestseller-badge">Meilleure vente</span>
                                    @elseif(isset($course['new']) && $course['new'])
                                    <span class="badge bg-info text-white position-absolute bottom-0 start-0 m-2">Nouveau</span>
                                    @endif
                                </div>
                                <div class="card-body d-flex flex-column p-3">
                                    <h5 class="card-title fw-bold ud-text-md mb-1 course-title-ellipsis">{{ $course['title'] }}</h5>
                                    <p class="card-text text-muted small mb-2 course-desc-ellipsis">{{ $course['desc'] }}</p>
                                    <div class="ud-text-xs text-muted mb-2">
                                        {{ $course['instructor'] }}
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="text-warning me-1 fw-bold ud-text-sm">{{ number_format($course['rating'], 1) }}</span>
                                        <div class="text-warning me-1 star-rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($course['rating']))
                                                    <i class="fas fa-star fa-xs"></i>
                                                @elseif ($i - 0.5 <= $course['rating'])
                                                    <i class="fas fa-star-half-alt fa-xs"></i>
                                                @else
                                                    <i class="far fa-star fa-xs"></i> {{-- Empty star --}}
                                                @endif
                                            @endfor
                                        </div>
                                        <small class="text-muted ud-text-xs">({{ $course['reviews'] }})</small>
                                    </div>
                                    <div class="mt-auto">
                                        <div class="d-flex align-items-center">
                                            <strong class="fs-5 text-dark me-2 ud-heading-md">{{ $course['price'] }} €</strong>
                                            @if(isset($course['old_price']))
                                            <span class="text-decoration-line-through text-muted ud-text-sm">{{ $course['old_price'] }} €</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div> -->
    
    <!-- Section CourseShowcase -->
    <div class="py-5">
        @livewire('course-showcase')
    </div>
    
    <!-- Témoignages -->
    <div class="container my-5 py-4">
        <h2 class="fw-bold text-center mb-5 ud-heading-serif">Ce que disent nos apprenants</h2>
        
        <div class="row g-4">
             @php
                $testimonials = [
                    ['img' => 'https://randomuser.me/api/portraits/men/32.jpg', 'name' => 'Thomas Mensah', 'rating' => 5, 'text' => "Les cours d'AfriCode sont extrêmement bien structurés. J'ai réussi à trouver un emploi en tant que développeur web après seulement 3 mois d'apprentissage intensif!", 'course' => 'JavaScript Moderne'],
                    ['img' => 'https://randomuser.me/api/portraits/women/44.jpg', 'name' => 'Aïcha Diallo', 'rating' => 4.5, 'text' => "Je n'avais aucune expérience en programmation avant de commencer. Les instructeurs expliquent tout pas à pas et sont très disponibles pour répondre aux questions.", 'course' => 'Python pour Débutants'],
                    ['img' => 'https://randomuser.me/api/portraits/men/67.jpg', 'name' => 'Moussa Sané', 'rating' => 5, 'text' => "Chaque cours contient des projets pratiques qui m'ont permis de construire un portfolio impressionnant. Le retour sur investissement est incroyable!", 'course' => 'Laravel 10 Avancé'],
                ];
            @endphp

            @foreach($testimonials as $testimonial)
            <div class="col-md-6 col-lg-4 d-flex" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                <div class="card testimonial-card flex-fill p-4">
                    <div class="d-flex mb-3 align-items-center">
                        <img src="{{ $testimonial['img'] }}" class="rounded-circle me-3 testimonial-img" alt="{{ $testimonial['name'] }}">
                        <div>
                            <h5 class="mb-0 fw-bold ud-text-md">{{ $testimonial['name'] }}</h5>
                            <div class="text-warning star-rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= floor($testimonial['rating'])) <i class="fas fa-star fa-sm"></i>
                                    @elseif ($i - 0.5 <= $testimonial['rating']) <i class="fas fa-star-half-alt fa-sm"></i>
                                    @else <i class="far fa-star fa-sm"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                    <blockquote class="blockquote mb-3 flex-grow-1">
                        <p class="mb-0 ud-text-sm fst-italic">"{{ $testimonial['text'] }}"</p>
                    </blockquote>
                    <footer class="blockquote-footer mt-auto text-muted ud-text-xs">
                        <i class="fas fa-book-open me-1"></i> Cours suivi : {{ $testimonial['course'] }}
                    </footer>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Devenez instructeur (Call to Action) -->
    <div class="bg-primary text-white py-5 instructor-cta">
        <div class="container py-3">
            <div class="row align-items-center">
                <div class="col-lg-7 mb-4 mb-lg-0 text-center text-lg-start">
                    <h2 class="fw-bold mb-3 ud-heading-xl">Devenez instructeur</h2>
                    <p class="lead mb-4">Partagez vos connaissances et gagnez un revenu en enseignant sur AfriCode. Rejoignez notre réseau d'experts et aidez les apprenants à atteindre leurs objectifs.</p>
                    <ul class="list-unstyled mb-4 text-start mx-auto mx-lg-0" style="max-width: 450px;">
                        <li class="mb-2 d-flex"><i class="fas fa-check-circle me-2 mt-1"></i>Atteignez des milliers d'apprenants à travers l'Afrique.</li>
                        <li class="mb-2 d-flex"><i class="fas fa-check-circle me-2 mt-1"></i>Fixez vos propres tarifs et horaires flexibles.</li>
                        <li class="mb-2 d-flex"><i class="fas fa-check-circle me-2 mt-1"></i>Bénéficiez de notre plateforme et de nos outils marketing.</li>
                    </ul>
                    <a href="#" class="btn btn-light btn-lg px-4 py-3">Commencer maintenant</a>
                </div>
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="rounded shadow-lg overflow-hidden">
                        <img src="{{ asset('assets/images/instructor-cta.jpg') }}" class="img-fluid w-100" alt="Instructor" style="aspect-ratio: 4/3; object-fit: cover;"> {{-- Prévoyez une image engageante --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Entreprises qui nous font confiance -->
    <div class="partner-logos-section bg-white py-5">
        <div class="container">
            <h3 class="text-center text-muted fw-normal mb-5 ud-heading-lg">Des entreprises qui nous font confiance pour former leurs équipes</h3>
            <div class="d-flex flex-wrap align-items-center justify-content-center g-5">
                <div class="partner-logo mx-3 my-2 text-center">
                    <img src="https://s.udemycdn.com/partner-logos/v4/nasdaq-light.svg" alt="Nasdaq" class="img-fluid" style="max-height: 40px; filter: grayscale(100%) opacity(60%);">
                </div>
                <div class="partner-logo mx-3 my-2 text-center">
                    <img src="https://s.udemycdn.com/partner-logos/v4/volkswagen-light.svg" alt="Volkswagen" class="img-fluid" style="max-height: 40px; filter: grayscale(100%) opacity(60%);">
                </div>
                <div class="partner-logo mx-3 my-2 text-center">
                    <img src="https://s.udemycdn.com/partner-logos/v4/cisco-light.svg" alt="Cisco" class="img-fluid" style="max-height: 40px; filter: grayscale(100%) opacity(60%);">
                </div>
                <div class="partner-logo mx-3 my-2 text-center">
                    <img src="https://s.udemycdn.com/partner-logos/v4/eventbrite-light.svg" alt="Eventbrite" class="img-fluid" style="max-height: 40px; filter: grayscale(100%) opacity(60%);">
                </div>
                 <div class="partner-logo mx-3 my-2 text-center">
                    <img src="https://s.udemycdn.com/partner-logos/v4/netapp-light.svg" alt="NetApp" class="img-fluid" style="max-height: 40px; filter: grayscale(100%) opacity(60%);">
                </div>
            </div>
        </div>
    </div>

    <!-- Newsletter -->
    <div class="bg-light py-5 newsletter-section">
        <div class="container py-2">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <h3 class="fw-bold mb-3 ud-heading-xl">Restez informé</h3>
                    <p class="mb-4 lead">Inscrivez-vous à notre newsletter pour recevoir les dernières nouvelles, les nouveaux cours et les promotions exclusives.</p>
                    <form class="input-group mb-3 mx-auto shadow-sm" style="max-width: 550px;">
                        <input type="email" class="form-control form-control-lg py-3 ps-4" placeholder="Votre adresse email" aria-label="Email address">
                        <button class="btn btn-primary px-4 py-3" type="button">S'abonner</button>
                    </form>
                    <small class="text-muted">Nous respectons votre vie privée. Désabonnez-vous à tout moment.</small>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<style>
    :root {
        --udemy-purple: #a435f0; /* Couleur principale Udemy */
        --udemy-purple-dark: #5624d0;
        --udemy-dark-gray: #1c1d1f;
        --udemy-light-gray: #f7f9fa;
        --udemy-text-color: #1c1d1f;
        --udemy-text-muted: #6a6f73;
    }
    
    body {
        font-family: "SF Pro Text", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        color: var(--udemy-text-color);
        line-height: 1.6;
    }

    .ud-heading-serif { /* Pour les titres principaux, imitant SuisseWorks d'Udemy */
        font-family: ui-serif, Georgia, Cambria, "Times New Roman", Times, serif;
    }
    .ud-heading-xl { font-size: 2rem; line-height: 1.2; } /* Equivalent ud-heading-xl */
    .ud-heading-lg { font-size: 1.5rem; line-height: 1.25; } /* Equivalent ud-heading-lg */
    .ud-heading-md { font-size: 1.25rem; line-height: 1.3; } /* Equivalent ud-heading-md */
    .ud-text-md { font-size: 1rem; } /* Equivalent ud-text-md */
    .ud-text-sm { font-size: 0.875rem; } /* Equivalent ud-text-sm */
    .ud-text-xs { font-size: 0.75rem; } /* Equivalent ud-text-xs */

    .min-vh-60 { min-height: 60vh; }

    .btn {
        border-radius: 4px;
        padding: 0.75rem 1.5rem; /* Plus proche d'Udemy */
        font-weight: 700; /* Udemy utilise des poids plus forts */
        transition: background-color 0.2s ease-in-out, border-color 0.2s ease-in-out, color 0.2s ease-in-out;
    }

    .btn-primary {
        background-color: var(--udemy-purple);
        border-color: var(--udemy-purple);
    }
    .btn-primary:hover, .btn-primary:focus {
        background-color: var(--udemy-purple-dark);
        border-color: var(--udemy-purple-dark);
    }

    .btn-outline-primary {
        border-color: var(--udemy-purple);
        color: var(--udemy-purple);
    }
    .btn-outline-primary:hover, .btn-outline-primary:focus {
        background-color: var(--udemy-purple);
        color: white;
    }
    .btn-light:hover {
        background-color: #e2e6ea;
        border-color: #dae0e5;
    }

    .text-primary { color: var(--udemy-purple) !important; }
    .bg-primary { background-color: var(--udemy-purple) !important; }
    .bg-dark { background-color: var(--udemy-dark-gray) !important; }
    .bg-light { background-color: var(--udemy-light-gray) !important; }

    .hero-section {
        background-color: var(--udemy-dark-gray);
        margin-top: 0 !important; /* Assurez-vous qu'il n'y a pas de marge négative si le header est fixe */
    }
    .hero-section h1 { color: white; }
    .hero-section .lead { color: rgba(255,255,255,0.85); }
    .search-container .form-control-lg {
        box-shadow: 0 2px 4px rgba(0,0,0,.08), 0 4px 12px rgba(0,0,0,.08);
        border: none;
    }
    .search-container button i { font-size: 1.1rem; }

    .trust-bar .fa-2x { font-size: 1.8em; }
    .trust-bar small { font-size: 0.85em; }

    .category-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        border-radius: 4px; /* Udemy utilise des bords moins arrondis */
        background-color: white;
        border: 1px solid #d1d7dc;
    }
    .category-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0,0,0,.08) !important;
    }
    .category-card .icon-wrapper {
        width: 48px !important; height: 48px !important;
    }
    .category-card .card-title {
        font-weight: 700;
        color: var(--udemy-text-color);
    }

    .course-card {
        background-color: white;
        border: 1px solid #d1d7dc !important; /* Bordure subtile comme Udemy */
        box-shadow: none !important; /* Udemy n'a pas d'ombre par défaut sur les cartes */
        border-radius: 4px;
        transition: box-shadow 0.2s ease-in-out;
    }
    .course-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,.08) !important;
    }
    .course-img-container {
        aspect-ratio: 16/9;
        overflow: hidden;
        background-color: #f0f0f0; /* Placeholder si l'image ne charge pas */
    }
    .course-img-container img {
        width: 100%; 
        height: 100%; 
        object-fit: cover;
        transition: transform 0.3s ease;
        display: block; /* Empêche l'espace blanc sous l'image */
    }
    .course-card:hover .course-img-container img {
        transform: scale(1.05);
    }
    .course-card .card-title { font-weight: 700; line-height: 1.2; }
    .course-card .text-muted { color: var(--udemy-text-muted) !important; }
    .star-rating .fa-xs { font-size: 0.75em; }
    .bestseller-badge {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.25em 0.5em;
    }
    .course-title-ellipsis { /* Pour les titres de cours sur plusieurs lignes */
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        min-height: calc(1.2em * 2); /* Hauteur pour 2 lignes de texte */
    }
     .course-desc-ellipsis {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        min-height: calc(1.4em * 2);
    }


    .testimonial-card {
        border-radius: 4px;
        background-color: var(--udemy-light-gray);
        border: 1px solid #e0e0e0;
        box-shadow: none;
    }
    .testimonial-img { width: 50px; height: 50px; }

    .instructor-cta { background-color: var(--udemy-dark-gray) !important; } /* Couleur foncée d'Udemy */
    .instructor-cta h2 { color: white; }
    .instructor-cta p, .instructor-cta li { color: rgba(255,255,255,0.85); }
    .instructor-cta .btn-light {
        color: var(--udemy-dark-gray);
        font-weight: 700;
    }

    .partner-logos-section img {
        max-height: 35px; /* Plus petit et subtil comme Udemy */
        filter: grayscale(100%) opacity(70%);
        transition: filter 0.2s ease, opacity 0.2s ease;
    }
    .partner-logos-section img:hover {
        filter: grayscale(0%) opacity(100%);
    }

    .newsletter-section {
        background-color: var(--udemy-light-gray);
    }
    .newsletter-section .form-control-lg {
        border-right: none;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }
    .newsletter-section .btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    /* Footer styling */
    .ud-footer {
        background-color: var(--udemy-dark-gray);
        color: white;
        padding-top: 3rem;
        padding-bottom: 2rem;
    }
    .ud-footer .ud-heading-md {
        font-weight: 700;
        margin-bottom: 1rem;
        color: white;
    }
    .ud-footer .link-column a {
        color: #d1d7dc; /* Couleur de lien plus claire sur fond sombre */
        text-decoration: none;
        display: block;
        padding: 0.3rem 0;
        font-size: 0.875rem;
    }
    .ud-footer .link-column a:hover {
        color: white;
        text-decoration: underline;
    }
    .footer-bottom {
        border-top: 1px solid #3e4143;
        padding-top: 2rem;
        margin-top: 2rem;
    }
    .footer-bottom .logo-container img {
        height: 34px;
    }
    .footer-bottom .copyright-container {
        font-size: 0.8rem;
        color: #d1d7dc;
    }
    .language-selector-button-bottom {
        border: 1px solid #d1d7dc;
        color: white;
        padding: 0.5rem 1rem;
    }
    .language-selector-button-bottom:hover {
        background-color: rgba(255,255,255,0.1);
    }
    .language-selector-button-bottom i { margin-right: 0.5rem; }

    /* Fix pour les images qui ne s'affichent pas correctement */
    .card-img-top {
        width: 100%;
        height: auto;
        min-height: 140px;
        max-height: 160px;
        object-fit: cover;
        display: block;
    }

    /* Styles pour les catégories défilables */
    .categories-slider {
        display: flex;
        overflow-x: auto;
        scroll-behavior: smooth;
        gap: 1rem;
        padding: 0.5rem 0;
    }
    .category-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        background-color: var(--udemy-light-gray);
        border: 1px solid #d1d7dc;
        transition: background-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        white-space: nowrap;
    }
    .category-btn:hover {
        background-color: var(--udemy-purple);
        color: white;
        box-shadow: 0 4px 12px rgba(0,0,0,.08);
    }
    .category-btn .course-count {
        font-size: 0.75rem;
        color: var(--udemy-text-muted);
        margin-left: 0.5rem;
    }
    .slider-controls {
        display: flex;
        justify-content: space-between;
        margin-top: 1rem;
    }
    .control-btn {
        background-color: rgba(0,0,0,0.5);
        color: white;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color 0.2s ease-in-out;
    }
    .control-btn:hover {
        background-color: rgba(0,0,0,0.7);
    }
</style>
@endpush

@push('scripts')
    <!-- Script pour compteurs (votre script actuel est bien) -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const counters = document.querySelectorAll(".counter");
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };

            const counterObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const counter = entry.target;
                        const min = parseInt(counter.dataset.min) || 0;
                        const max = parseInt(counter.dataset.max);
                        let current = min;
                        const duration = 1500; // ms
                        const stepTime = Math.abs(Math.floor(duration / (max - min)));
                        
                        const timer = setInterval(() => {
                            current += 1;
                            counter.innerText = current;
                            if (current == max) {
                                clearInterval(timer);
                            }
                        }, stepTime > 0 ? stepTime : 20); // Min step time to prevent freezing
                        
                        observer.unobserve(counter);
                    }
                });
            }, observerOptions);

            counters.forEach(counter => {
                counterObserver.observe(counter);
            });
        });

        // Script pour les boutons de défilement des catégories
        document.addEventListener("DOMContentLoaded", function() {
            const slider = document.querySelector(".categories-slider");
            const prevBtn = document.querySelector(".prev-btn");
            const nextBtn = document.querySelector(".next-btn");

            prevBtn.addEventListener("click", () => {
                slider.scrollBy({ left: -200, behavior: "smooth" });
            });

            nextBtn.addEventListener("click", () => {
                slider.scrollBy({ left: 200, behavior: "smooth" });
            });
        });
    </script>
    {{-- AOS est déjà dans votre layout, c'est parfait --}}
@endpush