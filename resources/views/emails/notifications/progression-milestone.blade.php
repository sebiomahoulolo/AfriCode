@extends('emails.layout')

@section('title', 'Nouveau palier de progression — AfriCode')

@section('header_content')
<div class="header-icon">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="white"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 3c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm7 13H5v-.23c0-.62.28-1.2.76-1.58C7.47 15.82 9.64 15 12 15s4.53.82 6.24 2.19c.48.38.76.97.76 1.58V19z"/></svg>
</div>
<p class="header-title">Bravo pour votre progression !</p>
<p class="header-subtitle">Vous avez atteint un nouveau palier</p>
@endsection

@section('content')
<p class="greeting">Félicitations {{ $firstName }} !</p>
<p class="text">
    Vous avez atteint un nouveau palier de progression sur AfriCode. Votre régularité et votre investissement portent leurs fruits — continuez sur cette lancée !
</p>

<div class="highlight">
    <p>Chaque palier franchi vous rapproche de vos objectifs et renforce votre profil auprès des recruteurs et de la communauté.</p>
</div>

<div class="btn-wrap">
    <a href="{{ $dashboardUrl }}" class="btn">Voir ma progression</a>
</div>

<div class="next-box">
    <p class="next-box-title">La prochaine étape vous attend</p>
    <ul>
        <li>Continuez vos cours pour atteindre le palier suivant</li>
        <li>Partagez votre progression avec votre réseau</li>
        <li>Explorez les nouvelles formations disponibles</li>
    </ul>
</div>

<hr>
<p class="note">Nous sommes fiers de votre progression ! <a href="mailto:contact@africode.tech">contact@africode.tech</a></p>
@endsection
