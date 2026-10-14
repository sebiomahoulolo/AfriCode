@extends('emails.layout')

@section('title', 'Réponse à votre demande de mentorat — AfriCode')

@section('header_content')
<div class="header-icon">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="white"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
</div>
<p class="header-title">Réponse à votre demande<br>de mentorat</p>
<p class="header-subtitle">Concernant votre demande auprès de {{ $formateur->first_name }} {{ $formateur->last_name }}</p>
@endsection

@section('content')
<p class="greeting">Bonjour {{ $mentee->first_name }},</p>
<p class="text">
    Suite à votre demande de mentorat, <strong>{{ $formateur->first_name }} {{ $formateur->last_name }}</strong>
    n'est pas en mesure d'accepter votre demande pour le moment. Cela ne remet pas en question vos compétences —
    les formateurs ont souvent des contraintes de disponibilité.
</p>

<div class="info-card">
    <div class="info-row">
        <span class="info-label">Formateur</span>
        <span class="info-value">{{ $formateur->first_name }} {{ $formateur->last_name }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Domaine</span>
        <span class="info-value">{{ $mentorship->specialization_area }}</span>
    </div>
</div>

<div class="next-box">
    <p class="next-box-title">Que faire maintenant ?</p>
    <ul>
        <li>Consultez la liste des autres formateurs disponibles</li>
        <li>Faites une demande à un autre formateur dans votre domaine</li>
        <li>Continuez vos cours — votre progression est déjà excellente !</li>
        <li>Rejoignez les forums de discussion pour obtenir de l'aide</li>
    </ul>
</div>

<div class="btn-wrap">
    <a href="{{ $mentorsUrl }}" class="btn">Trouver un autre mentor</a>
</div>
@endsection
