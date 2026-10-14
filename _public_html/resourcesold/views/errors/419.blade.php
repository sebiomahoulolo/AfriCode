@extends('errors.layout')
@section('error_title', 'Session expirée')

@section('error_content')
<div class="err-icon-wrap" style="background:rgba(254,168,55,.12);color:#fea837;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
    </svg>
</div>
<div class="err-code">419</div>
<div class="err-code-badge">Session expirée</div>
<h1 class="err-title">La page a expiré</h1>
<p class="err-desc">
    Votre session a expiré pour des raisons de sécurité. Cela se produit après une longue période d'inactivité ou lorsque vous utilisez le bouton <strong>Précédent</strong> du navigateur après une action.
    <br><br>Retournez sur la page précédente et réessayez.
</p>
<div class="err-actions">
    <a href="javascript:history.back()" class="err-btn err-btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
        Retour à la page précédente
    </a>
    <a href="{{ url('/') }}" class="err-btn err-btn-ghost">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
        Accueil
    </a>
</div>
@endsection
