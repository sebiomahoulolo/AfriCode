@extends('formateurs.layouts.app')

@section('title', 'Mes Mentorats — AfriCode')
@section('page-title', 'Mes Mentorats')
@section('page-subtitle', 'Gérez vos relations de mentorat avec vos apprenants')

@section('header-actions')
    <a href="{{ route('mentorship.requests') }}" class="btn text-white" style="background:linear-gradient(135deg,#FF8E2A,#e07820);border:none;">
        <i class="fas fa-bell me-2"></i>Demandes
        @if($mentorships->where('status', 'pending_mentor_approval')->count() > 0)
            <span class="badge bg-danger ms-1">{{ $mentorships->where('status', 'pending_mentor_approval')->count() }}</span>
        @endif
    </a>
@endsection

@section('content')
<div class="container-fluid">

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white border-0" style="background:linear-gradient(135deg,#1EA38B,#27B371);border-radius:14px;box-shadow:0 4px 18px rgba(30,163,139,.35);">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 fw-bold">{{ $mentorships->where('status', 'active')->count() }}</h3>
                            <p class="mb-0 opacity-90" style="font-size:.85rem;">Mentorats Actifs</p>
                        </div>
                        <div style="width:48px;height:48px;background:rgba(255,255,255,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-user-friends fa-lg"></i>
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
                            <h3 class="mb-0 fw-bold">{{ $mentorships->where('status', 'pending_mentor_approval')->count() }}</h3>
                            <p class="mb-0 opacity-90" style="font-size:.85rem;">En Attente</p>
                        </div>
                        <div style="width:48px;height:48px;background:rgba(255,255,255,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-clock fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white border-0" style="background:linear-gradient(135deg,#6b7280,#4b5563);border-radius:14px;box-shadow:0 4px 18px rgba(75,85,99,.3);">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 fw-bold">{{ $mentorships->whereIn('status', ['ended_by_mentor', 'ended_by_mentee'])->count() }}</h3>
                            <p class="mb-0 opacity-90" style="font-size:.85rem;">Terminés</p>
                        </div>
                        <div style="width:48px;height:48px;background:rgba(255,255,255,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-check-circle fa-lg"></i>
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
                            <h3 class="mb-0 fw-bold">{{ $mentorships->count() }}</h3>
                            <p class="mb-0 opacity-90" style="font-size:.85rem;">Total</p>
                        </div>
                        <div style="width:48px;height:48px;background:rgba(255,255,255,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-chart-line fa-lg"></i>
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
                                        <th>Apprenant</th>
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
                                                    <div class="avatar-sm text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="background:#27B371;">
                                                        {{ substr($mentorship->mentee->first_name, 0, 1) }}{{ substr($mentorship->mentee->last_name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $mentorship->mentee->first_name }} {{ $mentorship->mentee->last_name }}</h6>
                                                        <small class="text-muted">{{ $mentorship->mentee->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $mentorship->specialization_area }}</span>
                                            </td>
                                            <td>
                                                @switch($mentorship->status)
                                                    @case('pending_mentor_approval')
                                                        <span class="badge" style="background:#FF8E2A;">En Attente</span>
                                                        @break
                                                    @case('active')
                                                        <span class="badge" style="background:#27B371;">Actif</span>
                                                        @break
                                                    @case('declined')
                                                        <span class="badge" style="background:#E32D31;">Refusé</span>
                                                        @break
                                                    @case('ended_by_mentor')
                                                        <span class="badge bg-secondary">Terminé par Vous</span>
                                                        @break
                                                    @case('ended_by_mentee')
                                                        <span class="badge bg-secondary">Terminé par l'Apprenant</span>
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
                                                    <a href="{{ route('mentorship.show', $mentorship) }}" class="btn btn-sm" style="color:#1EA38B;border:1px solid #1EA38B;" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($mentorship->status === 'pending_mentor_approval')
                                                        <form method="POST" action="{{ route('mentorship.approve', $mentorship) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm ms-1" style="color:#27B371;border:1px solid #27B371;"
                                                                    onclick="return confirm('Êtes-vous sûr de vouloir approuver ce mentorat ?')">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('mentorship.decline', $mentorship) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-danger ms-1"
                                                                    onclick="return confirm('Êtes-vous sûr de vouloir refuser ce mentorat ?')">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @elseif($mentorship->status === 'active')
                                                        <form method="POST" action="{{ route('mentorship.end', $mentorship) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm ms-1" style="color:#FF8E2A;border:1px solid #FF8E2A;"
                                                                    onclick="return confirm('Êtes-vous sûr de vouloir terminer ce mentorat ?')">
                                                                <i class="fas fa-stop"></i>
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
                            <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun mentorat trouvé</h5>
                            <p class="text-muted">Vous n'avez pas encore de relation de mentorat. Les demandes d'apprenants apparaîtront ici.</p>
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

.badge {
    font-size: 0.75em;
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
