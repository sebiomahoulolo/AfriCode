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
    <!-- Hero Section moderne -->
    <section class="courses-hero py-5">
        <div class="container">
            <div class="row align-items-center min-vh-50">
                <div class="col-lg-8">
                    <div class="hero-content">
                        <h1 class="hero-title mb-4">Toutes nos <span class="text-gradient">formations</span></h1>
                        <p class="hero-subtitle mb-4">Explorez notre catalogue complet de cours pour développer vos compétences numériques et transformer votre carrière.</p>
                        <div class="floating-badges">
                            <span class="tech-badge" id="courses-count">
                                <i class="fas fa-spinner fa-spin"></i> Chargement...
                            </span>
                            <span class="tech-badge">Certifiées</span>
                            <span class="tech-badge">Tous niveaux</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <div class="organic-container cta-container">
                        <button id="toggle-filters" class="modern-btn">
                            <i class="fas fa-filter me-2"></i>
                            <span>Filtres & Recherche</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section de recherche et filtrage -->
    <section class="courses-filters py-4" id="filters-section" style="display: none;">
        <div class="container">
            <div class="filters-card">
                <div class="row g-3">
                    <!-- Recherche textuelle -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="search-group">
                            <label for="search-input" class="form-label">Rechercher</label>
                            <div class="search-input-wrapper">
                                <input type="text" id="search-input" class="form-control" placeholder="Titre, description...">
                                <i class="fas fa-search search-icon"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Filtre par catégorie -->
                    <div class="col-12 col-md-6 col-lg-2">
                        <div class="filter-group">
                            <label for="category-filter" class="form-label">Catégorie</label>
                            <select id="category-filter" class="form-select">
                                <option value="all">Toutes</option>
                                <!-- Sera peuplé dynamiquement -->
                            </select>
                        </div>
                    </div>

                    <!-- Filtre par niveau -->
                    <div class="col-12 col-md-6 col-lg-2">
                        <div class="filter-group">
                            <label for="level-filter" class="form-label">Niveau</label>
                            <select id="level-filter" class="form-select">
                                <option value="all">Tous</option>
                                <option value="debutant">Débutant</option>
                                <option value="intermediaire">Intermédiaire</option>
                                <option value="avance">Avancé</option>
                                <option value="tous_niveaux">Tous niveaux</option>
                            </select>
                        </div>
                    </div>

                    <!-- Filtre par prix -->
                    <div class="col-12 col-md-6 col-lg-2">
                        <div class="filter-group">
                            <label for="price-filter" class="form-label">Prix</label>
                            <select id="price-filter" class="form-select">
                                <option value="all">Tous</option>
                                <option value="free">Gratuit</option>
                                <option value="paid">Payant</option>
                            </select>
                        </div>
                    </div>

                    <!-- Filtre par certification -->
                    <div class="col-12 col-md-6 col-lg-2">
                        <div class="filter-group">
                            <label for="certification-filter" class="form-label">Certification</label>
                            <select id="certification-filter" class="form-select">
                                <option value="all">Toutes</option>
                                <option value="certified">Certifiées</option>
                                <option value="not_certified">Non certifiées</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <!-- Tri -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="filter-group">
                            <label for="sort-filter" class="form-label">Trier par</label>
                            <select id="sort-filter" class="form-select">
                                <option value="latest">Plus récents</option>
                                <option value="oldest">Plus anciens</option>
                                <option value="title">Titre A-Z</option>
                                <option value="price_asc">Prix croissant</option>
                                <option value="price_desc">Prix décroissant</option>
                            </select>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="col-12 col-md-6 col-lg-9 d-flex align-items-end">
                        <div class="filter-actions">
                            <button id="reset-filters" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-undo me-1"></i>
                                Réinitialiser
                            </button>
                            <button id="apply-filters" class="modern-btn-sm">
                                <i class="fas fa-search me-1"></i>
                                Appliquer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Liste des cours dynamique -->
    <section class="courses-list py-5">
        <div class="container">
            <!-- État de chargement -->
            <div id="loading-state" class="text-center py-5">
                <div class="organic-container loading-container mb-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
                <h4 class="section-title mb-3">Chargement des formations...</h4>
                <p class="text-content">Veuillez patienter</p>
            </div>

            <!-- État vide -->
            <div id="empty-state" class="text-center py-5" style="display: none;">
                <div class="organic-container empty-container mb-4">
                    <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                         alt="Aucun cours" class="empty-image">
                </div>
                <h4 class="section-title mb-3">Aucune formation trouvée</h4>
                <p class="text-content mb-4">Essayez de modifier vos critères de recherche ou de filtrage.</p>
                <button id="clear-all-filters" class="modern-btn">
                    <i class="fas fa-undo me-2"></i>
                    <span>Effacer tous les filtres</span>
                </button>
            </div>

            <!-- Grille des cours -->
            <div id="courses-grid" class="row g-4" style="display: none;">
                <!-- Les cours seront ajoutés ici dynamiquement via JavaScript -->
            </div>

            <!-- Pagination -->
            <div id="pagination-container" class="pagination-container mt-5" style="display: none;">
                <div class="organic-container pagination-wrapper">
                    <!-- La pagination sera ajoutée ici dynamiquement -->
                </div>
            </div>
        </div>
    </section>

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

    <!-- Toast Notifications (Ajouté) -->
    <div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 350px;"></div>
