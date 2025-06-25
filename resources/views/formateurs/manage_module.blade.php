@extends('formateurs.layouts.app')

@section('title', 'AfriCode - Gérer le module')

@section('page-heading', $module->title)
@section('page-subheading', 'Module du cours: ' . $module->course->title)

@section('styles')
<style>
    .module-header {
        background-color: var(--light-bg);
        border-radius: var(--border-radius);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .lesson-card {
        margin-bottom: 1rem;
    }
    
    .lesson-card .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .lesson-card .lesson-type {
        display: flex;
        align-items: center;
    }
    
    .lesson-card .lesson-type i {
        margin-right: 0.5rem;
    }
</style>
@endsection

@section('content')
    <div class="module-header">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <span class="badge bg-secondary mb-2">Module {{ $module->order }}</span>
                <h4>{{ $module->title }}</h4>
                @if($module->description)
                    <p class="mb-0">{{ $module->description }}</p>
                @endif
            </div>
            <div>
                <a href="#" class="btn btn-outline-primary btn-sm me-2">
                    <i class="fas fa-edit me-1"></i> Modifier
                </a>
                <a href="{{ route('formateur.manage.course', ['courseId' => $module->course->id]) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Retour au cours
                </a>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Contenu du module</h5>
            <div>
                <a href="{{ route('formateur.lessons.create', ['moduleId' => $module->id]) }}" class="btn btn-success btn-sm me-2">
                    <i class="fas fa-plus me-1"></i> Ajouter une leçon
                </a>
                @if(!$module->quiz)
                    <a href="{{ route('formateur.quizzes.create', ['moduleId' => $module->id]) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-question-circle me-1"></i> Ajouter le quiz
                    </a>
                @endif
            </div>
        </div>
        
        <!-- Lessons and Quiz List -->
        <div class="col-12">
            @if($module->lessons->isEmpty() && !$module->quiz)
                <div class="alert alert-info">
                    Ce module ne contient pas encore de contenu. Ajoutez des leçons ou un quiz pour commencer.
                </div>
            @else
                <div class="card">
                    <div class="list-group list-group-flush">
                        {{-- Afficher les leçons --}}
                        @foreach($module->lessons as $lesson)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="d-flex align-items-center">
                                        @if($lesson->content_type === 'video')
                                            <i class="fas fa-video text-primary me-2"></i>
                                        @elseif($lesson->content_type === 'text')
                                            <i class="fas fa-file-alt text-info me-2"></i>
                                        @elseif($lesson->content_type === 'pdf')
                                            <i class="fas fa-file-pdf text-danger me-2"></i>
                                        @else
                                            <i class="fas fa-link text-success me-2"></i>
                                        @endif
                                        <strong>{{ $lesson->title }}</strong>
                                        @if($lesson->is_previewable)
                                            <span class="badge bg-info ms-2">Prévisualisable</span>
                                        @endif
                                    </div>
                                    <div class="small text-muted mt-1">
                                        Type: {{ ucfirst($lesson->content_type) }}
                                        @if($lesson->duration_minutes)
                                            • Durée: {{ $lesson->duration_minutes }} min
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ route('formateur.lessons.edit', ['lessonId' => $lesson->id]) }}" class="btn btn-sm btn-outline-primary me-2">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteLesson{{ $lesson->id }}Modal">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                        
                        {{-- Afficher le quiz s'il existe --}}
                        @if($module->quiz)
                            <div class="list-group-item d-flex justify-content-between align-items-center" style="background-color: #fff8e1; border-left: 4px solid #ff9800;">
                                <div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-clipboard-check text-warning me-2" style="font-size: 1.2em;"></i>
                                        <strong>{{ $module->quiz->title }}</strong>
                                        <span class="badge bg-warning text-dark ms-2">QUIZ DE MODULE</span>
                                    </div>
                                    <div class="small text-muted mt-1">
                                        {{ $module->quiz->questions->count() }} question(s) • Score minimum: {{ $module->quiz->passing_score }}%
                                        @if($module->quiz->time_limit_minutes)
                                            • Durée limite: {{ $module->quiz->time_limit_minutes }} min
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ route('formateur.quizzes.edit', ['quizId' => $module->quiz->id]) }}" class="btn btn-sm btn-outline-primary me-2" title="Modifier le quiz">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteQuiz{{ $module->quiz->id }}Modal" title="Supprimer le quiz">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <div class="d-flex justify-content-between">
        <a href="{{ route('formateur.manage.course', ['courseId' => $module->course->id]) }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-1"></i> Retour au cours
        </a>
    </div>

    {{-- Modals de suppression pour les leçons --}}
    @foreach($module->lessons as $lesson)
        <div class="modal fade" id="deleteLesson{{ $lesson->id }}Modal" tabindex="-1" aria-labelledby="deleteLesson{{ $lesson->id }}ModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteLesson{{ $lesson->id }}ModalLabel">Confirmation de suppression</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir supprimer cette leçon ?</p>
                        <p class="fw-bold">{{ $lesson->title }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <form action="{{ route('formateur.lessons.destroy', ['lessonId' => $lesson->id]) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Modal de suppression pour le quiz --}}
    @if($module->quiz)
        <div class="modal fade" id="deleteQuiz{{ $module->quiz->id }}Modal" tabindex="-1" aria-labelledby="deleteQuiz{{ $module->quiz->id }}ModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteQuiz{{ $module->quiz->id }}ModalLabel">Confirmation de suppression</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir supprimer ce quiz ? Cette action supprimera également toutes les questions et réponses associées ainsi que les tentatives des étudiants.</p>
                        <p class="fw-bold">{{ $module->quiz->title }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <form action="{{ route('formateur.quizzes.destroy', ['quizId' => $module->quiz->id]) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
