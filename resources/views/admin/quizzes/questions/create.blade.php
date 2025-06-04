@extends('admin.layouts.app')

@section('title', 'Ajouter une Question')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-plus fa-fw me-1"></i> Ajouter une question au quiz "{{ $quiz->title }}"
            </h6>
            <div>
                <a href="{{ route('admin.quizzes.edit', $quiz->id) }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left fa-fw"></i> Retour au quiz
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.quiz.questions.store', $quiz->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="question" class="form-label">Question <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('question') is-invalid @enderror" id="question" name="question" value="{{ old('question') }}" required>
                    @error('question') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label for="type" class="form-label">Type de question <span class="text-danger">*</span></label>
                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="multiple_choice" {{ old('type') === 'multiple_choice' ? 'selected' : '' }}>Choix multiple</option>
                        <option value="true_false" {{ old('type') === 'true_false' ? 'selected' : '' }}>Vrai/Faux</option>
                        <option value="short_answer" {{ old('type') === 'short_answer' ? 'selected' : '' }}>Réponse courte</option>
                    </select>
                    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div id="options-container" class="mb-3 {{ old('type') === 'short_answer' ? 'd-none' : '' }}">
                    <label class="form-label">Options de réponse <span class="text-danger">*</span></label>
                    
                    <div id="multiple-choice-options" class="{{ old('type') === 'true_false' ? 'd-none' : '' }}">
                        <div class="options-list">
                            @if(old('options') && old('type') === 'multiple_choice')
                                @foreach(old('options') as $index => $option)
                                    <div class="input-group mb-2 option-row">
                                        <input type="text" class="form-control @error('options.'.$index) is-invalid @enderror" name="options[]" value="{{ $option }}" required>
                                        <button type="button" class="btn btn-danger remove-option"><i class="fas fa-times"></i></button>
                                        @error('options.'.$index) <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                @endforeach
                            @else
                                <div class="input-group mb-2 option-row">
                                    <input type="text" class="form-control" name="options[]" required>
                                    <button type="button" class="btn btn-danger remove-option"><i class="fas fa-times"></i></button>
                                </div>
                                <div class="input-group mb-2 option-row">
                                    <input type="text" class="form-control" name="options[]" required>
                                    <button type="button" class="btn btn-danger remove-option"><i class="fas fa-times"></i></button>
                                </div>
                            @endif
                        </div>
                        
                        <div>
                            <button type="button" class="btn btn-sm btn-success add-option">
                                <i class="fas fa-plus fa-fw"></i> Ajouter une option
                            </button>
                        </div>
                        @error('options') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    
                    <div id="true-false-options" class="{{ old('type') !== 'true_false' ? 'd-none' : '' }}">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="correct_answer" id="option-true" value="true" {{ old('correct_answer') === 'true' ? 'checked' : '' }} required>
                            <label class="form-check-label" for="option-true">Vrai</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="correct_answer" id="option-false" value="false" {{ old('correct_answer') === 'false' ? 'checked' : '' }}>
                            <label class="form-check-label" for="option-false">Faux</label>
                        </div>
                    </div>
                </div>
                
                <div id="correct-answer-container" class="mb-3 {{ old('type') === 'true_false' ? 'd-none' : '' }}">
                    <label for="correct_answer" class="form-label">Réponse correcte <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('correct_answer') is-invalid @enderror" id="correct_answer" name="correct_answer" value="{{ old('correct_answer') }}" {{ old('type') === 'true_false' ? 'readonly' : '' }} required>
                    <div class="form-text" id="correct-answer-help">
                        Pour les questions à choix multiple, entrez exactement une des options ci-dessus.
                    </div>
                    @error('correct_answer') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="points" class="form-label">Points</label>
                        <input type="number" class="form-control @error('points') is-invalid @enderror" id="points" name="points" value="{{ old('points', 1) }}" min="1">
                        @error('points') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="explanation" class="form-label">Explication (optionnelle)</label>
                    <textarea class="form-control @error('explanation') is-invalid @enderror" id="explanation" name="explanation" rows="3">{{ old('explanation') }}</textarea>
                    <div class="form-text">Explication qui sera montrée après que l'étudiant ait répondu à la question</div>
                    @error('explanation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="d-flex mt-4">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-save fa-fw"></i> Ajouter la question
                    </button>
                    <a href="{{ route('admin.quizzes.edit', $quiz->id) }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const questionType = document.getElementById('type');
        const optionsContainer = document.getElementById('options-container');
        const multipleChoiceOptions = document.getElementById('multiple-choice-options');
        const trueFalseOptions = document.getElementById('true-false-options');
        const correctAnswerContainer = document.getElementById('correct-answer-container');
        const correctAnswerInput = document.getElementById('correct_answer');
        const correctAnswerHelp = document.getElementById('correct-answer-help');
        
        // Gestion des types de questions
        questionType.addEventListener('change', function() {
            if (this.value === 'multiple_choice') {
                optionsContainer.classList.remove('d-none');
                multipleChoiceOptions.classList.remove('d-none');
                trueFalseOptions.classList.add('d-none');
                correctAnswerContainer.classList.remove('d-none');
                correctAnswerInput.readOnly = false;
                correctAnswerHelp.textContent = 'Pour les questions à choix multiple, entrez exactement une des options ci-dessus.';
            } else if (this.value === 'true_false') {
                optionsContainer.classList.remove('d-none');
                multipleChoiceOptions.classList.add('d-none');
                trueFalseOptions.classList.remove('d-none');
                correctAnswerContainer.classList.add('d-none');
                // Définir la valeur par défaut à 'true' pour les questions vrai/faux
                correctAnswerInput.value = 'true';
            } else if (this.value === 'short_answer') {
                optionsContainer.classList.add('d-none');
                correctAnswerContainer.classList.remove('d-none');
                correctAnswerInput.readOnly = false;
                correctAnswerHelp.textContent = 'Entrez la réponse exacte attendue.';
            }
        });
        
        // Gestion des options pour les questions à choix multiple
        const addOptionBtn = document.querySelector('.add-option');
        const optionsList = document.querySelector('.options-list');
        
        addOptionBtn.addEventListener('click', function() {
            const optionRow = document.createElement('div');
            optionRow.className = 'input-group mb-2 option-row';
            optionRow.innerHTML = `
                <input type="text" class="form-control" name="options[]" required>
                <button type="button" class="btn btn-danger remove-option"><i class="fas fa-times"></i></button>
            `;
            optionsList.appendChild(optionRow);
            
            // Ajouter l'événement de suppression au nouveau bouton
            optionRow.querySelector('.remove-option').addEventListener('click', function() {
                if (document.querySelectorAll('.option-row').length > 2) {
                    optionRow.remove();
                } else {
                    alert('Il doit y avoir au moins deux options de réponse.');
                }
            });
        });
        
        // Ajouter les événements de suppression aux boutons existants
        document.querySelectorAll('.remove-option').forEach(button => {
            button.addEventListener('click', function() {
                if (document.querySelectorAll('.option-row').length > 2) {
                    this.closest('.option-row').remove();
                } else {
                    alert('Il doit y avoir au moins deux options de réponse.');
                }
            });
        });
        
        // Gestion des options vrai/faux
        document.querySelectorAll('[name="correct_answer"]').forEach(radio => {
            radio.addEventListener('change', function() {
                correctAnswerInput.value = this.value;
            });
        });
    });
</script>
@endsection
