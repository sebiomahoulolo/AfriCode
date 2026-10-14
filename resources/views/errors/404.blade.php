@extends('errors.layout')
@section('error_title', 'Page introuvable')

@section('error_content')
<div class="err-icon-wrap" style="background:rgba(30,163,139,.12);color:#1EA38B;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/>
    </svg>
</div>
<div class="err-code">404</div>
<div class="err-code-badge">Page introuvable</div>
<h1 class="err-title">Cette page n'existe pas</h1>
<p class="err-desc">
    La page que vous cherchez a peut-être été déplacée, renommée ou supprimée. Vérifiez l'URL ou revenez à l'accueil pour continuer votre parcours.
</p>
<div class="err-actions">
    <a href="{{ url('/') }}" class="err-btn err-btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
        Retour à l'accueil
    </a>
    <a href="{{ url('/courses') }}" class="err-btn err-btn-ghost">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/></svg>
        Voir les cours
    </a>
</div>
@endsection
