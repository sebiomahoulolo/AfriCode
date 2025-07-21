@extends('layouts.layout')

@section('title', $course->title . ' | AfriCode')

@section('meta_tags')
    <meta name="description" content="{{ $course->short_description }}">
    <meta name="keywords" content="cours en ligne, {{ $course->title }}, {{ $course->category ? $course->category->name : 'programmation' }}, apprendre à coder">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $course->title }} | AfriCode">
    <meta property="og:description" content="{{ $course->short_description }}">
    <meta property="og:image" content="{{ asset($course->cover_image_path ?? 'assets/images/course-placeholder.jpg') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $course->title }} | AfriCode">
    <meta property="twitter:description" content="{{ $course->short_description }}">
    <meta property="twitter:image" content="{{ asset($course->cover_image_path ?? 'assets/images/course-placeholder.jpg') }}">
@endsection

@section('content')
    <!-- Hero Section Moderne avec Design Immersif -->
    <section class="course-hero-modern px-2 py-2">
        <!-- Background Pattern et Gradient -->
        <div class="hero-background">
            <div class="background-pattern"></div>
            <div class="floating-elements">
                <div class="floating-shape shape-1"></div>
                <div class="floating-shape shape-2"></div>
                <div class="floating-shape shape-3"></div>
            </div>
        </div>
        
        <div class="container position-relative">
            <!-- Breadcrumb Modernisé -->
            <nav aria-label="breadcrumb" class="modern-breadcrumb mb-4">
                <ol class="breadcrumb-modern">
                    <li><a href="/" class="breadcrumb-link">Accueil</a></li>
                    <li><a href="{{ route('courses.index') }}" class="breadcrumb-link">Formations</a></li>
                    <li><a href="{{ route('courses.index', ['cat' => $course->category_id]) }}" class="breadcrumb-link">
                        {{ $course->category ? $course->category->name : 'Développement' }}
                    </a></li>
                    <li class="breadcrumb-current">{{ $course->title }}</li>
                </ol>
            </nav>

            <div class="row align-items-center min-vh-75">
                <!-- Contenu Principal du Hero -->
                <div class="col-lg-7">
                    <div class="hero-content-modern">
                        <!-- Badges et Labels Innovants -->
                        <div class="course-labels mb-4">
                            @php
                                $isBestseller = $course->enrollments->count() > 50;
                                $isNew = $course->created_at && $course->created_at->diffInDays(now()) < 30;
                                $avgRating = $course->ratings->avg('rating') ?? 4.5;
                                $reviewsCount = $course->ratings->count() ?? 127;
                            @endphp
                            
                            @if($isBestseller)
                                <span class="modern-badge badge-bestseller">
                                    <i class="fas fa-crown"></i> Meilleure Vente
                                </span>
                            @elseif($isNew)
                                <span class="modern-badge badge-new">
                                    <i class="fas fa-sparkles"></i> Nouveau
                                </span>
                            @endif

                            <span class="modern-badge badge-level badge-level-{{ strtolower($course->level ?? 'beginner') }}">
                                <i class="fas fa-signal"></i>
                                @if($course->level === 'beginner')
                                    Débutant
                                @elseif($course->level === 'intermediate')
                                    Intermédiaire
                                @elseif($course->level === 'advanced')
                                    Avancé
                                @else
                                    Tous niveaux
                                @endif
                            </span>
                        </div>

                        <!-- Titre Principal avec Animation -->
                        <h1 class="hero-title-modern mb-4">{{ $course->title }}</h1>
                        
                        <!-- Description Enrichie -->
                        <p class="hero-description-modern mb-4">{{ $course->short_description }}</p>

                        <!-- Statistiques Visuelles -->
                        <div class="course-stats-modern mb-4">
                            <div class="stat-item">
                                <div class="stat-value">{{ number_format($avgRating, 1) }}</div>
                                <div class="stat-stars">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($avgRating))
                                            <i class="fas fa-star"></i>
                                        @elseif ($i - 0.5 <= $avgRating)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="stat-label">({{ $reviewsCount }} avis)</div>
                            </div>

                            <div class="stat-divider"></div>

                            <div class="stat-item">
                                <div class="stat-value">{{ number_format($course->enrollments->count()) }}</div>
                                <div class="stat-label">Apprenants</div>
                            </div>

                            <div class="stat-divider"></div>

                            <div class="stat-item">
                                <div class="stat-value">{{ $course->getEstimatedDuration() ?? '10h' }}</div>
                                <div class="stat-label">de contenu</div>
                            </div>
                        </div>

                        <!-- Informations Instructeur -->
                        <div class="instructor-preview mb-4">
                            <img src="{{ $course->formateur->profile_image ?? 'https://randomuser.me/api/portraits/men/32.jpg' }}" 
                                 alt="Instructeur" class="instructor-avatar">
                            <div class="instructor-info">
                                <div class="instructor-name">
                                    {{ $course->formateur ? $course->formateur->first_name . ' ' . $course->formateur->last_name : 'Instructeur AfriCode' }}
                                </div>
                                <div class="instructor-title">Expert {{ $course->category ? $course->category->name : 'Développement' }}</div>
                            </div>
                        </div>

                        <!-- Actions Rapides -->
                        <div class="hero-actions">
                            @auth
                                @php
                                    $userEnrollment = $course->enrollments->where('user_id', auth()->id())->first();
                                @endphp
                                
                                @if($userEnrollment)
                                    <a href="{{ route('apprenant.course.access', ['courseId' => $course->id]) }}" 
                                       class="btn-modern btn-primary-modern w-full focus:outline-none focus:ring-2 focus:ring-primary" aria-label="Continuer la formation {{ $course->title }}">
                                        <i class="fas fa-play"></i>
                                        Continuer la Formation
                                    </a>
                                @else
                                    @if($course->price > 0)
                                        <a href="{{ route('enrollment.show', $course) }}" 
                                           class="btn-modern btn-primary-modern w-full focus:outline-none focus:ring-2 focus:ring-primary" aria-label="S'inscrire au cours {{ $course->title }}">
                                            <i class="fas fa-graduation-cap"></i>
                                            S'inscrire • {{ number_format($course->price, 0, ',', ' ') }}€
                                        </a>
                                    @else
                                        <a href="{{ route('enrollment.show', $course) }}" 
                                           class="btn-modern btn-primary-modern w-full focus:outline-none focus:ring-2 focus:ring-primary" aria-label="S'inscrire gratuitement au cours {{ $course->title }}">
                                            <i class="fas fa-gift"></i>
                                            S'inscrire Gratuitement
                                        </a>
                                    @endif
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn-modern btn-primary-modern w-full focus:outline-none focus:ring-2 focus:ring-primary" aria-label="Se connecter pour s'inscrire au cours {{ $course->title }}">
                                    <i class="fas fa-user"></i>
                                    Se connecter pour s'inscrire
                                </a>
                            @endauth

                            <button class="btn-modern btn-secondary-modern w-full focus:outline-none focus:ring-2 focus:ring-primary" data-bs-toggle="modal" data-bs-target="#previewModal" aria-label="Aperçu gratuit du cours {{ $course->title }}">
                                <i class="fas fa-eye"></i>
                                Aperçu Gratuit
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Aperçu Visuel Immersif -->
                <div class="col-lg-5">
                    <div class="course-preview-modern">
                        <div class="preview-container">
                            <div class="preview-image-wrapper">
                                <img src="{{ asset($course->cover_image_path ?? 'assets/images/course-placeholder.jpg') }}" 
                                     alt="Image de couverture du cours {{ $course->title }}" class="preview-image">
                                <div class="preview-overlay">
                                    <button class="play-button-modern" data-bs-toggle="modal" data-bs-target="#previewModal">
                                        <i class="fas fa-play"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Éléments Informatifs Flottants -->
                            <div class="floating-info info-duration">
                                <i class="fas fa-clock"></i>
                                {{ $course->getEstimatedDuration() ?? '10h' }}
                            </div>
                            <div class="floating-info info-modules">
                                <i class="fas fa-layers"></i>
                                {{ $course->modules->count() }} modules
                            </div>
                            <div class="floating-info info-certificate">
                                <i class="fas fa-certificate"></i>
                                Certificat inclus
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Navigation Moderne avec Onglets -->
    <section class="course-navigation-modern">
        <div class="container">
            <div class="nav-wrapper">
                <nav class="nav-modern" id="courseNav">
                    <a href="#overview" class="nav-item-modern active" data-section="overview" tabindex="0" aria-label="Voir l'aperçu du cours">
                        <i class="fas fa-info-circle"></i>
                        Vue d'ensemble
                    </a>
                    <a href="#curriculum" class="nav-item-modern" data-section="curriculum" tabindex="0" aria-label="Voir le programme du cours">
                        <i class="fas fa-list"></i>
                        Programme
                    </a>
                    <a href="#instructor" class="nav-item-modern" data-section="instructor" tabindex="0" aria-label="Voir l'instructeur du cours">
                        <i class="fas fa-user-tie"></i>
                        Instructeur
                    </a>
                    <a href="#reviews" class="nav-item-modern" data-section="reviews" tabindex="0" aria-label="Voir les avis du cours">
                        <i class="fas fa-star"></i>
                        Avis
                    </a>
                    <a href="#faq" class="nav-item-modern" data-section="faq" tabindex="0" aria-label="Voir la FAQ du cours">
                        <i class="fas fa-question-circle"></i>
                        FAQ
                    </a>
                </nav>
            </div>
        </div>
    </section>

    <!-- Contenu Principal Moderne -->
    <main class="course-main-content">
        <div class="container">
            <div class="row">
                <!-- Contenu Principal -->
                <div class="col-lg-8">
                    <!-- Section Vue d'ensemble -->
                    <section id="overview" class="content-section active">
                        <!-- Ce que vous apprendrez - Design Modernisé -->
                        <div class="modern-card learning-outcomes-card">
                            <div class="card-header-modern">
                                <h2 class="section-title-modern">
                                    <i class="fas fa-lightbulb"></i>
                                    Ce que vous apprendrez
                                </h2>
                            </div>
                            <div class="card-content-modern">
                                @php
                                    $learningPoints = $course->learning_objectives ?? [
                                        'Maîtriser les concepts fondamentaux de cette technologie',
                                        'Créer des applications professionnelles et robustes',
                                        'Comprendre et appliquer les bonnes pratiques',
                                        'Développer des projets concrets et portfolio',
                                        'Optimiser les performances et la sécurité',
                                        'Travailler efficacement en équipe'
                                    ];
                                @endphp

                                <div class="learning-grid">
                                    @foreach ($learningPoints as $index => $point)
                                        <div class="learning-item" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                                            <div class="check-icon">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <span class="learning-text">{{ $point }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Description Immersive -->
                        <div class="modern-card description-card">
                            <div class="card-header-modern">
                                <h2 class="section-title-modern">
                                    <i class="fas fa-file-alt"></i>
                                    À propos de cette formation
                                </h2>
                            </div>
                            <div class="card-content-modern">
                                <div class="description-content">
                                    {!! $course->full_description ??
                                        '<div class="rich-description">
                                            <p class="lead">Cette formation complète vous guidera pas à pas dans la maîtrise de cette technologie, en combinant théorie solide et pratique intensive.</p>
                                            
                                            <h4>🎯 Objectifs pédagogiques</h4>
                                            <p>Que vous soyez débutant ou avec une expérience préalable, cette formation est conçue pour vous faire progresser efficacement. Nous commencerons par les fondamentaux avant d\'aborder des concepts plus avancés.</p>
                                            
                                            <h4>🛠️ Approche pratique</h4>
                                            <p>Chaque section comprend des projets concrets pour appliquer immédiatement ce que vous apprenez. Vous développerez un portfolio de projets professionnels.</p>
                                            
                                            <h4>🎓 Résultats attendus</h4>
                                            <p>À la fin de cette formation, vous serez capable de créer vos propres applications et d\'appliquer ces connaissances dans des projets professionnels réels.</p>
                                        </div>'
                                    !!}
                                </div>
                            </div>
                        </div>

                        <!-- Prérequis Modernes -->
                        <div class="modern-card prerequisites-card">
                            <div class="card-header-modern">
                                <h2 class="section-title-modern">
                                    <i class="fas fa-clipboard-list"></i>
                                    Prérequis
                                </h2>
                            </div>
                            <div class="card-content-modern">
                                @php
                                    $requirements = $course->prerequisites ?? [
                                        'Motivation pour apprendre et pratiquer régulièrement',
                                        'Ordinateur avec connexion Internet stable',
                                        'Notions de base en informatique (navigation web, installation logiciels)',
                                        'Aucune expérience préalable en programmation requise'
                                    ];
                                @endphp
                                
                                <div class="prerequisites-list">
                                    @foreach ($requirements as $requirement)
                                        <div class="prerequisite-item">
                                            <i class="fas fa-arrow-right"></i>
                                            <span>{{ $requirement }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Section Programme -->
                    <section id="curriculum" class="content-section">
                        <div class="modern-card curriculum-card">
                            <div class="card-header-modern">
                                <h2 class="section-title-modern">
                                    <i class="fas fa-graduation-cap"></i>
                                    Programme de la formation
                                </h2>
                                <div class="curriculum-stats">
                                    <span class="stat">{{ $course->modules->count() }} modules</span>
                                    <span class="stat-divider">•</span>
                                    <span class="stat">{{ $course->modules->sum(function($module) { return $module->lessons->count(); }) }} leçons</span>
                                    <span class="stat-divider">•</span>
                                    <span class="stat">{{ $course->getEstimatedDuration() ?? '10h' }} de contenu</span>
                                </div>
                            </div>
                            <div class="card-content-modern">
                                <div class="curriculum-container">
                                    @forelse($course->modules as $key => $module)
                                        <div class="module-modern" data-aos="fade-up" data-aos-delay="{{ $key * 100 }}">
                                            <div class="module-header">
                                                <div class="module-info">
                                                    <h3 class="module-title">
                                                        <span class="module-number">{{ sprintf('%02d', $key + 1) }}</span>
                                                        {{ $module->title }}
                                                    </h3>
                                                    <div class="module-meta">
                                                        <span class="lesson-count">{{ $module->lessons->count() }} leçons</span>
                                                        <span class="duration">{{ $module->lessons->sum('duration_minutes') ?? '45' }} min</span>
                                                    </div>
                                                </div>
                                                <button class="module-toggle" data-bs-toggle="collapse" data-bs-target="#module-{{ $key }}" aria-expanded="{{ $key === 0 ? 'true' : 'false' }}">
                                                    <i class="fas fa-chevron-down"></i>
                                                </button>
                                            </div>

                                            <div id="module-{{ $key }}" class="module-content collapse {{ $key === 0 ? 'show' : '' }}">
                                                <div class="lessons-list">
                                                    @forelse($module->lessons as $lesson)
                                                        <div class="lesson-item">
                                                            <div class="lesson-icon">
                                                                @if ($lesson->type === 'video')
                                                                    <i class="fas fa-play-circle"></i>
                                                                @elseif ($lesson->type === 'quiz')
                                                                    <i class="fas fa-question-circle"></i>
                                                                @elseif ($lesson->type === 'exercise')
                                                                    <i class="fas fa-code"></i>
                                                                @else
                                                                    <i class="fas fa-file-alt"></i>
                                                                @endif
                                                            </div>
                                                            <div class="lesson-info">
                                                                <h4 class="lesson-title">{{ $lesson->title }}</h4>
                                                                @if ($lesson->is_free)
                                                                    <span class="preview-badge">Aperçu gratuit</span>
                                                                @endif
                                                            </div>
                                                            <div class="lesson-duration">{{ $lesson->duration_minutes ?? 10 }}:00</div>
                                                        </div>
                                                    @empty
                                                        <div class="empty-module">
                                                            <i class="fas fa-tools"></i>
                                                            <p>Contenu en cours de développement</p>
                                                        </div>
                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="empty-curriculum">
                                            <i class="fas fa-book-open"></i>
                                            <h3>Programme en cours de création</h3>
                                            <p>Le contenu détaillé de cette formation est actuellement en développement par notre équipe pédagogique.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Section Instructeur -->
                    <section id="instructor" class="content-section">
                        <div class="modern-card instructor-card">
                            <div class="card-header-modern">
                                <h2 class="section-title-modern">
                                    <i class="fas fa-user-tie"></i>
                                    Votre instructeur
                                </h2>
                            </div>
                            <div class="card-content-modern">
                                @if($course->formateur)
                                    <div class="instructor-profile">
                                        <div class="instructor-avatar-large">
                                            <img src="{{ $course->formateur->profile_image ?? 'https://randomuser.me/api/portraits/men/32.jpg' }}" 
                                                 alt="Photo de l'instructeur {{ $course->formateur ? $course->formateur->first_name . ' ' . $course->formateur->last_name : 'Instructeur AfriCode' }}" class="instructor-avatar">
                                            <div class="verified-badge">
                                                <i class="fas fa-check"></i>
                                            </div>
                                        </div>
                                        <div class="instructor-details">
                                            <h3 class="instructor-name">{{ $course->formateur->first_name }} {{ $course->formateur->last_name }}</h3>
                                            <p class="instructor-title">Expert {{ $course->category ? $course->category->name : 'Développement' }}</p>
                                            
                                            <div class="instructor-stats">
                                                <div class="stat-item">
                                                    <div class="stat-icon">
                                                        <i class="fas fa-star"></i>
                                                    </div>
                                                    <div class="stat-content">
                                                        <div class="stat-value">{{ number_format($course->formateur->ratings_avg ?? 4.8, 1) }}</div>
                                                        <div class="stat-label">Note instructeur</div>
                                                    </div>
                                                </div>
                                                
                                                <div class="stat-item">
                                                    <div class="stat-icon">
                                                        <i class="fas fa-users"></i>
                                                    </div>
                                                    <div class="stat-content">
                                                        <div class="stat-value">{{ number_format($course->formateur->students_count ?? 1250) }}</div>
                                                        <div class="stat-label">Apprenants</div>
                                                    </div>
                                                </div>
                                                
                                                <div class="stat-item">
                                                    <div class="stat-icon">
                                                        <i class="fas fa-graduation-cap"></i>
                                                    </div>
                                                    <div class="stat-content">
                                                        <div class="stat-value">{{ $course->formateur->courses_count ?? 8 }}</div>
                                                        <div class="stat-label">Formations</div>
                                                    </div>
                                                </div>
                                                
                                                <div class="stat-item">
                                                    <div class="stat-icon">
                                                        <i class="fas fa-comments"></i>
                                                    </div>
                                                    <div class="stat-content">
                                                        <div class="stat-value">{{ $course->formateur->ratings_count ?? 340 }}</div>
                                                        <div class="stat-label">Avis</div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="instructor-bio">
                                                <p>{{ $course->formateur->bio ?? 'Formateur passionné avec plus de 10 ans d\'expérience dans l\'enseignement et le développement professionnel. Expert reconnu dans son domaine, il accompagne les apprenants dans leur parcours de compétences avec une approche pratique et bienveillante.' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="instructor-profile">
                                        <div class="instructor-avatar-large">
                                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Instructeur AfriCode">
                                            <div class="verified-badge">
                                                <i class="fas fa-check"></i>
                                            </div>
                                        </div>
                                        <div class="instructor-details">
                                            <h3 class="instructor-name">Équipe AfriCode</h3>
                                            <p class="instructor-title">Experts en technologies modernes</p>
                                            
                                            <div class="instructor-stats">
                                                <div class="stat-item">
                                                    <div class="stat-icon">
                                                        <i class="fas fa-star"></i>
                                                    </div>
                                                    <div class="stat-content">
                                                        <div class="stat-value">4.9</div>
                                                        <div class="stat-label">Note moyenne</div>
                                                    </div>
                                                </div>
                                                
                                                <div class="stat-item">
                                                    <div class="stat-icon">
                                                        <i class="fas fa-users"></i>
                                                    </div>
                                                    <div class="stat-content">
                                                        <div class="stat-value">5000+</div>
                                                        <div class="stat-label">Apprenants</div>
                                                    </div>
                                                </div>
                                                
                                                <div class="stat-item">
                                                    <div class="stat-icon">
                                                        <i class="fas fa-graduation-cap"></i>
                                                    </div>
                                                    <div class="stat-content">
                                                        <div class="stat-value">50+</div>
                                                        <div class="stat-label">Formations</div>
                                                    </div>
                                                </div>
                                                
                                                <div class="stat-item">
                                                    <div class="stat-icon">
                                                        <i class="fas fa-award"></i>
                                                    </div>
                                                    <div class="stat-content">
                                                        <div class="stat-value">10+</div>
                                                        <div class="stat-label">Années</div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="instructor-bio">
                                                <p>Notre équipe d'experts passionnés met son savoir-faire au service de votre apprentissage. Avec plus de 10 ans d'expérience dans l'enseignement numérique, nous vous accompagnons vers l'excellence.</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>

                    <!-- Section Avis -->
                    <section id="reviews" class="content-section">
                        <div class="modern-card reviews-card">
                            <div class="card-header-modern">
                                <h2 class="section-title-modern">
                                    <i class="fas fa-star"></i>
                                    Avis des apprenants
                                </h2>
                            </div>
                            <div class="card-content-modern">
                                <!-- Vue d'ensemble des notes -->
                                <div class="reviews-overview">
                                    <div class="overall-rating">
                                        <div class="rating-score">{{ number_format($avgRating, 1) }}</div>
                                        <div class="rating-stars">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($avgRating))
                                                    <i class="fas fa-star"></i>
                                                @elseif ($i - 0.5 <= $avgRating)
                                                    <i class="fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <div class="rating-text">Note de la formation</div>
                                        <div class="rating-count">{{ $reviewsCount }} avis</div>
                                    </div>

                                    <div class="rating-breakdown">
                                        @php
                                            $ratings = [
                                                5 => $course->ratings->where('rating', 5)->count(),
                                                4 => $course->ratings->where('rating', 4)->count(),
                                                3 => $course->ratings->where('rating', 3)->count(),
                                                2 => $course->ratings->where('rating', 2)->count(),
                                                1 => $course->ratings->where('rating', 1)->count()
                                            ];
                                            
                                            $totalRatings = array_sum($ratings);
                                            if ($totalRatings > 0) {
                                                foreach ($ratings as $star => $count) {
                                                    $ratings[$star] = round(($count / $totalRatings) * 100);
                                                }
                                            } else {
                                                $ratings = [5 => 78, 4 => 15, 3 => 5, 2 => 1, 1 => 1];
                                            }
                                        @endphp
                                        
                                        @foreach ($ratings as $star => $percentage)
                                            <div class="rating-bar">
                                                <span class="star-label">{{ $star }} <i class="fas fa-star"></i></span>
                                                <div class="progress-bar-modern">
                                                    <div class="progress-fill" style="width: {{ $percentage }}%"></div>
                                                </div>
                                                <span class="percentage">{{ $percentage }}%</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Témoignages -->
                                <div class="testimonials-section">
                                    @php
                                        $testimonials = $course->testimonials ?? [
                                            [
                                                'name' => 'Sarah Kone',
                                                'avatar' => 'https://randomuser.me/api/portraits/women/44.jpg',
                                                'rating' => 5,
                                                'date' => now()->subDays(15),
                                                'comment' => 'Formation exceptionnelle ! Les explications sont claires, les projets pratiques et le support pédagogique excellent. J\'ai pu appliquer immédiatement mes nouvelles compétences au travail.',
                                                'helpful' => 23,
                                                'verified' => true
                                            ],
                                            [
                                                'name' => 'Mohamed Traore',
                                                'avatar' => 'https://randomuser.me/api/portraits/men/67.jpg',
                                                'rating' => 5,
                                                'date' => now()->subDays(30),
                                                'comment' => 'Très bien structuré avec une progression logique. L\'instructeur maîtrise parfaitement son sujet et sait le transmettre. Un investissement qui en vaut vraiment la peine !',
                                                'helpful' => 18,
                                                'verified' => true
                                            ],
                                            [
                                                'name' => 'Fatou Diop',
                                                'avatar' => 'https://randomuser.me/api/portraits/women/32.jpg',
                                                'rating' => 4,
                                                'date' => now()->subDays(45),
                                                'comment' => 'Excellente formation avec un contenu riche et des exemples concrets. J\'aurais aimé un peu plus d\'exercices pratiques, mais dans l\'ensemble c\'est top !',
                                                'helpful' => 12,
                                                'verified' => true
                                            ]
                                        ];
                                    @endphp
                                    
                                    @foreach($testimonials as $testimonial)
                                        <div class="testimonial-card" data-aos="fade-up">
                                            <div class="testimonial-header">
                                                <div class="reviewer-info">
                                                    <img src="{{ $testimonial['avatar'] }}" alt="{{ $testimonial['name'] }}" class="reviewer-avatar">
                                                    <div class="reviewer-details">
                                                        <div class="reviewer-name">
                                                            {{ $testimonial['name'] }}
                                                            @if($testimonial['verified'] ?? false)
                                                                <span class="verified-reviewer">
                                                                    <i class="fas fa-check" style="color: #e67e22"></i>
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="review-meta">
                                                            <div class="review-rating">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    @if ($i <= ($testimonial['rating'] ?? 5))
                                                                        <i class="fas fa-star" style="color: #e67e22"></i>
                                                                    @else
                                                                        <i class="far fa-star" style="color: #e67e22"></i>
                                                                    @endif
                                                                @endfor
                                                            </div>
                                                            <span class="review-date">
                                                                @if (isset($testimonial['date']) && is_object($testimonial['date']) && method_exists($testimonial['date'], 'format'))
                                                                    {{ $testimonial['date']->format('d M Y') }}
                                                                @else
                                                                    {{ now()->subDays(rand(5, 60))->format('d M Y') }}
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="testimonial-content">
                                                <p>{{ $testimonial['comment'] }}</p>
                                            </div>
                                            <div class="testimonial-footer">
                                                <div class="helpful-count">
                                                    <i class="fas fa-thumbs-up"></i>
                                                    {{ $testimonial['helpful'] ?? rand(5, 25) }} personnes ont trouvé cet avis utile
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Section FAQ -->
                    <section id="faq" class="content-section">
                        <div class="modern-card faq-card">
                            <div class="card-header-modern">
                                <h2 class="section-title-modern">
                                    <i class="fas fa-question-circle" style="color: #e67e22"></i>
                                    Questions fréquentes
                                </h2>
                            </div>
                            <div class="card-content-modern">
                                @php
                                    $faqs = $course->faq ?? [
                                        [
                                            'question' => 'Cette formation est-elle adaptée aux débutants ?',
                                            'answer' => 'Absolument ! Cette formation est conçue pour être accessible aux débutants tout en offrant également du contenu avancé pour les apprenants plus expérimentés. Nous commençons par les bases et progressons graduellement.'
                                        ],
                                        [
                                            'question' => 'Combien de temps ai-je accès à la formation ?',
                                            'answer' => 'Une fois inscrit, vous avez un accès à vie à la formation, y compris toutes les mises à jour futures du contenu. Vous pouvez apprendre à votre rythme, quand vous le souhaitez.'
                                        ],
                                        [
                                            'question' => 'Y a-t-il un certificat à la fin de la formation ?',
                                            'answer' => 'Oui, vous recevrez un certificat d\'achèvement une fois que vous aurez terminé tous les modules de la formation. Ce certificat peut être ajouté à votre profil LinkedIn et CV.'
                                        ],
                                        [
                                            'question' => 'Comment puis-je obtenir de l\'aide si je suis bloqué ?',
                                            'answer' => 'Plusieurs options s\'offrent à vous : section Q&A de chaque leçon, forum communautaire, support par email, et sessions de questions-réponses en direct avec l\'instructeur.'
                                        ],
                                        [
                                            'question' => 'Puis-je télécharger les ressources de la formation ?',
                                            'answer' => 'Oui, toutes les ressources (PDF, codes sources, exercices) sont téléchargeables. Vous pouvez également regarder les vidéos hors ligne avec notre application mobile.'
                                        ],
                                        [
                                            'question' => 'Y a-t-il une garantie de remboursement ?',
                                            'answer' => 'Nous offrons une garantie de remboursement de 30 jours, sans questions posées. Si vous n\'êtes pas satisfait, contactez-nous pour un remboursement intégral.'
                                        ]
                                    ];
                                @endphp
                                
                                <div class="faq-container">
                                    @foreach ($faqs as $key => $faq)
                                        <div class="faq-item" data-aos="fade-up" data-aos-delay="{{ $key * 100 }}">
                                            <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq-{{ $key }}" aria-expanded="{{ $key === 0 ? 'true' : 'false' }}">
                                                <h3>{{ $faq['question'] ?? $faq['q'] }}</h3>
                                                <div class="faq-toggle">
                                                    <i class="fas fa-plus" style="color: #e67e22"></i>
                                                </div>
                                            </div>
                                            <div id="faq-{{ $key }}" class="faq-answer collapse {{ $key === 0 ? 'show' : '' }}">
                                                <div class="faq-content">
                                                    <p>{{ $faq['answer'] ?? $faq['a'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <!-- Sidebar Modernisée et Sticky -->
                <div class="col-lg-4">
                    <div class="sidebar-modern">
                        <!-- Card d'Inscription Principale -->
                        <div class="enrollment-card-modern">
                            <!-- Header avec Prix -->
                            <div class="price-section">
                                @php
                                    $originalPrice = $course->price * 3.5;
                                    $discountPercentage = 71;
                                @endphp
                                
                                @if($course->price > 0)
                                    <div class="current-price">{{ number_format($course->price, 0, ',', ' ') }}€</div>
                                    <div class="price-details">
                                        <span class="original-price">{{ number_format($originalPrice, 0, ',', ' ') }}€</span>
                                        <span class="discount">{{ $discountPercentage }}% de réduction</span>
                                    </div>
                                @else
                                    <div class="current-price free">Gratuit</div>
                                    <div class="price-details">
                                        <span class="free-text">Accès illimité</span>
                                    </div>
                                @endif

                                <div class="promotion-timer">
                                    <i class="fas fa-clock" style="color: #e67e22"></i>
                                    <span>Offre limitée : <span id="countdown-modern">2 jours</span></span>
                                </div>
                            </div>

                            <!-- Actions Principales -->
                            <div class="enrollment-actions">
                                @auth
                                    @php
                                        $userEnrollment = $course->enrollments->where('user_id', auth()->id())->first();
                                    @endphp
                                    
                                    @if($userEnrollment)
                                        <a href="{{ route('apprenant.course.access', ['courseId' => $course->id]) }}" 
                                           class="btn-enroll enrolled">
                                            <i class="fas fa-play" style="color: #e67e22"></i>
                                            <span>Continuer la Formation</span>
                                        </a>
                                        <div class="enrollment-status">
                                            <i class="fas fa-check-circle" style="color: #e67e22"></i>
                                            Vous êtes inscrit à cette formation
                                        </div>
                                    @else
                                        @if($course->price > 0)
                                            <a href="{{ route('enrollment.show', $course) }}" 
                                               class="btn-enroll primary">
                                                <i class="fas fa-graduation-cap" style="color: #e67e22"></i>
                                                <span>S'inscrire maintenant</span>
                                            </a>
                                        @else
                                            <a href="{{ route('enrollment.show', $course) }}" 
                                               class="btn-enroll free">
                                                <i class="fas fa-gift" style="color: #e67e22"></i>
                                                <span>Commencer gratuitement</span>
                                            </a>
                                        @endif
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn-enroll primary">
                                        <i class="fas fa-user" style="color: #e67e22"></i>
                                        <span>Se connecter pour s'inscrire</span>
                                    </a>
                                @endauth
<br>
                                <button class="btn-secondary-action" data-bs-toggle="modal" data-bs-target="#wishlistModal">
                                    <i class="far fa-heart" style="color: #e67e22"></i>
                                    <span>Ajouter à mes favoris</span>
                                </button>
                            </div>

                            <!-- Garanties et Promesses -->
                            <div class="guarantees">
                                <div class="guarantee-item">
                                    <i class="fas fa-shield-alt" style="color: #e67e22"></i>
                                    <span>Garantie 30 jours</span>
                                </div>
                                <div class="guarantee-item">
                                    <i class="fas fa-infinity" style="color: #e67e22"></i>
                                    <span>Accès à vie</span>
                                </div>
                                <div class="guarantee-item">
                                    <i class="fas fa-mobile-alt" style="color: #e67e22"></i>
                                    <span>Mobile & Desktop</span>
                                </div>
                            </div>

                            <!-- Ce qui est Inclus -->
                            <div class="includes-section">
                                <h3 class="includes-title">Cette formation comprend :</h3>
                                <div class="includes-list">
                                    <div class="include-item">
                                        <i class="fas fa-video" style="color: #e67e22"></i>
                                        <span>{{ $course->getEstimatedDuration() ?? '10h' }} de vidéos HD</span>
                                    </div>
                                    <div class="include-item">
                                        <i class="fas fa-file-download" style="color: #e67e22"></i>
                                        <span>{{ rand(5, 15) }} ressources téléchargeables</span>
                                    </div>
                                    <div class="include-item">
                                        <i class="fas fa-code" style="color: #e67e22"></i>
                                        <span>{{ rand(8, 20) }} exercices pratiques</span>
                                    </div>
                                    <div class="include-item">
                                        <i class="fas fa-certificate" style="color: #e67e22"></i>
                                        <span>Certificat d'achèvement</span>
                                    </div>
                                    <div class="include-item">
                                        <i class="fas fa-comments" style="color: #e67e22"></i>
                                        <span>Support communautaire</span>
                                    </div>
                                    <div class="include-item">
                                        <i class="fas fa-sync-alt" style="color: #e67e22"></i>
                                        <span>Mises à jour gratuites</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Partage Social -->
                            <div class="social-share-section">
                                <h4>Partager cette formation</h4>
                                <div class="social-buttons">
                                    <a href="#" class="social-btn facebook" title="Partager sur Facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="#" class="social-btn twitter" title="Partager sur Twitter">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                    <a href="#" class="social-btn linkedin" title="Partager sur LinkedIn">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                    <a href="#" class="social-btn whatsapp" title="Partager sur WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    <a href="#" class="social-btn copy" title="Copier le lien">
                                        <i class="fas fa-link"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Formations Recommandées -->
                        <div class="recommended-courses-modern">
                            <h3 class="section-title">Formations recommandées</h3>
                            
                            @php
                                $relatedCourses = \App\Models\Course::where('id', '!=', $course->id)
                                    ->where('status', 'published')
                                    ->where('category_id', $course->category_id ?? null)
                                    ->take(3)
                                    ->get();
                                    
                                if($relatedCourses->count() < 3) {
                                    $additionalCourses = \App\Models\Course::where('id', '!=', $course->id)
                                        ->where('status', 'published')
                                        ->whereNotIn('id', $relatedCourses->pluck('id')->toArray())
                                        ->take(3 - $relatedCourses->count())
                                        ->get();
                                        
                                    $relatedCourses = $relatedCourses->concat($additionalCourses);
                                }
                            @endphp
                            
                            <div class="recommended-list">
                                @forelse($relatedCourses as $related)
                                    <div class="recommended-item" data-aos="fade-up">
                                        <div class="course-thumbnail">
                                            <img src="{{ asset($related->cover_image_path ?? 'assets/images/course-placeholder.jpg') }}" 
                                                 alt="{{ $related->title }}">
                                            <div class="course-overlay">
                                                <a href="{{ route('courses.show', $related->slug) }}" class="view-course">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="course-info">
                                            <h4 class="course-title">
                                                <a href="{{ route('courses.show', $related->slug) }}">{{ $related->title }}</a>
                                            </h4>
                                            <div class="course-instructor">
                                                {{ $related->formateur ? $related->formateur->first_name . ' ' . $related->formateur->last_name : 'Équipe AfriCode' }}
                                            </div>
                                            <div class="course-rating">
                                                @php $relatedRating = $related->ratings->avg('rating') ?? 4.5; @endphp
                                                <span class="rating-value">{{ number_format($relatedRating, 1) }}</span>
                                                <div class="stars">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= floor($relatedRating))
                                                            <i class="fas fa-star"></i>
                                                        @elseif ($i - 0.5 <= $relatedRating)
                                                            <i class="fas fa-star-half-alt"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                            <div class="course-price">
                                                {{ $related->price > 0 ? number_format($related->price, 0) . '€' : 'Gratuit' }}
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    @php
                                        $defaultCourses = [
                                            [
                                                'title' => 'Formation JavaScript Avancé',
                                                'slug' => 'formation-javascript-avance',
                                                'image' => 'assets/images/th.jpeg',
                                                'instructor' => 'Sophie Martin',
                                                'rating' => 4.8,
                                                'price' => 49
                                            ],
                                            [
                                                'title' => 'Maîtriser React & Redux',
                                                'slug' => 'maitriser-react-redux',
                                                'image' => 'assets/images/th (4).jpeg',
                                                'instructor' => 'Ahmed Kouassi',
                                                'rating' => 4.9,
                                                'price' => 69
                                            ],
                                            [
                                                'title' => 'Node.js pour Débutants',
                                                'slug' => 'nodejs-pour-debutants',
                                                'image' => 'assets/images/télécharger.jpeg',
                                                'instructor' => 'Marie Diallo',
                                                'rating' => 4.7,
                                                'price' => 39
                                            ]
                                        ];
                                    @endphp
                                    
                                    @foreach($defaultCourses as $related)
                                        <div class="recommended-item" data-aos="fade-up">
                                            <div class="course-thumbnail">
                                                <img src="{{ asset($related['image']) }}" alt="{{ $related['title'] }}">
                                                <div class="course-overlay">
                                                    <a href="{{ route('courses.show', $related['slug']) }}" class="view-course">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="course-info">
                                                <h4 class="course-title">
                                                    <a href="{{ route('courses.show', $related['slug']) }}">{{ $related['title'] }}</a>
                                                </h4>
                                                <div class="course-instructor">{{ $related['instructor'] }}</div>
                                                <div class="course-rating">
                                                    <span class="rating-value">{{ number_format($related['rating'], 1) }}</span>
                                                    <div class="stars">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($i <= floor($related['rating']))
                                                                <i class="fas fa-star"></i>
                                                            @elseif ($i - 0.5 <= $related['rating'])
                                                                <i class="fas fa-star-half-alt"></i>
                                                            @else
                                                                <i class="far fa-star"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                </div>
                                                <div class="course-price">{{ $related['price'] }}€</div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Aperçu -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content modern-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">
                        <i class="fas fa-play-circle"></i>
                        Aperçu de la formation
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="video-container">
                        <img src="{{ asset($course->cover_image_path ?? 'assets/images/course-placeholder.jpg') }}" 
                             alt="{{ $course->title }}" class="preview-video-placeholder">
                        <div class="video-overlay">
                            <div class="play-button-large">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                    </div>
                    <div class="preview-info">
                        <h6>{{ $course->title }}</h6>
                        <p>{{ $course->short_description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
/* Import AOS pour les animations */
@import url('https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css');

/* Variables étendues pour le design moderne */
:root {
    /* Nouvelles variables pour le design moderne */
    --course-primary: #1EA38B;
    --course-primary-light: #27B371;
    --course-primary-dark: #167c6a;
    --course-secondary: #FF8E2A;
    --course-accent: #E32D31;
    --course-success: #10B981;
    --course-warning: #F59E0B;
    --course-info: #3B82F6;
    
    /* Gradients modernes */
    --gradient-primary: linear-gradient(135deg, #1EA38B 0%, #27B371 100%);
    --gradient-secondary: linear-gradient(135deg, #FF8E2A 0%, #FFB366 100%);
    --gradient-hero: linear-gradient(135deg, rgba(30, 163, 139, 0.1) 0%, rgba(255, 142, 42, 0.05) 50%, transparent 100%);
    --gradient-card: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
    
    /* Ombres modernes */
    --shadow-xs: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    
    /* Espacement */
    --space-xs: 0.25rem;
    --space-sm: 0.5rem;
    --space-md: 1rem;
    --space-lg: 1.5rem;
    --space-xl: 2rem;
    --space-2xl: 3rem;
    
    /* Border radius */
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --radius-xl: 24px;
    --radius-2xl: 32px;
    
    /* Transitions */
    --transition-fast: all 0.15s ease;
    --transition-base: all 0.3s ease;
    --transition-slow: all 0.5s ease;
    --transition-bounce: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

/* Reset moderne et base */
.course-hero-modern {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    overflow: hidden;
    background: var(--gradient-hero);
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 0;
}

.background-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(circle at 20% 50%, rgba(30, 163, 139, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255, 142, 42, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 40% 80%, rgba(227, 45, 49, 0.05) 0%, transparent 50%);
    animation: float 20s ease-in-out infinite;
}

.floating-elements {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
}

.floating-shape {
    position: absolute;
    border-radius: 50%;
    opacity: 0.1;
    animation: floatShape 15s ease-in-out infinite;
}

.shape-1 {
    width: 200px;
    height: 200px;
    background: var(--course-primary);
    top: 10%;
    left: 10%;
    animation-delay: 0s;
}

.shape-2 {
    width: 150px;
    height: 150px;
    background: var(--course-secondary);
    top: 60%;
    right: 15%;
    animation-delay: 5s;
}

.shape-3 {
    width: 100px;
    height: 100px;
    background: var(--course-accent);
    bottom: 20%;
    left: 60%;
    animation-delay: 10s;
}

/* Breadcrumb Moderne */
.modern-breadcrumb {
    margin-bottom: 2rem;
}

.breadcrumb-modern {
    display: flex;
    align-items: center;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 0.5rem;
}

.breadcrumb-modern li:not(:last-child)::after {
    content: '/';
    margin-left: 0.5rem;
    color: rgba(255, 255, 255, 0.5);
}

.breadcrumb-link {
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    transition: var(--transition-fast);
}

.breadcrumb-link:hover {
    color: var(--course-primary);
}

.breadcrumb-current {
    color: var(--course-primary);
    font-weight: 600;
}

/* Hero Content Moderne */
.hero-content-modern {
    position: relative;
    z-index: 2;
}

.course-labels {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.modern-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: var(--radius-xl);
    font-size: 0.875rem;
    font-weight: 600;
    color: white;
    backdrop-filter: blur(10px);
    box-shadow: var(--shadow-sm);
}

.badge-bestseller {
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
}

.badge-new {
    background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
}

.badge-level-beginner {
    background: linear-gradient(135deg, #10B981 0%, #059669 100%);
}

.badge-level-intermediate {
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
}

.badge-level-advanced {
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
}

.hero-title-modern {
    font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 800;
    line-height: 1.1;
    color: #1f2937;
    margin-bottom: 1.5rem;
    background: linear-gradient(135deg, #1f2937 0%, var(--course-primary) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-description-modern {
    font-size: 1.25rem;
    line-height: 1.6;
    color: #6b7280;
    margin-bottom: 2rem;
}

/* Statistiques Modernes */
.course-stats-modern {
    display: flex;
    align-items: center;
    gap: 2rem;
    margin-bottom: 2rem;
}

.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.stat-value {
    font-size: 1.875rem;
    font-weight: 800;
    color: var(--course-primary);
    line-height: 1;
}

.stat-stars {
    color: #F59E0B;
    font-size: 1rem;
    margin: 0.25rem 0;
}

.stat-label {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.stat-divider {
    width: 1px;
    height: 40px;
    background: #e5e7eb;
}

/* Aperçu Instructeur */
.instructor-preview {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.8);
    border-radius: var(--radius-lg);
    backdrop-filter: blur(10px);
    box-shadow: var(--shadow-sm);
    margin-bottom: 2rem;
}

.instructor-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--course-primary);
}

.instructor-name {
    font-weight: 700;
    color: #1f2937;
    font-size: 1.125rem;
}

.instructor-title {
    color: var(--course-primary);
    font-weight: 500;
    font-size: 0.875rem;
}

/* Actions Hero */
.hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

.btn-modern {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 2rem;
    border-radius: var(--radius-xl);
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition-bounce);
    border: none;
    cursor: pointer;
    font-size: 1rem;
    box-shadow: var(--shadow-md);
}

.btn-primary-modern {
    background: var(--gradient-primary);
    color: white;
}

.btn-primary-modern:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    color: white;
}

.btn-secondary-modern {
    background: white;
    color: var(--course-primary);
    border: 2px solid var(--course-primary);
}

.btn-secondary-modern:hover {
    background: var(--course-primary);
    color: white;
    transform: translateY(-2px);
}

/* Aperçu Cours Moderne */
.course-preview-modern {
    position: relative;
    z-index: 2;
}

.preview-container {
    position: relative;
    border-radius: var(--radius-2xl);
    overflow: hidden;
    box-shadow: var(--shadow-2xl);
    transform: perspective(1000px) rotateY(-5deg);
    transition: var(--transition-slow);
}

.preview-container:hover {
    transform: perspective(1000px) rotateY(0deg) scale(1.02);
}

.preview-image-wrapper {
    position: relative;
    aspect-ratio: 16/9;
    overflow: hidden;
}

.preview-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition-slow);
}

.preview-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: var(--transition-base);
}

.preview-container:hover .preview-overlay {
    opacity: 1;
}

.play-button-modern {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--gradient-primary);
    border: none;
    color: white;
    font-size: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition-bounce);
    box-shadow: var(--shadow-lg);
}

.play-button-modern:hover {
    transform: scale(1.1);
    box-shadow: var(--shadow-xl);
}

/* Éléments Flottants */
.floating-info {
    position: absolute;
    padding: 0.75rem 1rem;
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--course-primary);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    animation: floatInfo 3s ease-in-out infinite;
}

.info-duration {
    top: 20px;
    left: 20px;
    animation-delay: 0s;
}

.info-modules {
    top: 20px;
    right: 20px;
    animation-delay: 1s;
}

.info-certificate {
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    animation-delay: 2s;
}

/* Navigation Moderne */
.course-navigation-modern {
    background: white;
    border-bottom: 1px solid #e5e7eb;
    position: sticky;
    top: 0;
    z-index: 100;
    backdrop-filter: blur(10px);
}

.nav-wrapper {
    overflow-x: auto;
    scrollbar-width: none;
    -ms-overflow-style: none;
}

.nav-wrapper::-webkit-scrollbar {
    display: none;
}

.nav-modern {
    display: flex;
    gap: 0;
    min-width: max-content;
}

.nav-item-modern {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 1.5rem;
    text-decoration: none;
    color: #6b7280;
    font-weight: 500;
    transition: var(--transition-fast);
    border-bottom: 3px solid transparent;
    white-space: nowrap;
}

.nav-item-modern:hover,
.nav-item-modern.active {
    color: var(--course-primary);
    border-bottom-color: var(--course-primary);
    background: rgba(30, 163, 139, 0.05);
}

/* Contenu Principal */
.course-main-content {
    padding: 3rem 0;
    background: #f8fafc;
}

.content-section {
    display: none;
}

.content-section.active {
    display: block;
}

/* Cards Modernes */
.modern-card {
    background: var(--gradient-card);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: var(--transition-base);
}

.modern-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.card-header-modern {
    padding: 1.5rem 2rem;
    background: white;
    border-bottom: 1px solid #e5e7eb;
}

.section-title-modern {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
}

.section-title-modern i {
    color: var(--course-primary);
}

.card-content-modern {
    padding: 2rem;
}

/* Learning Outcomes */
.learning-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1rem;
}

.learning-item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 1rem;
    background: white;
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-xs);
    transition: var(--transition-base);
}

