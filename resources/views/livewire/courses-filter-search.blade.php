<div class="courses-filter-search-container">
    <!-- Section filtres de recherche -->
    <div class="search-section bg-light py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="search-container position-relative mb-4">
                        <div class="input-group shadow-sm">
                            <input wire:model.live.debounce.300ms="search" 
                                   class="form-control form-control-lg py-3 ps-4 border-end-0" 
                                   type="search" 
                                   placeholder="Rechercher une formation..." 
                                   aria-label="Recherche">
                            <button class="btn btn-primary btn-lg px-4" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-center mt-3">
                <button wire:click="toggleAdvancedFilters" class="btn btn-outline-primary rounded-pill">
                    <i class="fas fa-sliders-h me-2"></i>
                    {{ $showAdvancedFilters ? 'Masquer les filtres avancés' : 'Filtres avancés' }}
                </button>
            </div>
        </div>
    </div>

    <!-- Section filtres avancés (conditionnelle) -->
    <div class="advanced-filters-section py-4" style="display: {{ $showAdvancedFilters ? 'block' : 'none' }}">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Filtre par catégorie -->
                        <div class="col-md-6 col-lg-3 mb-3">
                            <h6 class="mb-3 fw-semibold">Catégorie</h6>
                            <div class="overflow-auto" style="max-height: 200px;">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="category" id="cat-all" 
                                           wire:click="selectCategory(null)"
                                           {{ $selectedCategory === null ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cat-all">
                                        Toutes les catégories
                                    </label>
                                </div>
                                
                                @foreach($categories as $category)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="category" id="cat-{{ $category->id }}" 
                                           wire:click="selectCategory({{ $category->id }})"
                                           {{ $selectedCategory == $category->id ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cat-{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Filtre par prix -->
                        <div class="col-md-6 col-lg-3 mb-3">
                            <h6 class="mb-3 fw-semibold">Prix</h6>
                            <div class="mb-2">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="price" id="price-all" 
                                           wire:click="setPriceRange('all')"
                                           {{ $priceRange === 'all' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="price-all">
                                        Tous les prix
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="price" id="price-free" 
                                           wire:click="setPriceRange('free')"
                                           {{ $priceRange === 'free' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="price-free">
                                        Gratuit
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="price" id="price-paid" 
                                           wire:click="setPriceRange('paid')"
                                           {{ $priceRange === 'paid' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="price-paid">
                                        Payant
                                    </label>
                                </div>
                                
                                <div class="d-flex align-items-center mb-2">
                                    <span class="me-2">De</span>
                                    <input type="number" class="form-control form-control-sm" placeholder="Min" 
                                           wire:model.live.debounce.500ms="minPrice" min="0">
                                    <span class="mx-2">à</span>
                                    <input type="number" class="form-control form-control-sm" placeholder="Max" 
                                           wire:model.live.debounce.500ms="maxPrice" min="0">
                                    <span class="ms-2">€</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Filtre par niveau -->
                        <div class="col-md-6 col-lg-3 mb-3">
                            <h6 class="mb-3 fw-semibold">Niveau</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="level" id="level-all" 
                                       wire:click="setLevel('all')"
                                       {{ $level === 'all' ? 'checked' : '' }}>
                                <label class="form-check-label" for="level-all">
                                    Tous les niveaux
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="level" id="level-debutant" 
                                       wire:click="setLevel('debutant')"
                                       {{ $level === 'debutant' ? 'checked' : '' }}>
                                <label class="form-check-label" for="level-debutant">
                                    Débutant
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="level" id="level-intermediaire" 
                                       wire:click="setLevel('intermediaire')"
                                       {{ $level === 'intermediaire' ? 'checked' : '' }}>
                                <label class="form-check-label" for="level-intermediaire">
                                    Intermédiaire
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="level" id="level-avance" 
                                       wire:click="setLevel('avance')"
                                       {{ $level === 'avance' ? 'checked' : '' }}>
                                <label class="form-check-label" for="level-avance">
                                    Avancé
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="level" id="level-tous-niveaux" 
                                       wire:click="setLevel('tous_niveaux')"
                                       {{ $level === 'tous_niveaux' ? 'checked' : '' }}>
                                <label class="form-check-label" for="level-tous-niveaux">
                                    Tous niveaux
                                </label>
                            </div>
                        </div>
                        
                        <!-- Filtre par certification -->
                        <div class="col-md-6 col-lg-3 mb-3">
                            <h6 class="mb-3 fw-semibold">Certification</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="certification" id="cert-all" 
                                       wire:click="$set('isCertifying', null)"
                                       {{ $isCertifying === null ? 'checked' : '' }}>
                                <label class="form-check-label" for="cert-all">
                                    Tous les cours
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="certification" id="cert-yes" 
                                       wire:click="$set('isCertifying', true)"
                                       {{ $isCertifying === true ? 'checked' : '' }}>
                                <label class="form-check-label" for="cert-yes">
                                    Avec certification
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="certification" id="cert-no" 
                                       wire:click="$set('isCertifying', false)"
                                       {{ $isCertifying === false ? 'checked' : '' }}>
                                <label class="form-check-label" for="cert-no">
                                    Sans certification
                                </label>
                            </div>
                        </div>
                        
                        <!-- Tri -->
                        <div class="col-md-6 col-lg-3 mb-3">
                            <h6 class="mb-3 fw-semibold">Trier par</h6>
                            <select class="form-select" wire:model.live="sort">
                                <option value="latest">Les plus récents</option>
                                <option value="oldest">Les plus anciens</option>
                                <option value="price_asc">Prix croissant</option>
                                <option value="price_desc">Prix décroissant</option>
                                <option value="rating">Meilleures notes</option>
                                <option value="popular">Popularité</option>
                            </select>
                            
                            <div class="mt-4">
                                <button wire:click="resetFilters" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-undo me-2"></i> Réinitialiser les filtres
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Résultats de recherche -->
    <div class="courses-results py-5">
        <div class="container">
            <!-- Indicateur de recherche active + nombre de résultats -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div>
                    @if(!empty($search) || $selectedCategory || $level !== 'all' || $priceRange !== 'all')
                        <h5 class="mb-0">
                            <span class="text-muted">Résultats pour:</span> 
                            @if(!empty($search))
                                <span class="badge bg-primary me-2">"{{ $search }}"</span>
                            @endif
                            @if($selectedCategory)
                                <span class="badge bg-secondary me-2">{{ $categories->firstWhere('id', $selectedCategory)->name }}</span>
                            @endif
                            @if($level !== 'all')
                                <span class="badge bg-info text-dark me-2">
                                    {{ $level === 'beginner' ? 'Débutant' : 
                                      ($level === 'intermediate' ? 'Intermédiaire' : 'Avancé') }}
                                </span>
                            @endif
                            @if($priceRange !== 'all')
                                <span class="badge bg-success me-2">
                                    {{ $priceRange === 'free' ? 'Gratuit' : 'Payant' }}
                                </span>
                            @endif
                        </h5>
                    @endif
                </div>
                
                <p class="mb-0">
                    <strong>{{ $courses->total() }}</strong> formation(s) trouvée(s)
                </p>
            </div>

            <!-- Indicateur de chargement -->
            <div wire:loading.delay.long class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p class="mt-2">Chargement des formations...</p>
            </div>

            <!-- Liste des cours -->
            <div wire:loading.delay.long.remove class="row g-4">
                @forelse($courses as $course)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex">
                        <div class="card course-card flex-fill">
                            <a href="{{ route('courses.show', $course->slug) }}" class="text-decoration-none text-dark">
                                <div class="course-img-container position-relative">
                                    @if($course->cover_image_path)
                                        <img src="{{ asset($course->cover_image_path) }}" class="card-img-top" alt="{{ $course->title }}" onerror="this.src='{{ asset('assets/courses/images/course-placeholder.avif') }}'">
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
                                    
                                    <!-- Rating -->
                                    <div class="d-flex align-items-center mb-2">
                                        @php
                                            $avgRating = $course->ratings_avg_rating ?? 0; // Note moyenne
                                            $reviewsCount = $course->ratings_count ?? 0; // Nombre d'avis
                                        @endphp
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
                        <h4 class="mb-3">Aucune formation trouvée</h4>
                        <p class="text-muted">Essayez de modifier vos filtres ou votre recherche.</p>
                        <button wire:click="resetFilters" class="btn btn-primary mt-3">
                            <i class="fas fa-sync-alt me-2"></i> Réinitialiser tous les filtres
                        </button>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($courses->hasPages())
                <div class="mt-5 d-flex justify-content-center">
                    {{ $courses->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Styles pour la page -->
    <style>
        /* Styles spécifiques au composant */
        .courses-filter-search-container {
            font-family: "SF Pro Text", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        
        .search-header {
            background-color: var(--africode-light-bg, #f8f9fa);
        }
        
        .ud-heading-serif {
            font-family: ui-serif, Georgia, Cambria, "Times New Roman", Times, serif;
        }
        
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
            border: 1px solid #d1d7dc !important;
            border-radius: 4px;
            height: 100%;
        }
        
        .course-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,.08) !important;
        }
        
        .course-img-container {
            aspect-ratio: 16/9;
            overflow: hidden;
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
        
        .bestseller-badge {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.25em 0.5em;
        }
        
        /* Correction pour les écrans mobiles */
        @media (max-width: 767px) {
            .search-header h1 {
                font-size: 1.75rem;
            }
        }
    </style>
</div>
