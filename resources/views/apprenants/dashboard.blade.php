@extends('apprenants.layouts.app')

@section('title', 'AfriCode')
@section('page-title', __('messages.learner_dashboard'))

@push('styles')
<style>
    /* Dashboard AfriCode - Interface propre et professionnelle */
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: #FFFFFF;
        border: 1px solid #E9ECEF;
        border-radius: 0.5rem;
        padding: 2rem;
        text-align: center;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: #1EA38B;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        border-color: #1EA38B;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 1rem;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        background: #1EA38B;
        color: white;
        box-shadow: 0 2px 8px rgba(30, 163, 139, 0.25);
    }

    .stat-number {
        font-size: 2.25rem;
        font-weight: 700;
        color: #1EA38B;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #6C757D;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 0.875rem;
    }

    .progress-card {
        background: #1EA38B;
        color: white;
        border-radius: 0.5rem;
        padding: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(30, 163, 139, 0.25);
    }

    .course-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        margin: 2rem 0;
    }

    .course-card {
        background: #FFFFFF;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        transition: all 0.2s ease;
        border: 1px solid #E9ECEF;
    }

    .course-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        border-color: #1EA38B;
    }

    .course-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
        background: #1EA38B;
    }

    .course-content {
        padding: 1.25rem;
    }

    .course-title {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }

    .course-meta {
        color: var(--africode-text-secondary);
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }

    .course-progress {
        margin-bottom: 1rem;
    }

    .welcome-section {
        background: linear-gradient(135deg, rgba(30, 163, 139, 0.08), rgba(39, 179, 113, 0.08));
        border-radius: var(--africode-border-radius-lg);
        padding: 3rem 2rem;
        text-align: center;
        margin-bottom: 3rem;
        border: 1px solid var(--africode-border);
        box-shadow: var(--africode-shadow-sm);
    }

    .welcome-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--africode-primary);
        box-shadow: var(--africode-shadow-md);
        margin-bottom: 1rem;
    }

    .activity-feed {
        background: var(--africode-surface);
        border-radius: var(--africode-border-radius);
        padding: 1.5rem;
        box-shadow: var(--africode-shadow-sm);
        border: 1px solid var(--africode-border);
    }

    .activity-item {
        display: flex;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid var(--africode-border-light);
        transition: var(--africode-transition-fast);
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-item:hover {
        background: var(--africode-hover-bg);
        border-radius: var(--africode-border-radius-sm);
        margin: 0 -0.5rem;
        padding: 1rem 0.5rem;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1rem;
        background: var(--africode-gradient-primary);
        color: white;
        box-shadow: var(--africode-shadow-sm);
    }

    /* Corrections spécifiques pour les problèmes de visibilité - VERSION RENFORCÉE */
    
    /* Barres de progression corrigées avec visibilité garantie */
    .progress-modern {
        height: 8px !important;
        border-radius: var(--africode-border-radius-sm) !important;
        background: rgba(30, 163, 139, 0.15) !important;
        overflow: hidden !important;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.1) !important;
        margin: 0.5rem 0 !important;
        min-height: 8px !important;
    }

    .progress-bar-modern {
        height: 100% !important;
        background: linear-gradient(135deg, #1EA38B 0%, #27B371 100%) !important;
        border-radius: var(--africode-border-radius-sm) !important;
        transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1) !important;
        box-shadow: 0 1px 3px rgba(30, 163, 139, 0.3) !important;
        position: relative !important;
        min-width: 2px !important;
        opacity: 1 !important;
        visibility: visible !important;
    }

    .progress-bar-modern::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    /* Boutons modernes corrigés avec visibilité garantie */
    .btn-modern {
        background: linear-gradient(135deg, #1EA38B 0%, #27B371 100%) !important;
        border: none !important;
        color: white !important;
        padding: 0.75rem 1.5rem !important;
        border-radius: var(--africode-border-radius-sm) !important;
        font-weight: 600 !important;
        transition: var(--africode-transition) !important;
        position: relative !important;
        overflow: hidden !important;
        box-shadow: var(--africode-shadow-sm) !important;
        text-decoration: none !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        opacity: 1 !important;
        visibility: visible !important;
        z-index: 1 !important;
    }

    .btn-modern:hover {
        transform: translateY(-2px) !important;
        box-shadow: var(--africode-shadow-md) !important;
        color: white !important;
        background: linear-gradient(135deg, #27B371 0%, #1EA38B 100%) !important;
        filter: brightness(1.1) !important;
        text-decoration: none !important;
    }

    .btn-modern:focus,
    .btn-modern:active {
        color: white !important;
        background: linear-gradient(135deg, #1EA38B 0%, #27B371 100%) !important;
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(30, 163, 139, 0.25) !important;
    }

    .btn-modern.btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }

    .btn-outline-modern {
        background: transparent !important;
        border: 2px solid var(--africode-primary) !important;
        color: var(--africode-primary) !important;
        padding: 0.75rem 1.5rem !important;
        border-radius: var(--africode-border-radius-sm) !important;
        font-weight: 600 !important;
        transition: var(--africode-transition) !important;
        text-decoration: none !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        opacity: 1 !important;
        visibility: visible !important;
    }

    .btn-outline-modern:hover {
        background: var(--africode-primary) !important;
        color: white !important;
        transform: translateY(-2px) !important;
        box-shadow: var(--africode-shadow-sm) !important;
        text-decoration: none !important;
    }

    .btn-outline-modern:focus,
    .btn-outline-modern:active {
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(30, 163, 139, 0.25) !important;
    }

    /* Cartes modernes corrigées */
    .modern-card {
        background: var(--africode-surface);
        border-radius: var(--africode-border-radius);
        padding: 1.5rem;
        box-shadow: var(--africode-shadow-sm);
        border: 1px solid var(--africode-border);
        transition: var(--africode-transition);
        position: relative;
        overflow: hidden;
    }

    .modern-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--africode-gradient-primary);
    }

    .modern-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--africode-shadow-lg);
        border-color: var(--africode-primary);
    }

    /* Badges modernes */
    .badge-modern {
        padding: 0.375rem 0.75rem !important;
        border-radius: 20px !important;
        font-weight: 600 !important;
        font-size: 0.75rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        opacity: 1 !important;
        visibility: visible !important;
        border: none !important;
        white-space: nowrap !important;
    }

    .badge-primary {
        background: linear-gradient(135deg, #1EA38B 0%, #27B371 100%) !important;
        color: white !important;
    }

    .badge-success {
        background: #27B371 !important;
        color: white !important;
    }

    .badge-warning {
        background: #FF8E2A !important;
        color: white !important;
    }

    .badge-danger {
        background: #E32D31 !important;
        color: white !important;
    }

    /* Statuts de cours corrigés */
    .course-status,
    .status-badge {
        padding: 0.25rem 0.75rem !important;
        border-radius: 15px !important;
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        display: inline-block !important;
        opacity: 1 !important;
        visibility: visible !important;
        border: none !important;
    }

    .course-status.completed,
    .status-completed {
        background: #27B371 !important;
        color: white !important;
    }

    .course-status.in-progress,
    .status-progress {
        background: #FF8E2A !important;
        color: white !important;
    }

    .course-status.not-started,
    .status-pending {
        background: #6c757d !important;
        color: white !important;
    }

    /* Correction des modules actifs qui deviennent blancs */
    .module-item.active,
    .lesson-link.active,
    .nav-link.active {
        background: linear-gradient(135deg, rgba(30, 163, 139, 0.15) 0%, rgba(39, 179, 113, 0.15) 100%) !important;
        border-left: 4px solid #1EA38B !important;
        color: #1EA38B !important;
        font-weight: 600 !important;
    }

    .module-item.active:hover,
    .lesson-link.active:hover {
        background: linear-gradient(135deg, rgba(30, 163, 139, 0.2) 0%, rgba(39, 179, 113, 0.2) 100%) !important;
        color: #17896E !important;
    }

    /* Responsive amélioré */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .course-grid {
            grid-template-columns: 1fr;
        }

        .welcome-section {
            padding: 2rem 1rem;
        }

        .stat-card {
            padding: 1.5rem 1rem;
        }

        .course-card .row {
            --bs-gutter-x: 0;
        }

        .course-image {
            height: 150px !important;
        }

        .quick-actions {
            grid-template-columns: 1fr 1fr;
        }
    }

    /* Fix pour le texte primaire */
    .text-primary {
        color: var(--africode-primary) !important;
    }

    .text-success {
        color: var(--africode-highlight) !important;
    }

    .text-warning {
        color: var(--africode-secondary) !important;
    }

    .text-muted {
        color: var(--africode-text-muted) !important;
    }
    </style>
