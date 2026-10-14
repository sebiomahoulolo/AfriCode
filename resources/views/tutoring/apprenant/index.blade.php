@extends('layouts.layout')

@section('title', 'Mes Sessions de Tutorat')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1" style="color:#1EA38B;">Mes Sessions de Tutorat</h2>
                    <p class="text-muted mb-0">Suivez vos sessions de tutorat et accédez aux ressources</p>
                </div>
                <a href="{{ route('mentorship.index') }}" class="btn" style="color:#1EA38B;border:1px solid #1EA38B;">
                    <i class="fas fa-user-graduate me-2"></i>Demander un Mentorat
                </a>
            </div>

            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card text-white border-0" style="background:linear-gradient(135deg,#1EA38B,#27B371);border-radius:14px;box-shadow:0 4px 18px rgba(30,163,139,.35);">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="mb-0 fw-bold">{{ $sessions->where('status', 'scheduled')->count() }}</h3>
                                    <p class="mb-0 opacity-90" style="font-size:.85rem;">Programmées</p>
                                </div>
                                <div style="width:48px;height:48px;background:rgba(255,255,255,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-calendar-alt fa-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card text-white border-0" style="background:linear-gradient(135deg,#0d7a63,#1EA38B);border-radius:14px;box-shadow:0 4px 18px rgba(13,122,99,.35);">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="mb-0 fw-bold">{{ $sessions->where('status', 'completed')->count() }}</h3>
                                    <p class="mb-0 opacity-90" style="font-size:.85rem;">Terminées</p>
                                </div>
                                <div style="width:48px;height:48px;background:rgba(255,255,255,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-check-circle fa-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card text-white border-0" style="background:linear-gradient(135deg,#FF8E2A,#e07820);border-radius:14px;box-shadow:0 4px 18px rgba(255,142,42,.35);">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="mb-0 fw-bold">{{ $sessions->where('status', 'in_progress')->count() }}</h3>
                                    <p class="mb-0 opacity-90" style="font-size:.85rem;">En cours</p>
                                </div>
                                <div style="width:48px;height:48px;background:rgba(255,255,255,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-play-circle fa-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card text-white border-0" style="background:linear-gradient(135deg,#E32D31,#b5202e);border-radius:14px;box-shadow:0 4px 18px rgba(227,45,49,.35);">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="mb-0 fw-bold">{{ $sessions->where('status', 'cancelled')->count() }}</h3>
                                    <p class="mb-0 opacity-90" style="font-size:.85rem;">Annulées</p>
                                </div>
                                <div style="width:48px;height:48px;background:rgba(255,255,255,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-times-circle fa-lg"></i>
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
                    <h5 class="card-title mb-0">Mes Sessions de Tutorat</h5>
                </div>
                <div class="card-body p-0">
                    @if($sessions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Titre</th>
                                        <th>Tuteur</th>
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
                                                        <div class="avatar-title rounded-circle" style="background:#27B371;">
                                                            {{ substr($session->tutor->user->first_name, 0, 1) }}{{ substr($session->tutor->user->last_name, 0, 1) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $session->tutor->user->first_name }} {{ $session->tutor->user->last_name }}</h6>
                                                        <small class="text-muted">{{ $session->tutor->user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge" style="{{ $session->type === 'one_on_one' ? 'background:#1EA38B;' : 'background:#6c757d;' }}">
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
                                                        'scheduled' => ['style' => 'background:#FF8E2A;', 'text' => 'Programmée'],
                                                        'in_progress' => ['style' => 'background:#1EA38B;', 'text' => 'En cours'],
                                                        'completed' => ['style' => 'background:#27B371;', 'text' => 'Terminée'],
                                                        'cancelled' => ['style' => 'background:#E32D31;', 'text' => 'Annulée']
                                                    ];
                                                    $config = $statusConfig[$session->status] ?? ['style' => 'background:#6c757d;', 'text' => ucfirst($session->status)];
                                                @endphp
                                                <span class="badge" style="{{ $config['style'] }}">{{ $config['text'] }}</span>
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
                                                        @elseif($session->status === 'in_progress')
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('tutoring.sessions.join-room', $session) }}">
                                                                    <i class="fas fa-video me-2"></i>Rejoindre
                                                                </a>
                                                            </li>
                                                        @elseif($session->status === 'completed')
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('tutoring.sessions.show', $session) }}">
                                                                    <i class="fas fa-star me-2"></i>Évaluer
                                                                </a>
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
                            <a href="{{ route('mentorship.index') }}" class="btn text-white" style="background:linear-gradient(135deg,#1EA38B,#27B371);border:none;">
                                <i class="fas fa-user-graduate me-2"></i>Demander un mentorat
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
