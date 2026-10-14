@extends('emails.layout')

@section('title', 'Nouveau badge débloqué — AfriCode')

@section('header_content')
<div class="header-icon">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="white"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
</div>
<p class="header-title">Nouveau badge débloqué !</p>
<p class="header-subtitle">Félicitations pour votre accomplissement</p>
@endsection

@section('content')
<p class="greeting">Félicitations {{ $firstName }} !</p>
<p class="text">
    Vous venez de débloquer un nouveau badge sur AfriCode. Chaque badge témoigne de votre progression et de votre engagement dans votre parcours numérique.
</p>

<div class="highlight">
    <p>Vos badges sont visibles sur votre profil et attestent de vos compétences auprès de la communauté AfriCode.</p>
</div>

<div class="btn-wrap">
    <a href="{{ $badgesUrl }}" class="btn">Voir mes badges</a>
</div>

<div class="next-box">
    <p class="next-box-title">Continuez sur cette lancée !</p>
    <ul>
        <li>Terminez vos cours en cours pour débloquer d'autres badges</li>
        <li>Participez aux compétitions pour des badges exclusifs</li>
        <li>Partagez vos accomplissements avec la communauté</li>
    </ul>
</div>

<hr>
<p class="note">Bravo pour votre travail ! <a href="mailto:contact@africode.tech">contact@africode.tech</a></p>
@endsection