@endpush

@section('content')
    <!-- Welcome Section -->
    <div class="welcome-section" data-aos="fade-up">
        <img src="{{ Auth::user()->profile_image_path ? asset(Auth::user()->profile_image_path) : asset('assets/images/default-avatar.png') }}" 
             alt="Profile Picture" class="welcome-avatar">
        <h2 class="mb-3">Bienvenue, {{ Auth::user()->first_name }} ! 👋</h2>
        <p class="text-muted mb-4">Continuez votre parcours d'apprentissage et atteignez vos objectifs.</p>
        
        @php
            $completedCourses = $enrollments->where('completed_at', '!=', null)->count();
            $level = max(1, min(10, ceil($completedCourses / 2)));
            $xp = $completedCourses * 100 + $certifications->count() * 250;
            $avgProgress = $enrollments->isEmpty() ? 0 : $enrollments->avg('progress_percentage');
        @endphp
        
        <div class="d-flex justify-content-center gap-3">
            <span class="badge-modern badge-primary">Niveau {{ $level }}</span>
            <span class="badge-modern badge-warning">{{ $xp }} XP</span>
            <span class="badge-modern badge-success">{{ round($avgProgress) }}% complété</span>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="stats-grid" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="stat-number">{{ $enrollments->where('completed_at', null)->count() }}</div>
            <div class="stat-label">Cours en cours</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-certificate"></i>
            </div>
            <div class="stat-number">{{ $certifications->count() }}</div>
            <div class="stat-label">Certifications</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-number">{{ $enrollments->where('completed_at', '!=', null)->count() }}</div>
            <div class="stat-label">Cours complétés</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="stat-number">{{ round($avgProgress) }}%</div>
            <div class="stat-label">Progression moyenne</div>
        </div>
    </div>

    <div class="row">
        <!-- Mes Cours -->
        <div class="col-lg-8 mb-4">
            <div class="modern-card" data-aos="fade-up" data-aos-delay="200">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="h4 mb-0">
                        <i class="fas fa-book-open me-2 text-primary"></i>
                        Mes formations en cours
                    </h3>
                    <a href="{{ route('apprenant.courses') }}" class="btn btn-outline-modern btn-sm">
                        Voir tout
                    </a>
                </div>
                
                @forelse($enrollments->take(3) as $enrollment)
                    <div class="course-card mb-3">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <div class="course-image" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem;">
                                    <i class="fas fa-play-circle"></i>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="course-content">
                                    <h5 class="course-title">{{ $enrollment->course->title }}</h5>
                                    <p class="course-meta">
                                        <i class="fas fa-layer-group me-1"></i>{{ $enrollment->course->modules->count() }} modules •
                                        <i class="fas fa-clock me-1"></i>{{ $enrollment->course->getLessonsCount() }} leçons
                                    </p>
                                    
                                    <div class="course-progress mb-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <small class="text-muted">Progression</small>
                                            <small class="fw-bold">{{ round($enrollment->progress_percentage) }}%</small>
                                        </div>
                                        <div class="progress-modern">
                                            <div class="progress-bar-modern" style="width: {{ $enrollment->progress_percentage }}%"></div>
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('apprenant.course.access', ['courseId' => $enrollment->course->id]) }}" 
                                       class="btn btn-modern btn-sm">
                                        <i class="fas fa-play me-2"></i>Continuer
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-book-open display-1 text-muted opacity-50"></i>
                        </div>
                        <h5 class="text-muted mb-3">Aucun cours en cours</h5>
                        <p class="text-muted mb-4">Découvrez notre catalogue de formations pour commencer votre apprentissage.</p>
                        <a href="{{ route('courses.index') }}" class="btn btn-modern">
                            <i class="fas fa-search me-2"></i>Découvrir les cours
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Progress Overview -->
            <div class="progress-card mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Progression globale</h5>
                    <span class="h4 mb-0">{{ round($avgProgress) }}%</span>
                </div>
                
                <div class="progress-modern mb-3" style="background: rgba(255, 255, 255, 0.2);">
                    <div class="progress-bar-modern" style="width: {{ $avgProgress }}%; background: white;"></div>
                </div>
                
                <div class="row text-center">
                    <div class="col-4">
                        <div class="h6 mb-0">{{ $enrollments->count() }}</div>
                        <small class="opacity-75">Cours inscrits</small>
                    </div>
                    <div class="col-4">
                        <div class="h6 mb-0">{{ $completedCourses }}</div>
                        <small class="opacity-75">Terminés</small>
                    </div>
                    <div class="col-4">
                        <div class="h6 mb-0">{{ $certifications->count() }}</div>
                        <small class="opacity-75">Certifiés</small>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="modern-card mb-4" data-aos="fade-up" data-aos-delay="400">
                <h5 class="mb-4">
                    <i class="fas fa-bolt me-2 text-warning"></i>
                    Actions rapides
                </h5>
                
                <div class="quick-actions">
                    <div class="action-card" onclick="window.location.href='{{ route('courses.index') }}'">
                        <div class="action-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h6 class="mb-0">Explorer</h6>
                        <small class="text-muted">Nouveaux cours</small>
                    </div>
                    
                    <div class="action-card" onclick="window.location.href='{{ route('pages.forumapp') }}'">
                        <div class="action-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h6 class="mb-0">Communauté</h6>
                        <small class="text-muted">Échanger</small>
                    </div>
                </div>
            </div>

            <!-- Certifications -->
            <div class="modern-card" data-aos="fade-up" data-aos-delay="500">
                <h5 class="mb-4">
                    <i class="fas fa-award me-2 text-success"></i>
                    Mes certifications
                </h5>
                
                @forelse($certifications->take(3) as $certification)
                    <div class="activity-item">
                        <div class="activity-icon" style="background: var(--success-color); color: white;">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $certification->course->title }}</h6>
                            <small class="text-muted">Obtenue le {{ $certification->issued_at->format('d/m/Y') }}</small>
                        </div>
                        <a href="{{ route('apprenant.certification.download', ['certificationId' => $certification->id]) }}" 
                           class="btn btn-outline-modern btn-sm">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <div class="mb-3">
                            <i class="fas fa-award display-4 text-muted opacity-50"></i>
                        </div>
                        <p class="text-muted mb-3">Aucune certification obtenue</p>
                        <small class="text-muted">Complétez un cours à 100% pour obtenir votre première certification.</small>
                    </div>
                @endforelse
                
                @if($certifications->count() > 3)
                    <div class="text-center mt-3">
                        <a href="#" class="btn btn-outline-modern btn-sm">
                            Voir toutes ({{ $certifications->count() }})
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recommendations -->
    @if($enrollments->isNotEmpty())
    <div class="modern-card mt-4" data-aos="fade-up" data-aos-delay="600">
        <h3 class="h4 mb-4">
            <i class="fas fa-lightbulb me-2 text-warning"></i>
            Formations recommandées
        </h3>
        
        <div class="course-grid">
            <div class="course-card">
                <div class="course-image" style="background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem;">
                    <i class="fab fa-react"></i>
                </div>
                <div class="course-content">
                    <h5 class="course-title">ReactJS Avancé</h5>
                    <p class="course-meta">Maîtrisez React pour des applications modernes</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge-modern badge-primary">Intermédiaire</span>
                        <span class="fw-bold text-success">45€</span>
                    </div>
                    <button class="btn btn-outline-modern w-100 mt-3">
                        <i class="fas fa-info-circle me-2"></i>Voir détails
                    </button>
                </div>
            </div>
            
            <div class="course-card">
                <div class="course-image" style="background: linear-gradient(135deg, #4facfe, #00f2fe); display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem;">
                    <i class="fab fa-node-js"></i>
                </div>
                <div class="course-content">
                    <h5 class="course-title">Node.js & Express</h5>
                    <p class="course-meta">Développement backend avec JavaScript</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge-modern badge-warning">Avancé</span>
                        <span class="fw-bold text-success">55€</span>
                    </div>
                    <button class="btn btn-outline-modern w-100 mt-3">
                        <i class="fas fa-info-circle me-2"></i>Voir détails
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection