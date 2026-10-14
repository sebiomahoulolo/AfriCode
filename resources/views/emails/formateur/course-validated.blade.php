@extends('emails.layout')

@section('title', 'Cours validé et publié — AfriCode')

@section('header_content')
<div class="header-icon">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
</div>
<p class="header-title">Votre cours est publié !</p>
<p class="header-subtitle">Félicitations pour la qualité de votre travail</p>
@endsection

@section('content')
<p class="greeting">Bonjour {{ $formateur->first_name }},</p>
<p class="text">
    Nous avons le plaisir de vous informer que votre cours <strong>« {{ $course->title }} »</strong>
    a été validé avec succès et est désormais <strong>publié sur AfriCode</strong>.
</p>
<p class="text">
    Votre expertise contribue à enrichir notre communauté d'apprenants.
    Votre cours est maintenant accessible aux étudiants et prêt à recevoir des inscriptions.
</p>

<div class="btn-wrap">
    <a href="{{ $courseManageUrl }}" class="btn">Voir et gérer le cours</a>
    <a href="{{ $dashboardUrl }}" class="btn-secondary">Tableau de bord</a>
</div>

<hr>
<p class="note">Merci pour votre confiance et votre engagement envers la communauté AfriCode.</p>
@endsection