@endsection

@push('styles')
<style>
    /* Styles pour la page des cours unifiée */
    .courses-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .courses-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        opacity: 0.3;
    }

    .hero-content {
        position: relative;
        z-index: 1;
    }

    .courses-filters {
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }

    .filters-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
    }

    .search-input-wrapper {
        position: relative;
    }

    .search-input-wrapper .search-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .modern-btn-sm {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .modern-btn-sm:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        color: white;
    }

    .course-card-modern {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        height: 100%;
        min-height: 420px;
        border: 1px solid #f0f0f0;
        display: flex;
        flex-direction: column;
    }

    .course-card-modern:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    /* Styles pour compatibilité - redirection vers course-card-enhanced */
    .course-card-modern .course-image {
        position: relative;
        height: 200px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .course-card-modern .course-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .course-card-modern:hover .course-image img {
        transform: scale(1.05);
    }

    .level-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .level-badge.level-debutant {
        background: linear-gradient(135deg, #4ade80, #22c55e);
    }

    .level-badge.level-intermediaire {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .level-badge.level-avance {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    .level-badge.level-tous_niveaux {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    }

    .course-content {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        min-height: 220px;
    }

    .course-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        font-size: 0.875rem;
        flex-shrink: 0;
    }

    .course-rating {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .course-rating i {
        color: #fbbf24;
        font-size: 0.75rem;
    }

    .rating-value {
        margin-left: 0.25rem;
        font-weight: 600;
        color: #374151;
    }

    .student-count {
        color: #6b7280;
        font-size: 0.8rem;
    }

    .course-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        color: #1f2937;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 3.2rem;
        flex-shrink: 0;
    }

    .course-instructor {
        margin-bottom: 1rem;
        flex-grow: 1;
        display: flex;
        align-items: center;
    }

    .instructor-name {
        color: #6b7280;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .course-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        padding-top: 1rem;
        border-top: 1px solid #f3f4f6;
        flex-shrink: 0;
    }

    .current-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: #059669;
    }

    .free-price {
        font-size: 1.1rem;
        font-weight: 700;
        color: #059669;
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        border: 1px solid #059669;
    }

    .certification-badge {
        color: #7c3aed;
        font-size: 1.1rem;
    }

    /* Styles pour l'opacité des étoiles */
    .opacity-25 {
        opacity: 0.25;
    }

    .loading-container, .empty-container {
        padding: 2rem;
        border-radius: 20px;
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        border: 2px solid #e2e8f0;
        display: inline-block;
    }

    .empty-image {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 12px;
        opacity: 0.7;
    }

    .filter-actions {
        display: flex;
        gap: 0.5rem;
        width: 100%;
        justify-content: flex-end;
    }

    /* Grille des cours - Espacement et disposition */
    #courses-grid {
        margin-left: -0.75rem;
        margin-right: -0.75rem;
    }

    #courses-grid .col-12,
    #courses-grid .col-sm-6,
    #courses-grid .col-lg-4,
    #courses-grid .col-xl-3 {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
        margin-bottom: 1.5rem;
    }

    /* Responsive - 4 cartes par ligne sur grand écran */
    @media (min-width: 1200px) {
        #courses-grid .col-xl-3 {
            flex: 0 0 25%;
            max-width: 25%;
        }
    }

    /* Responsive - 3 cartes par ligne sur écran moyen */
    @media (min-width: 992px) and (max-width: 1199px) {
        #courses-grid .col-lg-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
    }

    /* Responsive - 2 cartes par ligne sur tablette */
    @media (min-width: 576px) and (max-width: 991px) {
        #courses-grid .col-sm-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }

    /* Responsive - 1 carte par ligne sur mobile */
    @media (max-width: 575px) {
        #courses-grid .col-12
        }
        
        .course-content {
            padding: 1rem;
            min-height: 220px;
        }
    }
    
    /* Améliorations pour la pagination */
    .pagination {
        gap: 0.25rem;
    }
    
    .page-link {
        border-radius: 8px;
        border: 1px solid #e9ecef;
        color: #667eea;
        font-weight: 500;
        padding: 0.5rem 0.75rem;
        transition: all 0.3s ease;
    }
    
    .page-link:hover {
        background-color: #667eea;
        border-color: #667eea;
        color: white;
        transform: translateY(-1px);
    }
    
    .page-item.active .page-link {
        background-color: #667eea;
        border-color: #667eea;
        color: white;
    }
    
    /* Animation pour les cartes */
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
    
    .course-card-modern {
        animation: fadeInUp 0.6s ease forwards;
    }
    
    .course-card-modern:nth-child(1) { animation-delay: 0.1s; }
    .course-card-modern:nth-child(2) { animation-delay: 0.2s; }
    .course-card-modern:nth-child(3) { animation-delay: 0.3s; }
    .course-card-modern:nth-child(4) { animation-delay: 0.4s; }
    
    /* Styles pour les états de chargement */
    .spinner-border {
        width: 3rem;
        height: 3rem;
    }
    
    /* Amélioration des filtres sur mobile */
    @media (max-width: 768px) {
        .filters-card {
            padding: 1rem;
        }
        
        .filter-group label {
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .form-control, .form-select {
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration
    const COURSES_API_URL = '{{ route("courses.filter") }}';
    let currentPage = 1;
    let isLoading = false;
    let currentFilters = {};

    // Éléments DOM
    const loadingState = document.getElementById('loading-state');
    const emptyState = document.getElementById('empty-state');
    const coursesGrid = document.getElementById('courses-grid');
    const paginationContainer = document.getElementById('pagination-container');
    const coursesCount = document.getElementById('courses-count');
    const filtersSection = document.getElementById('filters-section');
    const toggleFiltersBtn = document.getElementById('toggle-filters');

    // Éléments de filtres
    const searchInput = document.getElementById('search-input');
    const categoryFilter = document.getElementById('category-filter');
    const levelFilter = document.getElementById('level-filter');
    const priceFilter = document.getElementById('price-filter');
    const certificationFilter = document.getElementById('certification-filter');
    const sortFilter = document.getElementById('sort-filter');
    const applyFiltersBtn = document.getElementById('apply-filters');
    const resetFiltersBtn = document.getElementById('reset-filters');
    const clearAllFiltersBtn = document.getElementById('clear-all-filters');

    // Toggle des filtres
    toggleFiltersBtn.addEventListener('click', function() {
        const isVisible = filtersSection.style.display !== 'none';
        filtersSection.style.display = isVisible ? 'none' : 'block';
        const icon = this.querySelector('i');
        const text = this.querySelector('span');
        
        if (isVisible) {
            icon.className = 'fas fa-filter me-2';
            text.textContent = 'Filtres & Recherche';
        } else {
            icon.className = 'fas fa-times me-2';
            text.textContent = 'Fermer les filtres';
        }
    });

    // Événements de filtres
    applyFiltersBtn.addEventListener('click', applyFilters);
    resetFiltersBtn.addEventListener('click', resetFilters);
    clearAllFiltersBtn.addEventListener('click', resetFilters);

    // Recherche en temps réel (avec debounce)
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            applyFilters();
        }, 500);
    });

    // Application automatique des filtres au changement
    [categoryFilter, levelFilter, priceFilter, certificationFilter, sortFilter].forEach(select => {
        select.addEventListener('change', applyFilters);
    });

    // Fonction pour charger les cours
    async function loadCourses(page = 1, filters = {}) {
        if (isLoading) return;
        
        isLoading = true;
        showLoadingState();

        try {
            const params = new URLSearchParams({
                page: page,
                ...filters
            });

            const response = await fetch(`${COURSES_API_URL}?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Erreur de chargement');
            }

            const data = await response.json();
            
            displayCourses(data.courses);
            updatePagination(data.pagination);
            updateCoursesCount(data.pagination.total);
            
            currentPage = page;
            currentFilters = filters;

        } catch (error) {
            console.error('Erreur lors du chargement des cours:', error);
            showErrorState();
        } finally {
            isLoading = false;
        }
    }

    // Fonction pour appliquer les filtres
    function applyFilters() {
        const filters = {
            search: searchInput.value.trim(),
            category: categoryFilter.value,
            level: levelFilter.value,
            price: priceFilter.value,
            certification: certificationFilter.value,
            sort: sortFilter.value
        };

        // Supprimer les valeurs vides ou "all"
        Object.keys(filters).forEach(key => {
            if (!filters[key] || filters[key] === 'all') {
                delete filters[key];
            }
        });

        // Afficher notification
        const activeFiltersCount = Object.keys(filters).length;
        if (activeFiltersCount > 0) {
            showToast(`Filtres appliqués (${activeFiltersCount} actif${activeFiltersCount > 1 ? 's' : ''})`, 'success');
        }

        loadCourses(1, filters);
    }

    // Fonction pour réinitialiser les filtres
    function resetFilters() {
        searchInput.value = '';
        categoryFilter.value = 'all';
        levelFilter.value = 'all';
        priceFilter.value = 'all';
        certificationFilter.value = 'all';
        sortFilter.value = 'latest';
        
        showToast('Filtres réinitialisés', 'info');
        loadCourses(1, {});
    }

    // Fonction pour afficher les cours
    function displayCourses(courses) {
        if (!courses || courses.length === 0) {
            showEmptyState();
            return;
        }

        coursesGrid.innerHTML = courses.map(course => createCourseCard(course)).join('');
        showCoursesGrid();
    }

    // Fonction pour créer une carte de cours
    function createCourseCard(course) {
        const rating = (Math.random() * 1 + 4).toFixed(1); // 4.0 à 5.0
        const studentsCount = Math.floor(Math.random() * 150) + 50; // 50 à 200
        const fullStars = Math.floor(rating);
        const hasHalfStar = (rating - fullStars) >= 0.5;

        let starsHtml = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= fullStars) {
                starsHtml += '<i class="fas fa-star"></i>';
            } else if (i == fullStars + 1 && hasHalfStar) {
                starsHtml += '<i class="fas fa-star-half-alt"></i>';
            } else {
                starsHtml += '<i class="fas fa-star opacity-25"></i>';
            }
        }

        const levelText = {
            'debutant': 'Débutant',
            'intermediaire': 'Intermédiaire',
            'avance': 'Avancé',
            'tous_niveaux': 'Tous niveaux'
        };

        const imageUrl = course.cover_image_path 
            ? `{{ asset('') }}${course.cover_image_path}`
            : 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80';

        const priceHtml = course.price > 0 
            ? `<span>${new Intl.NumberFormat('fr-FR').format(parseInt(course.price))} €</span>`
            : `<span>Gratuit</span>`;

        const freeBadge = course.price === 0 || course.price === '0' 
            ? `<div class="free-badge">
                <i class="fas fa-gift"></i>
                <span>Gratuit</span>
               </div>`
            : '';

        const certificationBadge = course.is_certifying 
            ? `<div class="certified-badge">
                <i class="fas fa-certificate"></i>
               </div>`
            : '';

        // Générer un avatar d'instructeur aléatoire
        const instructorAvatar = `https://randomuser.me/api/portraits/${Math.random() > 0.5 ? 'men' : 'women'}/${Math.floor(Math.random() * 50) + 1}.jpg`;

        return `
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
                <div class="course-card-enhanced">
                    <div class="course-image">
                        <img src="${imageUrl}" alt="${course.title}" class="img-fluid">
                        ${course.level ? `<div class="course-level-badge level-${course.level}">${levelText[course.level] || course.level}</div>` : ''}
                        ${freeBadge}
                        ${certificationBadge}
                    </div>
                    <div class="course-content">
                        <div class="course-rating">
                            <div class="stars">
                                ${starsHtml}
                                <span class="rating-value">${rating}</span>
                            </div>
                            <span class="student-count">${studentsCount} étudiants</span>
                        </div>
                        
                        <h4 class="course-title">${course.title || 'Titre du cours indisponible'}</h4>
                        
                        <div class="course-instructor">
                            <img src="${instructorAvatar}" alt="${course.formateur ? (course.formateur.first_name + ' ' + course.formateur.last_name) : 'Instructeur AfriCode'}" class="instructor-avatar">
                            <span class="instructor-name">${course.formateur ? (course.formateur.first_name + ' ' + course.formateur.last_name) : 'Instructeur AfriCode'}</span>
                        </div>
                        
                        <div class="course-meta">
                            <div class="course-duration">
                                <i class="far fa-clock"></i>
                                <span>${Math.floor(Math.random() * 20) + 10}h</span>
                            </div>
                            <div class="course-price ${course.price === 0 || course.price === '0' ? 'free' : ''}">
                                <i class="fas fa-tag"></i>
                                ${priceHtml}
                            </div>
                        </div>
                        
                        <div class="course-actions">
                            <a href="/courses/${course.slug}" class="btn btn-primary btn-sm course-enroll">
                                <i class="fas fa-play me-1"></i>
                                ${course.price === 0 || course.price === '0' ? 'Commencer' : 'S\'inscrire'}
                            </a>
                            <button class="course-save">
                                <i class="far fa-heart"></i>
                            </button>
                            <button class="course-share">
                                <i class="fas fa-share-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    // Fonctions d'état d'affichage
    function showLoadingState() {
        loadingState.style.display = 'block';
        emptyState.style.display = 'none';
        coursesGrid.style.display = 'none';
        paginationContainer.style.display = 'none';
    }

    function showEmptyState() {
        loadingState.style.display = 'none';
        emptyState.style.display = 'block';
        coursesGrid.style.display = 'none';
        paginationContainer.style.display = 'none';
    }

    function showCoursesGrid() {
        loadingState.style.display = 'none';
        emptyState.style.display = 'none';
        coursesGrid.style.display = 'flex';
        paginationContainer.style.display = 'block';
    }

    function showErrorState() {
        loadingState.style.display = 'none';
        emptyState.style.display = 'block';
        coursesGrid.style.display = 'none';
        paginationContainer.style.display = 'none';
        
        // Personnaliser le message d'erreur
        emptyState.querySelector('h4').textContent = 'Erreur de chargement';
        emptyState.querySelector('p').textContent = 'Une erreur s\'est produite lors du chargement des formations. Veuillez réessayer.';
    }

    // Fonction pour mettre à jour le compteur de cours
    function updateCoursesCount(total) {
        coursesCount.innerHTML = `<i class="fas fa-graduation-cap me-1"></i>${total} Formation${total > 1 ? 's' : ''}`;
    }

    // Fonction pour mettre à jour la pagination
    function updatePagination(pagination) {
        if (!pagination || pagination.last_page <= 1) {
            paginationContainer.style.display = 'none';
            return;
        }

        let paginationHtml = '<nav aria-label="Navigation des cours"><ul class="pagination justify-content-center">';
        
        // Bouton précédent
        if (pagination.current_page > 1) {
            paginationHtml += `<li class="page-item">
                <a class="page-link" href="#" data-page="${pagination.current_page - 1}">&laquo; Précédent</a>
            </li>`;
        }

        // Pages
        const startPage = Math.max(1, pagination.current_page - 2);
        const endPage = Math.min(pagination.last_page, pagination.current_page + 2);

        if (startPage > 1) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`;
            if (startPage > 2) {
                paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            const activeClass = i === pagination.current_page ? 'active' : '';
            paginationHtml += `<li class="page-item ${activeClass}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>`;
        }

        if (endPage < pagination.last_page) {
            if (endPage < pagination.last_page - 1) {
                paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.last_page}">${pagination.last_page}</a></li>`;
        }

        // Bouton suivant
        if (pagination.current_page < pagination.last_page) {
            paginationHtml += `<li class="page-item">
                <a class="page-link" href="#" data-page="${pagination.current_page + 1}">Suivant &raquo;</a>
            </li>`;
        }

        paginationHtml += '</ul></nav>';
        paginationContainer.innerHTML = paginationHtml;

        // Événements de pagination
        paginationContainer.querySelectorAll('.page-link[data-page]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const page = parseInt(this.dataset.page);
                loadCourses(page, currentFilters);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });

        paginationContainer.style.display = 'block';
    }

    // Charger les catégories pour le filtre
    async function loadCategories() {
        try {
            const response = await fetch('{{ route("courses.categories") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                data.categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    categoryFilter.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Erreur lors du chargement des catégories:', error);
            // Utiliser des catégories statiques en cas d'erreur
            const staticCategories = [
                { id: 1, name: 'Développement Web' },
                { id: 2, name: 'Développement Mobile' },
                { id: 3, name: 'Intelligence Artificielle' },
                { id: 4, name: 'Data Science' },
                { id: 5, name: 'DevOps' },
                { id: 6, name: 'Design' }
            ];

            staticCategories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                categoryFilter.appendChild(option);
            });
        }
    }

    // Gestion des paramètres URL
    function getUrlParams() {
        const params = new URLSearchParams(window.location.search);
        const filters = {};
        
        if (params.get('search')) filters.search = params.get('search');
        if (params.get('category')) filters.category = params.get('category');
        if (params.get('level')) filters.level = params.get('level');
        if (params.get('price')) filters.price = params.get('price');
        if (params.get('certification')) filters.certification = params.get('certification');
        if (params.get('sort')) filters.sort = params.get('sort');

        return filters;
    }

    function applyUrlParams(filters) {
        if (filters.search) searchInput.value = filters.search;
        if (filters.category) categoryFilter.value = filters.category;
        if (filters.level) levelFilter.value = filters.level;
        if (filters.price) priceFilter.value = filters.price;
        if (filters.certification) certificationFilter.value = filters.certification;
        if (filters.sort) sortFilter.value = filters.sort;
    }

    // Initialisation
    function init() {
        loadCategories();
        
        // Appliquer les paramètres URL s'ils existent
        const urlParams = getUrlParams();
        if (Object.keys(urlParams).length > 0) {
            applyUrlParams(urlParams);
            filtersSection.style.display = 'block';
            toggleFiltersBtn.querySelector('i').className = 'fas fa-times me-2';
            toggleFiltersBtn.querySelector('span').textContent = 'Fermer les filtres';
            loadCourses(1, urlParams);
        } else {
            loadCourses(1, {});
        }
    }

    // Démarrer l'application
    init();

    // Système de notification toast
    function showToast(message, type = 'info') {
        // Créer le toast s'il n'existe pas
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                max-width: 350px;
            `;
            document.body.appendChild(toastContainer);
        }

        const toastId = 'toast-' + Date.now();
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'primary'} border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;

        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 3000 });
        toast.show();

        // Supprimer le toast après qu'il soit masqué
        toastElement.addEventListener('hidden.bs.toast', function() {
            toastElement.remove();
        });
    }
});
</script>
@endpush

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

    /* Cartes de cours modernes - Style identique à la page d'accueil */
    .course-card-enhanced {
        background: var(--africode-white);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: var(--africode-shadow-md);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid var(--africode-gray-medium);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .course-card-enhanced:hover {
        transform: translateY(-10px);
        box-shadow: var(--africode-shadow-lg);
    }

    .course-image {
        position: relative;
        height: 200px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .course-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .course-card-enhanced:hover .course-image img {
        transform: scale(1.1);
    }

    .course-level-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        padding: 0.5rem 1rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .level-debutant {
        background: rgba(39, 179, 113, 0.9);
        color: white;
    }

    .level-intermediaire {
        background: rgba(255, 142, 42, 0.9);
        color: white;
    }

    .level-avance {
        background: rgba(227, 45, 49, 0.9);
        color: white;
    }

    .level-tous_niveaux {
        background: rgba(139, 92, 246, 0.9);
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
        flex: 1;
        display: flex;
        flex-direction: column;
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
        margin-top: auto;
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
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .course-enroll:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(30, 163, 139, 0.3);
        color: white;
    }

    .course-save,
    .course-share {
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
        margin-left: 0.5rem;
        cursor: pointer;
    }

    .course-save:hover,
    .course-share:hover {
        border-color: var(--africode-primary);
        color: var(--africode-primary);
        transform: scale(1.1);
    }

    /* Grille des cours - Espacement et disposition */
    #courses-grid {
        margin-left: -0.75rem;
        margin-right: -0.75rem;
    }

    #courses-grid .col-12,
    #courses-grid .col-sm-6,
    #courses-grid .col-lg-4,
    #courses-grid .col-xl-3 {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
        margin-bottom: 1.5rem;
    }

    /* Responsive - 4 cartes par ligne sur grand écran */
    @media (min-width: 1200px) {
        #courses-grid .col-xl-3 {
            flex: 0 0 25%;
            max-width: 25%;
        }
    }

    /* Responsive - 3 cartes par ligne sur écran moyen */
    @media (min-width: 992px) and (max-width: 1199px) {
        #courses-grid .col-lg-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
    }

    /* Responsive - 2 cartes par ligne sur tablette */
    @media (min-width: 576px) and (max-width: 991px) {
        #courses-grid .col-sm-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }

    /* Responsive - 1 carte par ligne sur mobile */
    @media (max-width: 575px) {
        #courses-grid .col-12 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        .course-card-enhanced {
            margin-bottom: 1rem;
        }
    }

    /* Filtres horizontaux */
    .filters-row {
        border-top: 1px solid var(--africode-gray-light);
        padding-top: 1rem;
    }

    .filter-dropdown .btn {
        border-radius: 20px;
        font-size: 0.85rem;
        padding: 0.5rem 1rem;
        border: 1px solid var(--africode-gray-medium);
        background: white;
        color: var(--africode-text-dark);
    }

    .filter-dropdown .btn:hover,
    .filter-dropdown .btn.show {
        background: var(--africode-primary);
        color: white;
        border-color: var(--africode-primary);
    }

    .filter-dropdown .dropdown-menu {
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        padding: 0.5rem;
    }

    .filter-dropdown .dropdown-item {
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }

    .filter-dropdown .dropdown-item:hover {
        background: var(--africode-primary);
        color: white;
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

    @media (max-width: 576px) {
        .hero-title {
            font-size: 2rem;
        }
        
        .hero-subtitle {
            font-size: 1rem;
        }
        
        .floating-badges .tech-badge {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
        
        .course-card-modern {
            margin-bottom: 1rem;
        }
        
        .course-image {
            height: 160px;
        }
        
        .course-title {
            font-size: 1rem;
            -webkit-line-clamp: 3;
            min-height: 3.6rem;
        }
        
        .course-content {
            padding: 1rem;
        }
    }
    
    /* Améliorations pour la pagination */
    .pagination {
        gap: 0.25rem;
    }
    
    .page-link {
        border-radius: 8px;
        border: 1px solid #e9ecef;
        color: #667eea;
        font-weight: 500;
        padding: 0.5rem 0.75rem;
        transition: all 0.3s ease;
    }
    
    .page-link:hover {
        background-color: #667eea;
        border-color: #667eea;
        color: white;
        transform: translateY(-1px);
    }
    
    .page-item.active .page-link {
        background-color: #667eea;
        border-color: #667eea;
        color: white;
    }
    
    /* Animation pour les cartes */
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
    
    .course-card-modern {
        animation: fadeInUp 0.6s ease forwards;
    }
    
    .course-card-modern:nth-child(1) { animation-delay: 0.1s; }
    .course-card-modern:nth-child(2) { animation-delay: 0.2s; }
    .course-card-modern:nth-child(3) { animation-delay: 0.3s; }
    .course-card-modern:nth-child(4) { animation-delay: 0.4s; }
    
    /* Styles pour les états de chargement */
    .spinner-border {
        width: 3rem;
        height: 3rem;
    }
    
    /* Amélioration des filtres sur mobile */
    @media (max-width: 768px) {
        .filters-card {
            padding: 1rem;
        }
        
        .filter-group label {
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .form-control, .form-select {
            font-size: 0.9rem;
        }
    }
</style>
@endpush