.learning-item:hover {
    box-shadow: var(--shadow-sm);
    transform: translateX(4px);
}

.check-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    background: var(--course-success);
    color: white;
    border-radius: 50%;
    flex-shrink: 0;
    font-size: 0.875rem;
}

.learning-text {
    font-weight: 500;
    color: #374151;
    line-height: 1.5;
}

/* Programme Curriculum */
.curriculum-stats {
    display: flex;
    align-items: center;
    gap: 1rem;
    color: #6b7280;
    font-size: 0.875rem;
}

.curriculum-container {
    space-y: 1rem;
}

.module-modern {
    background: white;
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-xs);
    overflow: hidden;
    margin-bottom: 1rem;
}

.module-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.5rem;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

.module-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: var(--course-primary);
    color: white;
    border-radius: 50%;
    font-size: 0.875rem;
    font-weight: 700;
    margin-right: 1rem;
}

.module-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.module-meta {
    display: flex;
    gap: 1rem;
    color: #6b7280;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.module-toggle {
    background: none;
    border: none;
    color: var(--course-primary);
    font-size: 1.25rem;
    cursor: pointer;
    transition: var(--transition-fast);
}

.module-toggle:hover {
    transform: scale(1.1);
}

.lessons-list {
    padding: 0;
}

.lesson-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f3f4f6;
    transition: var(--transition-fast);
}

