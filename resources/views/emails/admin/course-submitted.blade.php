@extends('emails.layout')

@section('title', 'Cours soumis pour validation — AfriCode Admin')

@section('header_content')
<div class="header-icon">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="white"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
</div>
<p class="header-title">Cours soumis pour validation</p>
<p class="header-subtitle">Un formateur attend votre examen</p>
@endsection

@section('content')
<p class="text">Un formateur a soumis un <strong>cours pour validation</strong> sur la plateforme AfriCode.</p>

<div class="highlight">
    <p><strong>Cours :</strong> {{ $course->title }}</p>
    <p><strong>Formateur :</strong> {{ $formateurName }}</p>
    @if($formateur && $formateur->email)
    <p><strong>Email :</strong> {{ $formateur->email }}</p>
    @endif
    @if($course->submitted_for_validation_at)
    <p><strong>Soumis le :</strong> {{ $course->submitted_for_validation_at->format('d/m/Y à H:i') }}</p>
    @endif
</div>

<p class="text">
    Merci d'examiner ce cours et de le valider, demander des révisions ou le rejeter depuis l'interface d'administration.
</p>

<div class="btn-wrap">
    <a href="{{ $courseUrl }}" class="btn">Voir le cours en attente</a>
</div>
@endsection
