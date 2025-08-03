@extends('formateurs.layouts.app')

@section('title', 'AfriCode - Mes cours')
@section('page-title', 'Mes cours')
@section('page-subtitle', 'Gérez et organisez tous vos cours')

@section('header-actions')
   
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
        position: relative;
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
    
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
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
    }
</style>
@endsection

@section('content')
    <div class="mb-4">
        <a href="{{ route('formateur.courses.create') }}" class="btn-primary-africode">
            <i class="fas fa-plus me-2"></i>Créer un nouveau cours
        </a>
    </div>
    
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
                        - <a href="{{ route('formateur.courses.index') }}" class="text-primary">Réinitialiser les filtres</a>
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
                    <div class="course-image" style="background-image: url('{{ $course->cover_image_path ? asset($course->cover_image_path) : 'https://via.placeholder.com/400x200?text='.urlencode($course->title) }}')">
                        <div class="course-status-badge">
                            @if($course->status === 'published')
                                <span class="badge bg-success">
                                    <i class="fas fa-eye me-1"></i>Publié
                                </span>
                            @else
                                <span class="badge bg-warning">
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
                                <i class="fas fa-users text-primary"></i>
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
                            <a href="{{ route('formateur.manage.course', $course) }}" class="btn btn-primary btn-sm flex-fill">
                                <i class="fas fa-cog me-1"></i>Gérer
                            </a>
                            <a href="{{ route('formateur.courses.edit', $course) }}" class="btn btn-outline-secondary btn-sm flex-fill">
                                <i class="fas fa-edit me-1"></i>Modifier
                            </a>
                            @if($course->status === 'published')
                                <a href="#" class="btn btn-outline-info btn-sm flex-fill" title="Voir en tant qu'étudiant">
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
        @if($courses->hasPages())
            <div class="pagination-wrapper" data-aos="fade-up" data-aos-delay="200">
                {{ $courses->appends(request()->query())->links() }}
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
                    <a href="{{ route('formateur.courses.index') }}" class="btn btn-outline-primary">
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
</script>
@endsection
