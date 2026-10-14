@extends('emails.layout')

@section('title', 'Accusé de réception — Candidature AfriCode')

@section('header_content')
<div class="header-icon">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="white"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
</div>
<p class="header-title">Candidature bien reçue !</p>
<p class="header-subtitle">Accusé de réception de votre candidature</p>
@endsection

@section('content')
<p class="greeting">Bonjour <strong>{{ $application->nom_prenoms }}</strong>,</p>
<p class="text">
    Nous accusons réception de votre candidature pour le poste de <strong>{{ $application->jobPosition->title }}</strong>.
</p>

<div class="highlight">
    <p><strong>Récapitulatif de votre candidature</strong></p>
    <p>Poste : {{ $application->jobPosition->title }}</p>
    <p>Email : {{ $application->email }}</p>
    <p>Téléphone : {{ $application->telephone ?? '—' }}</p>
    @if($application->sexe)
    <p>Sexe : {{ $application->sexe === 'feminin' ? 'Féminin' : 'Masculin' }}</p>
    @endif
    @if($application->niveau_etudes)
    <p>Niveau d'études : {{ $application->niveau_etudes }}</p>
    @endif
    @if($application->test_score !== null)
    <p>Score QCM : <span class="badge-success">{{ $application->test_score }}/10</span> ({{ ucfirst($application->getScoreLevel()) }})</p>
    @endif
</div>

<p class="steps-title">Prochaines étapes</p>
<div class="step">
    <div class="step-num">1</div>
    <div class="step-text">Les candidats présélectionnés après le test QCM seront contactés pour la suite du processus.</div>
</div>
<div class="step">
    <div class="step-num">2</div>
    <div class="step-text">
        Seuls les candidats retenus seront contactés.
        @if($application->jobPosition->deadline)
            Date limite de dépôt : {{ \Carbon\Carbon::parse($application->jobPosition->deadline)->locale('fr')->isoFormat('D MMMM YYYY') }}.
        @endif
    </div>
</div>

<div class="info-card" style="margin-top:24px;text-align:center;">
    <p class="info-card-title">Renforcez vos compétences sur AfriCode</p>
    <p class="text" style="margin-bottom:16px;">
        Développement web, cybersécurité, intelligence artificielle, data, et bien plus.
    </p>
    <a href="{{ url()->to(route('courses.index')) }}" class="btn">Voir les cours</a>
</div>

<hr>
<p class="note">Nous vous remercions pour l'intérêt que vous portez à AfriCode et vous souhaitons bonne chance.</p>
@endsection
