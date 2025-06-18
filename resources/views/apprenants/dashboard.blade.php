
@extends('apprenants.layouts.app')

@section('title', 'Tableau de bord | AfriCode')
@section('page-title', 'Tableau de bord')

@push('styles')
<style>
    /* Dashboard specific styles */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.8));
        backdrop-filter: blur(20px);
        border-radius: var(--border-radius);
        padding: 2rem;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
    }

    .stat-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        box-shadow: var(--shadow);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--gray-800);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: var(--gray-600);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 0.875rem;
    }

    .progress-card {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border-radius: var(--border-radius);
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .progress-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(30px, -30px);
    }

    .course-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
        margin: 2rem 0;
    }

    .course-card {
        background: white;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        border: 1px solid var(--gray-200);
    }

    .course-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
    }

    .course-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    }

    .course-content {
        padding: 1.5rem;
    }

    .course-title {
        font-weight: 700;
        color: var(--gray-800);
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }

    .course-meta {
        color: var(--gray-600);
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }

    .course-progress {
        margin-bottom: 1rem;
    }

    .welcome-section {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        border-radius: var(--border-radius-lg);
        padding: 3rem 2rem;
        text-align: center;
        margin-bottom: 3rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .welcome-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: var(--shadow);
        margin-bottom: 1rem;
    }

    .activity-feed {
        background: white;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
    }

    .activity-item {
        display: flex;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid var(--gray-200);
    }

    .activity-item:last-child {
        border-bottom: none;
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
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin: 2rem 0;
    }

    .action-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        text-align: center;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .action-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow);
    }

    .action-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 1rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
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
                    <a href="{{ route('courses.index') }}" class="btn btn-outline-modern btn-sm">
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
                            <small class="text-muted">Obtenue le {{ $certification->issue_date->format('d/m/Y') }}</small>
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