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
    <!-- En-tête de la page -->
    <div class="courses-header bg-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="fw-bold mb-3 ud-heading-serif">Toutes nos formations</h1>
                    <p class="lead mb-3">Explorez notre catalogue complet de cours pour développer vos compétences numériques</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <a href="{{ route('courses.search') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-filter me-2"></i> Recherche avancée
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des cours -->
    <div class="courses-list py-5">
        <div class="container">
            <div class="row g-4">
                @forelse($courses as $course)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex">
                        <div class="card course-card flex-fill">
                            <a href="{{ route('courses.show', $course->slug) }}" class="text-decoration-none text-dark">
                                <div class="course-img-container position-relative">
                                    @if($course->cover_image_path)
                                        <img src="{{ asset($course->cover_image_path) }}" class="card-img-top" alt="{{ $course->title }}">
                                    @else
                                        <img src="{{ asset('assets/images/course-placeholder.jpg') }}" class="card-img-top" alt="Image du cours par défaut">
                                    @endif
                                    @php
                                        $isBestseller = ($course->enrollments_count ?? 0) > 50; // Seuil pour "Meilleure vente"
                                        $isNew = $course->created_at && $course->created_at->diffInDays(now()) < 30; // Cours de moins de 30 jours
                                    @endphp
                                    @if($isBestseller)
                                        <span class="badge bg-warning text-dark position-absolute bottom-0 start-0 m-2 bestseller-badge">Meilleure vente</span>
                                    @elseif($isNew)
                                        <span class="badge bg-info text-white position-absolute bottom-0 start-0 m-2">Nouveau</span>
                                    @endif
                                    
                                    <!-- Badge de niveau -->
                                    @if($course->level)
                                        <span class="badge bg-light text-dark position-absolute top-0 end-0 m-2">
                                            @if($course->level === 'beginner')
                                                <i class="fas fa-signal-1 text-success"></i> Débutant
                                            @elseif($course->level === 'intermediate')
                                                <i class="fas fa-signal-2 text-warning"></i> Intermédiaire
                                            @elseif($course->level === 'advanced')
                                                <i class="fas fa-signal-3 text-danger"></i> Avancé
                                            @endif
                                        </span>
                                    @endif
                                </div>
                                <div class="card-body d-flex flex-column p-3">
                                    <div class="small mb-2 text-primary">{{ $course->category?->name ?? 'Sans catégorie' }}</div>
                                    
                                    <h5 class="card-title fw-bold ud-text-md mb-1 course-title-ellipsis">
                                        {{ $course->title ?: 'Titre du cours indisponible' }}
                                    </h5>
                                    
                                    <p class="card-text text-muted small mb-2 course-desc-ellipsis">
                                        {{ $course->short_description ?: Str::limit(strip_tags($course->full_description ?? 'Description courte indisponible.'), 80) }}
                                    </p>
                                    
                                    <div class="ud-text-xs text-muted mb-2">
                                        {{ $course->formateur ? $course->formateur->full_name : 'Instructeur AfriCode' }}
                                    </div>
                                    
                                    <!-- Prix -->
                                    <div class="mt-auto">
                                        <div class="d-flex align-items-center">
                                            @if(isset($course->price) && $course->price > 0)
                                                <strong class="fs-5 text-dark me-2 ud-heading-md">{{ number_format($course->price, 0) }} €</strong>
                                                @if(isset($course->original_price) && $course->original_price > $course->price)
                                                    <span class="text-decoration-line-through text-muted ud-text-sm">{{ number_format($course->original_price, 0) }} €</span>
                                                @endif
                                            @elseif(isset($course->price) && $course->price == 0)
                                                <strong class="fs-5 text-success me-2 ud-heading-md">Gratuit</strong>
                                            @else
                                                <strong class="fs-5 text-dark me-2 ud-heading-md"> </strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="empty-state mb-4">
                            <img src="{{ asset('assets/images/empty-state.svg') }}" alt="Aucun cours" style="max-width: 200px; opacity: 0.7;">
                        </div>
                        <h4 class="mb-3">Aucune formation disponible pour le moment</h4>
                        <p class="text-muted">Nous travaillons à l'ajout de nouveaux cours, veuillez vérifier plus tard.</p>
                        <a href="{{ url('/') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-home me-2"></i> Retour à l'accueil
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if(isset($courses) && $courses->hasPages())
                <div class="mt-5 d-flex justify-content-center">
                    {{ $courses->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- CTA pour devenir instructeur -->
    <div class="instructor-cta bg-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mb-4 mb-lg-0 text-center text-lg-start">
                    <h2 class="fw-bold mb-3">Vous avez des connaissances à partager?</h2>
                    <p class="lead mb-0">Devenez instructeur sur AfriCode et aidez d'autres apprenants à développer leurs compétences.</p>
                </div>
                <div class="col-lg-4 text-center text-lg-end">
                    <a href="#" class="btn btn-light btn-lg">Devenir instructeur</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* Style spécifique à la page d'index des cours */
    .course-title-ellipsis {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        min-height: calc(1.2em * 2);
    }
    
    .course-desc-ellipsis {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        min-height: calc(1.4em * 2);
    }
    
    .course-card {
        transition: box-shadow 0.2s ease-in-out;
        border: 1px solid var(--udemy-border-color, #d1d7dc) !important;
        border-radius: 4px;
        height: 100%;
    }
    
    .course-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,.08) !important;
    }
    
    .course-img-container {
        aspect-ratio: 16/9;
        overflow: hidden;
        position: relative;
        background-color: #f0f0f0;
    }
    
    .course-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
        display: block;
    }
    
    .course-card:hover .course-img-container img {
        transform: scale(1.05);
    }
    
    .ud-heading-serif {
        font-family: ui-serif, Georgia, Cambria, "Times New Roman", Times, serif;
    }
    
    .bestseller-badge {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.25em 0.5em;
    }
    
    /* Utilisation des variables africode pour les couleurs principales */
    .text-primary {
        color: var(--africode-primary, #1EA38B) !important;
    }
    
    .bg-primary {
        background-color: var(--africode-primary, #1EA38B) !important;
    }
    
    .btn-primary {
        background-color: var(--africode-primary, #1EA38B);
        border-color: var(--africode-primary, #1EA38B);
    }
    
    .btn-primary:hover, .btn-primary:focus {
        background-color: var(--africode-highlight-green, #27B371);
        border-color: var(--africode-highlight-green, #27B371);
    }
</style>
@endpush