@extends('layouts.layout')

@section('title', 'Détails de la Session - AfriCode')

@section('content')
<div class="container mt-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="fas fa-video me-2" style="color:#1EA38B;"></i>
                        {{ $session->title }}
                    </h1>
                    <p class="text-muted mb-0">{{ $session->description }}</p>
                </div>
                <a href="{{ route('tutoring.sessions.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informations principales -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informations de la Session
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Type de Session</label>
                                <p class="mb-0">
                                    <span class="badge" style="{{ $session->type === 'one_on_one' ? 'background:#1EA38B;' : 'background:#6c757d;' }}">
                                        {{ $session->type === 'one_on_one' ? 'Individuel' : ($session->type === 'group' ? 'Groupe' : 'Atelier') }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Statut</label>
                                <p class="mb-0">
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
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Date et Heure</label>
                                <p class="mb-0">
                                    <i class="fas fa-calendar me-2"></i>{{ $session->start_time->format('d/m/Y') }}
                                    <br>
                                    <i class="fas fa-clock me-2"></i>{{ $session->start_time->format('H:i') }} - {{ $session->end_time->format('H:i') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Durée</label>
                                <p class="mb-0">
                                    <i class="fas fa-hourglass-half me-2"></i>{{ $session->duration_minutes }} minutes
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Code de Session</label>
                                <p class="mb-0">
                                    <code class="bg-light p-2 rounded">{{ $session->session_code }}</code>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Prix</label>
                                <p class="mb-0">
                                    <strong class="text-success">{{ number_format($session->price, 0) }} FCFA</strong>
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($session->notes)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Notes</label>
                        <p class="mb-0">{{ $session->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Participants -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>Participants
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Étudiant principal -->
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="background:#1EA38B;">
                                    {{ substr($session->student->first_name, 0, 1) }}{{ substr($session->student->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $session->student->first_name }} {{ $session->student->last_name }}</h6>
                                    <small class="text-muted">{{ $session->student->email }}</small>
                                    <br>
                                    <span class="badge" style="background:#1EA38B;">Étudiant principal</span>
                                </div>
                            </div>
                        </div>

                        <!-- Participants supplémentaires -->
                        @foreach($session->participants as $participant)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                    {{ substr($participant->user->first_name, 0, 1) }}{{ substr($participant->user->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $participant->user->first_name }} {{ $participant->user->last_name }}</h6>
                                    <small class="text-muted">{{ $participant->user->email }}</small>
                                    <br>
                                    <span class="badge bg-secondary">Participant</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Ressources -->
            @if($session->sessionResources->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-link me-2"></i>Ressources
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($session->sessionResources as $resource)
                        <div class="col-md-6 mb-3">
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $resource->title }}</h6>
                                    <p class="card-text text-muted">{{ $resource->description }}</p>
                                    @if($resource->url)
                                    <a href="{{ $resource->url }}" target="_blank" class="btn btn-sm" style="color:#1EA38B;border:1px solid #1EA38B;">
                                        <i class="fas fa-external-link-alt me-2"></i>Ouvrir
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Objectifs d'apprentissage -->
            @if($session->learningObjectives->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-target me-2"></i>Objectifs d'Apprentissage
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($session->learningObjectives as $objective)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $objective->objective }}
                            @if($objective->is_achieved)
                                <span class="badge" style="background:#27B371;">Atteint</span>
                            @else
                                <span class="badge text-white" style="background:linear-gradient(135deg,#FF8E2A,#e07820);border:none;">En cours</span>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Tuteur -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chalkboard-teacher me-2"></i>Tuteur
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="background:#27B371;">
                            {{ substr($session->tutor->user->first_name, 0, 1) }}{{ substr($session->tutor->user->last_name, 0, 1) }}
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $session->tutor->user->first_name }} {{ $session->tutor->user->last_name }}</h6>
                            <small class="text-muted">{{ $session->tutor->user->email }}</small>
                        </div>
                    </div>
                    @if($session->tutor->bio)
                    <div class="mt-3">
                        <p class="text-muted small">{{ $session->tutor->bio }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cogs me-2"></i>Actions
                    </h5>
                </div>
                <div class="card-body">
                    @if($session->status === 'scheduled' || $session->status === 'in_progress')
                        <a href="{{ route('tutoring.sessions.join-room', $session) }}" class="btn text-white w-100 mb-2" style="background:linear-gradient(135deg,#1EA38B,#27B371);border:none;">
                            <i class="fas fa-video me-2"></i>Rejoindre la Session
                        </a>
                    @endif

                    @if(auth()->user()->role === 'formateur' && $session->tutor->user_id === auth()->id())
                        @if($session->status === 'scheduled')
                            <form action="{{ route('tutoring.sessions.start', $session) }}" method="POST" class="mb-2">
                                @csrf
                                <button type="submit" class="btn text-white w-100" style="background:linear-gradient(135deg,#27B371,#1EA38B);border:none;">
                                    <i class="fas fa-play me-2"></i>Démarrer la Session
                                </button>
                            </form>
                        @elseif($session->status === 'in_progress')
                            <form action="{{ route('tutoring.sessions.complete', $session) }}" method="POST" class="mb-2">
                                @csrf
                                <button type="submit" class="btn text-white w-100" style="background:linear-gradient(135deg,#FF8E2A,#e07820);border:none;">
                                    <i class="fas fa-check me-2"></i>Terminer la Session
                                </button>
                            </form>
                        @endif
                    @endif

                    <a href="{{ route('tutoring.sessions.join-room', $session) }}" class="btn w-100" style="color:#1EA38B;border:1px solid #1EA38B;">
                        <i class="fas fa-comments me-2"></i>Messagerie
                    </a>
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

.badge {
    font-size: 0.75em;
}
</style>
@endsection
