@extends('emails.layout')

@section('title', 'Nouvelle demande de mentorat — AfriCode')

@section('header_content')
<div class="header-icon">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="white"><path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
</div>
<p class="header-title">Nouvelle demande de mentorat</p>
<p class="header-subtitle">Un apprenant souhaite bénéficier de votre accompagnement</p>
@endsection

@section('content')
<p class="greeting">Bonjour {{ $formateur->first_name }} {{ $formateur->last_name }},</p>
<p class="text">
    Vous avez reçu une nouvelle demande de mentorat sur AfriCode.
    Un apprenant souhaite bénéficier de votre expertise. Vous pouvez l'accepter ou la refuser depuis votre tableau de bord.
</p>

<div class="info-card">
    <div class="info-row">
        <span class="info-label">Apprenant</span>
        <span class="info-value">{{ $mentee->first_name }} {{ $mentee->last_name }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Email</span>
        <span class="info-value">{{ $mentee->email }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Domaine</span>
        <span class="info-value">{{ $mentorship->specialization_area }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Demande du</span>
        <span class="info-value">{{ $mentorship->requested_at->format('d/m/Y à H\hi') }}</span>
    </div>
</div>

@if($mentorship->message)
<div class="highlight">
    <p><strong>Message de l'apprenant :</strong><br>{{ $mentorship->message }}</p>
</div>
@endif

<p class="text">Rendez-vous sur votre tableau de bord pour répondre à cette demande.</p>

<div class="btn-wrap">
    <a href="{{ $requestsUrl }}" class="btn">Voir les demandes en attente</a>
</div>
@endsection
