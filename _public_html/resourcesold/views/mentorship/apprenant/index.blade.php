@extends('layouts.mentorship')

@section('title', 'Mes Mentorats - AfriCode')

@section('content')
<div class="container mt-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1" style="color: #0ab285;">
                        <i class="fas fa-user-graduate text-primary me-2"></i>
                        Mes Mentorats
                    </h1>
                    <p class="text-muted mb-0">Gérez vos relations de mentorat avec nos formateurs experts</p>
                </div>
                <div>
                    <a href="{{ route('apprenant.dashboard') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                    <a href="{{ route('mentorship.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Demander 
                    </a>
                    <a href="{{ route('mentorship.mentors') }}" class="btn btn-outline-primary ms-2">
                        <i class="fas fa-search me-2"></i>Découvrir 
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $mentorships->where('status', 'active')->count() }}</h4>
                            <p class="mb-0">Mentorats Actifs</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-friends fa-2x"></i>
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
                            <h4 class="mb-0">{{ $mentorships->where('status', 'pending_mentor_approval')->count() }}</h4>
                            <p class="mb-0">En Attente</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
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
                            <h4 class="mb-0">{{ $mentorships->whereIn('status', ['ended_by_mentor', 'ended_by_mentee'])->count() }}</h4>
                            <p class="mb-0">Terminés</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $mentorships->count() }}</h4>
                            <p class="mb-0">Total</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mentorships List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>Liste des Mentorats
                    </h5>
                </div>
                <div class="card-body">
                    @if($mentorships->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Mentor</th>
                                        <th>Spécialisation</th>
                                        <th>Statut</th>
                                        <th>Date de Demande</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mentorships as $mentorship)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                                        {{ substr($mentorship->mentor->first_name, 0, 1) }}{{ substr($mentorship->mentor->last_name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $mentorship->mentor->first_name }} {{ $mentorship->mentor->last_name }}</h6>
                                                        <small class="text-muted">{{ $mentorship->mentor->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $mentorship->specialization_area }}</span>
                                            </td>
                                            <td>
                                                @switch($mentorship->status)
                                                    @case('pending_mentor_approval')
                                                        <span class="badge bg-warning">En Attente</span>
                                                        @break
                                                    @case('active')
                                                        <span class="badge bg-success">Actif</span>
                                                        @break
                                                    @case('declined')
                                                        <span class="badge bg-danger">Refusé</span>
                                                        @break
                                                    @case('ended_by_mentor')
                                                        <span class="badge bg-secondary">Terminé par le Mentor</span>
                                                        @break
                                                    @case('ended_by_mentee')
                                                        <span class="badge bg-secondary">Terminé par Vous</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $mentorship->requested_at->format('d/m/Y H:i') }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('mentorship.show', $mentorship) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($mentorship->status === 'active')
                                                        <form method="POST" action="{{ route('mentorship.end', $mentorship) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                    onclick="return confirm('Êtes-vous sûr de vouloir terminer ce mentorat ?')">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $mentorships->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun mentorat trouvé</h5>
                            <p class="text-muted">Vous n'avez pas encore de relation de mentorat. Commencez par découvrir nos mentors experts.</p>
                            <a href="{{ route('mentorship.mentors') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Découvrir les Mentors
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

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.btn-outline-secondary {
    border-color: #6c757d;
    color: #6c757d;
    transition: all 0.3s ease;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    color: white;
    transform: translateX(-3px);
}
</style>
@endsection
