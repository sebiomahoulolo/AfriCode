@extends('layouts.layout')

@section('title', $competition->title)

@section('content')
<div class="container mt-5">
    <div class="competition-header">
        <h1><i class="fas fa-trophy me-2"></i>{{ $competition->title }}</h1>
        <p>{{ $competition->description }}</p>
    </div>
    <div class="row mb-4">
        <div class="col-md-6">
            <ul class="list-group">
                <li class="list-group-item"><strong>Dates :</strong> {{ $competition->start_datetime ? $competition->start_datetime->format('d/m/Y H:i') : '-' }} - {{ $competition->end_datetime ? $competition->end_datetime->format('d/m/Y H:i') : '-' }}</li>
                <li class="list-group-item"><strong>Participants :</strong> {{ $participantsCount }} / {{ $competition->max_participants ?? '-' }}</li>
                <li class="list-group-item"><strong>Statut :</strong> {{ ucfirst($competition->status) }}</li>
            </ul>
        </div>
    </div>
    <div class="mb-4">
        @if($isRegistered)
            <div class="alert alert-success">Vous êtes déjà inscrit à cette compétition.</div>
            <a href="{{ route('competitions.play', $competition->slug) }}" class="btn text-white mt-2" style="background:linear-gradient(135deg,#27B371,#1EA38B);border:none;">
                <i class="fas fa-play"></i> Participer maintenant
            </a>
        @else
            <form method="POST" action="{{ route('competitions.register', $competition->id) }}">
                @csrf
                <button type="submit" class="btn text-white" style="background:linear-gradient(135deg,#1EA38B,#27B371);border:none;">
                    <i class="fas fa-sign-in-alt"></i> Participer à la compétition
                </button>
            </form>
        @endif
    </div>
    <a href="{{ route('pages.compdisp') }}" class="btn btn-link"><i class="fas fa-arrow-left"></i> Retour à la liste</a>
</div>
@endsection 