.lesson-item:hover {
    background: #f9fafb;
}

.lesson-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    font-size: 0.875rem;
}

.lesson-icon .fa-play-circle {
    color: var(--course-primary);
}

.lesson-icon .fa-question-circle {
    color: var(--course-warning);
}

.lesson-icon .fa-code {
    color: var(--course-success);
}

.lesson-icon .fa-file-alt {
    color: #6b7280;
}

.lesson-title {
    font-weight: 500;
    color: #374151;
    margin: 0;
    flex-grow: 1;
}

.preview-badge {
    background: var(--course-info);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-sm);
    font-size: 0.75rem;
    font-weight: 600;
}

.lesson-duration {
    color: #6b7280;
    font-size: 0.875rem;
    font-weight: 500;
}

/* Prérequis */
.prerequisites-list {
    space-y: 0.75rem;
}

.prerequisite-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: white;
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-xs);
}

.prerequisite-item i {
    color: var(--course-primary);
}

/* Instructeur */
.instructor-profile {
    display: flex;
    gap: 2rem;
    align-items: flex-start;
}

.instructor-avatar-large {
    position: relative;
    flex-shrink: 0;
}

.instructor-avatar-large img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid var(--course-primary);
}

.verified-badge {
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 24px;
    height: 24px;
    background: var(--course-success);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
}

