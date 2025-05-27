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
                <a href="{{ route('formateur.quizzes.create', ['moduleId' => $module->id]) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-question-circle me-1"></i> Ajouter un quiz
                </a>
            </div>
        </div>
        
        <!-- Lessons List -->
        <div class="col-12">
            @if($module->lessons->isEmpty() && $module->quizzes->isEmpty())
                <div class="alert alert-info">
                    Ce module ne contient pas encore de contenu. Ajoutez des leçons ou des quiz pour commencer.
                </div>
            @else
                <div class="card">
                    <div class="list-group list-group-flush">
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
                                    <button class="btn btn-sm btn-outline-primary me-2">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                        
                        @foreach($module->quizzes as $quiz)
                            <div class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                <div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-question-circle text-warning me-2"></i>
                                        <strong>{{ $quiz->title }} (Quiz)</strong>
                                    </div>
                                    <div class="small text-muted mt-1">
                                        {{ $quiz->questions->count() }} question(s) • Score pour passer: {{ $quiz->passing_score }}%
                                    </div>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-outline-primary me-2">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
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
@endsection
