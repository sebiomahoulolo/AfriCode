@extends('formateurs.layouts.app')

@section('title', 'Demandes de Mentorat — AfriCode')
@section('page-title', 'Demandes de Mentorat')
@section('page-subtitle', 'Gérez les demandes de mentorat en attente de votre approbation')

@section('header-actions')
    <a href="{{ route('mentorship.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour
    </a>
@endsection

@section('content')
<div class="container-fluid">

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card text-white border-0" style="background:linear-gradient(135deg,#FF8E2A,#e07820);border-radius:14px;box-shadow:0 4px 18px rgba(255,142,42,.35);">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 fw-bold">{{ $requests->count() }}</h3>
                            <p class="mb-0 opacity-90" style="font-size:.85rem;">Demandes en Attente</p>
                        </div>
                        <div style="width:48px;height:48px;background:rgba(255,255,255,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-clock fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white border-0" style="background:linear-gradient(135deg,#1EA38B,#27B371);border-radius:14px;box-shadow:0 4px 18px rgba(30,163,139,.35);">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 fw-bold">{{ $requests->where('created_at', '>=', now()->subDays(7))->count() }}</h3>
                            <p class="mb-0 opacity-90" style="font-size:.85rem;">Cette Semaine</p>
                        </div>
                        <div style="width:48px;height:48px;background:rgba(255,255,255,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-calendar-week fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white border-0" style="background:linear-gradient(135deg,#0d7a63,#1EA38B);border-radius:14px;box-shadow:0 4px 18px rgba(13,122,99,.35);">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 fw-bold">{{ $requests->where('created_at', '>=', now()->subDays(30))->count() }}</h3>
                            <p class="mb-0 opacity-90" style="font-size:.85rem;">Ce Mois</p>
                        </div>
                        <div style="width:48px;height:48px;background:rgba(255,255,255,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-calendar-alt fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Requests List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>Liste des Demandes
                    </h5>
                </div>
                <div class="card-body">
                    @if($requests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Apprenant</th>
                                        <th>Spécialisation</th>
                                        <th>Date de Demande</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $request)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="background:#27B371;">
                                                        {{ substr($request->mentee->first_name, 0, 1) }}{{ substr($request->mentee->last_name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $request->mentee->first_name }} {{ $request->mentee->last_name }}</h6>
                                                        <small class="text-muted">{{ $request->mentee->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge" style="background:#1EA38B;">{{ $request->specialization_area }}</span>
                                            </td>
                                            <td>
                                                <div>
                                                    <small class="text-muted">
                                                        {{ $request->requested_at->format('d/m/Y H:i') }}
                                                    </small>
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $request->requested_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('mentorship.show', $request) }}" class="btn btn-sm" style="color:#1EA38B;border:1px solid #1EA38B;">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('mentorship.approve', $request) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm" style="color:#27B371;border:1px solid #27B371;"
                                                                onclick="return confirm('Êtes-vous sûr de vouloir approuver ce mentorat ?')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('mentorship.decline', $request) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                onclick="return confirm('Êtes-vous sûr de vouloir refuser ce mentorat ?')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $requests->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune demande en attente</h5>
                            <p class="text-muted">Vous n'avez actuellement aucune demande de mentorat en attente d'approbation.</p>
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
</style>
@endsection
