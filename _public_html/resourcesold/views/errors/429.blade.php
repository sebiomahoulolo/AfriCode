@extends('errors.layout')
@section('error_title', 'Trop de requêtes')

@section('error_content')
<div class="err-icon-wrap" style="background:rgba(254,168,55,.12);color:#fea837;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
    </svg>
</div>
<div class="err-code">429</div>
<div class="err-code-badge">Trop de requêtes</div>
<h1 class="err-title">Vous allez trop vite !</h1>
<p class="err-desc">
    Vous avez effectué trop de requêtes en peu de temps. Pour protéger la plateforme et garantir un service stable à tous les utilisateurs, un délai est nécessaire.
    <br><br>Veuillez patienter quelques instants avant de réessayer.
</p>
<div class="err-actions">
    <a href="javascript:history.back()" class="err-btn err-btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
        Retour
    </a>
    <a href="{{ url('/') }}" class="err-btn err-btn-ghost">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
        Accueil
    </a>
</div>
@endsection
