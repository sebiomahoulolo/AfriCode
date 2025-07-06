@extends('apprenants.layouts.app')

@section('title', 'AfriCode')
@section('page-title', 'Mes Cours')

@push('styles')
<style>
    /* Mes Cours - Application de la charte AfriCode */
    .stats-overview {
        background: var(--africode-gradient-primary);
        border-radius: var(--africode-border-radius);
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: var(--africode-shadow-md);
        position: relative;
        overflow: hidden;
    }

    .stats-overview::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 120px;
        height: 120px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .stat-item {
        text-align: center;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.15);
        border-radius: var(--africode-border-radius-sm);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: var(--africode-transition);
    }

    .stat-item:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.875rem;
        opacity: 0.9;
    }

    .course-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .course-item {
        background: var(--africode-surface);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
        overflow: hidden;
        transition: all 0.3s ease;
        position: relative;
    }

    .course-item:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow);
    }

    .course-header {
        position: relative;
        height: 200px;
        overflow: hidden;
    }

    .course-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .course-item:hover .course-image {
        transform: scale(1.05);
    }

    .course-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(0,0,0,0.6), rgba(0,0,0,0.3));
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .course-item:hover .course-overlay {
        opacity: 1;
    }

    .play-button {
        background: var(--primary-color);
        color: white;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: var(--shadow);
        transform: scale(0.8);
        transition: transform 0.3s ease;
    }

    .course-item:hover .play-button {
        transform: scale(1);
    }

    .course-content {
        padding: 1.5rem;
    }

    .course-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--gray-800);
    }

    .course-instructor {
        color: var(--gray-600);
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }

    .course-progress {
        margin-bottom: 1rem;
    }

    .progress-bar-container {
        background: rgba(30, 163, 139, 0.15) !important;
        height: 8px !important;
        border-radius: 4px !important;
        overflow: hidden !important;
        margin-bottom: 0.5rem !important;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.1) !important;
    }

    .progress-bar-fill {
        height: 100% !important;
        background: linear-gradient(135deg, #1EA38B 0%, #27B371 100%) !important;
        transition: width 0.5s ease !important;
        border-radius: 4px !important;
        box-shadow: 0 1px 3px rgba(30, 163, 139, 0.3) !important;
    }

    .progress-text {
        display: flex;
        justify-content: between;
        font-size: 0.875rem;
        color: var(--gray-600);
    }

    .course-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        font-size: 0.875rem;
        color: var(--gray-600);
    }

    .course-status {
        position: absolute;
        top: 1rem;
        right: 1rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-completed {
        background: var(--success-color);
        color: white;
    }

    .status-in-progress {
        background: var(--warning-color);
        color: white;
    }

    .status-not-started {
        background: var(--gray-400);
        color: white;
    }

    .course-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-continue {
        flex: 1;
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 0.75rem 1rem;
        border-radius: var(--border-radius-sm);
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-continue:hover {
        background: var(--secondary-color);
        color: white;
        transform: translateY(-1px);
    }

    .btn-details {
        padding: 0.75rem;
        background: transparent;
        color: var(--gray-600);
        border: 1px solid var(--gray-300);
        border-radius: var(--border-radius-sm);
        transition: all 0.3s ease;
    }

    .btn-details:hover {
        background: var(--gray-100);
        color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--gray-600);
    }

    .empty-icon {
        font-size: 4rem;
        color: var(--gray-400);
        margin-bottom: 1rem;
    }

    .filters-bar {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-select {
        padding: 0.5rem 1rem;
        border: 1px solid var(--gray-300);
        border-radius: var(--border-radius-sm);
        background: white;
    }

    @media (max-width: 768px) {
        .course-grid {
            grid-template-columns: 1fr;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .filters-bar {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-right">
        <div>
            <h1 class="h3 mb-0">Mes Cours</h1>
            <p class="text-muted mb-0">Suivez votre progression et continuez votre apprentissage</p>
        </div>
        <a href="{{ route('courses.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-plus me-2"></i>Découvrir plus de cours
        </a>
    </div>

    <!-- Stats Overview -->
    <div class="stats-overview" data-aos="fade-up">
        <h4 class="mb-0">Vue d'ensemble</h4>
        <p class="opacity-75 mb-0">Votre progression dans l'apprentissage</p>
        
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">{{ $totalEnrollments }}</div>
                <div class="stat-label">Cours inscrits</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $inProgressCourses }}</div>
                <div class="stat-label">En cours</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $completedCourses }}</div>
                <div class="stat-label">Terminés</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ round($averageProgress) }}%</div>
                <div class="stat-label">Progression moyenne</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-bar" data-aos="fade-up" data-aos-delay="100">
        <div class="filter-group">
            <label for="statusFilter" class="form-label mb-0">Statut:</label>
            <select id="statusFilter" class="filter-select">
                <option value="">Tous les cours</option>
                <option value="in-progress">En cours</option>
                <option value="completed">Terminés</option>
                <option value="not-started">Non commencés</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label for="sortFilter" class="form-label mb-0">Trier par:</label>
            <select id="sortFilter" class="filter-select">
                <option value="recent">Plus récents</option>
                <option value="progress">Progression</option>
                <option value="title">Titre</option>
            </select>
        </div>
        
        <div class="ms-auto">
            <span class="text-muted">{{ $enrollments->total() }} cours trouvés</span>
        </div>
    </div>

    <!-- Courses Grid -->
    @if($enrollments->count() > 0)
        <div class="course-grid" data-aos="fade-up" data-aos-delay="200">
            @foreach($enrollments as $enrollment)
                @php
                    $course = $enrollment->course;
                    $progress = $enrollment->progress_percentage;
                    $status = $enrollment->completed_at ? 'completed' : ($progress > 0 ? 'in-progress' : 'not-started');
                @endphp
                
                <div class="course-item" data-status="{{ $status }}">
                    <div class="course-header">
                        @if($course->cover_image_path && file_exists(public_path($course->cover_image_path)))
                            <img src="{{ asset($course->cover_image_path) }}" alt="{{ $course->title }}" class="course-image">
                        @else
                            <div class="course-image" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                        @endif
                        
                        <div class="course-overlay">
                            <a href="{{ route('apprenant.course.access', ['courseId' => $course->id]) }}" class="play-button">
                                <i class="fas fa-play"></i>
                            </a>
                        </div>
                        
                        <div class="course-status status-{{ $status }}">
                            @if($status === 'completed')
                                Terminé
                            @elseif($status === 'in-progress')
                                En cours
                            @else
                                Non commencé
                            @endif
                        </div>
                    </div>
                    
                    <div class="course-content">
                        <h5 class="course-title">{{ $course->title }}</h5>
                        <div class="course-instructor">
                            <i class="fas fa-user me-1"></i>
                            {{ $course->formateur->first_name }} {{ $course->formateur->last_name }}
                        </div>
                        
                        <div class="course-meta">
                            <span><i class="fas fa-layer-group me-1"></i>{{ $course->modules->count() }} modules</span>
                            <span><i class="fas fa-clock me-1"></i>{{ $course->modules->sum(function($module) { return $module->lessons->count(); }) }} leçons</span>
                            @if($course->category)
                                <span><i class="fas fa-tag me-1"></i>{{ $course->category->name }}</span>
                            @endif
                        </div>
                        
                        <div class="course-progress">
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill" style="width: {{ $progress }}%"></div>
                            </div>
                            <div class="progress-text">
                                <span>Progression</span>
                                <span class="fw-bold">{{ round($progress) }}%</span>
                            </div>
                        </div>
                        
                        <div class="course-actions">
                            <a href="{{ route('apprenant.course.access', ['courseId' => $course->id]) }}" class="btn-continue">
                                <i class="fas fa-play me-2"></i>
                                @if($status === 'completed')
                                    Revoir
                                @elseif($status === 'in-progress')
                                    Continuer
                                @else
                                    Commencer
                                @endif
                            </a>
                            <a href="{{ route('courses.show', $course->slug) }}" class="btn-details" title="Voir les détails">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($enrollments->hasPages())
            <div class="d-flex justify-content-center mt-4" data-aos="fade-up">
                {{ $enrollments->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="empty-state" data-aos="fade-up" data-aos-delay="200">
            <div class="empty-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <h4>Aucun cours inscrit</h4>
            <p class="mb-4">Vous n'êtes inscrit à aucun cours pour le moment. Découvrez notre catalogue de formations pour commencer votre apprentissage.</p>
            <a href="{{ route('courses.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-search me-2"></i>Découvrir les cours
            </a>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusFilter = document.getElementById('statusFilter');
        const sortFilter = document.getElementById('sortFilter');
        const courseItems = document.querySelectorAll('.course-item');
        
        // Filter by status
        statusFilter.addEventListener('change', function() {
            const selectedStatus = this.value;
            
            courseItems.forEach(item => {
                if (selectedStatus === '' || item.dataset.status === selectedStatus) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
        
        // Sort functionality
        sortFilter.addEventListener('change', function() {
            const sortBy = this.value;
            const container = document.querySelector('.course-grid');
            const items = Array.from(courseItems);
            
            items.sort((a, b) => {
                switch(sortBy) {
                    case 'title':
                        const titleA = a.querySelector('.course-title').textContent;
                        const titleB = b.querySelector('.course-title').textContent;
                        return titleA.localeCompare(titleB);
                    
                    case 'progress':
                        const progressA = parseFloat(a.querySelector('.progress-bar-fill').style.width);
                        const progressB = parseFloat(b.querySelector('.progress-bar-fill').style.width);
                        return progressB - progressA;
                    
                    default: // recent
                        return 0; // Keep original order (most recent first)
                }
            });
            
            // Re-append sorted items
            items.forEach(item => container.appendChild(item));
        });
    });
</script>
@endpush