.instructor-details {
    flex-grow: 1;
}

.instructor-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.instructor-title {
    color: var(--course-primary);
    font-weight: 600;
    margin-bottom: 1.5rem;
}

.instructor-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.instructor-stats .stat-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: white;
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-xs);
}

.stat-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: rgba(30, 163, 139, 0.1);
    color: var(--course-primary);
    border-radius: 50%;
}

.stat-content .stat-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
    line-height: 1;
}

.stat-content .stat-label {
    font-size: 0.75rem;
    color: #6b7280;
    font-weight: 500;
}

.instructor-bio {
    color: #6b7280;
    line-height: 1.6;
}

/* Avis et Reviews */
.reviews-overview {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 2rem;
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #e5e7eb;
}

.overall-rating {
    text-align: center;
}

.rating-score {
    font-size: 4rem;
    font-weight: 800;
    color: var(--course-primary);
    line-height: 1;
}

.rating-stars {
    color: #F59E0B;
    font-size: 1.5rem;
    margin: 0.5rem 0;
}

.rating-text {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.25rem;
}

.rating-count {
    color: #6b7280;
    font-size: 0.875rem;
}

.rating-breakdown {
    space-y: 0.75rem;
}

.rating-bar {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.star-label {
    width: 60px;
    font-size: 0.875rem;
    color: #6b7280;
}

.progress-bar-modern {
    flex-grow: 1;
    height: 8px;
    background: #e5e7eb;
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: var(--gradient-primary);
    transition: width 1s ease;
}

.percentage {
    width: 40px;
    text-align: right;
    font-size: 0.875rem;
    color: #6b7280;
}

/* Témoignages */
.testimonials-section {
    space-y: 1.5rem;
}

.testimonial-card {
    background: white;
    padding: 1.5rem;
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-xs);
    transition: var(--transition-base);
}

