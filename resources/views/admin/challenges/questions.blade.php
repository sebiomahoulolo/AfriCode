@extends('admin.layouts.app')

@section('content')
<div class="admin-content">
    <!-- Page Header -->
    <div class="admin-content-header">
        <div class="admin-content-header-content">
            <h1 class="admin-content-title">
                <i class="fas fa-question-circle"></i> QCM du défi : {{ $challenge->name }}
            </h1>
            <nav class="admin-breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.challenges.index') }}">Défis</a>
                <span>/</span>
                <span>QCM</span>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="admin-card">
        <div class="admin-card-body">
            @if(session('success'))
                <div class="admin-alert admin-alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <!-- Formulaire d'ajout de question -->
            <div class="admin-form-section">
                <h3 class="admin-form-section-title">
                    <i class="fas fa-plus-circle"></i> Ajouter une question
                </h3>
                
                <form method="POST" action="{{ route('admin.challenges.questions.store', $challenge->id) }}" id="question-form" class="admin-form">
                    @csrf
                    <div class="admin-form-group">
                        <label for="question_text" class="admin-form-label">Question *</label>
                        <input type="text" name="question_text" id="question_text" class="admin-form-input" required>
                    </div>
                    
                    <div class="admin-form-group">
                        <label class="admin-form-label">Réponses possibles *</label>
                        <div id="options-list" class="admin-options-list">
                            <div class="admin-option-item">
                                <input type="text" name="options[0][option_text]" class="admin-form-input" placeholder="Réponse 1" required>
                                <label class="admin-option-correct">
                                    <input type="checkbox" name="options[0][is_correct]" value="1">
                                    Correcte
                                </label>
                            </div>
                            <div class="admin-option-item">
                                <input type="text" name="options[1][option_text]" class="admin-form-input" placeholder="Réponse 2" required>
                                <label class="admin-option-correct">
                                    <input type="checkbox" name="options[1][is_correct]" value="1">
                                    Correcte
                                </label>
                            </div>
                        </div>
                        <button type="button" class="admin-button admin-button-secondary" onclick="addOption()">
                            <i class="fas fa-plus"></i> Ajouter une réponse
                        </button>
                    </div>
                    
                    <div class="admin-form-actions">
                        <button type="submit" class="admin-button admin-button-primary" id="add-question-btn">
                            <i class="fas fa-save"></i> Ajouter la question
                        </button>
                        <button type="button" class="admin-button admin-button-success" id="add-another-btn" style="display:none;">
                            <i class="fas fa-plus-circle"></i> Ajouter une autre question
                        </button>
                    </div>
                </form>
            </div>

            <hr class="admin-divider">

            <!-- Liste des questions existantes -->
            <div class="admin-form-section">
                <h3 class="admin-form-section-title">
                    <i class="fas fa-list"></i> Questions existantes
                </h3>
                
                @forelse($questions as $question)
                    <div class="admin-question-card">
                        <div class="admin-question-header">
                            <strong>{{ $question->question_text }}</strong>
                            <form method="POST" action="{{ route('admin.challenges.questions.destroy', [$challenge->id, $question->id]) }}" class="admin-form-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-button-icon admin-button-icon-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette question ?')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                        
                        <ul class="admin-options-list">
                            @foreach($question->options as $option)
                                <li class="admin-option-item">
                                    {{ $option->option_text }}
                                    @if($option->is_correct) 
                                        <span class="admin-badge admin-badge-success">
                                            <i class="fas fa-check"></i> Correcte
                                        </span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @empty
                    <div class="admin-empty-state">
                        <i class="fas fa-info-circle"></i> Aucune question disponible pour ce défi
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
let optionIndex = 2;

function addOption() {
    const list = document.getElementById('options-list');
    const div = document.createElement('div');
    div.className = 'admin-option-item';
    div.innerHTML = `
        <input type="text" name="options[${optionIndex}][option_text]" class="admin-form-input" placeholder="Réponse ${optionIndex+1}" required>
        <label class="admin-option-correct">
            <input type="checkbox" name="options[${optionIndex}][is_correct]" value="1">
            Correcte
        </label>
    `;
    list.appendChild(div);
    optionIndex++;
}

// Réinitialiser le formulaire après ajout
const form = document.getElementById('question-form');
const addAnotherBtn = document.getElementById('add-another-btn');
const addQuestionBtn = document.getElementById('add-question-btn');

form.addEventListener('submit', function(e) {
    addQuestionBtn.disabled = true;
    setTimeout(() => {
        addQuestionBtn.disabled = false;
        addAnotherBtn.style.display = 'inline-block';
    }, 500);
});

addAnotherBtn.addEventListener('click', function() {
    form.reset();
    const list = document.getElementById('options-list');
    list.innerHTML = '';
    optionIndex = 0;
    for(let i=0; i<2; i++) addOption();
    addAnotherBtn.style.display = 'none';
});
</script>

<style>
/* Styles spécifiques pour la page QCM */
.admin-options-list {
    margin-bottom: 15px;
}

.admin-option-item {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.admin-option-item input[type="text"] {
    flex: 1;
}

.admin-option-correct {
    display: flex;
    align-items: center;
    gap: 5px;
    white-space: nowrap;
    padding: 0 10px;
    color: #495057;
}

.admin-question-card {
    background-color: #fff;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 15px;
    margin-bottom: 15px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.admin-question-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.admin-question-header strong {
    font-size: 1.05rem;
}

.admin-empty-state {
    text-align: center;
    padding: 20px;
    color: #6c757d;
    font-style: italic;
    background-color: #f8f9fa;
    border-radius: 6px;
}

.admin-divider {
    border: none;
    border-top: 1px solid #e9ecef;
    margin: 25px 0;
}

.admin-alert {
    padding: 12px 15px;
    border-radius: 6px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.admin-alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

@media (max-width: 768px) {
    .admin-option-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
    
    .admin-option-correct {
        padding: 0;
        width: 100%;
    }
    
    .admin-question-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}
</style>
@endsection