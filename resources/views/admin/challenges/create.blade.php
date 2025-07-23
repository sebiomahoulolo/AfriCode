@extends('admin.layouts.app')

@section('content')
<div class="admin-content">
    <!-- Page Header -->
    <div class="admin-content-header">
        <div class="admin-content-header-content">
            <h1 class="admin-content-title">
                <i class="fas fa-plus-circle"></i> Créer un Défi ou une Compétition
            </h1>
            <nav class="admin-breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.challenges.index') }}">Défis</a>
                <span>/</span>
                <span>Créer</span>
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

            @if(session('error'))
                <div class="admin-alert admin-alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            @if(session('success') && session('challenge_id'))
                <a href="{{ route('admin.challenges.questions', session('challenge_id')) }}" class="admin-button admin-button-outline mb-4">
                    <i class="fas fa-question-circle"></i> Gérer les QCM de ce défi
                </a>
            @endif

            <form method="POST" action="{{ route('admin.challenges.store') }}" class="admin-form">
                @csrf
                
                <div class="admin-form-grid">
                    <!-- Colonne de gauche -->
                    <div class="admin-form-column">
                        <div class="admin-form-group">
                            <label for="type" class="admin-form-label">Type *</label>
                            <select id="type" name="type" class="admin-form-select" required>
                                <option value="challenge">Défi</option>
                                <option value="competition">Compétition</option>
                            </select>
                        </div>

                        <div class="admin-form-group">
                            <label for="name" class="admin-form-label">Titre *</label>
                            <input type="text" id="name" name="name" class="admin-form-input" required>
                        </div>

                        <div class="admin-form-group">
                            <label for="description" class="admin-form-label">Description *</label>
                            <textarea id="description" name="description" class="admin-form-textarea" rows="4" required></textarea>
                        </div>
                    </div>

                    <!-- Colonne de droite - Champs dynamiques -->
                    <div class="admin-form-column">
                        <!-- Champs spécifiques aux défis -->
                        <div id="challenge-fields">
                            <div class="admin-form-group">
                                <label for="type_challenge" class="admin-form-label">Type de défi</label>
                                <select id="type_challenge" name="type_challenge" class="admin-form-select">
                                    <option value="daily">Quotidien</option>
                                    <option value="weekly">Hebdomadaire</option>
                                    <option value="monthly">Mensuel</option>
                                    <option value="special">Spécial</option>
                                </select>
                            </div>

                            <div class="admin-form-group">
                                <label for="difficulty" class="admin-form-label">Difficulté</label>
                                <select id="difficulty" name="difficulty" class="admin-form-select">
                                    <option value="debutant">Débutant</option>
                                    <option value="intermediaire">Intermédiaire</option>
                                    <option value="avance">Avancé</option>
                                </select>
                            </div>

                            <div class="admin-form-group">
                                <label for="time_limit_minutes" class="admin-form-label">Temps limite (minutes)</label>
                                <input type="number" id="time_limit_minutes" name="time_limit_minutes" class="admin-form-input">
                            </div>
                        </div>

                        <!-- Champs spécifiques aux compétitions -->
                        <div id="competition-fields" style="display: none;">
                            <div class="admin-form-group">
                                <label for="start_datetime" class="admin-form-label">Date de début</label>
                                <input type="datetime-local" id="start_datetime" name="start_datetime" class="admin-form-input">
                            </div>

                            <div class="admin-form-group">
                                <label for="end_datetime" class="admin-form-label">Date de fin</label>
                                <input type="datetime-local" id="end_datetime" name="end_datetime" class="admin-form-input">
                            </div>

                            <div class="admin-form-group">
                                <label for="max_participants" class="admin-form-label">Participants maximum</label>
                                <input type="number" id="max_participants" name="max_participants" class="admin-form-input">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Champs en pleine largeur -->
                <div class="admin-form-group">
                    <label for="requirements" class="admin-form-label">Conditions (JSON ou texte)</label>
                    <textarea id="requirements" name="requirements" class="admin-form-textarea" rows="3" placeholder='Ex: {"min_score": 80}'></textarea>
                </div>

                <div class="admin-form-group">
                    <label for="rewards" class="admin-form-label">Récompenses (JSON ou texte)</label>
                    <textarea id="rewards" name="rewards" class="admin-form-textarea" rows="3" placeholder='Ex: {"badge": "Super Défi"}'></textarea>
                </div>

                <div class="admin-form-actions">
                    <button type="submit" class="admin-button admin-button-primary">
                        <i class="fas fa-save"></i> Créer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const challengeFields = document.getElementById('challenge-fields');
    const competitionFields = document.getElementById('competition-fields');

    function toggleFields() {
        if (typeSelect.value === 'challenge') {
            challengeFields.style.display = 'block';
            competitionFields.style.display = 'none';
        } else {
            challengeFields.style.display = 'none';
            competitionFields.style.display = 'block';
        }
    }

    // Initial state
    toggleFields();
    
    // Listen for changes
    typeSelect.addEventListener('change', toggleFields);
});
</script>

<style>
/* Styles spécifiques pour cette page */
.admin-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.admin-form-column {
    display: flex;
    flex-direction: column;
    gap: 15px;
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

.admin-alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.admin-button-outline {
    background-color: transparent;
    border: 1px solid #3498db;
    color: #3498db;
}

.admin-button-outline:hover {
    background-color: #e3f2fd;
}

@media (max-width: 768px) {
    .admin-form-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection