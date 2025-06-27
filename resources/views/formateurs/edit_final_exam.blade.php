@extends('formateurs.layouts.app')

@section('title', 'AfriCode - Modifier l\'examen final')

@section('page-heading', 'Modifier l\'examen final')
@section('page-subheading', 'Cours: ' . $course->title)

@section('styles')
<style>
    .quiz-form label {
        font-weight: 600;
    }
    
    .question-card {
        background-color: #f8f9fa;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        margin-bottom: 1rem;
        position: relative;
    }
    
    .question-card .remove-question {
        position: absolute;
        top: 1rem;
        right: 1rem;
        cursor: pointer;
        color: #dc3545;
    }
    
    .answer-row {
        margin-bottom: 0.5rem;
        padding: 0.5rem;
        border-radius: 0.25rem;
        background-color: white;
        border: 1px solid #dee2e6;
    }
    
    .answer-row:hover {
        background-color: #f0f0f0;
    }
    
    .answer-row.correct {
        background-color: rgba(25, 135, 84, 0.1);
        border-color: #198754;
    }
    
    .add-question-btn {
        border: 2px dashed #ccc;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .add-question-btn:hover {
        border-color: var(--primary-color);
        background-color: #f8f9fa;
    }

    .final-exam-notice {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border-radius: var(--border-radius);
        margin-bottom: 2rem;
    }
</style>
@endsection

