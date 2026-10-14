@extends('emails.layout')

@section('title', 'Reprenez votre formation — AfriCode')

@section('header_content')
<div class="header-icon">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="white"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/></svg>
</div>
<p class="header-title">On ne vous voit plus sur AfriCode !</p>
<p class="header-subtitle">Votre parcours vous attend</p>
@endsection

@section('content')
<p class="greeting">Bonjour {{ $firstName }},</p>
<p class="text">
    Nous avons remarqué que vous n'avez pas progressé depuis quelques jours.
    Ne laissez pas votre élan s'arrêter — chaque leçon compte dans votre parcours vers la maîtrise du numérique.
</p>

<div class="highlight">
    <p>Votre formation vous attend exactement là où vous l'avez laissée. Reprenez dès maintenant et continuez à avancer vers vos objectifs.</p>
</div>

<div class="btn-wrap">
    <a href="{{ $dashboardUrl }}" class="btn">Reprendre ma formation</a>
</div>

<div class="next-box">
    <p class="next-box-title">Pourquoi continuer maintenant ?</p>
    <ul>
        <li>Restez dans la dynamique d'apprentissage</li>
        <li>Débloquez des badges et certifications</li>
        <li>Rejoignez des apprenants qui progressent chaque jour</li>
    </ul>
</div>

<hr>

<div class="url-box">
    <p>Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :</p>
    <a href="{{ $dashboardUrl }}">{{ $dashboardUrl }}</a>
</div>

<p class="note">Votre réussite nous tient à cœur ! <a href="mailto:contact@africode.tech">contact@africode.tech</a></p>
@endsection
