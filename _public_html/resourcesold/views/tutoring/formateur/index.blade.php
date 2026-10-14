@extends('layouts.mentorship')

@section('title', 'Mes Sessions de Tutorat')

@section('content')
<style>
    body {
        background-color: #ffffff !important;
    }
</style>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1" style="color: #0ab285;">Mes Sessions de Tutorat</h2>
                    <p class="text-muted mb-0">Gérez vos sessions de tutorat et suivez vos étudiants</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('formateur.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                    <a href="{{ route('tutoring.sessions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nouvelle Session
                    </a>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $sessions->where('status', 'scheduled')->count() }}</h4>
                                    <p class="mb-0">Programmées</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-calendar-alt fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $sessions->where('status', 'completed')->count() }}</h4>
                                    <p class="mb-0">Terminées</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $sessions->where('status', 'in_progress')->count() }}</h4>
                                    <p class="mb-0">En cours</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-play-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $sessions->where('status', 'cancelled')->count() }}</h4>
                                    <p class="mb-0">Annulées</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-times-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="d-flex gap-2">
                                <select class="form-select" id="statusFilter" style="width: auto;">
                                    <option value="">Tous les statuts</option>
                                    <option value="scheduled">Programmées</option>
                                    <option value="in_progress">En cours</option>
                                    <option value="completed">Terminées</option>
                                    <option value="cancelled">Annulées</option>
                                </select>
                                <select class="form-select" id="typeFilter" style="width: auto;">
                                    <option value="">Tous les types</option>
                                    <option value="one_on_one">Individuel</option>
                                    <option value="group">Groupe</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end">
                                <div class="input-group" style="width: 300px;">
                                    <input type="text" class="form-control" placeholder="Rechercher une session..." id="searchInput">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des sessions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sessions de Tutorat</h5>
                </div>
                <div class="card-body p-0">
                    @if($sessions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Titre</th>
                                        <th>Étudiant</th>
                                        <th>Type</th>
                                        <th>Date & Heure</th>
                                        <th>Durée</th>
                                        <th>Prix</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sessions as $session)
                                        <tr>
                                            <td>
                                                <div>
                                                    <h6 class="mb-1">{{ $session->title }}</h6>
                                                    <small class="text-muted">{{ Str::limit($session->description, 50) }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        <div class="avatar-title bg-primary rounded-circle">
                                                            {{ substr($session->student->first_name, 0, 1) }}{{ substr($session->student->last_name, 0, 1) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $session->student->first_name }} {{ $session->student->last_name }}</h6>
                                                        <small class="text-muted">{{ $session->student->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $session->type === 'one_on_one' ? 'info' : 'secondary' }}">
                                                    {{ $session->type === 'one_on_one' ? 'Individuel' : 'Groupe' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $session->start_time->format('d/m/Y') }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $session->start_time->format('H:i') }} - {{ $session->end_time->format('H:i') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ $session->duration_minutes }} min
                                                </span>
                                            </td>
                                            <td>
                                                <strong class="text-success">{{ number_format($session->price, 0) }} FCFA</strong>
                                            </td>
                                            <td>
                                                @php
                                                    $statusConfig = [
                                                        'scheduled' => ['class' => 'bg-warning', 'text' => 'Programmée'],
                                                        'in_progress' => ['class' => 'bg-info', 'text' => 'En cours'],
                                                        'completed' => ['class' => 'bg-success', 'text' => 'Terminée'],
                                                        'cancelled' => ['class' => 'bg-danger', 'text' => 'Annulée']
                                                    ];
                                                    $config = $statusConfig[$session->status] ?? ['class' => 'bg-secondary', 'text' => ucfirst($session->status)];
                                                @endphp
                                                <span class="badge {{ $config['class'] }}">{{ $config['text'] }}</span>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('tutoring.sessions.show', $session) }}">
                                                                <i class="fas fa-eye me-2"></i>Voir détails
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('tutoring.sessions.join-room', $session) }}">
                                                                <i class="fas fa-comments me-2"></i>Messagerie
                                                            </a>
                                                        </li>
                                                        @if($session->status === 'scheduled')
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('tutoring.sessions.join-room', $session) }}">
                                                                    <i class="fas fa-video me-2"></i>Rejoindre
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('tutoring.sessions.start', $session) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" class="dropdown-item">
                                                                        <i class="fas fa-play me-2"></i>Démarrer
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @elseif($session->status === 'in_progress')
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('tutoring.sessions.join-room', $session) }}">
                                                                    <i class="fas fa-video me-2"></i>Rejoindre
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('tutoring.sessions.complete', $session) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" class="dropdown-item">
                                                                        <i class="fas fa-check me-2"></i>Terminer
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                        @if(in_array($session->status, ['scheduled', 'in_progress']))
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <form action="{{ route('tutoring.sessions.cancel', $session) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette session ?')">
                                                                        <i class="fas fa-times me-2"></i>Annuler
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="card-footer">
                            {{ $sessions->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-calendar-times fa-4x text-muted"></i>
                            </div>
                            <h4 class="text-muted">Aucune session trouvée</h4>
                            <p class="text-muted mb-4">Vous n'avez pas encore de sessions de tutorat programmées.</p>
                            <a href="{{ route('tutoring.sessions.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Créer votre première session
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 600;
    color: white;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #6c757d;
    font-size: 0.875rem;
}

.table td {
    vertical-align: middle;
}

.dropdown-menu {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    border: none;
}

.dropdown-item {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtres
    const statusFilter = document.getElementById('statusFilter');
    const typeFilter = document.getElementById('typeFilter');
    const searchInput = document.getElementById('searchInput');
    
    function filterSessions() {
        const status = statusFilter.value;
        const type = typeFilter.value;
        const search = searchInput.value.toLowerCase();
        
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const statusBadge = row.querySelector('.badge');
            const typeBadge = row.querySelectorAll('.badge')[1];
            const title = row.querySelector('h6').textContent.toLowerCase();
            const description = row.querySelector('small').textContent.toLowerCase();
            
            const statusMatch = !status || statusBadge.textContent.toLowerCase().includes(status);
            const typeMatch = !type || (type === 'one_on_one' && typeBadge.textContent.includes('Individuel')) || 
                             (type === 'group' && typeBadge.textContent.includes('Groupe'));
            const searchMatch = !search || title.includes(search) || description.includes(search);
            
            row.style.display = (statusMatch && typeMatch && searchMatch) ? '' : 'none';
        });
    }
    
    statusFilter.addEventListener('change', filterSessions);
    typeFilter.addEventListener('change', filterSessions);
    searchInput.addEventListener('input', filterSessions);
});
</script>
@endsection