.testimonial-card:hover {
    box-shadow: var(--shadow-sm);
    transform: translateY(-2px);
}

.testimonial-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.reviewer-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
}

.reviewer-name {
    font-weight: 600;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.verified-reviewer {
    width: 16px;
    height: 16px;
    background: var(--course-success);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.625rem;
}

.review-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-top: 0.25rem;
}

.review-rating {
    color: #F59E0B;
    font-size: 0.875rem;
}

.review-date {
    color: #6b7280;
    font-size: 0.75rem;
}

.testimonial-content p {
    color: #374151;
    line-height: 1.6;
    margin: 0;
}

.testimonial-footer {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #f3f4f6;
}

.helpful-count {
    color: #6b7280;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.helpful-count i {
    color: var(--course-primary);
}

/* FAQ */
.faq-container {
    space-y: 1rem;
}

.faq-item {
    background: white;
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-xs);
    overflow: hidden;
}

.faq-question {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.5rem;
    cursor: pointer;
    transition: var(--transition-fast);
    background: white;
}

.faq-question:hover {
    background: #f9fafb;
}

.faq-question h3 {
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.faq-toggle {
    width: 24px;
    height: 24px;
    background: var(--course-primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition-fast);
}

.faq-question[aria-expanded="true"] .faq-toggle {
    transform: rotate(45deg);
}

.faq-content {
    padding: 0 1.5rem 1.5rem;
    color: #6b7280;
    line-height: 1.6;
}

/* Sidebar Moderne */
.sidebar-modern {
    position: sticky;
    top: 100px;
    space-y: 2rem;
}

.enrollment-card-modern {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    border-top: 4px solid var(--course-primary);
}

.price-section {
    padding: 2rem;
    text-align: center;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

.current-price {
    font-size: 3rem;
    font-weight: 800;
    color: var(--course-primary);
    line-height: 1;
}

.current-price.free {
    color: var(--course-success);
}

.price-details {
    margin-top: 0.5rem;
}

.original-price {
    text-decoration: line-through;
    color: #9ca3af;
    font-size: 1.25rem;
    margin-right: 0.5rem;
}

.discount {
    background: var(--course-accent);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-sm);
    font-size: 0.875rem;
    font-weight: 600;
}

.free-text {
    color: var(--course-success);
    font-weight: 600;
}

.promotion-timer {
    margin-top: 1rem;
    color: var(--course-accent);
    font-size: 0.875rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.enrollment-actions {
    padding: 2rem;
    space-y: 1rem;
}

.btn-enroll {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    width: 100%;
    padding: 1rem 1.5rem;
    border-radius: var(--radius-lg);
    font-weight: 700;
    font-size: 1.125rem;
    text-decoration: none;
    transition: var(--transition-bounce);
    border: none;
    cursor: pointer;
    box-shadow: var(--shadow-md);
}

.btn-enroll.primary {
    background: var(--gradient-primary);
    color: white;
}

.btn-enroll.primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    color: white;
}

.btn-enroll.free {
    background: var(--gradient-primary);
    color: white;
}

.btn-enroll.enrolled {
    background: var(--course-success);
    color: white;
}

.btn-secondary-action {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    width: 100%;
    padding: 0.75rem 1.5rem;
    border-radius: var(--radius-lg);
    font-weight: 600;
    text-decoration: none;
    background: white;
    color: var(--course-primary);
    border: 2px solid var(--course-primary);
    transition: var(--transition-fast);
}

.btn-secondary-action:hover {
    background: var(--course-primary);
    color: white;
}

.enrollment-status {
    text-align: center;
    color: var(--course-success);
    font-size: 0.875rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.guarantees {
    padding: 1.5rem 2rem;
    background: #f9fafb;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}

.guarantee-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    font-size: 0.875rem;
    font-weight: 500;
}

.guarantee-item i {
    color: var(--course-success);
}

.includes-section {
    padding: 2rem;
}

.includes-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1rem;
}

.includes-list {
    space-y: 0.75rem;
}

.include-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #374151;
    font-size: 0.875rem;
}

