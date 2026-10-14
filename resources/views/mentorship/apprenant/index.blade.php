@extends('apprenants.layouts.app')

@section('title', 'Mes Mentorats — AfriCode')
@section('page-title', 'Mes Mentorats')

@section('content')
<div class="container-fluid py-4">

    <!-- Action Bar -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <p class="text-muted mb-0">Gérez vos relations de mentorat avec nos formateurs experts</p>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('apprenant.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
            <a href="{{ route('mentorship.create') }}" class="btn text-white" style="background:linear-gradient(135deg,#1EA38B,#27B371);border:none;">
                <i class="fas fa-plus me-2"></i>Demander un mentorat
            </a>
            <a href="{{ route('mentorship.mentors') }}" class="btn" style="color:#1EA38B;border:1px solid #1EA38B;">
                <i class="fas fa-search me-2"></i>Découvrir les mentors
            </a>
        </div>
    </div>

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
            <div class="card border-0" style="box-shadow:0 2px 10px rgba(0,0,0,.06);border-radius:.5rem;">
                <div class="card-header" style="background:#f8f9fa;border-bottom:1px solid #dee2e6;border-radius:.5rem .5rem 0 0;">
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
                                                    <div class="avatar-sm text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="background:#1EA38B;">
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
                                                        <span class="badge" style="background:#FF8E2A;">En Attente</span>
                                                        @break
                                                    @case('active')
                                                        <span class="badge" style="background:#27B371;">Actif</span>
                                                        @break
                                                    @case('declined')
                                                        <span class="badge" style="background:#E32D31;">Refusé</span>
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
                                                    <a href="{{ route('mentorship.show', $mentorship) }}" class="btn btn-sm" style="color:#1EA38B;border:1px solid #1EA38B;" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($mentorship->status === 'active')
                                                        <form method="POST" action="{{ route('mentorship.end', $mentorship) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-danger ms-1"
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

                        <div class="d-flex justify-content-center mt-4">
                            {{ $mentorships->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun mentorat trouvé</h5>
                            <p class="text-muted">Vous n'avez pas encore de relation de mentorat. Commencez par découvrir nos mentors experts.</p>
                            <a href="{{ route('mentorship.mentors') }}" class="btn text-white" style="background:linear-gradient(135deg,#1EA38B,#27B371);border:none;">
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
.avatar-sm { width:40px; height:40px; font-size:14px; font-weight:bold; }
.table th { border-top:none; font-weight:600; color:#495057; }
.btn-group .btn { margin-right:2px; }
.btn-group .btn:last-child { margin-right:0; }
</style>
@endsection
