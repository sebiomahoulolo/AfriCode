{{-- resources/views/livewire/course-showcase.blade.php --}}

@push('styles')
<style>
    :root {
        --africode-primary: #1EA38B; /* Vert Principal */
        --africode-accent-orange: #FF8E2A; /* Orange */
        --africode-accent-red: #E32D31; /* Rouge */
        --africode-highlight-green: #27B371; /* Vert Clair */
        --africode-white: #FFFFFF; /* Blanc */
        --africode-dark-text: #333333; /* Texte foncé */
        --africode-light-bg: #f8f9fa; /* Fond clair */
        --africode-border-light: rgba(255, 255, 255, 0.5); /* Bordure boutons */
        --udemy-text-dark: #1c1d1f; /* Conservé pour certains textes */
        --udemy-text-light: #6a6f73; /* Conservé pour textes secondaires */
        --udemy-star-gold: #e59819; /* Conservé pour étoiles */
        --udemy-bestseller-bg: #eceb98; /* Conservé pour badges bestseller */
        --udemy-bestseller-text: #3d3c0a;
        --udemy-new-bg: #ceeafe; /* Conservé pour badges nouveauté */
        --udemy-new-text: #043966;
        --udemy-border-color: #d1d7dc; /* Gris clair pour les bordures */
    }

    /* --- Styles des onglets et boutons globaux --- */
    .course-showcase-livewire .ud-heading-serif {
        font-family: ui-serif, Georgia, Cambria, "Times New Roman", Times, serif;
    }

    .course-showcase-livewire .ud-tab-nav-button {
        background-color: transparent;
        border: none;
        color: var(--africode-dark-text);
        font-weight: 700;
        padding: 0.75rem 1rem;
        margin: 0.25rem;
        border-bottom: 2px solid transparent;
        transition: color 0.2s ease, border-color 0.2s ease;
    }
    .course-showcase-livewire .ud-tab-nav-button:hover,
    .course-showcase-livewire .ud-tab-nav-button.ud-tab-active {
        color: var(--africode-primary);
        border-bottom-color: var(--africode-primary);
    }
    .course-showcase-livewire .ud-btn-secondary {
        border: 1px solid var(--africode-primary);
        color: var(--africode-primary);
        font-weight: 700;
        padding: 0.75rem 1.5rem;
        border-radius: 4px;
        transition: background-color 0.2s ease-in-out, border-color 0.2s ease-in-out, color 0.2s ease-in-out;
    }
    .course-showcase-livewire .ud-btn-secondary:hover {
        background-color: var(--africode-primary);
        color: var(--africode-white);
    }

    /* Style de la carte exactement comme dans index.blade.php */
    .course-card {
        background-color: white;
        border: 1px solid #d1d7dc !important;
        box-shadow: none !important;
        border-radius: 4px;
        transition: box-shadow 0.2s ease-in-out;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .course-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,.08) !important;
    }
    .course-img-container {
        aspect-ratio: 16/9;
        overflow: hidden;
        position: relative;
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
    
    .card-title.fw-bold.ud-text-md {
        font-weight: 700; 
        line-height: 1.2;
        font-size: 1.25rem;
    }
    
    .text-muted { 
        color: var(--udemy-text-light) !important; 
    }
    
    .ud-text-xs { 
        font-size: 0.75rem; 
    }
    
    .ud-text-md { 
        font-size: 1rem; 
    }
    
    .ud-text-sm { 
        font-size: 0.875rem; 
    }
    
    .star-rating .fa-xs { 
        font-size: 0.75em; 
    }
    
    .bestseller-badge {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.25em 0.5em;
    }
    
    /* Pour les titres de cours sur plusieurs lignes */
    .course-title-ellipsis {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        min-height: calc(1.2em * 2); /* Hauteur pour 2 lignes de texte */
    }
    
    /* Pour les descriptions sur plusieurs lignes */
    .course-desc-ellipsis {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        min-height: calc(1.4em * 2);
    }

    /* Ces classes peuvent être supprimées si vous utilisez exactement les classes de index.blade.php */
    .course-card-udemy, .card-body-udemy, .star-rating-udemy,
    .rating-score, .reviews-count, .course-instructor,
    .course-rating, .course-meta-info, .course-price,
    .badge-udemy-container, .badge-udemy, .badge-bestseller, .badge-new {
        /* On peut les laisser pour la compatibilité, mais elles ne sont plus nécessaires */
    }

    /* Styles pour les catégories */
    .category-tabs {
        margin-bottom: 2rem;
    }

    .category-tabs .ud-tab-nav-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.5rem;
        margin: 0.25rem;
        white-space: nowrap;
        font-weight: 700;
        transition: all 0.2s ease;
    }

    /* Amélioration du spinner de chargement */
    .course-showcase-livewire .spinner-border {
        width: 3rem;
        height: 3rem;
        color: var(--africode-primary);
    }

    /* Styles pour les messages vides */
    .course-showcase-livewire .text-muted {
        color: var(--udemy-text-light) !important;
    }

    /* Styles pour la pagination */
    .course-showcase-livewire .pagination {
        margin-top: 1.5rem;
    }
    
    .course-showcase-livewire .page-link {
        color: var(--africode-dark-text);
        border-color: var(--udemy-border-color);
    }
    
    .course-showcase-livewire .page-item.active .page-link {
        background-color: var(--africode-primary);
        border-color: var(--africode-primary);
    }
