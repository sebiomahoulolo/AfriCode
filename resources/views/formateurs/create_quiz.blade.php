@extends('formateurs.layouts.app')

@section('title', 'AfriCode - Créer un quiz')

@section('page-heading', 'Créer un quiz')
@section('page-subheading', 'Module: ' . $module->title)

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
    }
    
    .answer-row:hover {
        background-color: #f0f0f0;
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
</style>
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <form class="quiz-form" method="POST" action="{{ route('formateur.quizzes.store', ['moduleId' => $module->id]) }}">
                @csrf
                
                <div class="mb-4">
                    <h5>Informations générales</h5>
                    <hr>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre du quiz <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                            <div class="form-text">Donnez un titre descriptif à ce quiz.</div>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="passing_score" class="form-label">Score minimum de réussite (%) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('passing_score') is-invalid @enderror" id="passing_score" name="passing_score" value="{{ old('passing_score', 70) }}" min="0" max="100" required>
                            <div class="form-text">Pourcentage minimum pour réussir ce quiz.</div>
                            @error('passing_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description du quiz</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
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
                    <!-- Les questions seront ajoutées ici dynamiquement -->
                    @if(old('questions'))
                        @foreach(old('questions') as $index => $question)
                            <div class="question-card" data-question-index="{{ $index }}">
                                <span class="remove-question"><i class="fas fa-times-circle"></i></span>
                                <div class="mb-3">
                                    <label for="questions[{{ $index }}][text]" class="form-label">Question {{ $index + 1 }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('questions.'.$index.'.text') is-invalid @enderror" id="questions[{{ $index }}][text]" name="questions[{{ $index }}][text]" value="{{ $question['text'] ?? '' }}" required>
                                    @error('questions.'.$index.'.text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Réponses <span class="text-danger">*</span></label>
                                    <div class="answers-container">
                                        @foreach($question['answers'] ?? [] as $aIndex => $answer)
                                            <div class="answer-row d-flex align-items-center">
                                                <div class="form-check">
                                                    <input class="form-check-input correct-answer-radio" type="radio" name="questions[{{ $index }}][correct_answer]" value="{{ $aIndex }}" {{ isset($answer['is_correct']) && $answer['is_correct'] ? 'checked' : '' }}>
                                                    <label class="form-check-label">Correct</label>
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    <input type="text" class="form-control @error('questions.'.$index.'.answers.'.$aIndex.'.text') is-invalid @enderror" name="questions[{{ $index }}][answers][{{ $aIndex }}][text]" value="{{ $answer['text'] ?? '' }}" placeholder="Réponse" required>
                                                    <input type="hidden" name="questions[{{ $index }}][answers][{{ $aIndex }}][is_correct]" value="{{ isset($answer['is_correct']) && $answer['is_correct'] ? '1' : '0' }}" class="is-correct-input">
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
                    @endif
                </div>
                
                <div class="add-question-btn mb-4" id="add-question-btn">
                    <i class="fas fa-plus-circle fs-4 mb-2"></i>
                    <p class="mb-0">Ajouter une question</p>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('formateur.manage.module', ['moduleId' => $module->id]) }}" class="btn btn-outline-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Créer le quiz</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let questionCounter = {{ old('questions') ? count(old('questions')) : 0 }};
        
        // Add first question if none exists
        if (questionCounter === 0) {
            addQuestion();
        }
        
        // Add new question
        document.getElementById('add-question-btn').addEventListener('click', function() {
            addQuestion();
        });
        
        // Remove question (delegated event)
        document.getElementById('questions-container').addEventListener('click', function(e) {
            if (e.target.closest('.remove-question')) {
                const questionCard = e.target.closest('.question-card');
                questionCard.remove();
                
                // Reindex questions
                reindexQuestions();
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
        
        // Handle radio button clicks for correct answers
        document.getElementById('questions-container').addEventListener('change', function(e) {
            if (e.target.matches('.correct-answer-radio')) {
                const questionCard = e.target.closest('.question-card');
                const questionIndex = questionCard.getAttribute('data-question-index');
                const selectedAnswerIndex = e.target.value;
                
                // Reset all is_correct inputs to 0
                questionCard.querySelectorAll('.is-correct-input').forEach(input => {
                    input.value = '0';
                });
                
                // Set the selected answer as correct
                const selectedHiddenInput = questionCard.querySelector(`input[name="questions[${questionIndex}][answers][${selectedAnswerIndex}][is_correct]"]`);
                if (selectedHiddenInput) {
                    selectedHiddenInput.value = '1';
                }
            }
        });
        
        // Function to add a new question
        function addQuestion() {
            const questionsContainer = document.getElementById('questions-container');
            
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
            
            questionsContainer.appendChild(questionCard);
            
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
            
            answerRow.innerHTML = `
                <div class="form-check">
                    <input class="form-check-input correct-answer-radio" type="radio" name="questions[${questionIndex}][correct_answer]" value="${answerIndex}" ${isCorrect ? 'checked' : ''}>
                    <label class="form-check-label">Correct</label>
                </div>
                <div class="flex-grow-1 ms-2">
                    <input type="text" class="form-control" name="questions[${questionIndex}][answers][${answerIndex}][text]" placeholder="Réponse" required>
                    <input type="hidden" name="questions[${questionIndex}][answers][${answerIndex}][is_correct]" value="${isCorrect ? '1' : '0'}" class="is-correct-input">
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
                
                questionLabel.setAttribute('for', `questions[${index}][text]`);
                questionLabel.innerHTML = `Question ${index + 1} <span class="text-danger">*</span>`;
                questionInput.setAttribute('name', `questions[${index}][text]`);
                questionInput.setAttribute('id', `questions[${index}][text]`);
                
                // Update answers
                const answerRows = card.querySelectorAll('.answer-row');
                answerRows.forEach((row, aIndex) => {
                    const radioInput = row.querySelector('.correct-answer-radio');
                    const textInput = row.querySelector('input[type="text"]');
                    const hiddenInput = row.querySelector('.is-correct-input');
                    
                    radioInput.setAttribute('name', `questions[${index}][correct_answer]`);
                    radioInput.setAttribute('value', aIndex);
                    textInput.setAttribute('name', `questions[${index}][answers][${aIndex}][text]`);
                    hiddenInput.setAttribute('name', `questions[${index}][answers][${aIndex}][is_correct]`);
                });
            });
            
            questionCounter = questionCards.length;
        }
    });
</script>
@endsection
