@extends('admin.layouts.app')

@section('title', 'Détails du Quiz')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-question-circle me-2"></i>
                        {{ $quiz->title }}
                    </h5>
                    <div>
                        @if($module)
                            <a href="{{ route('admin.courses.show', $module->course_id) }}" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-arrow-left me-1"></i> Retour au cours
                            </a>
                        @endif
                        <a href="{{ route('admin.quizzes.edit', $quiz->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> Modifier
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Informations générales</h6>
                            <div class="mb-3">
                                <strong>Module :</strong> 
                                {{ $module ? $module->title : 'Module non trouvé' }}
                            </div>
                            <div class="mb-3">
                                <strong>Description :</strong>
                                <p class="mt-1">{{ $quiz->description ?: 'Aucune description fournie' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Paramètres</h6>
                            <div class="mb-3">
                                <strong>Score minimum pour réussir :</strong> 
                                <span class="badge bg-info">{{ $quiz->passing_score }}%</span>
                            </div>
                            <div class="mb-3">
                                <strong>Temps limite :</strong> 
                                @if($quiz->time_limit_minutes)
                                    <span class="badge bg-warning">{{ $quiz->time_limit_minutes }} minutes</span>
                                @else
                                    <span class="text-muted">Aucune limite</span>
                                @endif
                            </div>
                            <div class="mb-3">
                                <strong>Statut :</strong>
                                @if($quiz->is_required ?? false)
                                    <span class="badge bg-danger">Obligatoire</span>
                                @else
                                    <span class="badge bg-success">Optionnel</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-muted">Questions ({{ $quiz->questions->count() }})</h6>
                                @if($quiz->questions->count() > 0)
                                    <a href="{{ route('admin.quiz.questions.create', $quiz->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-plus me-1"></i> Ajouter une question
                                    </a>
                                @endif
                            </div>

                            @forelse($quiz->questions as $index => $question)
                                <div class="card mb-3">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="card-title mb-0">
                                            Question {{ $index + 1 }}
                                            <span class="badge bg-light text-dark ms-2">{{ $question->points ?? 1 }} point(s)</span>
                                        </h6>
                                        <div>
                                            <a href="{{ route('admin.questions.edit', $question->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.questions.destroy', $question->id) }}" method="POST" 
                                                  class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette question ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">{{ $question->text }}</p>
                                        
                                        @if($question->answers->count() > 0)
                                            <h6 class="text-muted mt-3">Réponses :</h6>
                                            <div class="list-group">
                                                @foreach($question->answers as $answer)
                                                    <div class="list-group-item d-flex justify-content-between align-items-center {{ $answer->is_correct ? 'border-success' : '' }}">
                                                        <span>{{ $answer->text }}</span>
                                                        @if($answer->is_correct)
                                                            <span class="badge bg-success">Correct</span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                                    <h6 class="text-muted">Aucune question n'a été ajoutée</h6>
                                    <p class="text-muted">Commencez par ajouter des questions à ce quiz.</p>
                                    <a href="{{ route('admin.quiz.questions.create', $quiz->id) }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i> Ajouter la première question
                                    </a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
