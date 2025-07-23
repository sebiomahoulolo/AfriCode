@extends('formateurs.layouts.app')

@section('title', __('messages.edit_quiz_title'))

@section('page-heading', __('messages.edit_quiz'))
@section('page-subheading', __('messages.module_for', ['module' => $module->title]))

@section('styles')
<style>
    .question-card {
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 1.5rem;
        position: relative;
    }
    
    .question-header {
        background-color: #f8f9fa;
        padding: 10px 15px;
        border-bottom: 1px solid #ddd;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .answer-row {
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 10px;
        padding: 10px;
        position: relative;
    }
    
    .answer-row.correct {
        background-color: rgba(25, 135, 84, 0.1);
        border-color: #198754;
    }
    
    .drag-handle {
        cursor: move;
        color: #6c757d;
    }
    
    .remove-btn {
        color: #dc3545;
        background: transparent;
        border: none;
        font-size: 1rem;
        cursor: pointer;
        padding: 0;
    }
    
    .add-btn {
        color: #0d6efd;
        background: transparent;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        padding: 5px;
        margin: 0 auto;
    }
    
    .correct-toggle {
        width: 20px;
        height: 20px;
    }
    
    .invalid-feedback {
        display: block;
    }
    
    .question-template, .answer-template {
        display: none;
    }
    
    .fa-grip-lines {
        cursor: move;
    }
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('formateur.quizzes.update', ['quizId' => $quiz->id]) }}" id="quizForm">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="title" class="form-label">Titre du quiz <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $quiz->title) }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $quiz->description) }}</textarea>
                <div class="form-text">Une description facultative pour expliquer le but du quiz.</div>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="passing_score" class="form-label">Score minimum pour réussir (%) <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('passing_score') is-invalid @enderror" id="passing_score" name="passing_score" value="{{ old('passing_score', $quiz->passing_score) }}" min="0" max="100" required>
                @error('passing_score')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <hr class="my-4">
            
            <h4 class="mb-3">Questions</h4>
            
            <div id="questions-container">
                @foreach($quiz->questions as $index => $question)
                    <div class="question-card" data-index="{{ $index }}">
                        <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $question->id }}">
                        
                        <div class="question-header">
                            <span class="drag-handle"><i class="fas fa-grip-lines me-2"></i>Question {{ $index + 1 }}</span>
                            <button type="button" class="remove-btn remove-question" title="Supprimer la question">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Texte de la question <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="questions[{{ $index }}][text]" value="{{ $question->text }}" required>
                            </div>
                            
                            <div class="mt-3">
                                <label class="form-label">Réponses <span class="text-danger">*</span></label>
                                <p class="form-text">Cochez la ou les réponses correctes. Vous devez avoir au moins 2 réponses par question.</p>
                                
                                <div class="answers-container">
                                    <!-- Ajout d'un input radio caché pour gérer la sélection de la réponse correcte -->
                                    <input type="hidden" name="questions[{{ $index }}][correct_answer]" value="{{ $question->answers->where('is_correct', true)->keys()->first() ?? 0 }}" class="correct-answer-input">
                                    
                                    @foreach($question->answers as $ansIndex => $answer)
                                        <div class="answer-row {{ $answer->is_correct ? 'correct' : '' }}">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <i class="fas fa-grip-lines drag-handle"></i>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="form-check">
                                                        <input class="form-check-input correct-answer-radio" type="radio" name="questions_{{ $index }}_correct_radio" value="{{ $ansIndex }}" {{ $answer->is_correct ? 'checked' : '' }}>
                                                        <label class="form-check-label">Correct</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <input type="hidden" name="questions[{{ $index }}][answers][{{ $ansIndex }}][id]" value="{{ $answer->id }}">
                                                    <input type="text" class="form-control" name="questions[{{ $index }}][answers][{{ $ansIndex }}][text]" value="{{ $answer->text }}" required>
                                                    <input type="hidden" name="questions[{{ $index }}][answers][{{ $ansIndex }}][is_correct]" value="{{ $answer->is_correct ? '1' : '0' }}" class="is-correct-input">
                                                </div>
                                                <div class="col-auto">
                                                    <button type="button" class="remove-btn remove-answer" title="Supprimer la réponse">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <button type="button" class="add-btn add-answer">
                                    <i class="fas fa-plus-circle me-1"></i> Ajouter une réponse
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <button type="button" class="btn btn-outline-primary mb-4" id="add-question">
                <i class="fas fa-plus-circle me-1"></i> Ajouter une question
            </button>
            
            <div class="d-flex justify-content-between mt-4">
                <div>
                    <a href="{{ route('formateur.manage.module', ['moduleId' => $module->id]) }}" class="btn btn-outline-secondary me-2">Annuler</a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Mettre à jour le quiz</button>
                </div>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteQuizModal">
                    <i class="fas fa-trash-alt"></i> Supprimer le quiz
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Templates pour JS -->
<div class="question-template">
    <div class="question-card" data-index="__QUESTION_INDEX__">
        <div class="question-header">
            <span class="drag-handle"><i class="fas fa-grip-lines me-2"></i>Question __QUESTION_NUMBER__</span>
            <button type="button" class="remove-btn remove-question" title="Supprimer la question">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Texte de la question <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="questions[__QUESTION_INDEX__][text]" required>
            </div>
            
            <div class="mt-3">
                <label class="form-label">Réponses <span class="text-danger">*</span></label>
                <p class="form-text">Sélectionnez la réponse correcte. Vous devez avoir au moins 2 réponses par question.</p>
                
                <div class="answers-container">
                    <!-- Input caché pour gérer la sélection -->
                    <input type="hidden" name="questions[__QUESTION_INDEX__][correct_answer]" value="0" class="correct-answer-input">
                    
                    <div class="answer-row">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <i class="fas fa-grip-lines drag-handle"></i>
                            </div>
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input correct-answer-radio" type="radio" name="questions___QUESTION_INDEX___correct_radio" value="0" checked>
                                    <label class="form-check-label">Correct</label>
                                </div>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" name="questions[__QUESTION_INDEX__][answers][0][text]" required>
                                <input type="hidden" name="questions[__QUESTION_INDEX__][answers][0][is_correct]" value="1" class="is-correct-input">
                            </div>
                            <div class="col-auto">
                                <button type="button" class="remove-btn remove-answer" title="Supprimer la réponse">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="answer-row">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <i class="fas fa-grip-lines drag-handle"></i>
                            </div>
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input correct-answer-radio" type="radio" name="questions___QUESTION_INDEX___correct_radio" value="1">
                                    <label class="form-check-label">Correct</label>
                                </div>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" name="questions[__QUESTION_INDEX__][answers][1][text]" required>
                                <input type="hidden" name="questions[__QUESTION_INDEX__][answers][1][is_correct]" value="0" class="is-correct-input">
                            </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="remove-btn remove-answer" title="Supprimer la réponse">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="button" class="add-btn add-answer">
                    <i class="fas fa-plus-circle me-1"></i> Ajouter une réponse
                </button>
            </div>
        </div>
    </div>
