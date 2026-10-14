@extends('errors.layout')
@section('error_title', 'Maintenance en cours')

@section('error_content')
<div class="err-icon-wrap" style="background:rgba(30,163,139,.12);color:#1EA38B;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/>
    </svg>
</div>
<div class="err-code">503</div>
<div class="err-code-badge">Maintenance</div>
<h1 class="err-title">AfriCode est en maintenance</h1>
<p class="err-desc">
    Nous effectuons des améliorations sur la plateforme pour vous offrir une meilleure expérience. Le service sera de retour très prochainement.
    <br><br>Merci pour votre patience.
</p>
<div class="err-actions">
    <a href="javascript:location.reload()" class="err-btn err-btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 11-2.12-9.36L23 10"/></svg>
        Vérifier de nouveau
    </a>
</div>
@endsection
