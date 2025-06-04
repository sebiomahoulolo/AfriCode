@extends('admin.layouts.app')

@section('title', 'Modifier un Quiz')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-edit fa-fw me-1"></i> Modifier le quiz
            </h6>
            <div>
                @if($module)
                <a href="{{ route('admin.modules.edit', $module->id) }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left fa-fw"></i> Retour au module
                </a>
                @else
                <a href="{{ route('admin.courses.show', $course->id) }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left fa-fw"></i> Retour au cours
                </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.quizzes.update', $quiz->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label">Titre du quiz <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $quiz->title) }}" required>
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $quiz->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="passing_score" class="form-label">Score de réussite (%) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('passing_score') is-invalid @enderror" id="passing_score" name="passing_score" value="{{ old('passing_score', $quiz->passing_score) }}" min="0" max="100">
                        @error('passing_score') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="time_limit" class="form-label">Limite de temps (minutes)</label>
                        <input type="number" class="form-control @error('time_limit') is-invalid @enderror" id="time_limit" name="time_limit" value="{{ old('time_limit', $quiz->time_limit) }}" min="0">
                        <div class="form-text">Laisser vide pour aucune limite de temps</div>
                        @error('time_limit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_required" name="is_required" value="1" {{ old('is_required', $quiz->is_required) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_required">
                            Obligatoire pour compléter le module
                        </label>
                    </div>
                </div>
                
                <div class="d-flex mt-4">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-save fa-fw"></i> Enregistrer les modifications
                    </button>
                    @if($module)
                    <a href="{{ route('admin.modules.edit', $module->id) }}" class="btn btn-secondary">Annuler</a>
                    @else
                    <a href="{{ route('admin.courses.show', $course->id) }}" class="btn btn-secondary">Annuler</a>
                    @endif
                    
                    <button type="button" class="btn btn-danger ms-auto" data-bs-toggle="modal" data-bs-target="#deleteQuizModal">
                        <i class="fas fa-trash fa-fw"></i> Supprimer le quiz
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Questions du quiz -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-question-circle fa-fw me-1"></i> Questions
            </h6>
            <a href="{{ route('admin.quiz.questions.create', $quiz->id) }}" class="btn btn-sm btn-success">
                <i class="fas fa-plus fa-fw"></i> Ajouter une question
            </a>
        </div>
        <div class="card-body">
            @if($quiz->questions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="50%">Question</th>
                                <th>Type</th>
                                <th>Points</th>
                                <th width="15%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quiz->questions as $question)
                                <tr>
                                    <td>{{ $question->question }}</td>
                                    <td>
                                        @if($question->type === 'multiple_choice')
                                            <span class="badge bg-info">Choix multiple</span>
                                        @elseif($question->type === 'true_false')
                                            <span class="badge bg-success">Vrai/Faux</span>
                                        @elseif($question->type === 'short_answer')
                                            <span class="badge bg-warning">Réponse courte</span>
                                        @endif
                                    </td>
                                    <td>{{ $question->points }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info view-question-btn" data-bs-toggle="modal" data-bs-target="#viewQuestionModal" 
                                                data-question="{{ $question->question }}"
                                                data-type="{{ $question->type }}"
                                                data-options="{{ json_encode($question->options) }}"
                                                data-correct="{{ $question->correct_answer }}"
                                                data-explanation="{{ $question->explanation }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('admin.question.edit', $question->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger delete-question-btn" 
                                                data-bs-toggle="modal" data-bs-target="#deleteQuestionModal"
                                                data-question-id="{{ $question->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    Ce quiz n'a pas encore de questions. Ajoutez-en en cliquant sur le bouton ci-dessus.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de suppression du quiz -->
<div class="modal fade" id="deleteQuizModal" tabindex="-1" aria-labelledby="deleteQuizModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteQuizModalLabel">Confirmation de suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce quiz et toutes ses questions ?</p>
                <p class="text-danger">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('admin.quizzes.destroy', $quiz->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de suppression d'une question -->
<div class="modal fade" id="deleteQuestionModal" tabindex="-1" aria-labelledby="deleteQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteQuestionModalLabel">Supprimer la question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cette question ?</p>
                <p class="text-danger">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="delete-question-form" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'affichage d'une question -->
<div class="modal fade" id="viewQuestionModal" tabindex="-1" aria-labelledby="viewQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewQuestionModalLabel">Détails de la question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <h5 id="modal-question-text"></h5>
                <hr>
                <div class="mb-3">
                    <strong>Type:</strong> <span id="modal-question-type"></span>
                </div>
                
                <div id="modal-question-options" class="mb-3">
                    <strong>Options:</strong>
                    <ul id="modal-options-list" class="list-group mt-2"></ul>
                </div>
                
                <div class="mb-3">
                    <strong>Réponse correcte:</strong> <span id="modal-correct-answer" class="badge bg-success"></span>
                </div>
                
                <div id="modal-explanation-section" class="mb-3">
                    <strong>Explication:</strong>
                    <p id="modal-explanation" class="mt-2 p-2 bg-light"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion du modal de suppression d'une question
        const deleteQuestionButtons = document.querySelectorAll('.delete-question-btn');
        if (deleteQuestionButtons) {
            deleteQuestionButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const questionId = this.getAttribute('data-question-id');
                    document.getElementById('delete-question-form').action = `/admin/questions/${questionId}`;
                });
            });
        }
        
        // Gestion du modal de visualisation d'une question
        const viewQuestionButtons = document.querySelectorAll('.view-question-btn');
        if (viewQuestionButtons) {
            viewQuestionButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const questionText = this.getAttribute('data-question');
                    const questionType = this.getAttribute('data-type');
                    const questionOptions = JSON.parse(this.getAttribute('data-options'));
                    const correctAnswer = this.getAttribute('data-correct');
                    const explanation = this.getAttribute('data-explanation');
                    
                    document.getElementById('modal-question-text').textContent = questionText;
                    
                    let typeText = '';
                    if (questionType === 'multiple_choice') {
                        typeText = 'Choix multiple';
                    } else if (questionType === 'true_false') {
                        typeText = 'Vrai/Faux';
                    } else if (questionType === 'short_answer') {
                        typeText = 'Réponse courte';
                    }
                    document.getElementById('modal-question-type').textContent = typeText;
                    
                    // Afficher les options si disponibles
                    const optionsSection = document.getElementById('modal-question-options');
                    const optionsList = document.getElementById('modal-options-list');
                    optionsList.innerHTML = '';
                    
                    if (questionType === 'multiple_choice' || questionType === 'true_false') {
                        optionsSection.classList.remove('d-none');
                        
                        for (let option of questionOptions) {
                            const li = document.createElement('li');
                            li.className = 'list-group-item';
                            if (option === correctAnswer) {
                                li.className += ' list-group-item-success';
                            }
                            li.textContent = option;
                            optionsList.appendChild(li);
                        }
                    } else {
                        optionsSection.classList.add('d-none');
                    }
                    
                    document.getElementById('modal-correct-answer').textContent = correctAnswer;
                    
                    // Explication
                    const explanationSection = document.getElementById('modal-explanation-section');
                    const explanationText = document.getElementById('modal-explanation');
                    if (explanation) {
                        explanationSection.classList.remove('d-none');
                        explanationText.textContent = explanation;
                    } else {
                        explanationSection.classList.add('d-none');
                    }
                });
            });
        }
    });
</script>
@endsection
