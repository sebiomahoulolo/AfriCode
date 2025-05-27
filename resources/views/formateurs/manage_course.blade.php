@extends('formateurs.layouts.app')

@section('title', 'AfriCode - Gestion du cours')

@section('page-heading', $course->title)
@section('page-subheading', 'Gestion du cours')

@section('styles')
<style>
    .course-header {
        background-size: cover;
        background-position: center;
        border-radius: var(--border-radius);
        padding: 80px 30px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: white;
    }
    
    .course-header::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1;
    }
    
    .course-header-content {
        position: relative;
        z-index: 2;
    }
    
    .course-actions {
        margin-bottom: 2rem;
    }
    
    .module-card {
        margin-bottom: 1.5rem;
    }
    
    .module-header {
        padding: 1rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .lesson-list {
        padding: 0;
        list-style: none;
    }
    
    .lesson-item {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .lesson-item:last-child {
        border-bottom: none;
    }
    
    .lesson-type-badge {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
    
    .stats-row .stat-card {
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
    <!-- Course Header Banner -->
    <div class="course-header" style="background-image: url('{{ $course->cover_image_path ? asset($course->cover_image_path) : 'https://via.placeholder.com/1200x400?text='.urlencode($course->title) }}')">
        <div class="course-header-content">
            <h2 class="mb-2">{{ $course->title }}</h2>
            <p class="mb-0">{{ $course->short_description }}</p>
            <div class="mt-3">
                <span class="badge bg-{{ $course->status === 'published' ? 'success' : 'warning' }} me-2">
                    {{ $course->status === 'published' ? 'Publié' : 'Brouillon' }}
                </span>
                <span class="badge bg-light text-dark me-2">
                    {{ ucfirst($course->level) }}
                </span>
                @if($course->category)
                <span class="badge bg-secondary">
                    {{ $course->category->name }}
                </span>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Course Actions -->
    <div class="course-actions d-flex justify-content-between">
        <div>
            <a href="{{ route('formateur.courses.edit', ['courseId' => $course->id]) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i> Modifier le cours
            </a>
            @if($course->status !== 'published')
            <form action="{{ route('formateur.courses.publish', ['courseId' => $course->id]) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success ms-2">
                    <i class="fas fa-globe me-1"></i> Publier le cours
                </button>
            </form>
            @endif
        </div>
        <div>
            <a href="{{ route('courses.show', ['slug' => $course->slug]) }}" target="_blank" class="btn btn-outline-primary">
                <i class="fas fa-eye me-1"></i> Voir comme étudiant
            </a>
        </div>
    </div>
    
    <!-- Course Statistics -->
    <div class="row stats-row mb-4">
        <div class="col-md-3">
            <div class="stat-card text-center">
                <h3>{{ $studentsCount }}</h3>
                <p>Étudiants inscrits</p>
                <a href="{{ route('formateur.courses.students', ['courseId' => $course->id]) }}" class="btn btn-sm btn-outline-primary mt-2">Voir tous</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card text-center">
                <h3>{{ number_format($averageRating, 1) }} <i class="fas fa-star text-warning"></i></h3>
                <p>Note moyenne ({{ $ratingsCount }})</p>
                <a href="{{ route('formateur.courses.ratings', ['courseId' => $course->id]) }}" class="btn btn-sm btn-outline-primary mt-2">Voir les avis</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card text-center">
                <h3>{{ $enrollmentsCount }}</h3>
                <p>Inscriptions totales</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card text-center">
                <h3>{{ number_format($revenue, 0) }} €</h3>
                <p>Revenus générés</p>
                <a href="{{ route('formateur.courses.revenues', ['courseId' => $course->id]) }}" class="btn btn-sm btn-outline-primary mt-2">Détails</a>
            </div>
        </div>
    </div>
    
    <!-- Course Content (Modules and Lessons) -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Contenu du cours</h5>
            <a href="{{ route('formateur.modules.create', ['courseId' => $course->id]) }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus me-1"></i> Ajouter un module
            </a>
        </div>
        <div class="card-body">
            @if($course->modules->isEmpty())
                <div class="alert alert-info">
                    <p class="mb-0">Aucun module n'a encore été ajouté à ce cours. Commencez par ajouter un module.</p>
                </div>
            @else
                <div class="accordion" id="moduleAccordion">
                    @foreach($course->modules as $module)
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="module-{{ $module->id }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#moduleCollapse-{{ $module->id }}" aria-expanded="false" aria-controls="moduleCollapse-{{ $module->id }}">
                                    <div class="d-flex justify-content-between w-100 align-items-center">
                                        <span>{{ $module->order }}. {{ $module->title }}</span>
                                    </div>
                                </button>
                            </h2>
                            <div id="moduleCollapse-{{ $module->id }}" class="accordion-collapse collapse" aria-labelledby="module-{{ $module->id }}">
                                <div class="accordion-body p-0">
                                    <div class="d-flex justify-content-end p-2">
                                        <a href="{{ route('formateur.manage.module', ['moduleId' => $module->id]) }}" class="btn btn-sm btn-outline-primary me-2">
                                            <i class="fas fa-cog me-1"></i> Gérer
                                        </a>
                                        <a href="{{ route('formateur.lessons.create', ['moduleId' => $module->id]) }}" class="btn btn-sm btn-outline-success me-2">
                                            <i class="fas fa-plus me-1"></i> Ajouter une leçon
                                        </a>
                                        <a href="{{ route('formateur.quizzes.create', ['moduleId' => $module->id]) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-question-circle me-1"></i> Ajouter un quiz
                                        </a>
                                    </div>
                                    <ul class="lesson-list">
                                        @forelse($module->lessons as $lesson)
                                            <li class="lesson-item">
                                                <div>
                                                    <i class="fas fa-{{ $lesson->content_type === 'video' ? 'video' : ($lesson->content_type === 'text' ? 'file-alt' : 'file-pdf') }} me-2"></i>
                                                    {{ $lesson->title }}
                                                    @if($lesson->is_previewable)
                                                        <span class="badge bg-info ms-1">Aperçu</span>
                                                    @endif
                                                </div>
                                                <span class="badge bg-light text-dark lesson-type-badge">
                                                    {{ $lesson->content_type === 'video' ? 'Vidéo' : ($lesson->content_type === 'text' ? 'Texte' : 'PDF') }}
                                                    @if($lesson->duration_minutes)
                                                        • {{ $lesson->duration_minutes }} min
                                                    @endif
                                                </span>
                                            </li>
                                        @empty
                                            <li class="lesson-item text-muted">Aucune leçon dans ce module.</li>
                                        @endforelse

                                        @foreach($module->quizzes as $quiz)
                                            <li class="lesson-item">
                                                <div>
                                                    <i class="fas fa-question-circle me-2 text-warning"></i>
                                                    {{ $quiz->title }}
                                                </div>
                                                <span class="badge bg-warning lesson-type-badge">Quiz • {{ $quiz->questions->count() }} questions</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
