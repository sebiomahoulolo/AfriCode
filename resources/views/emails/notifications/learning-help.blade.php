@extends('emails.layout')

@section('title', "Besoin d'aide dans votre apprentissage — AfriCode")

@section('header_content')
<div class="header-icon">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="white"><path d="M11 18h2v-2h-2v2zm1-16C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-14c-2.21 0-4 1.79-4 4h2c0-1.1.9-2 2-2s2 .9 2 2c0 2-3 1.75-3 5h2c0-2.25 3-2.5 3-5 0-2.21-1.79-4-4-4z"/></svg>
</div>
<p class="header-title">Besoin d'aide dans votre apprentissage ?</p>
<p class="header-subtitle">Nous sommes là pour vous accompagner</p>
@endsection

@section('content')
<p class="greeting">Bonjour {{ $firstName }},</p>
<p class="text">
    Nous avons détecté que vous rencontrez des difficultés dans votre apprentissage sur AfriCode.
    C'est tout à fait normal — apprendre demande de la persévérance, et nous sommes là pour vous aider à surmonter les obstacles.
</p>

<div class="highlight">
    <p>Ne restez pas bloqué(e) seul(e). Nos ressources d'aide et notre communauté sont là pour vous.</p>
</div>

<div class="btn-wrap">
    <a href="{{ $helpUrl }}" class="btn">Voir les ressources d'aide</a>
</div>

<div class="next-box">
    <p class="next-box-title">Options d'aide disponibles</p>
    <ul>
        <li>Consultez la FAQ pour les questions fréquentes</li>
        <li>Contactez un mentor pour un accompagnement personnalisé</li>
        <li>Posez votre question dans le forum du cours</li>
        <li>Ouvrez un ticket support si le problème persiste</li>
    </ul>
</div>

<hr>

<div class="url-box">
    <p>Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :</p>
    <a href="{{ $helpUrl }}">{{ $helpUrl }}</a>
</div>

<p class="note">Notre équipe est disponible du Lun au Ven 8h–18h · <a href="mailto:contact@africode.tech">contact@africode.tech</a></p>
@endsection