.include-item i {
    color: var(--course-primary);
    width: 16px;
}

.social-share-section {
    padding: 1.5rem 2rem;
    border-top: 1px solid #e5e7eb;
    text-align: center;
}

.social-share-section h4 {
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 1rem;
}

.social-buttons {
    display: flex;
    justify-content: center;
    gap: 0.75rem;
}

.social-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    text-decoration: none;
    transition: var(--transition-fast);
    color: white;
}

.social-btn.facebook { background: #1877f2; }
.social-btn.twitter { background: #1da1f2; }
.social-btn.linkedin { background: #0077b5; }
.social-btn.whatsapp { background: #25d366; }
.social-btn.copy { background: #6b7280; }

.social-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    color: white;
}

/* Formations Recommandées */
.recommended-courses-modern {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

.recommended-courses-modern .section-title {
    padding: 1.5rem 2rem;
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
    border-bottom: 1px solid #e5e7eb;
}

.recommended-list {
    padding: 1rem;
    space-y: 1rem;
}

.recommended-item {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    background: #f9fafb;
    border-radius: var(--radius-md);
    transition: var(--transition-base);
}

.recommended-item:hover {
    background: white;
    box-shadow: var(--shadow-sm);
    transform: translateY(-2px);
}

.course-thumbnail {
    position: relative;
    width: 80px;
    height: 60px;
    flex-shrink: 0;
    border-radius: var(--radius-sm);
    overflow: hidden;
}

.course-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.course-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: var(--transition-fast);
}

.course-thumbnail:hover .course-overlay {
    opacity: 1;
}

.view-course {
    color: white;
    font-size: 1.25rem;
}

.course-info {
    flex-grow: 1;
}

.course-title a {
    color: #1f2937;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.875rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.course-title a:hover {
    color: var(--course-primary);
}

.course-instructor {
    color: #6b7280;
    font-size: 0.75rem;
    margin: 0.25rem 0;
}

.course-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0.25rem 0;
}

.rating-value {
    font-size: 0.75rem;
    font-weight: 600;
    color: #1f2937;
}

.stars {
    color: #F59E0B;
    font-size: 0.625rem;
}

.course-price {
    font-weight: 700;
    color: var(--course-primary);
    font-size: 0.875rem;
}

/* Modal Moderne */
.modern-modal .modal-content {
    border: none;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-2xl);
}

.modern-modal .modal-header {
    background: var(--gradient-primary);
    color: white;
    border-bottom: none;
    border-radius: var(--radius-lg) var(--radius-lg) 0 0;
}

.modern-modal .btn-close {
    filter: invert(1);
}

.video-container {
    position: relative;
    aspect-ratio: 16/9;
    border-radius: var(--radius-md);
    overflow: hidden;
    margin-bottom: 1rem;
}

.preview-video-placeholder {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.video-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
}

.play-button-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--gradient-primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    cursor: pointer;
    transition: var(--transition-bounce);
}

.play-button-large:hover {
    transform: scale(1.1);
}

.preview-info h6 {
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.preview-info p {
    color: #6b7280;
    margin: 0;
}

/* Animations */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

@keyframes floatShape {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-30px) rotate(180deg); }
}

