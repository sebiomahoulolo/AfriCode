@extends('errors.layout')
@section('error_title', 'Accès refusé')

@section('error_content')
<div class="err-icon-wrap" style="background:rgba(254,168,55,.12);color:#fea837;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
    </svg>
</div>
<div class="err-code">403</div>
<div class="err-code-badge">Accès refusé</div>
<h1 class="err-title">Vous n'avez pas l'autorisation</h1>
<p class="err-desc">
    Vous n'êtes pas autorisé à accéder à cette ressource. Si vous pensez qu'il s'agit d'une erreur, contactez l'administrateur ou reconnectez-vous avec un compte approprié.
</p>
<div class="err-actions">
    <a href="{{ url('/') }}" class="err-btn err-btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
        Retour à l'accueil
    </a>
    <a href="{{ route('login') }}" class="err-btn err-btn-ghost">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
        Se connecter
    </a>
</div>
@endsection
