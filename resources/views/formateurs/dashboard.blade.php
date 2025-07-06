@extends('formateurs.layouts.app')

@section('title', 'AfriCode - Tableau de bord formateur')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Bonjour ' . Auth::user()->first_name . ', gérez vos cours et suivez vos performances')

@section('header-actions')
@endsection

@section('content')
    <!-- Statistiques principales -->
    <div class="row mb-4" data-aos="fade-up">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card-modern">
                <div class="card-body-modern text-center">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon text-primary">
                            <i class="fas fa-book-open fa-2x"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-0 text-primary">{{ $courses->where('status', 'published')->count() }}</h3>
                            <small class="text-muted">Cours actifs</small>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-primary" style="width: {{ $courses->count() ? ($courses->where('status', 'published')->count() / $courses->count() * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card-modern">
                <div class="card-body-modern text-center">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon text-success">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-0 text-success">{{ $totalEnrollments }}</h3>
                            <small class="text-muted">Étudiants inscrits</small>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-success" style="width: 75%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card-modern">
                <div class="card-body-modern text-center">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon text-warning">
                            <i class="fas fa-euro-sign fa-2x"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-0 text-warning">{{ number_format($totalRevenue, 0) }}</h3>
                            <small class="text-muted">Revenus (€)</small>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-warning" style="width: 60%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card-modern">
                <div class="card-body-modern text-center">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon text-info">
                            <i class="fas fa-star fa-2x"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-0 text-info">{{ number_format($averageRating, 1) }}</h3>
                            <small class="text-muted">Note moyenne</small>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-info" style="width: {{ ($averageRating / 5) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section principale -->
    <div class="row">
        <!-- Mes cours -->
        <div class="col-xl-8 mb-4">
            <div class="card-modern" data-aos="fade-up" data-aos-delay="100">
                <div class="card-header-modern d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-book-open me-2 text-primary"></i>
                        Mes cours
                    </h5>
                    @if($courses->count() > 3)
                        <a href="{{ route('formateur.courses.index') }}" class="btn btn-outline-primary btn-sm">
                            Voir tout ({{ $courses->count() }})
                        </a>
                    @endif
                </div>
                <div class="card-body-modern">
                    @if($courses->isEmpty())
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-book-open fa-4x text-muted opacity-50"></i>
                            </div>
                            <h5 class="text-muted mb-3">Aucun cours créé</h5>
                            <p class="text-muted mb-4">Commencez par créer votre premier cours pour partager vos connaissances.</p>
                            <a href="{{ route('formateur.courses.create') }}" class="btn-primary-africode">
                                <i class="fas fa-plus me-2"></i>Créer mon premier cours
                            </a>
                        </div>
                    @else
                        <div class="row">
                            @foreach($courses->take(3) as $course)
                                <div class="col-md-4 mb-3">
                                    <div class="course-card-modern">
                                        <div class="course-image-modern">
                                            @if($course->cover_image_path)
                                                <img src="{{ asset($course->cover_image_path) }}" alt="{{ $course->title }}">
                                            @else
                                                <div class="course-placeholder">
                                                    <i class="fas fa-graduation-cap fa-3x"></i>
                                                </div>
                                            @endif
                                            <div class="course-status">
                                                @if($course->status === 'published')
                                                    <span class="badge bg-success">Publié</span>
                                                @else
                                                    <span class="badge bg-warning">Brouillon</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="course-content-modern">
                                            <h6 class="course-title-modern">{{ Str::limit($course->title, 35) }}</h6>
                                            <p class="course-description-modern">{{ Str::limit($course->short_description, 60) }}</p>
                                            <div class="course-stats-modern mb-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-users me-1"></i>{{ $course->students_count ?? 0 }} étudiants
                                                </small>
                                                <small class="text-muted ms-2">
                                                    <i class="fas fa-layer-group me-1"></i>{{ $course->modules->count() }} modules
                                                </small>
                                            </div>
                                            <a href="{{ route('formateur.manage.course', ['courseId' => $course->id]) }}" class="btn btn-outline-primary btn-sm w-100">
                                                <i class="fas fa-cog me-1"></i>Gérer
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar droite -->
        <div class="col-xl-4">
            <!-- Actions rapides -->
            <div class="card-modern mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card-header-modern">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2 text-warning"></i>
                        Actions rapides
                    </h6>
                </div>
                <div class="card-body-modern">
                    <div class="d-grid gap-2">
                        <a href="{{ route('formateur.courses.create') }}" class="btn-primary-africode w-100">
                            <i class="fas fa-plus me-2"></i>Créer un cours
                        </a>
                        @if($courses->isNotEmpty())
                            <a href="{{ route('formateur.manage.course', ['courseId' => $courses->first()->id]) }}" class="btn-secondary-africode w-100">
                                <i class="fas fa-edit me-2"></i>Gérer le dernier cours
                            </a>
                        @endif
                        <a href="#" class="btn btn-outline-primary w-100">
                            <i class="fas fa-chart-bar me-2"></i>Voir les analytics
                        </a>
                    </div>
                </div>
            </div>

            <!-- État des cours -->
            <div class="card-modern mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card-header-modern">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie me-2 text-primary"></i>
                        État de vos cours
                    </h6>
                </div>
                <div class="card-body-modern">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Cours publiés</span>
                        <div>
                            <span class="badge bg-success">{{ $courses->where('status', 'published')->count() }}</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">En brouillon</span>
                        <div>
                            <span class="badge bg-warning">{{ $courses->where('status', 'draft')->count() }}</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted fw-bold">Total</span>
                        <div>
                            <span class="badge bg-primary">{{ $courses->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activité récente -->
            <div class="card-modern" data-aos="fade-up" data-aos-delay="400">
                <div class="card-header-modern">
                    <h6 class="mb-0">
                        <i class="fas fa-clock me-2 text-info"></i>
                        Activité récente
                    </h6>
                </div>
                <div class="card-body-modern">
                    @if($recentActivities->isEmpty())
                        <div class="text-center py-3">
                            <i class="fas fa-history fa-2x text-muted opacity-50 mb-2"></i>
                            <p class="text-muted mb-0">Aucune activité récente</p>
                        </div>
                    @else
                        <div class="activity-timeline">
                            @foreach($recentActivities->take(5) as $activity)
                                <div class="activity-item">
                                    <div class="activity-icon">
                                        @if($activity['type'] === 'enrollment')
                                            <i class="fas fa-user-plus text-success"></i>
                                        @elseif($activity['type'] === 'rating')
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="fas fa-info text-primary"></i>
                                        @endif
                                    </div>
                                    <div class="activity-content">
                                        <p class="mb-1">{{ $activity['message'] }}</p>
                                        @if(isset($activity['course']))
                                            <small class="text-primary">"{{ Str::limit($activity['course']->title, 30) }}"</small>
                                        @endif
                                        <br>
                                        <small class="text-muted">{{ $activity['date']->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    /* Cartes de cours modernes */
    .course-card-modern {
        border: 1px solid var(--africode-gray-medium);
        border-radius: var(--africode-border-radius);
        overflow: hidden;
        transition: var(--africode-transition);
        height: 100%;
    }
    
    .course-card-modern:hover {
        transform: translateY(-4px);
        box-shadow: var(--africode-shadow-lg);
        border-color: var(--africode-primary);
    }
    
    .course-image-modern {
        height: 120px;
        position: relative;
        overflow: hidden;
    }
    
    .course-image-modern img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .course-placeholder {
        width: 100%;
        height: 100%;
        background: var(--africode-gradient-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    
    .course-status {
        position: absolute;
        top: 8px;
        right: 8px;
    }
    
    .course-content-modern {
        padding: 1rem;
    }
    
    .course-title-modern {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--africode-dark-text);
    }
    
    .course-description-modern {
        font-size: 0.85rem;
        color: var(--africode-gray-dark);
        margin-bottom: 0.75rem;
    }
    
    .course-stats-modern {
        font-size: 0.75rem;
    }
    
    /* Timeline d'activité */
    .activity-timeline {
        position: relative;
    }
    
    .activity-item {
        display: flex;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--africode-gray-medium);
    }
    
    .activity-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .activity-icon {
        flex-shrink: 0;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--africode-gray-light);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
    }
    
    .activity-content {
        flex: 1;
        font-size: 0.85rem;
    }
    
    .activity-content p {
        margin-bottom: 0.25rem;
        color: var(--africode-dark-text);
    }
    
    /* Icônes de statistiques */
    .stat-icon {
        opacity: 0.8;
    }
    
    /* Responsive amélioré */
    @media (max-width: 1200px) {
        .course-card-modern {
            margin-bottom: 1.5rem;
        }
        
        .activity-item {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
        }
    }
    
    @media (max-width: 992px) {
        .course-image-modern {
            height: 100px;
        }
        
        .course-content-modern {
            padding: 0.75rem;
        }
        
        .course-title-modern {
            font-size: 0.9rem;
        }
        
        .course-description-modern {
            font-size: 0.8rem;
        }
        
        .activity-content {
            font-size: 0.8rem;
        }
    }
    
    @media (max-width: 768px) {
        .course-card-modern {
            margin-bottom: 1rem;
        }
        
        .course-image-modern {
            height: 80px;
        }
        
        .course-content-modern {
            padding: 0.5rem;
        }
        
        .course-title-modern {
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }
        
        .course-description-modern {
            font-size: 0.75rem;
            margin-bottom: 0.5rem;
        }
        
        .course-stats-modern {
            font-size: 0.7rem;
            margin-bottom: 0.5rem;
        }
        
        .activity-item {
            flex-direction: column;
            text-align: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
        }
        
        .activity-icon {
            margin: 0 auto 0.5rem;
        }
        
        .activity-content {
            text-align: center;
        }
        
        /* Statistiques responsive */
        .card-body-modern {
            padding: 1rem;
        }
        
        .stat-icon {
            font-size: 1.5rem !important;
        }
        
        .card-body-modern h3 {
            font-size: 1.5rem;
        }
        
        .card-body-modern small {
            font-size: 0.75rem;
        }
    }
    
    @media (max-width: 576px) {
        .course-image-modern {
            height: 60px;
        }
        
        .course-placeholder {
            font-size: 1.5rem;
        }
        
        .course-content-modern {
            padding: 0.375rem;
        }
        
        .course-title-modern {
            font-size: 0.8rem;
        }
        
        .course-description-modern {
            font-size: 0.7rem;
            margin-bottom: 0.375rem;
        }
        
        .course-stats-modern {
            font-size: 0.65rem;
            margin-bottom: 0.375rem;
        }
        
        .course-stats-modern small {
            display: block;
            margin-bottom: 0.125rem;
        }
        
        .activity-icon {
            width: 28px;
            height: 28px;
            font-size: 0.8rem;
        }
        
        .activity-content {
            font-size: 0.75rem;
        }
        
        .activity-content p {
            font-size: 0.75rem;
        }
        
        .activity-content small {
            font-size: 0.7rem;
        }
        
        /* Cartes de statistiques */
        .card-body-modern {
            padding: 0.75rem;
        }
        
        .stat-icon i {
            font-size: 1.25rem !important;
        }
        
        .card-body-modern h3 {
            font-size: 1.25rem;
        }
        
        .card-body-modern small {
            font-size: 0.7rem;
        }
        
        /* Boutons responsive */
        .btn-primary-africode,
        .btn-secondary-africode {
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
        }
        
        .btn-sm {
            font-size: 0.75rem;
            padding: 0.375rem 0.5rem;
        }
    }
    
    @media (max-width: 480px) {
        .course-card-modern:hover {
            transform: none;
        }
        
        .activity-item {
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
        }
        
        .activity-icon {
            width: 24px;
            height: 24px;
        }
        
        .card-body-modern {
            padding: 0.5rem;
        }
        
        .card-header-modern {
            padding: 0.75rem 0.5rem;
        }
        
        .card-header-modern h5,
        .card-header-modern h6 {
            font-size: 0.9rem;
        }
    }
</style>
@endsection