@keyframes floatInfo {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-5px); }
}

/* Responsive Design */
@media (max-width: 1024px) {
    .course-stats-modern {
        gap: 1rem;
    }
    
    .instructor-profile {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .reviews-overview {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .course-hero-modern {
        min-height: auto;
        padding: 2rem 0;
    }
    
    .hero-title-modern {
        font-size: 2.5rem;
    }
    
    .hero-actions {
        flex-direction: column;
    }
    
    .btn-modern {
        justify-content: center;
    }
    
    .course-stats-modern {
        flex-direction: column;
        gap: 1rem;
    }
    
    .preview-container {
        transform: none;
    }
    
    .learning-grid {
        grid-template-columns: 1fr;
    }
    
    .instructor-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .sidebar-modern {
        position: static;
        margin-top: 2rem;
    }
    
    .guarantees {
        flex-direction: column;
        align-items: center;
    }
    
    .social-buttons {
        flex-wrap: wrap;
    }
}

@media (max-width: 480px) {
    .hero-title-modern {
        font-size: 2rem;
    }
    
    .hero-description-modern {
        font-size: 1rem;
    }
    
    .current-price {
        font-size: 2.5rem;
    }
    
    .instructor-stats {
        grid-template-columns: 1fr;
    }
    
    .nav-modern {
        flex-direction: column;
    }
    
    .nav-item-modern {
        border-bottom: none;
        border-left: 3px solid transparent;
    }
    
    .nav-item-modern:hover,
    .nav-item-modern.active {
        border-left-color: var(--course-primary);
        border-bottom-color: transparent;
    }
}

/* Optimizations for better performance */
.modern-card,
.learning-item,
.testimonial-card,
.recommended-item {
    will-change: transform;
}

/* Accessibility improvements */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    :root {
        --course-bg: #1f2937;
        --course-card-bg: #374151;
        --course-text: #f9fafb;
        --course-text-muted: #9ca3af;
    }
}

/* Retina display optimization */
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 2dppx) {
    .preview-image,
    .instructor-avatar,
    .reviewer-avatar,
    .course-thumbnail img {
        image-rendering: -webkit-optimize-contrast;
        image-rendering: crisp-edges;
    }
}
</style>
@endpush

