@extends('emails.layout')

@section('title', 'Réinitialisation de mot de passe — AfriCode')

@section('header_content')
<div class="header-icon">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="white"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
</div>
<p class="header-title">Réinitialisation de<br>votre mot de passe</p>
<p class="header-subtitle">Sécurité de votre compte AfriCode</p>
@endsection

@section('styles')
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.5} }
.badge-dot { animation:pulse 2s infinite; }
@endsection

@section('content')
<div class="badge-status">
    <span class="badge-dot"></span>
    Sécurité du compte
</div>

<p class="greeting">Bonjour {{ $firstName }},</p>
<p class="text">
    Vous recevez cet email car nous avons reçu une demande de réinitialisation du mot de passe de votre compte <strong>AfriCode</strong>.
    Cliquez sur le bouton ci-dessous pour choisir un nouveau mot de passe sécurisé.
</p>

<div class="btn-wrap">
    <a href="{{ $url }}" class="btn" target="_blank">Réinitialiser mon mot de passe</a>
</div>

<div class="warn-box">
    <span>⏱️</span>
    <p>Ce lien expire dans <strong>60 minutes</strong>. Après ce délai, vous devrez faire une nouvelle demande de réinitialisation.</p>
</div>

<div class="url-box">
    <p>Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :</p>
    <a href="{{ $url }}" target="_blank">{{ $url }}</a>
</div>

<hr>
<p class="note">
    Si vous n'avez pas demandé de réinitialisation, ignorez cet email — votre compte reste sécurisé.<br>
    Questions : <a href="mailto:support@africode.tech">support@africode.tech</a>
</p>
@endsection