</style>
@endpush

<div class="course-showcase-livewire container my-5">
    <h2 class="section-title fw-bold mb-4 ud-heading-serif text-center">Explorez nos Cours par Catégorie</h2>
    
    <div class="category-tabs mb-5">
        <div class="d-flex flex-wrap justify-content-center">
            <button
                wire:click="selectAllCategories"
                class="btn {{ $selectedCategory === null ? 'ud-tab-nav-button ud-tab-active' : 'ud-tab-nav-button' }}">
                Tous les cours
            </button>
            @foreach($categories as $category)
                <button 
                    wire:click="selectCategory({{ $category->id }})" 
                    class="btn {{ $selectedCategory == $category->id ? 'ud-tab-nav-button ud-tab-active' : 'ud-tab-nav-button' }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
    </div>

    <div wire:loading.delay.long class="text-center my-4">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Chargement...</span>
        </div>
        <p>Chargement des cours...</p>
    </div>

    <div class="courses-container" wire:loading.remove>
        <div class="row g-4"> {{-- g-4 pour l'espacement entre les cartes --}}
            @forelse($courses as $course)
                <div class="col-12 col-sm-6 col-lg-3 d-flex">
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
                            </div>
                            <div class="card-body d-flex flex-column p-3">
                                <h5 class="card-title fw-bold ud-text-md mb-1 course-title-ellipsis">{{ $course->title ?: 'Titre du cours indisponible' }}</h5>
                                <p class="card-text text-muted small mb-2 course-desc-ellipsis">{{ $course->short_description ?: Str::limit(strip_tags($course->full_description ?? 'Description courte indisponible.'), 80) }}</p>
                                <div class="ud-text-xs text-muted mb-2">
                                    {{ $course->formateur ? $course->formateur->full_name : 'Instructeur AfriCode' }}
                                </div>
                                @php
                                    $avgRating = $course->ratings_avg_rating ?? 0; // Note moyenne
                                    $reviewsCount = $course->ratings_count ?? 0; // Nombre d'avis
                                @endphp
                                <div class="d-flex align-items-center mb-2">
                                    @if($reviewsCount > 0)
                                        <span class="text-warning me-1 fw-bold ud-text-sm">{{ number_format($avgRating, 1) }}</span>
                                        <div class="text-warning me-1 star-rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($avgRating))
                                                    <i class="fas fa-star fa-xs"></i>
                                                @elseif ($i - 0.5 <= $avgRating)
                                                    <i class="fas fa-star-half-alt fa-xs"></i>
                                                @else
                                                    <i class="far fa-star fa-xs"></i> {{-- Empty star --}}
                                                @endif
                                            @endfor
                                        </div>
                                        <small class="text-muted ud-text-xs">({{ number_format($reviewsCount) }})</small>
                                    @endif
                                </div>
                                 
                                <div class="mt-auto">
                                    <div class="d-flex align-items-center">
                                        @if(isset($course->price) && $course->price > 0)
                                            <p class="text-success"><strong>{{ number_format($course->price, 0) }} €</strong> | Certificat inclus | <span class="text-danger">{{ number_format($course->price, 0) }}h</span></p>
                   
                                            @if($course->original_price && $course->original_price > $course->price)
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
                    <p class="text-muted" style="font-size: 1.2rem;">Aucun cours disponible correspondant à votre sélection.</p>
                    <img src="{{ asset('assets/images/empty-state.svg') }}" alt="Aucun cours" style="max-width: 200px; opacity: 0.7;" class="my-3">
                </div>
            @endforelse
        </div>
        
        @if ($courses->hasPages())
            <div class="mt-5 d-flex justify-content-center">
                {{ $courses->links() }}
            </div>
        @endif
    </div>
    
    <div class="text-center mt-5">
        <a href="{{ route('courses.index') }}" class="btn ud-btn-secondary">Voir tous les cours</a>
    </div>
</div>