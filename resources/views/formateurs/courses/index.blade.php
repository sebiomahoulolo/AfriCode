@extends('formateurs.layouts.app')

@section('title', 'AfriCode - Mes cours')
@section('page-title', 'Mes cours')
@section('page-subtitle', 'Gérez et organisez tous vos cours')

@section('header-actions')
    <a href="{{ route('formateur.courses.create') }}" class="btn-primary-africode">
        <i class="fas fa-plus me-2"></i>Créer un nouveau cours
    </a>
@endsection

@section('styles')
<style>
    .filters-section {
        background: white;
        border-radius: var(--africode-border-radius);
        box-shadow: var(--africode-shadow-sm);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .course-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
    }
    
    .course-card {
        background: white;
        border-radius: var(--africode-border-radius);
        box-shadow: var(--africode-shadow-sm);
        overflow: hidden;
        transition: var(--africode-transition);
        position: relative;
    }
    
    .course-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--africode-shadow-md);
    }
    
    .course-image {
        height: 200px;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        position: relative;
        min-height: 200px;
    }
    
    .course-image::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.3) 100%);
        z-index: 1;
        pointer-events: none;
    }
    
    .course-status-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        z-index: 2;
    }
    
    .course-content {
        padding: 1.5rem;
    }
    
    .course-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--africode-dark-text);
        margin-bottom: 0.75rem;
        line-height: 1.3;
    }
    
    .course-description {
        color: var(--africode-gray-dark);
        font-size: 0.9rem;
        margin-bottom: 1rem;
        line-height: 1.5;
    }
    
    .course-stats {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        font-size: 0.85rem;
    }
    
    .course-stat {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        color: var(--africode-gray-dark);
    }
    
    .course-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .search-and-filter {
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .search-box {
        flex: 1;
        min-width: 250px;
    }
    
    .filter-group {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }
    
    .sort-dropdown {
        min-width: 150px;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--africode-gray-dark);
    }
    
    .empty-state i {
        font-size: 4rem;
        color: var(--africode-gray-medium);
        margin-bottom: 1.5rem;
    }
    
    /* Pagination */
    .pagination-container {
        margin-top: 2.5rem;
        padding-top: 2rem;
        border-top: 1px solid var(--africode-border-color);
    }
    
    .pagination-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .pagination-info-text {
        color: var(--africode-gray-dark);
        font-size: 0.9rem;
    }
    
    .pagination-info-text strong {
        color: var(--africode-primary);
        font-weight: 600;
    }
    
    .per-page-wrapper {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .per-page-wrapper label {
        font-size: 0.9rem;
        color: var(--africode-gray-dark);
        margin: 0;
        white-space: nowrap;
    }
    
    .per-page-wrapper select {
        padding: 0.5rem 0.75rem;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        font-size: 0.9rem;
        background: white;
        min-width: 70px;
        cursor: pointer;
    }
    
    .per-page-wrapper select:focus {
        outline: none;
        border-color: var(--africode-primary);
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    /* Pagination Bootstrap personnalisée */
    .pagination {
        justify-content: center;
        margin: 0;
    }
    
    .pagination .page-item {
        margin: 0 0.25rem;
    }
    
    .pagination .page-link {
        color: var(--africode-primary);
        background-color: white;
        border: 1px solid #dee2e6;
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
        min-width: 38px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .pagination .page-link i {
        font-size: 0.85rem;
    }
    
    .pagination .page-link:hover {
        color: white;
        background-color: var(--africode-primary);
        border-color: var(--africode-primary);
    }
    
    .pagination .page-item.active .page-link {
        color: white;
        background-color: var(--africode-primary);
        border-color: var(--africode-primary);
        font-weight: 600;
    }
    
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #f8f9fa;
        border-color: #dee2e6;
        cursor: not-allowed;
        opacity: 0.65;
    }
    
    .pagination .page-item.disabled .page-link:hover {
        color: #6c757d;
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .course-grid {
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1rem;
        }
        
        .filters-section {
            padding: 1rem;
        }
        
        .search-and-filter {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-box {
            min-width: auto;
        }
        
        .filter-group {
            flex-wrap: wrap;
        }
    }
    
    @media (max-width: 768px) {
        .course-grid {
            grid-template-columns: 1fr;
        }
        
        .course-image {
            height: 150px;
        }
        
        .course-content {
            padding: 1rem;
        }
        
        .course-title {
            font-size: 1.1rem;
        }
        
        .course-actions {
            flex-direction: column;
        }
        
        .course-actions .btn {
            width: 100%;
        }
        
        .search-and-filter {
            gap: 0.75rem;
        }
        
        .filter-group {
            justify-content: space-between;
        }
        
        .pagination-top {
            flex-direction: column;
            align-items: stretch;
        }
        
        .pagination-info-text {
            text-align: center;
        }
        
        .per-page-wrapper {
            justify-content: center;
        }
    }
    
    @media (max-width: 576px) {
        .filters-section {
            padding: 0.75rem;
            margin-bottom: 1rem;
        }
        
        .course-image {
            height: 120px;
        }
        
        .course-content {
            padding: 0.75rem;
        }
        
        .course-title {
            font-size: 1rem;
        }
        
        .course-stats {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .filter-group {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filter-group .form-select {
            margin-bottom: 0.5rem;
        }
        
        .pagination-info-text {
            font-size: 0.85rem;
        }
        
        .pagination .page-link {
            padding: 0.375rem 0.5rem;
            font-size: 0.875rem;
            min-width: 32px;
        }
        
        .per-page-wrapper label {
            font-size: 0.85rem;
        }
        
        .per-page-wrapper select {
            font-size: 0.85rem;
            padding: 0.4rem 0.6rem;
        }
    }
</style>
@endsection

@section('content')
    <!-- Filtres et recherche -->
    <div class="filters-section" data-aos="fade-up">
        <form method="GET" action="{{ route('formateur.courses.index') }}" class="search-and-filter">
            <!-- Barre de recherche -->
            <div class="search-box">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" 
                           class="form-control" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Rechercher un cours..."
                           id="searchInput">
                    @if(request('search'))
                        <button type="button" class="btn btn-outline-secondary" onclick="clearSearch()" title="Effacer la recherche">
                            <i class="fas fa-times"></i>
                        </button>
                    @endif
                </div>
            </div>
            
            <!-- Filtres -->
            <div class="filter-group">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Tous les statuts</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Publiés</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Brouillons</option>
                </select>
                
                <select name="level" class="form-select" onchange="this.form.submit()">
                    <option value="">Tous les niveaux</option>
                    <option value="beginner" {{ request('level') == 'beginner' ? 'selected' : '' }}>Débutant</option>
                    <option value="intermediate" {{ request('level') == 'intermediate' ? 'selected' : '' }}>Intermédiaire</option>
                    <option value="advanced" {{ request('level') == 'advanced' ? 'selected' : '' }}>Avancé</option>
                    <option value="expert" {{ request('level') == 'expert' ? 'selected' : '' }}>Expert</option>
                </select>
                
                <select name="sort" class="form-select sort-dropdown" onchange="this.form.submit()">
                    <option value="created_desc" {{ request('sort') == 'created_desc' ? 'selected' : '' }}>Plus récents</option>
                    <option value="created_asc" {{ request('sort') == 'created_asc' ? 'selected' : '' }}>Plus anciens</option>
                    <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Titre A-Z</option>
                    <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Titre Z-A</option>
                    <option value="students_desc" {{ request('sort') == 'students_desc' ? 'selected' : '' }}>Plus d'étudiants</option>
                </select>
            </div>
        </form>
        
        <!-- Statistiques rapides -->
        <div class="row mt-3">
            <div class="col-auto">
                <small class="text-muted">
                    <strong>{{ $courses->total() }}</strong> cours trouvé{{ $courses->total() > 1 ? 's' : '' }}
                    @if(request()->hasAny(['search', 'status', 'level']))
                        - <a href="{{ route('formateur.courses.index') }}" class="" style="color:#1EA38B;">Réinitialiser les filtres</a>
                    @endif
                </small>
            </div>
        </div>
    </div>

    <!-- Liste des cours -->
    @if($courses->count() > 0)
        <div class="course-grid" data-aos="fade-up" data-aos-delay="100">
            @foreach($courses as $course)
                <div class="course-card">
                    <!-- Image du cours -->
                    <div class="course-image" style="background-image: url('{{ $course->cover_image_url }}'); background-size: cover; background-position: center; background-repeat: no-repeat; position: relative; height: 200px; overflow: hidden;">
                        <div class="course-status-badge">
                            @if($course->status === 'published')
                                <span class="badge" style="background:#27B371;">
                                    <i class="fas fa-eye me-1"></i>Publié
                                </span>
                            @else
                                <span class="badge" style="background:#FF8E2A;">
                                    <i class="fas fa-edit me-1"></i>Brouillon
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Contenu du cours -->
                    <div class="course-content">
                        <h3 class="course-title">{{ $course->title }}</h3>
                        <p class="course-description">{{ Str::limit($course->short_description, 120) }}</p>
                        
                        <!-- Statistiques -->
                        <div class="course-stats">
                            <div class="course-stat">
                                <i class="fas fa-users" style="color:#1EA38B;"></i>
                                <span>{{ $course->students_count ?? 0 }} étudiants</span>
                            </div>
                            <div class="course-stat">
                                <i class="fas fa-layer-group text-info"></i>
                                <span>{{ $course->modules->count() }} modules</span>
                            </div>
                            @if($course->status === 'published')
                                <div class="course-stat">
                                    <i class="fas fa-star text-warning"></i>
                                    <span>{{ number_format($course->average_rating ?? 0, 1) }}/5</span>
                                </div>
                            @endif
                            <div class="course-stat">
                                <i class="fas fa-signal text-secondary"></i>
                                <span class="text-capitalize">{{ $course->level }}</span>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="course-actions">
                            <a href="{{ route('formateur.manage.course', $course) }}" class="btn btn-sm flex-fill text-white" style="background:linear-gradient(135deg,#1EA38B,#27B371);border:none;">
                                <i class="fas fa-cog me-1"></i>Gérer
                            </a>
                            <a href="{{ route('formateur.courses.edit', $course) }}" class="btn btn-outline-secondary btn-sm flex-fill">
                                <i class="fas fa-edit me-1"></i>Modifier
                            </a>
                            @php
                                $firstLesson = $course->modules->sortBy('order')->flatMap(function ($module) {
                                    return $module->lessons->sortBy('order');
                                })->first();
                            @endphp
                            @if($firstLesson)
                                <a href="{{ route('formateur.view.lesson', ['courseId' => $course->id, 'lessonId' => $firstLesson->id]) }}" class="btn btn-sm flex-fill" style="color:#1EA38B;border:1px solid #1EA38B;" title="Voir en tant qu'étudiant">
                                    <i class="fas fa-eye me-1"></i>Aperçu
                                </a>
                            @endif
                        </div>
                        
                        <!-- Informations supplémentaires -->
                        <div class="mt-2">
                            <small class="text-muted d-block">
                                <i class="fas fa-calendar me-1"></i>
                                Créé le {{ $course->created_at->format('d/m/Y') }}
                            </small>
                            @if($course->updated_at->gt($course->created_at))
                                <small class="text-muted d-block">
                                    <i class="fas fa-clock me-1"></i>
                                    Modifié le {{ $course->updated_at->format('d/m/Y') }}
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if($courses->total() > 0)
            <div class="pagination-container">
                <!-- Informations et sélecteur -->
                <div class="pagination-top">
                    <div class="pagination-info-text">
                        Affichage de <strong>{{ $courses->firstItem() }}</strong> à <strong>{{ $courses->lastItem() }}</strong> 
                        sur <strong>{{ $courses->total() }}</strong> cours
                    </div>
                    
                    <div class="per-page-wrapper">
                        <label for="perPageSelect">Afficher :</label>
                        <select id="perPageSelect" name="per_page" class="form-select form-select-sm" onchange="updatePerPage(this.value)">
                            <option value="6" {{ request('per_page', 9) == 6 ? 'selected' : '' }}>6</option>
                            <option value="9" {{ request('per_page', 9) == 9 ? 'selected' : '' }}>9</option>
                            <option value="12" {{ request('per_page', 9) == 12 ? 'selected' : '' }}>12</option>
                            <option value="18" {{ request('per_page', 9) == 18 ? 'selected' : '' }}>18</option>
                            <option value="24" {{ request('per_page', 9) == 24 ? 'selected' : '' }}>24</option>
                        </select>
                    </div>
                </div>
                
                <!-- Liens de pagination -->
                @if($courses->hasPages())
                    <nav aria-label="Navigation des pages">
                        <ul class="pagination">
                            <!-- Bouton Précédent -->
                            @if($courses->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="fas fa-chevron-left"></i>
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $courses->appends(request()->except('page'))->previousPageUrl() }}" aria-label="Précédent">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            @endif

                            <!-- Numéros de pages -->
                            @foreach($courses->getUrlRange(max(1, $courses->currentPage() - 2), min($courses->lastPage(), $courses->currentPage() + 2)) as $page => $url)
                                @if($page == $courses->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $courses->appends(request()->except('page'))->url($page) }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            <!-- Bouton Suivant -->
                            @if($courses->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $courses->appends(request()->except('page'))->nextPageUrl() }}" aria-label="Suivant">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                @endif
            </div>
        @endif
    @else
        <!-- État vide -->
        <div class="empty-state" data-aos="fade-up" data-aos-delay="100">
            <div>
                @if(request()->hasAny(['search', 'status', 'level']))
                    <i class="fas fa-search"></i>
                    <h4>Aucun cours trouvé</h4>
                    <p>Aucun cours ne correspond à vos critères de recherche.</p>
                    <a href="{{ route('formateur.courses.index') }}" class="btn" style="color:#1EA38B;border:1px solid #1EA38B;">
                        <i class="fas fa-times me-2"></i>Effacer les filtres
                    </a>
                @else
                    <i class="fas fa-book-open"></i>
                    <h4>Aucun cours créé</h4>
                    <p>Vous n'avez pas encore créé de cours. Commencez dès maintenant !</p>
                    <a href="{{ route('formateur.courses.create') }}" class="btn-primary-africode">
                        <i class="fas fa-plus me-2"></i>Créer votre premier cours
                    </a>
                @endif
            </div>
        </div>
    @endif
@endsection

@section('scripts')
<script>
    // Fonction pour effacer la recherche
    function clearSearch() {
        document.getElementById('searchInput').value = '';
        document.querySelector('form').submit();
    }
    
    // Recherche en temps réel (avec debounce)
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            if (this.value.length >= 3 || this.value.length === 0) {
                this.form.submit();
            }
        }, 500);
    });
    
    // Animation au scroll pour les cartes
    function animateOnScroll() {
        const cards = document.querySelectorAll('.course-card');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });
        
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
            observer.observe(card);
        });
    }
    
    // Initialiser les animations
    document.addEventListener('DOMContentLoaded', animateOnScroll);
    
    // Fonction pour mettre à jour le nombre de cours par page
    function updatePerPage(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', value);
        url.searchParams.set('page', '1'); // Retourner à la première page
        window.location.href = url.toString();
    }
</script>
@endsection
