@extends('emails.layout')

@section('title', 'Compte formateur validé — AfriCode')

@section('header_content')
<div class="header-icon">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="white"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
</div>
<p class="header-title">Compte formateur validé !</p>
<p class="header-subtitle">Bienvenue dans la communauté des formateurs AfriCode</p>
@endsection

@section('content')
<p class="greeting">Bonjour {{ $formateur->first_name }},</p>
<p class="text">
    Nous avons le plaisir de vous confirmer que <strong>votre compte formateur AfriCode a été validé</strong> par notre équipe.
</p>
<p class="text">
    Vous pouvez désormais créer et publier des formations, gérer vos cours et accompagner les apprenants sur la plateforme.
</p>

<div class="next-box">
    <p class="next-box-title">Vos prochaines étapes</p>
    <ul>
        <li>Accédez à votre tableau de bord formateur</li>
        <li>Créez votre premier cours et ajoutez vos contenus</li>
        <li>Soumettez votre cours pour validation par l'équipe</li>
        <li>Accompagnez vos apprenants et suivez leurs progressions</li>
    </ul>
</div>

<div class="btn-wrap">
    <a href="{{ $dashboardUrl }}" class="btn">Accéder à mon tableau de bord</a>
</div>
@endsection
