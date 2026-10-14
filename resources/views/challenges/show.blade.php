@extends('layouts.layout')

@section('title', $challenge->name)

@section('content')
<div class="container mt-5">
    <div class="competition-header">
        <h1><i class="fas fa-code me-2"></i>{{ $challenge->name }}</h1>
        <p>{{ $challenge->description }}</p>
    </div>
    <div class="row mb-4">
        <div class="col-md-6">
            <ul class="list-group">
                <li class="list-group-item"><strong>Difficulté :</strong> {{ ucfirst($challenge->difficulty) }}</li>
                <li class="list-group-item"><strong>Type :</strong> {{ ucfirst($challenge->type) }}</li>
                <li class="list-group-item"><strong>Participants :</strong> {{ $participantsCount }}</li>
                <li class="list-group-item"><strong>Statut :</strong> {{ $challenge->is_active ? 'Actif' : 'Inactif' }}</li>
            </ul>
        </div>
    </div>
    <div class="mb-4">
        @if($isParticipating)
            <div class="alert alert-success">Vous participez déjà à ce défi.</div>
            <a href="{{ route('challenges.play', $challenge->id) }}" class="btn text-white mt-2" style="background:linear-gradient(135deg,#27B371,#1EA38B);border:none;">
                <i class="fas fa-play"></i> Participer maintenant
            </a>
        @else
            <form method="POST" action="{{ route('challenges.participate', $challenge->id) }}">
                @csrf
                <button type="submit" class="btn text-white" style="background:linear-gradient(135deg,#1EA38B,#27B371);border:none;">
                    <i class="fas fa-sign-in-alt"></i> Participer au défi
                </button>
            </form>
        @endif
    </div>
    <a href="{{ route('pages.compdisp') }}" class="btn btn-link"><i class="fas fa-arrow-left"></i> Retour à la liste</a>
</div>
@endsection 