</div>

<div class="answer-template">
    <div class="answer-row">
        <div class="row align-items-center">
            <div class="col-auto">
                <i class="fas fa-grip-lines drag-handle"></i>
            </div>
            <div class="col-auto">
                <div class="form-check">
                    <input class="form-check-input correct-answer-radio" type="radio" name="questions___QUESTION_INDEX___correct_radio" value="__ANSWER_INDEX__">
                    <label class="form-check-label">Correct</label>
                </div>
            </div>
            <div class="col">
                <input type="text" class="form-control" name="questions[__QUESTION_INDEX__][answers][__ANSWER_INDEX__][text]" required>
                <input type="hidden" name="questions[__QUESTION_INDEX__][answers][__ANSWER_INDEX__][is_correct]" value="0" class="is-correct-input">
            </div>
            <div class="col-auto">
                <button type="button" class="remove-btn remove-answer" title="Supprimer la réponse">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteQuizModal" tabindex="-1" aria-labelledby="deleteQuizModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteQuizModalLabel">Confirmation de suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce quiz ? Cette action est irréversible et supprimera toutes les questions et réponses associées.</p>
                <p class="fw-bold">{{ $quiz->title }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('formateur.quizzes.destroy', ['quizId' => $quiz->id]) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Compteurs pour les nouveaux éléments
    let questionIndex = {{ count($quiz->questions) }};
    
    // Ajouter une nouvelle question
    document.getElementById('add-question').addEventListener('click', function() {
        const template = document.querySelector('.question-template').innerHTML;
        const questionNumber = questionIndex + 1;
        const newQuestionHtml = template
            .replace(/__QUESTION_INDEX__/g, questionIndex)
            .replace(/__QUESTION_NUMBER__/g, questionNumber)
            .replace(/questions___QUESTION_INDEX___correct_radio/g, `questions_${questionIndex}_correct_radio`);
        
        const container = document.getElementById('questions-container');
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = newQuestionHtml;
        
        const newQuestion = tempDiv.firstElementChild;
        container.appendChild(newQuestion);
        
        // Ajouter les écouteurs d'événements pour la nouvelle question
        addQuestionEventListeners(newQuestion);
        questionIndex++;
        updateQuestionNumbers();
    });
    
    // Ajouter les écouteurs d'événements aux questions existantes
    document.querySelectorAll('.question-card').forEach(function(question) {
        addQuestionEventListeners(question);
    });
    
    // Fonction pour ajouter les écouteurs d'événements à une question
    function addQuestionEventListeners(questionElement) {
        // Bouton pour supprimer la question
        questionElement.querySelector('.remove-question').addEventListener('click', function() {
            if (document.querySelectorAll('.question-card').length > 1 || confirm('Êtes-vous sûr de vouloir supprimer cette dernière question ?')) {
                questionElement.remove();
                updateQuestionNumbers();
            }
        });
        
        // Bouton pour ajouter une réponse
        questionElement.querySelector('.add-answer').addEventListener('click', function() {
            addAnswer(questionElement);
        });
        
        // Boutons pour supprimer une réponse
        questionElement.querySelectorAll('.remove-answer').forEach(function(button) {
            button.addEventListener('click', function() {
                const answersContainer = this.closest('.answers-container');
                const answerRow = this.closest('.answer-row');
                
                if (answersContainer.querySelectorAll('.answer-row').length > 2) {
                    answerRow.remove();
                    updateAnswerIndexes(questionElement);
                } else {
                    alert('Vous devez avoir au moins 2 réponses par question.');
                }
            });
        });
        
        // Gestion des boutons radio pour les réponses correctes
        questionElement.querySelectorAll('.correct-answer-radio').forEach(function(radio) {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    const questionIndex = questionElement.getAttribute('data-index');
                    const selectedAnswerIndex = this.value;
                    
                    // Mettre à jour l'input caché pour la réponse correcte
                    const correctAnswerInput = questionElement.querySelector('.correct-answer-input');
                    if (correctAnswerInput) {
                        correctAnswerInput.value = selectedAnswerIndex;
                    }
                    
                    // Reset tous les inputs is_correct à 0
                    questionElement.querySelectorAll('.is-correct-input').forEach(input => {
                        input.value = '0';
                    });
                    
                    // Mettre la réponse sélectionnée à 1
                    const selectedInput = questionElement.querySelector(`input[name="questions[${questionIndex}][answers][${selectedAnswerIndex}][is_correct]"]`);
                    if (selectedInput) {
                        selectedInput.value = '1';
                    }
                    
                    // Mettre à jour les classes CSS
                    questionElement.querySelectorAll('.answer-row').forEach(row => {
                        row.classList.remove('correct');
                    });
                    this.closest('.answer-row').classList.add('correct');
                }
            });
        });
    }
    
    // Fonction pour ajouter une nouvelle réponse à une question
    function addAnswer(questionElement) {
        const questionIndex = questionElement.getAttribute('data-index');
        const answersContainer = questionElement.querySelector('.answers-container');
        const answerCount = answersContainer.querySelectorAll('.answer-row').length;
        
        const template = document.querySelector('.answer-template').innerHTML;
        const newAnswerHtml = template
            .replace(/__QUESTION_INDEX__/g, questionIndex)
            .replace(/__ANSWER_INDEX__/g, answerCount)
            .replace(/questions___QUESTION_INDEX___correct_radio/g, `questions_${questionIndex}_correct_radio`);
        
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = newAnswerHtml;
        const newAnswer = tempDiv.firstElementChild;
        
        answersContainer.appendChild(newAnswer);
        
        // Ajouter les écouteurs d'événements pour la nouvelle réponse
        newAnswer.querySelector('.remove-answer').addEventListener('click', function() {
            if (answersContainer.querySelectorAll('.answer-row').length > 2) {
                newAnswer.remove();
                updateAnswerIndexes(questionElement);
            } else {
                alert('Vous devez avoir au moins 2 réponses par question.');
            }
        });
        
        newAnswer.querySelector('.correct-answer-radio').addEventListener('change', function() {
            if (this.checked) {
                const questionIndex = questionElement.getAttribute('data-index');
                const selectedAnswerIndex = this.value;
                
                // Mettre à jour l'input caché
                const correctAnswerInput = questionElement.querySelector('.correct-answer-input');
                if (correctAnswerInput) {
                    correctAnswerInput.value = selectedAnswerIndex;
                }
                
                // Reset tous les inputs is_correct à 0
                questionElement.querySelectorAll('.is-correct-input').forEach(input => {
                    input.value = '0';
                });
                
                // Mettre la réponse sélectionnée à 1
                const selectedInput = questionElement.querySelector(`input[name="questions[${questionIndex}][answers][${selectedAnswerIndex}][is_correct]"]`);
                if (selectedInput) {
                    selectedInput.value = '1';
                }
                
                // Mettre à jour les classes CSS
                questionElement.querySelectorAll('.answer-row').forEach(row => {
                    row.classList.remove('correct');
                });
                this.closest('.answer-row').classList.add('correct');
            }
        });
    }
    
    // Fonction pour mettre à jour les numéros des questions
    function updateQuestionNumbers() {
        document.querySelectorAll('.question-card').forEach(function(question, index) {
            question.setAttribute('data-index', index);
            question.querySelector('.question-header span').innerHTML = `<i class="fas fa-grip-lines me-2"></i>Question ${index + 1}`;
            
            // Mettre à jour les noms des champs
            const inputs = question.querySelectorAll('input[name^="questions["]');
            inputs.forEach(function(input) {
                const name = input.getAttribute('name');
                input.setAttribute('name', name.replace(/questions\[\d+\]/, `questions[${index}]`));
            });
            
            updateAnswerIndexes(question);
        });
    }
    
    // Fonction pour mettre à jour les index des réponses d'une question
    function updateAnswerIndexes(questionElement) {
        const questionIndex = questionElement.getAttribute('data-index');
        const answers = questionElement.querySelectorAll('.answer-row');
        
        answers.forEach(function(answer, ansIndex) {
            // Mettre à jour les boutons radio
            const radioInput = answer.querySelector('.correct-answer-radio');
            if (radioInput) {
                radioInput.setAttribute('name', `questions_${questionIndex}_correct_radio`);
                radioInput.setAttribute('value', ansIndex);
            }
            
            // Mettre à jour les autres inputs
            const inputs = answer.querySelectorAll('input[name^="questions["]');
            inputs.forEach(function(input) {
                const name = input.getAttribute('name');
                const newName = name.replace(/questions\[\d+\]\[answers\]\[\d+\]/, `questions[${questionIndex}][answers][${ansIndex}]`);
                input.setAttribute('name', newName);
            });
        });
    }
    
    // Validation avant soumission du formulaire
    document.getElementById('quizForm').addEventListener('submit', function(event) {
        let isValid = true;
        const questions = document.querySelectorAll('.question-card');
        
        // Vérifier qu'il y a au moins une question
        if (questions.length === 0) {
            alert('Vous devez ajouter au moins une question au quiz.');
            isValid = false;
        }
        
        // Vérifier chaque question
        questions.forEach(function(question, questionIndex) {
            const answers = question.querySelectorAll('.answer-row');
            const correctAnswerRadio = question.querySelector('.correct-answer-radio:checked');
            
            // Vérifier qu'il y a au moins deux réponses par question
            if (answers.length < 2) {
                alert('Chaque question doit avoir au moins deux réponses.');
                isValid = false;
            }
            
            // Vérifier qu'une réponse correcte est sélectionnée
            if (!correctAnswerRadio) {
                alert(`La question ${questionIndex + 1} doit avoir une réponse correcte sélectionnée.`);
                isValid = false;
            }
        });
        
        if (!isValid) {
            event.preventDefault();
        }
    });
});
</script>
@endsection