@push('scripts')
<!-- AOS Animation Library -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS animations
    AOS.init({
        duration: 800,
        easing: 'ease-out-cubic',
        once: true,
        offset: 100
    });

    // Navigation moderne - gestion des onglets
    const navItems = document.querySelectorAll('.nav-item-modern');
    const sections = document.querySelectorAll('.content-section');

    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all nav items and sections
            navItems.forEach(nav => nav.classList.remove('active'));
            sections.forEach(section => section.classList.remove('active'));
            
            // Add active class to clicked nav item
            this.classList.add('active');
            
            // Show corresponding section
            const targetSection = this.getAttribute('data-section');
            const targetElement = document.getElementById(targetSection);
            if (targetElement) {
                targetElement.classList.add('active');
                
                // Smooth scroll to section
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Smooth scrolling for navigation links
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

    // Countdown timer avec animation
    function updateCountdown() {
        const endDate = new Date();
        endDate.setDate(endDate.getDate() + 2);
        endDate.setHours(23, 59, 59, 999);
        
        function animate() {
            const now = new Date();
            const diff = endDate - now;
            
            if (diff <= 0) {
                const countdownElements = document.querySelectorAll('#countdown-modern, #countdown');
                countdownElements.forEach(el => {
                    if (el) el.textContent = "Offre expirée";
                });
                return;
            }
            
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);
            
            let timeText = "";
            if (days > 0) {
                timeText = `${days}j ${hours}h ${minutes}m`;
            } else if (hours > 0) {
                timeText = `${hours}h ${minutes}m ${seconds}s`;
            } else {
                timeText = `${minutes}m ${seconds}s`;
            }
            
            const countdownElements = document.querySelectorAll('#countdown-modern, #countdown');
            countdownElements.forEach(el => {
                if (el) {
                    el.textContent = timeText;
                    
                    // Add pulse animation when time is low
                    if (days === 0 && hours < 2) {
                        el.style.animation = 'pulse 1s infinite';
                    }
                }
            });
            
            requestAnimationFrame(animate);
        }
        
        animate();
    }
    
    updateCountdown();

    // FAQ Accordion Animation
    const faqQuestions = document.querySelectorAll('.faq-question');
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const target = this.getAttribute('data-bs-target');
            const content = document.querySelector(target);
            const icon = this.querySelector('.faq-toggle i');
            
            if (content) {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                
                // Animate icon
                if (icon) {
                    icon.style.transform = isExpanded ? 'rotate(0deg)' : 'rotate(45deg)';
                }
            }
        });
    });

    // Module Accordion Toggle
    const moduleToggles = document.querySelectorAll('.module-toggle');
    moduleToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const icon = this.querySelector('i');
            const target = this.getAttribute('data-bs-target');
            const content = document.querySelector(target);
            
            if (icon && content) {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                
                // Animate icon rotation
                icon.style.transform = isExpanded ? 'rotate(0deg)' : 'rotate(180deg)';
                icon.style.transition = 'transform 0.3s ease';
            }
        });
    });

    // Parallax effect for hero section
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const hero = document.querySelector('.course-hero-modern');
        const floatingShapes = document.querySelectorAll('.floating-shape');
        
        if (hero) {
            const rate = scrolled * -0.5;
            hero.style.transform = `translateY(${rate}px)`;
        }
        
        // Animate floating shapes
        floatingShapes.forEach((shape, index) => {
            const rate = scrolled * (0.2 + index * 0.1);
            shape.style.transform = `translateY(${rate}px) rotate(${rate * 0.5}deg)`;
        });
    });

    // Sticky navigation active state
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.id;
                navItems.forEach(item => {
                    item.classList.remove('active');
                    if (item.getAttribute('data-section') === id) {
                        item.classList.add('active');
                    }
                });
            }
        });
    }, {
        threshold: 0.3,
        rootMargin: '-100px 0px'
    });

    sections.forEach(section => {
        observer.observe(section);
    });

    // Social share functionality
    const socialButtons = document.querySelectorAll('.social-btn');
    socialButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const url = window.location.href;
            const title = document.querySelector('.hero-title-modern').textContent;
            
            if (this.classList.contains('facebook')) {
                window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank');
            } else if (this.classList.contains('twitter')) {
                window.open(`https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`, '_blank');
            } else if (this.classList.contains('linkedin')) {
                window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`, '_blank');
            } else if (this.classList.contains('whatsapp')) {
                window.open(`https://wa.me/?text=${encodeURIComponent(title + ' ' + url)}`, '_blank');
            } else if (this.classList.contains('copy')) {
                // Copy to clipboard
                navigator.clipboard.writeText(url).then(() => {
                    // Show feedback
                    const originalIcon = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-check"></i>';
                    this.style.background = '#10B981';
                    
                    setTimeout(() => {
                        this.innerHTML = originalIcon;
                        this.style.background = '#6b7280';
                    }, 2000);
                });
            }
        });
    });

    // Enhanced hover effects for cards
    const cards = document.querySelectorAll('.modern-card, .testimonial-card, .recommended-item');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
            this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Progress bar animation for ratings
    const progressBars = document.querySelectorAll('.progress-fill');
    const progressObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const width = entry.target.style.width;
                entry.target.style.width = '0%';
                setTimeout(() => {
                    entry.target.style.width = width;
                    entry.target.style.transition = 'width 1.5s ease-out';
                }, 100);
            }
        });
    }, { threshold: 0.5 });

    progressBars.forEach(bar => {
        progressObserver.observe(bar);
    });

    // Enhanced button interactions
    const modernButtons = document.querySelectorAll('.btn-modern, .btn-enroll, .btn-secondary-action');
    modernButtons.forEach(btn => {
        btn.addEventListener('mousedown', function() {
            this.style.transform = 'scale(0.95)';
        });
        
        btn.addEventListener('mouseup', function() {
            this.style.transform = 'scale(1)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });

    // Video preview modal enhancements
    const playButtons = document.querySelectorAll('.play-button-modern, .play-button-large');
    playButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Add ripple effect
            const ripple = document.createElement('div');
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(255, 255, 255, 0.3)';
            ripple.style.width = '100px';
            ripple.style.height = '100px';
            ripple.style.left = '50%';
            ripple.style.top = '50%';
            ripple.style.transform = 'translate(-50%, -50%) scale(0)';
            ripple.style.animation = 'ripple 0.6s linear';
            
            this.style.position = 'relative';
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Image lazy loading with blur effect
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.add('loaded');
                imageObserver.unobserve(img);
            }
        });
    });

    images.forEach(img => {
        imageObserver.observe(img);
    });

    // Enhanced search and filter interactions (if needed for future extensions)
    const searchInputs = document.querySelectorAll('input[type="search"]');
    searchInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.boxShadow = '0 0 0 3px rgba(30, 163, 139, 0.1)';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.boxShadow = 'none';
        });
    });

    // Performance optimization: Debounce scroll events
    let ticking = false;
    function updateOnScroll() {
        // Your scroll-based animations here
        ticking = false;
    }

    window.addEventListener('scroll', function() {
        if (!ticking) {
            requestAnimationFrame(updateOnScroll);
            ticking = true;
        }
    });
});

// Add custom CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    @keyframes ripple {
        to {
            transform: translate(-50%, -50%) scale(4);
            opacity: 0;
        }
    }
    
    .loaded {
        filter: blur(0);
        transition: filter 0.3s ease;
    }
    
    img[data-src] {
        filter: blur(5px);
        transition: filter 0.3s ease;
    }
`;
document.head.appendChild(style);
</script>
@endpush