@section('content')
    <div class="final-exam-notice">
        <div class="d-flex align-items-center">
            <i class="fas fa-graduation-cap fs-3 me-3"></i>
            <div>
                <h5 class="mb-1">Modifier l'examen final certifiant</h5>
                <p class="mb-0">Modifiez cet examen final qui permettra aux apprenants d'obtenir leur certificat de réussite.</p>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form class="quiz-form" method="POST" action="{{ route('formateur.final-exam.update', ['courseId' => $course->id]) }}">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <h5>Informations générales</h5>
                    <hr>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre de l'examen final <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $quiz->title) }}" required>
                            <div class="form-text">Donnez un titre descriptif à cet examen final.</div>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="passing_score" class="form-label">Score minimum de réussite (%) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('passing_score') is-invalid @enderror" id="passing_score" name="passing_score" value="{{ old('passing_score', $quiz->passing_score) }}" min="0" max="100" required>
                            <div class="form-text">Pourcentage minimum pour réussir cet examen.</div>
                            @error('passing_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description de l'examen</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $quiz->description) }}</textarea>
                    <div class="form-text">Instructions ou informations supplémentaires pour les apprenants.</div>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <h5>Questions</h5>
                    <hr>
                </div>
                
                <div id="questions-container">
                    <!-- Questions existantes -->
                    @foreach($quiz->questions as $index => $question)
                        <div class="question-card" data-question-index="{{ $index }}">
                            <span class="remove-question"><i class="fas fa-times-circle"></i></span>
                            <div class="mb-3">
                                <label for="questions[{{ $index }}][text]" class="form-label">Question {{ $index + 1 }} <span class="text-danger">*</span></label>
                                <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $question->id }}">
                                <input type="text" class="form-control @error('questions.'.$index.'.text') is-invalid @enderror" id="questions[{{ $index }}][text]" name="questions[{{ $index }}][text]" value="{{ old('questions.'.$index.'.text', $question->text) }}" required>
                                @error('questions.'.$index.'.text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Réponses <span class="text-danger">*</span></label>
                                <div class="answers-container">
                                    @foreach($question->answers as $aIndex => $answer)
                                        <div class="answer-row d-flex align-items-center {{ $answer->is_correct ? 'correct' : '' }}">
                                            <div class="form-check">
                                                <input class="form-check-input correct-answer-radio" type="radio" name="questions[{{ $index }}][correct_answer]" value="{{ $aIndex }}" {{ $answer->is_correct ? 'checked' : '' }}>
                                                <label class="form-check-label">Correct</label>
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <input type="hidden" name="questions[{{ $index }}][answers][{{ $aIndex }}][id]" value="{{ $answer->id }}">
                                                <input type="text" class="form-control @error('questions.'.$index.'.answers.'.$aIndex.'.text') is-invalid @enderror" name="questions[{{ $index }}][answers][{{ $aIndex }}][text]" value="{{ old('questions.'.$index.'.answers.'.$aIndex.'.text', $answer->text) }}" placeholder="Réponse" required>
                                                <input type="hidden" name="questions[{{ $index }}][answers][{{ $aIndex }}][is_correct]" value="{{ $answer->is_correct ? '1' : '0' }}" class="is-correct-input">
                                                @error('questions.'.$index.'.answers.'.$aIndex.'.text')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="ms-2">
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-answer"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2 add-answer-btn">
                                    <i class="fas fa-plus me-1"></i> Ajouter une réponse
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="add-question-btn mb-4" id="add-question-btn">
                    <i class="fas fa-plus-circle fs-4 mb-2"></i>
                    <p class="mb-0">Ajouter une question</p>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <div>
                        <a href="{{ route('formateur.manage.course', ['courseId' => $course->id]) }}" class="btn btn-outline-secondary me-2">Annuler</a>
                        <button type="submit" class="btn btn-primary">Mettre à jour l'examen final</button>
                    </div>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteFinalExamModal">
                        <i class="fas fa-trash-alt"></i> Supprimer l'examen final
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Final Exam Modal -->
    <div class="modal fade" id="deleteFinalExamModal" tabindex="-1" aria-labelledby="deleteFinalExamModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteFinalExamModalLabel">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer cet examen final ?</p>
                    <p class="text-danger"><strong>Attention :</strong> Cette action supprimera définitivement toutes les questions et réponses de l'examen. Cette action ne peut pas être annulée.</p>
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
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let questionCounter = {!! $quiz->questions->count() !!};
        
        // Add new question
        document.getElementById('add-question-btn').addEventListener('click', function() {
            addQuestion();
        });
        
        // Remove question (delegated event)
        document.getElementById('questions-container').addEventListener('click', function(e) {
            if (e.target.closest('.remove-question')) {
                const questionCard = e.target.closest('.question-card');
                
                if (document.querySelectorAll('.question-card').length > 1) {
                    questionCard.remove();
                    reindexQuestions();
                } else {
                    alert('Un examen final doit avoir au moins une question.');
                }
            }
        });
        
        // Add answer (delegated event)
        document.getElementById('questions-container').addEventListener('click', function(e) {
            if (e.target.closest('.add-answer-btn')) {
                const btn = e.target.closest('.add-answer-btn');
                const questionCard = btn.closest('.question-card');
                const questionIndex = questionCard.getAttribute('data-question-index');
                const answersContainer = questionCard.querySelector('.answers-container');
                const answerCount = answersContainer.querySelectorAll('.answer-row').length;
                addAnswer(answersContainer, questionIndex, answerCount);
            }
        });
        
        // Remove answer (delegated event)
        document.getElementById('questions-container').addEventListener('click', function(e) {
            if (e.target.closest('.remove-answer')) {
                const btn = e.target.closest('.remove-answer');
                const answerRow = btn.closest('.answer-row');
                const answersContainer = answerRow.closest('.answers-container');
                
                // Prevent removing if only 2 answers
                if (answersContainer.querySelectorAll('.answer-row').length > 2) {
                    answerRow.remove();
                } else {
                    alert('Un minimum de 2 réponses est requis pour chaque question.');
                }
            }
        });
        
        // Handle radio button clicks
        document.getElementById('questions-container').addEventListener('change', function(e) {
            if (e.target.matches('input[type="radio"]')) {
                // Uncheck all other radios in the same question
                const questionCard = e.target.closest('.question-card');
                const otherRadios = questionCard.querySelectorAll('input[type="radio"]');
                otherRadios.forEach(radio => {
                    if (radio !== e.target) {
                        radio.checked = false;
                        radio.closest('.answer-row').classList.remove('correct');
                    }
                });
                
                // Mark current answer as correct
                if (e.target.checked) {
                    e.target.closest('.answer-row').classList.add('correct');
                }
            }
        });
        
        // Function to add a new question
        function addQuestion() {
            const questionCard = document.createElement('div');
            questionCard.className = 'question-card';
            questionCard.setAttribute('data-question-index', questionCounter);
            
            questionCard.innerHTML = `
                <span class="remove-question"><i class="fas fa-times-circle"></i></span>
                <div class="mb-3">
                    <label for="questions[${questionCounter}][text]" class="form-label">Question ${questionCounter + 1} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="questions[${questionCounter}][text]" name="questions[${questionCounter}][text]" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Réponses <span class="text-danger">*</span></label>
                    <div class="answers-container">
                        <!-- Answers will be added here -->
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2 add-answer-btn">
                        <i class="fas fa-plus me-1"></i> Ajouter une réponse
                    </button>
                </div>
            `;
            
            document.getElementById('questions-container').appendChild(questionCard);
            
            // Add initial answers (minimum 2)
            const answersContainer = questionCard.querySelector('.answers-container');
            addAnswer(answersContainer, questionCounter, 0, true); // First answer (correct by default)
            addAnswer(answersContainer, questionCounter, 1);
            
            questionCounter++;
        }
        
        // Function to add a new answer
        function addAnswer(container, questionIndex, answerIndex, isCorrect = false) {
            const answerRow = document.createElement('div');
            answerRow.className = 'answer-row d-flex align-items-center';
            if (isCorrect) answerRow.classList.add('correct');
            
            answerRow.innerHTML = `
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="questions[${questionIndex}][answers][${answerIndex}][is_correct]" value="1" ${isCorrect ? 'checked' : ''}>
                </div>
                <div class="flex-grow-1 ms-2">
                    <input type="text" class="form-control" name="questions[${questionIndex}][answers][${answerIndex}][text]" placeholder="Réponse" required>
                    <input type="hidden" name="questions[${questionIndex}][answers][${answerIndex}][is_correct]" value="0">
                </div>
                <div class="ms-2">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-answer"><i class="fas fa-trash"></i></button>
                </div>
            `;
            
            container.appendChild(answerRow);
        }
        
        // Function to reindex questions after removal
        function reindexQuestions() {
            const questionCards = document.querySelectorAll('.question-card');
            
            questionCards.forEach((card, index) => {
                card.setAttribute('data-question-index', index);
                
                // Update question label and input name
                const questionLabel = card.querySelector('label[for^="questions["]');
                const questionInput = card.querySelector('input[name^="questions["][name$="][text]"]');
                const questionIdInput = card.querySelector('input[name^="questions["][name$="][id]"]');
                
                questionLabel.setAttribute('for', `questions[${index}][text]`);
                questionLabel.innerHTML = `Question ${index + 1} <span class="text-danger">*</span>`;
                questionInput.setAttribute('name', `questions[${index}][text]`);
                questionInput.setAttribute('id', `questions[${index}][text]`);
                
                if (questionIdInput) {
                    questionIdInput.setAttribute('name', `questions[${index}][id]`);
                }
                
                // Update answers
                const answerRows = card.querySelectorAll('.answer-row');
                answerRows.forEach((row, aIndex) => {
                    const radioInput = row.querySelector('input[type="radio"]');
                    const textInput = row.querySelector('input[type="text"]');
                    const hiddenInput = row.querySelector('input[type="hidden"][name$="[is_correct]"]');
                    const idInput = row.querySelector('input[type="hidden"][name$="[id]"]');
                    
                    radioInput.setAttribute('name', `questions[${index}][answers][${aIndex}][is_correct]`);
                    textInput.setAttribute('name', `questions[${index}][answers][${aIndex}][text]`);
                    hiddenInput.setAttribute('name', `questions[${index}][answers][${aIndex}][is_correct]`);
                    
                    if (idInput) {
                        idInput.setAttribute('name', `questions[${index}][answers][${aIndex}][id]`);
                    }
                });
            });
            
            questionCounter = questionCards.length;
        }
    });
</script>
@endsection
