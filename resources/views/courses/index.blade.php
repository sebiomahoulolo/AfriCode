@extends('layouts.layout')

@section('title', 'Toutes nos formations - AfriCode')

@section('meta_tags')
    <meta name="description" content="Découvrez toutes les formations disponibles sur AfriCode. Apprenez la programmation, le développement web, mobile, l'IA et plus encore.">
    <meta name="keywords" content="formations, cours en ligne, programmation, développement web, afrique, coder, compétences numériques">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/courses') }}">
    <meta property="og:title" content="Toutes nos formations - AfriCode">
    <meta property="og:description" content="Découvrez toutes les formations disponibles sur AfriCode. Apprenez la programmation, le développement web, mobile, l'IA et plus encore.">
    <meta property="og:image" content="{{ asset('assets/images/africode-og-image.jpg') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/courses') }}">
    <meta property="twitter:title" content="Toutes nos formations - AfriCode">
    <meta property="twitter:description" content="Découvrez toutes les formations disponibles sur AfriCode. Apprenez la programmation, le développement web, mobile, l'IA et plus encore.">
    <meta property="twitter:image" content="{{ asset('assets/images/africode-og-image.jpg') }}">
@endsection

@section('content')
    <!-- Hero Section harmonisé avec les autres pages -->
    <section class="courses-hero py-5">
        <div class="container">
            <div class="row align-items-center min-vh-50">
                <div class="col-lg-8">
                    <div class="hero-content">
                        <h1 class="hero-title mb-4">Toutes nos <span class="text-gradient">formations</span></h1>
                        <p class="hero-subtitle mb-4">Explorez notre catalogue complet de cours pour développer vos compétences numériques et transformer votre carrière.</p>
                        <div class="floating-badges">
                            <span class="tech-badge">
                                @if(isset($useFilters) && $useFilters)
                                    Recherche avancée
                                @else
                                    {{ $courses->total() ?? 0 }} Formations
                                @endif
                            </span>
                            <span class="tech-badge">Certifiées</span>
                            <span class="tech-badge">Tous niveaux</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <div class="organic-container cta-container">
                        @if(!isset($useFilters) || !$useFilters)
                            <a href="{{ route('courses.index', ['level' => 'all']) }}" class="modern-btn">
                                <i class="fas fa-filter me-2"></i>
                                <span>Recherche avancée</span>
                            </a>
                        @else
                            <a href="{{ route('courses.index') }}" class="modern-btn-secondary">
                                <i class="fas fa-list me-2"></i>
                                <span>Voir tous les cours</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if(isset($useFilters) && $useFilters)
        <!-- Section de recherche et filtrage avancé -->
        @livewire('courses-filter-search', ['initialFilters' => $initialFilters ?? []])
    @else
        <!-- Liste des cours avec design harmonisé -->
        <section class="courses-list py-5">
            <div class="container">
                <div class="row g-4">
                    @forelse($courses as $course)
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                        <div class="course-card-enhanced">
                            <a href="{{ route('courses.show', $course->slug) }}" class="text-decoration-none">
                                <div class="course-image">
                                    @if($course->cover_image_path)
                                        <img src="{{ asset($course->cover_image_path) }}" alt="{{ $course->title }}">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="{{ $course->title }}">
                                    @endif
                                    
                                    <!-- Badge de niveau -->
                                    @if($course->level)
                                        <div class="course-level-badge level-{{ strtolower($course->level) }}">
                                            @if($course->level === 'debutant')
                                                Débutant
                                            @elseif($course->level === 'intermediaire')
                                                Intermédiaire
                                            @elseif($course->level === 'avance')
                                                Avancé
                                            @elseif($course->level === 'tous_niveaux')
                                                Tous niveaux
                                            @else
                                                {{ ucfirst($course->level) }}
                                            @endif
                                        </div>
                                    @endif
                                    
                                    <!-- Badge gratuit -->
                                    @if($course->price == 0)
                                        <div class="free-badge">
                                            <i class="fas fa-gift"></i>
                                            Gratuit
                                        </div>
                                    @endif
                                    
                                    <!-- Badge bestseller -->
                                    @php
                                        $isBestseller = ($course->enrollments_count ?? 0) > 50;
                                        $isNew = $course->created_at && $course->created_at->diffInDays(now()) < 30;
                                    @endphp
                                    @if($isBestseller)
                                        <div class="bestseller-badge">
                                            <i class="fas fa-star"></i>
                                            Bestseller
                                        </div>
                                    @elseif($isNew)
                                        <div class="new-badge">
                                            <i class="fas fa-sparkles"></i>
                                            Nouveau
                                        </div>
                                    @endif
                                    
                                    <!-- Badge certifié -->
                                    <div class="certified-badge">
                                        <i class="fas fa-certificate"></i>
                                    </div>
                                </div>
                                
                                <div class="course-content">
                                    <div class="course-rating">
                                        <div class="stars">
                                            @php
                                                $rating = rand(40, 50) / 10; // Simulation du rating
                                                $fullStars = floor($rating);
                                                $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                            @endphp
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $fullStars)
                                                    <i class="fas fa-star"></i>
                                                @elseif($i == $fullStars + 1 && $hasHalfStar)
                                                    <i class="fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                            <span class="rating-value">{{ number_format($rating, 1) }}</span>
                                        </div>
                                        <div class="student-count">
                                            {{ $course->enrollments_count ?? rand(50, 200) }} étudiants
                                        </div>
                                    </div>
                                    
                                    <h3 class="course-title">{{ $course->title ?: 'Titre du cours indisponible' }}</h3>
                                    
                                    <div class="course-instructor">
                                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" 
                                             alt="Instructeur" class="instructor-avatar">
                                        <span class="instructor-name">
                                            {{ $course->formateur ? $course->formateur->full_name : 'Instructeur AfriCode' }}
                                        </span>
                                    </div>
                                    
                                    <div class="course-meta">
                                        <div class="course-duration">
                                            <i class="fas fa-clock"></i>
                                            <span>{{ rand(2, 8) }}h {{ rand(0, 59) }}min</span>
                                        </div>
                                        <div class="course-price {{ $course->price == 0 ? 'free' : '' }}">
                                            @if($course->price > 0)
                                                <i class="fas fa-tag"></i>
                                                <span>{{ number_format($course->price, 0) }} €</span>
                                            @else
                                                <i class="fas fa-gift"></i>
                                                <span>Gratuit</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="course-actions">
                                        <button class="btn btn-primary course-enroll">S'inscrire</button>
                                        <button class="btn btn-outline course-save" title="Sauvegarder">
                                            <i class="far fa-heart"></i>
                                        </button>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state text-center py-5">
                            <div class="organic-container empty-container mb-4">
                                <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                                     alt="Aucun cours" class="empty-image">
                            </div>
                            <h4 class="section-title mb-3">Aucune formation disponible pour le moment</h4>
                            <p class="text-content mb-4">Nous travaillons à l'ajout de nouveaux cours, veuillez vérifier plus tard.</p>
                            <a href="{{ url('/') }}" class="modern-btn">
                                <i class="fas fa-home me-2"></i>
                                <span>Retour à l'accueil</span>
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination modernisée -->
            @if(isset($courses) && $courses->hasPages())
                <div class="pagination-container mt-5">
                    <div class="organic-container pagination-wrapper">
                        {{ $courses->links() }}
                    </div>
                </div>
            @endif
        </div>
    </section>
    @endif

    <!-- CTA Instructeur harmonisé -->
    <section class="instructor-cta py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <h3 class="section-title mb-3">Vous avez des connaissances à <span class="text-gradient">partager</span> ?</h3>
                    <p class="text-content mb-0">Devenez instructeur sur AfriCode et aidez d'autres apprenants à développer leurs compétences numériques.</p>
                </div>
                <div class="col-lg-4 text-center text-lg-end">
                    <div class="organic-container cta-container">
                        <a href="#" class="modern-btn-secondary">
                            <i class="fas fa-chalkboard-teacher me-2"></i>
                            <span>Devenir instructeur</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    /* Variables CSS AfriCode - Cohérentes avec toutes les pages */
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

    /* Hero Section */
    .courses-hero {
        background: linear-gradient(135deg, 
            rgba(30, 163, 139, 0.08) 0%, 
            rgba(255, 142, 42, 0.05) 50%, 
            rgba(39, 179, 113, 0.08) 100%);
        position: relative;
        overflow: hidden;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        color: var(--africode-primary);
        line-height: 1.2;
    }

    .text-gradient {
        background: var(--africode-gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-subtitle {
        font-size: 1.3rem;
        color: var(--africode-gray-dark);
        line-height: 1.6;
    }

    .floating-badges {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .tech-badge {
        background: rgba(30, 163, 139, 0.1);
        color: var(--africode-primary);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 500;
        border: 1px solid rgba(30, 163, 139, 0.2);
        transition: var(--africode-transition);
    }

    .tech-badge:hover {
        background: var(--africode-primary);
        color: white;
        transform: translateY(-2px);
    }

    /* Conteneurs organiques */
    .organic-container {
        border-radius: var(--africode-border-radius);
        overflow: hidden;
        box-shadow: var(--africode-shadow-lg);
        transition: var(--africode-transition);
        position: relative;
    }

    .cta-container {
        background: white;
        padding: 1rem;
        transform: rotate(2deg);
        display: inline-block;
    }

    .empty-container {
        width: 300px;
        height: 200px;
        margin: 0 auto;
        transform: rotate(-3deg);
    }

    .pagination-wrapper {
        background: white;
        padding: 1rem;
        transform: rotate(1deg);
        display: inline-block;
    }

    .empty-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Boutons modernes */
    .modern-btn {
        background: var(--africode-gradient-primary);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 25px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--africode-transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .modern-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(30, 163, 139, 0.3);
        color: white;
    }

    .modern-btn-secondary {
        background: var(--africode-gradient-accent);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 25px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--africode-transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .modern-btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(255, 142, 42, 0.3);
        color: white;
    }

    /* Section titles */
    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--africode-primary);
        margin-bottom: 1.5rem;
    }

    .text-content {
        font-size: 1.1rem;
        color: var(--africode-gray-dark);
        line-height: 1.8;
    }

    /* Cartes de cours - Design compact */
    .course-card-enhanced {
        background: var(--africode-white);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: var(--africode-shadow-md);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 380px; /* Hauteur fixe réduite */
        display: flex;
        flex-direction: column;
    }

    .course-card-enhanced:hover {
        transform: translateY(-5px);
        box-shadow: var(--africode-shadow-lg);
    }

    .course-card-enhanced a {
        text-decoration: none;
        color: inherit;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .course-image {
        position: relative;
        height: 180px; /* Hauteur réduite */
        overflow: hidden;
        flex-shrink: 0;
    }

    .course-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .course-card-enhanced:hover .course-image img {
        transform: scale(1.05);
    }

    .course-content {
        padding: 1rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .course-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--africode-text-dark);
        margin-bottom: 0.8rem;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        height: 2.6rem;
    }

    .course-level-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .level-debutant {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .level-intermediaire {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .level-avance {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .level-tous_niveaux {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
    }

    /* Compatibility avec anciens noms */
    .level-beginner, .level-débutant {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .level-intermediate, .level-intermédiaire {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .level-advanced, .level-avancé {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .free-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--africode-highlight-green);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .bestseller-badge {
        position: absolute;
        bottom: 15px;
        left: 15px;
        background: var(--africode-secondary);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .new-badge {
        position: absolute;
        bottom: 15px;
        left: 15px;
        background: var(--africode-primary);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .certified-badge {
        position: absolute;
        bottom: 15px;
        right: 15px;
        background: rgba(255, 255, 255, 0.9);
        color: var(--africode-primary);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .course-content {
        padding: 1.5rem;
    }

    .course-rating {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .stars {
        display: flex;
        gap: 0.25rem;
        align-items: center;
    }

    .stars i {
        color: #FFD700;
        font-size: 0.875rem;
    }

    .rating-value {
        margin-left: 0.5rem;
        font-weight: 600;
        color: var(--africode-dark-text);
    }

    .student-count {
        font-size: 0.875rem;
        color: var(--africode-gray-dark);
    }

    .course-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--africode-dark-text);
        margin-bottom: 1rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .course-instructor {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .instructor-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
    }

    .instructor-name {
        font-size: 0.875rem;
        color: var(--africode-gray-dark);
    }

    .course-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid var(--africode-gray-medium);
    }

    .course-duration,
    .course-price {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
    }

    .course-duration {
        color: var(--africode-gray-dark);
    }

    .course-price {
        color: var(--africode-primary);
        font-weight: 600;
    }

    .course-price.free {
        color: var(--africode-highlight-green);
    }

    .course-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .course-enroll {
        flex: 1;
        margin-right: 0.75rem;
        background: var(--africode-gradient-primary);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 20px;
        color: white;
        font-weight: 600;
        transition: var(--africode-transition);
    }

    .course-enroll:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(30, 163, 139, 0.3);
    }

    .course-save {
        width: 40px;
        height: 40px;
        border: 2px solid var(--africode-gray-medium);
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--africode-gray-dark);
        transition: var(--africode-transition);
    }

    .course-save:hover {
        border-color: var(--africode-primary);
        color: var(--africode-primary);
        transform: scale(1.1);
    }

    .btn-outline {
        border: 2px solid var(--africode-gray-medium);
        background: white;
        color: var(--africode-gray-dark);
    }

    .btn-outline:hover {
        border-color: var(--africode-primary);
        color: var(--africode-primary);
    }

    /* Sections */
    .courses-list {
        background: white;
    }

    .instructor-cta {
        background: linear-gradient(135deg, var(--africode-gray-light) 0%, var(--africode-gray-medium) 100%);
    }

    .empty-state {
        padding: 3rem;
    }

    .pagination-container {
        display: flex;
        justify-content: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }
        
        .section-title {
            font-size: 2rem;
        }
        
        .organic-container {
            transform: none !important;
        }
        
        .cta-container,
        .empty-container,
        .pagination-wrapper {
            transform: none !important;
            display: block;
            width: 100%;
        }
        
        .course-card-enhanced {
            margin-bottom: 1rem;
        }
    }

    /* Animations d'entrée */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .hero-content {
        animation: fadeInUp 0.8s ease-out;
    }

    .course-card-enhanced {
        animation: fadeInUp 0.8s ease-out 0.2s both;
    }
</style>
@endpush