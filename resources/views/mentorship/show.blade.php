@extends('apprenants.layouts.app')

@section('title', 'Détails du Mentorat - AfriCode')
@section('page-title', 'Détail du Mentorat')

@section('content')
<div class="container mt-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1" style="color: #1EA38B;">
                        <i class="fas fa-user-graduate  me-2" style="color:#1EA38B!important"></i>
                        Détails du Mentorat
                    </h1>
                    <p class="text-muted mb-0">Informations et suivi de votre relation de mentorat</p>
                </div>
                <a href="{{ route('mentorship.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Info -->
        <div class="col-lg-8">
            <!-- Mentorship Details Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informations du Mentorat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Mentor : <div class="d-flex align-items-center">
                                    <div class="avatar-sm text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="background:#1EA38B;">
                                        {{ substr($mentorship->mentor->first_name, 0, 1) }}{{ substr($mentorship->mentor->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $mentorship->mentor->first_name }} {{ $mentorship->mentor->last_name }}</h6>
                                        <small class="text-muted">{{ $mentorship->mentor->email }}</small>
                                    </div>
                                </div> </label>
                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Apprenant : <div class="d-flex align-items-center">
                                    <div class="avatar-sm text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="background:#27B371;">
                                        {{ substr($mentorship->mentee->first_name, 0, 1) }}{{ substr($mentorship->mentee->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $mentorship->mentee->first_name }} {{ $mentorship->mentee->last_name }}</h6>
                                        <small class="text-muted">{{ $mentorship->mentee->email }}</small>
                                    </div>
                                </div></label>
                                
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Spécialisation : <span class="badge fs-6" style="background:#1EA38B;">{{ $mentorship->specialization_area }}</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Statut :  @switch($mentorship->status)
                                        @case('pending_mentor_approval')
                                            <span class="badge fs-6" style="background:#FF8E2A;color:#fff;">En Attente d'Approbation</span>
                                            @break
                                        @case('active')
                                            <span class="badge fs-6" style="background:#27B371;">Actif</span>
                                            @break
                                        @case('declined')
                                            <span class="badge bg-danger fs-6">Refusé</span>
                                            @break
                                        @case('ended_by_mentor')
                                            <span class="badge bg-secondary fs-6">Terminé par le Mentor</span>
                                            @break
                                        @case('ended_by_mentee')
                                            <span class="badge bg-secondary fs-6">Terminé par l'Apprenant</span>
                                            @break
                                    @endswitch</label>
                               
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Date de Demande :                                 <p class="mb-0 text-muted">{{ $mentorship->requested_at->format('d/m/Y à H:i') }}</p></label>
                            </div>
                        </div>
                        @if($mentorship->approved_at)
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Date d'Approbation :</label>
                                    <p class="mb-0 text-muted">{{ $mentorship->approved_at->format('d/m/Y à H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($mentorship->ended_at)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Date de Fin :</label>
                                    <p class="mb-0 text-muted">{{ $mentorship->ended_at->format('d/m/Y à H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tutoring Sessions -->
            @if($tutoringSessions->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>Sessions de Tutorat
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Durée</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tutoringSessions as $session)
                                        <tr>
                                            <td>{{ $session->title }}</td>
                                            <td>
                                                @switch($session->type)
                                                    @case('one_on_one')
                                                        <span class="badge" style="background:#1EA38B;">1 à 1</span>
                                                        @break
                                                    @case('group')
                                                        <span class="badge" style="background:#1EA38B;">Groupe</span>
                                                        @break
                                                    @case('workshop')
                                                        <span class="badge" style="background:#FF8E2A;color:#fff;">Atelier</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>{{ $session->start_time->format('d/m/Y H:i') }}</td>
                                            <td>{{ $session->duration_minutes }} min</td>
                                            <td>
                                                @switch($session->status)
                                                    @case('scheduled')
                                                        <span class="badge" style="background:#1EA38B;">Programmée</span>
                                                        @break
                                                    @case('in_progress')
                                                        <span class="badge text-white" style="background:linear-gradient(135deg,#FF8E2A,#e07820);border:none;">En Cours</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="badge" style="background:#27B371;">Terminée</span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="badge bg-danger">Annulée</span>
                                                        @break
                                                @endswitch
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Actions Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cogs me-2"></i>Actions
                    </h5>
                </div>
                <div class="card-body">
                    @if($mentorship->status === 'pending_mentor_approval' && Auth::user()->id === $mentorship->mentor_id)
                        <div class="d-grid gap-2">
                            <form method="POST" action="{{ route('mentorship.approve', $mentorship) }}">
                                @csrf
                                <button type="submit" class="btn text-white w-100" style="background:linear-gradient(135deg,#27B371,#1EA38B);border:none;"
                                        onclick="return confirm('Êtes-vous sûr de vouloir approuver ce mentorat ?')">
                                    <i class="fas fa-check me-2"></i>Approuver
                                </button>
                            </form>
                            <form method="POST" action="{{ route('mentorship.decline', $mentorship) }}">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir refuser ce mentorat ?')">
                                    <i class="fas fa-times me-2"></i>Refuser
                                </button>
                            </form>
                        </div>
                    @elseif($mentorship->status === 'active')
                        <div class="d-grid gap-2">
                            <a href="{{ route('tutoring.sessions.create') }}" class="btn text-white" style="background:linear-gradient(135deg,#1EA38B,#27B371);border:none;">
                                <i class="fas fa-calendar-plus me-2"></i>Planifier une Session
                            </a>
                            <a href="#" class="btn" style="color:#1EA38B;border:1px solid #1EA38B;">
                                <i class="fas fa-comments me-2"></i>Envoyer un Message
                            </a>
                            <form method="POST" action="{{ route('mentorship.end', $mentorship) }}">
                                @csrf
                                <button type="submit" class="btn text-white w-100" style="background:linear-gradient(135deg,#FF8E2A,#e07820);border:none;"
                                        onclick="return confirm('Êtes-vous sûr de vouloir terminer ce mentorat ?')">
                                    <i class="fas fa-stop me-2"></i>Terminer le Mentorat
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="text-center">
                            <p class="text-muted mb-0">Aucune action disponible</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Mentor Info Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-tie me-2"></i>Informations du Mentor
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="mentor-avatar mx-auto mb-3">
                            {{ substr($mentorship->mentor->first_name, 0, 1) }}{{ substr($mentorship->mentor->last_name, 0, 1) }}
                        </div>
                        <h6 class="mb-1">{{ $mentorship->mentor->first_name }} {{ $mentorship->mentor->last_name }}</h6>
                        <p class="text-muted small mb-2">{{ $mentorship->mentor->email }}</p>
                        
                        <!-- Rating -->
                        <div class="rating mb-3">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= 4 ? '' : 'text-muted' }}" style="color:#FF8E2A!important"></i>
                            @endfor
                            <span class="small text-muted ms-1">(4.0)</span>
                        </div>
                    </div>

                    @if($mentorship->mentor->bio)
                        <p class="text-muted small">{{ $mentorship->mentor->bio }}</p>
                    @endif

                    <div class="row text-center">
                        <div class="col-6">
                            <div class="stat-item">
                                <h6 class="mb-0 " style="color:#1EA38B!important">{{ $mentorship->mentor->coursesInstructed ? $mentorship->mentor->coursesInstructed->count() : 0 }}</h6>
                                <small class="text-muted">Cours</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <h6 class="mb-0 " style="color:#27B371!important">{{ $mentorship->mentor->mentorships ? $mentorship->mentor->mentorships->where('status', 'active')->count() : 0 }}</h6>
                                <small class="text-muted">Mentorats</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Card -->
            {{-- <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Historique
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker" style="background:#1EA38B;"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Demande créée</h6>
                                <p class="text-muted small mb-0">{{ $mentorship->requested_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($mentorship->approved_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Mentorat approuvé</h6>
                                    <p class="text-muted small mb-0">{{ $mentorship->approved_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endif
                        
                        @if($mentorship->ended_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-secondary"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Mentorat terminé</h6>
                                    <p class="text-muted small mb-0">{{ $mentorship->ended_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div> --}}
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

.mentor-avatar {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #1EA38B, #6610f2);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
}

.rating {
    font-size: 0.9rem;
}

.stat-item {
    padding: 0.5rem;
    border-radius: 0.375rem;
    background-color: #f8f9fa;
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content h6 {
    font-size: 0.9rem;
    font-weight: 600;
    color: #495057;
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

.btn {
    border-radius: 0.375rem;
    font-weight: 500;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}
</style>
@endsection
