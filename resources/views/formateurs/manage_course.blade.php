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
    <div class="course-actions d-flex justify-content-between align-items-center">
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
        <div class="d-flex align-items-center">
            <a href="{{ route('courses.show', ['slug' => $course->slug]) }}" target="_blank" class="btn btn-outline-primary me-2">
                <i class="fas fa-eye me-1"></i> Voir comme étudiant
            </a>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCourseModal">
                <i class="fas fa-trash-alt me-1"></i> Supprimer le cours
            </button>
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

    <!-- Final Exam Section -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-graduation-cap me-2"></i>Examen final
                @if($course->is_certifying)
                    <span class="badge bg-warning ms-2">Certifiant</span>
                @endif
            </h5>
            @if($course->finalQuiz)
                <div>
                    <a href="{{ route('formateur.final-exam.edit', ['courseId' => $course->id]) }}" class="btn btn-primary btn-sm me-2">
                        <i class="fas fa-edit me-1"></i> Modifier l'examen
                    </a>
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteFinalExamModal">
                        <i class="fas fa-trash me-1"></i> Supprimer
                    </button>
                </div>
            @else
                <a href="{{ route('formateur.final-exam.create', ['courseId' => $course->id]) }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus me-1"></i> Créer un examen final
                </a>
            @endif
        </div>
        <div class="card-body">
            @if($course->finalQuiz)
                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                    <div>
                        <h6 class="mb-1">
                            <i class="fas fa-flag-checkered me-2 text-primary"></i>
                            {{ $course->finalQuiz->title }}
                        </h6>
                        <p class="mb-0 text-muted">
                            {{ $course->finalQuiz->questions->count() }} question(s) • 
                            Score minimum: {{ $course->finalQuiz->passing_score }}%
                            @if($course->finalQuiz->description)
                                <br><small>{{ $course->finalQuiz->description }}</small>
                            @endif
                        </p>
                    </div>
                    <div>
                        <span class="badge bg-primary lesson-type-badge">
                            Examen final • {{ $course->finalQuiz->questions->count() }} questions
                        </span>
                    </div>
                </div>
                
                @if($course->is_certifying)
                    <div class="alert alert-info mt-3 mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Cours certifiant :</strong> Les apprenants devront réussir cet examen final avec au moins {{ $course->finalQuiz->passing_score }}% pour obtenir leur certificat de réussite.
                    </div>
                @endif
            @else
                <div class="text-center py-4">
                    <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                    <p class="mb-1">Aucun examen final configuré</p>
                    <p class="text-muted">
                        @if($course->is_certifying)
                            Ce cours est certifiant mais n'a pas encore d'examen final. Créez un examen final pour permettre aux apprenants d'obtenir leur certificat.
                        @else
                            Créez un examen final pour évaluer la compréhension globale du cours par vos apprenants.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Modal de confirmation de suppression -->
    <div class="modal fade" id="deleteCourseModal" tabindex="-1" aria-labelledby="deleteCourseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCourseModalLabel">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer le cours "<strong>{{ $course->title }}</strong>" ?</p>
                    <p class="text-danger"><strong>Attention :</strong> Cette action est irréversible et supprimera également tous les modules, leçons, quiz, inscriptions et évaluations associés à ce cours.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form action="{{ route('formateur.courses.destroy', ['courseId' => $course->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Final Exam Modal -->
    @if($course->finalQuiz)
    <div class="modal fade" id="deleteFinalExamModal" tabindex="-1" aria-labelledby="deleteFinalExamModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteFinalExamModalLabel">Confirmer la suppression de l'examen final</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer l'examen final "<strong>{{ $course->finalQuiz->title }}</strong>" ?</p>
                    <p class="text-danger"><strong>Attention :</strong> Cette action supprimera définitivement toutes les questions et réponses de l'examen. Cette action ne peut pas être annulée.</p>
                    @if($course->is_certifying)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Important :</strong> Ce cours est certifiant. Supprimer l'examen final empêchera les apprenants d'obtenir leur certificat.
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form action="{{ route('formateur.final-exam.destroy', ['courseId' => $course->id]) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer l'examen final</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection
