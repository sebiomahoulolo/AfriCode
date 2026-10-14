@extends('emails.layout')

@section('title', 'Confirmez votre adresse email — AfriCode')

@section('header_content')
<div class="header-icon">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="white"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
</div>
<p class="header-title">Bienvenue sur AfriCode !</p>
<p class="header-subtitle">Confirmez votre adresse email</p>
@endsection

@section('content')
<p class="greeting">Bonjour {{ $firstName }},</p>
<p class="text">
    Merci de vous être inscrit·e sur <strong>AfriCode</strong> ! Pour activer votre compte et accéder à votre espace
    d'apprentissage, il ne vous reste qu'une étape : confirmer votre adresse email.
</p>

<div class="btn-wrap">
    <a href="{{ $url }}" class="btn" target="_blank">Vérifier mon adresse email</a>
</div>

<div class="highlight">
    <p>Si vous n'avez pas créé de compte AfriCode, vous pouvez ignorer cet email en toute sécurité.</p>
</div>

<hr>

<div class="url-box">
    <p>Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :</p>
    <a href="{{ $url }}" target="_blank">{{ $url }}</a>
</div>

<p class="note">Une question ? <a href="mailto:contact@africode.tech">contact@africode.tech</a></p>
@endsection
