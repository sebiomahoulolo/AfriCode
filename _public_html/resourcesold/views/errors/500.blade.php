@extends('errors.layout')
@section('error_title', 'Erreur interne')

@section('error_content')
<div class="err-icon-wrap" style="background:rgba(250,28,57,.12);color:#fa1c39;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"/>
        <line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
    </svg>
</div>
<div class="err-code">500</div>
<div class="err-code-badge">Erreur serveur</div>
<h1 class="err-title">Une erreur s'est produite</h1>
<p class="err-desc">
    Quelque chose s'est mal passé de notre côté. Notre équipe technique a été automatiquement notifiée et travaille à résoudre le problème.
    <br><br>Veuillez réessayer dans quelques instants.
</p>
<div class="err-actions">
    <a href="javascript:location.reload()" class="err-btn err-btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 11-2.12-9.36L23 10"/></svg>
        Réessayer
    </a>
    <a href="{{ url('/') }}" class="err-btn err-btn-ghost">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
        Accueil
    </a>
</div>
@endsection
