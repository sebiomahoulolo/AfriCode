@extends('formateurs.layouts.app')

@section('title', 'Créer une Session de Tutorat — AfriCode')
@section('page-title', 'Nouvelle Session de Tutorat')
@section('page-subtitle', 'Planifiez une session de tutorat avec vos apprenants')

@section('header-actions')
    <a href="{{ route('tutoring.sessions.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-list me-2"></i>Mes Sessions
    </a>
@endsection

@section('content')
<div class="container-fluid">

    <div class="row">
        <!-- Form -->
        <div class="col-lg-8">
            <!-- Messages d'erreur -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Erreurs de validation</h5>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>Informations de la Session
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('tutoring.sessions.store') }}" id="sessionForm">
                        @csrf
                        
                        <!-- Titre -->
                        <div class="mb-4">
                            <label for="title" class="form-label">
                                <i class="fas fa-heading me-2"></i>Titre de la Session
                            </label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" 
                                   placeholder="Ex: Session de révision JavaScript" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-2"></i>Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Décrivez le contenu et les objectifs de cette session...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type de Session -->
                        <div class="mb-4">
                            <label for="type" class="form-label">
                                <i class="fas fa-users me-2"></i>Type de Session
                            </label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Sélectionnez un type...</option>
                                <option value="one_on_one" {{ old('type') == 'one_on_one' ? 'selected' : '' }}>1 à 1 (Individuel)</option>
                                <option value="group" {{ old('type') == 'group' ? 'selected' : '' }}>Groupe (2-5 personnes)</option>
                                <option value="workshop" {{ old('type') == 'workshop' ? 'selected' : '' }}>Atelier (6+ personnes)</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date et Heure -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="start_time" class="form-label">
                                    <i class="fas fa-calendar me-2"></i>Date et Heure
                                </label>
                                <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" 
                                       id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="duration_minutes" class="form-label">
                                    <i class="fas fa-clock me-2"></i>Durée (minutes)
                                </label>
                                <select class="form-select @error('duration_minutes') is-invalid @enderror" 
                                        id="duration_minutes" name="duration_minutes" required>
                                    <option value="">Sélectionnez...</option>
                                    <option value="30" {{ old('duration_minutes') == '30' ? 'selected' : '' }}>30 minutes</option>
                                    <option value="60" {{ old('duration_minutes') == '60' ? 'selected' : '' }}>1 heure</option>
                                    <option value="90" {{ old('duration_minutes') == '90' ? 'selected' : '' }}>1h30</option>
                                    <option value="120" {{ old('duration_minutes') == '120' ? 'selected' : '' }}>2 heures</option>
                                    <option value="180" {{ old('duration_minutes') == '180' ? 'selected' : '' }}>3 heures</option>
                                </select>
                                @error('duration_minutes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Participants -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="fas fa-user-friends me-2"></i>Participants
                            </label>
                            <div class="card">
                                <div class="card-body">
                                    <!-- Mentorats Actifs -->
                                    @if($mentorships->count() > 0)
                                        <h6 class="mb-3" style="color:#1EA38B!important">
                                            <i class="fas fa-user-graduate me-2"></i>Apprenants avec Mentorats Actifs
                                        </h6>
                                        <div class="row">
                                            @foreach($mentorships as $mentorship)
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input participant-checkbox" type="checkbox" 
                                                               name="participants[]" value="{{ $mentorship->mentee->id }}" 
                                                               id="mentorship_{{ $mentorship->id }}"
                                                               {{ in_array($mentorship->mentee->id, old('participants', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="mentorship_{{ $mentorship->id }}">
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="background:#27B371;">
                                                                    {{ substr($mentorship->mentee->first_name, 0, 1) }}{{ substr($mentorship->mentee->last_name, 0, 1) }}
                                                                </div>
                                                                <div>
                                                                    <strong>{{ $mentorship->mentee->first_name }} {{ $mentorship->mentee->last_name }}</strong>
                                                                    <br>
                                                                    <small class="text-muted">{{ $mentorship->specialization_area }}</small>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Demandes en Attente -->
                                    @if($pendingRequests->count() > 0)
                                        <hr class="my-4">
                                        <h6 class="mb-3" style="color:#FF8E2A!important">
                                            <i class="fas fa-clock me-2"></i>Demandes en Attente d'Approbation
                                        </h6>
                                        <div class="row">
                                            @foreach($pendingRequests as $request)
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input participant-checkbox" type="checkbox" 
                                                               name="participants[]" value="{{ $request->mentee->id }}" 
                                                               id="request_{{ $request->id }}"
                                                               {{ in_array($request->mentee->id, old('participants', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="request_{{ $request->id }}">
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="background:#FF8E2A;">
                                                                    {{ substr($request->mentee->first_name, 0, 1) }}{{ substr($request->mentee->last_name, 0, 1) }}
                                                                </div>
                                                                <div>
                                                                    <strong>{{ $request->mentee->first_name }} {{ $request->mentee->last_name }}</strong>
                                                                    <br>
                                                                    <small class="text-muted">{{ $request->specialization_area }} (En attente)</small>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if($mentorships->count() == 0 && $pendingRequests->count() == 0)
                                        @if($allStudents->count() > 0)
                                            <h6 class="mb-3" style="color:#1EA38B!important">
                                                <i class="fas fa-users me-2"></i>Tous les Apprenants Disponibles (inscrits à vos cours)
                                            </h6>
                                            <div class="row">
                                                @foreach($allStudents as $student)
                                                    <div class="col-md-6 mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input participant-checkbox" type="checkbox" 
                                                                   name="participants[]" value="{{ $student->id }}" 
                                                                   id="student_{{ $student->id }}"
                                                                   {{ in_array($student->id, old('participants', [])) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="student_{{ $student->id }}">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="avatar-sm text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="background:#1EA38B;">
                                                                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                                    </div>
                                                                    <div>
                                                                        <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                                                                        <br>
                                                                        <small class="text-muted">{{ $student->email }}</small>
                                                                    </div>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <strong>Aucun apprenant disponible.</strong>
                                                <p class="mb-0 mt-2">Vous devez avoir des étudiants inscrits à vos cours pour créer une session de tutorat.</p>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            @error('participants')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Ressources -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="fas fa-link me-2"></i>Ressources (Optionnel)
                            </label>
                            <div id="resources-container">
                                <div class="input-group mb-2">
                                    <input type="url" class="form-control" name="resources[]" 
                                           placeholder="https://exemple.com/ressource">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeResource(this)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm" style="color:#1EA38B;border:1px solid #1EA38B;" onclick="addResource()">
                                <i class="fas fa-plus me-2"></i>Ajouter une Ressource
                            </button>
                        </div>

                        <!-- Objectifs d'Apprentissage -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="fas fa-target me-2"></i>Objectifs d'Apprentissage (Optionnel)
                            </label>
                            <div id="objectives-container">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="learning_objectives[]" 
                                           placeholder="Ex: Comprendre les concepts de base de JavaScript">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeObjective(this)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm" style="color:#1EA38B;border:1px solid #1EA38B;" onclick="addObjective()">
                                <i class="fas fa-plus me-2"></i>Ajouter un Objectif
                            </button>
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label">
                                <i class="fas fa-sticky-note me-2"></i>Notes (Optionnel)
                            </label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="Notes supplémentaires pour cette session...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('tutoring.sessions.index') }}" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn text-white" style="background:linear-gradient(135deg,#1EA38B,#27B371);border:none;">
                                <i class="fas fa-calendar-plus me-2"></i>Créer la Session
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Panel -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Comment ça marche ?
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <div class="text-white rounded-circle d-flex align-items-center justify-content-center" style="width:30px;height:30px;background:#1EA38B;">
                                    <span class="small fw-bold">1</span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Planifiez votre session</h6>
                                <p class="text-muted small mb-0">Choisissez la date, l'heure et les participants.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <div class="text-white rounded-circle d-flex align-items-center justify-content-center" style="width:30px;height:30px;background:#1EA38B;">
                                    <span class="small fw-bold">2</span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Code généré automatiquement</h6>
                                <p class="text-muted small mb-0">Un code unique sera créé pour la session.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <div class="text-white rounded-circle d-flex align-items-center justify-content-center" style="width:30px;height:30px;background:#1EA38B;">
                                    <span class="small fw-bold">3</span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Vidéoconférence intégrée</h6>
                                <p class="text-muted small mb-0">Jitsi Meet sera configuré automatiquement.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <div class="text-white rounded-circle d-flex align-items-center justify-content-center" style="width:30px;height:30px;background:#1EA38B;">
                                    <span class="small fw-bold">4</span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Participants notifiés</h6>
                                <p class="text-muted small mb-0">Tous les participants recevront le code.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lightbulb me-2"></i>Conseils
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Planifiez au moins 24h à l'avance</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Préparez vos ressources à l'avance</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Testez votre connexion internet</small>
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Partagez le code avec les participants</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Gestion des ressources
function addResource() {
    const container = document.getElementById('resources-container');
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <input type="url" class="form-control" name="resources[]" placeholder="https://exemple.com/ressource">
        <button type="button" class="btn btn-outline-danger" onclick="removeResource(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeResource(button) {
    button.parentElement.remove();
}

// Gestion des objectifs
function addObjective() {
    const container = document.getElementById('objectives-container');
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <input type="text" class="form-control" name="learning_objectives[]" placeholder="Ex: Comprendre les concepts de base de JavaScript">
        <button type="button" class="btn btn-outline-danger" onclick="removeObjective(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeObjective(button) {
    button.parentElement.remove();
}

// Validation du formulaire
document.getElementById('sessionForm').addEventListener('submit', function(e) {
    const participants = document.querySelectorAll('input[name="participants[]"]:checked');
    if (participants.length === 0) {
        e.preventDefault();
        alert('Veuillez sélectionner au moins un participant pour créer la session.');
        return false;
    }
    
    // Vérifier que la date est valide
    const startTime = document.getElementById('start_time').value;
    if (!startTime) {
        e.preventDefault();
        alert('Veuillez sélectionner une date et une heure pour la session.');
        return false;
    }
    
    const startDate = new Date(startTime);
    const now = new Date();
    if (startDate <= now) {
        e.preventDefault();
        alert('La date et l\'heure de la session doivent être dans le futur.');
        return false;
    }
});

// Mise à jour de la date minimum
document.getElementById('start_time').min = new Date().toISOString().slice(0, 16);
</script>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 14px;
    font-weight: bold;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 0.5rem;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    border-radius: 0.5rem 0.5rem 0 0 !important;
}

.form-check-label {
    cursor: pointer;
    width: 100%;
}

.form-check-input:checked + .form-check-label {
    background-color: rgba(30,163,139,.1);
    border-radius: 0.375rem;
    padding: 0.5rem;
}

.btn {
    border-radius: 0.375rem;
    font-weight: 500;
}
</style>
@endsection
