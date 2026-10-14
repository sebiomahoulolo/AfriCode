@extends('emails.layout')

@section('title', 'Mentorat approuvé — AfriCode')

@section('header_content')
<div class="header-icon">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
</div>
<p class="header-title">Votre mentorat est approuvé !</p>
<p class="header-subtitle">{{ $formateur->first_name }} {{ $formateur->last_name }} accepte de vous accompagner</p>
@endsection

@section('content')
<p class="greeting">Félicitations {{ $mentee->first_name }} !</p>
<p class="text">
    Bonne nouvelle ! <strong>{{ $formateur->first_name }} {{ $formateur->last_name }}</strong> a accepté votre demande de mentorat.
    Vous pouvez maintenant commencer votre parcours d'apprentissage ensemble.
</p>

<div class="info-card">
    <div class="info-row">
        <span class="info-label">Votre mentor</span>
        <span class="info-value">{{ $formateur->first_name }} {{ $formateur->last_name }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Email</span>
        <span class="info-value">{{ $formateur->email }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Domaine</span>
        <span class="info-value">{{ $mentorship->specialization_area }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Approuvé le</span>
        <span class="info-value">{{ $mentorship->approved_at->format('d/m/Y à H\hi') }}</span>
    </div>
</div>

<div class="tip-box">
    <p class="tip-box-title">Conseils pour bien démarrer</p>
    <ul>
        <li>Présentez-vous et définissez vos objectifs d'apprentissage</li>
        <li>Préparez vos questions et vos points bloquants</li>
        <li>Soyez régulier et respectez les engagements pris</li>
        <li>N'hésitez pas à partager vos avancées et vos difficultés</li>
    </ul>
</div>

<div class="btn-wrap">
    <a href="{{ $mentorshipUrl }}" class="btn">Accéder à mon mentorat</a>
</div>
@endsection
