@extends('emails.layout')

@section('title', 'Bienvenue sur AfriCode')

@section('header_content')
<div class="header-icon">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="white"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
</div>
<p class="header-title">Bienvenue sur AfriCode !</p>
<p class="header-subtitle">Votre aventure tech commence maintenant</p>
@endsection

@section('content')
<p class="greeting">Bonjour {{ $user->first_name }} {{ $user->last_name }},</p>
<p class="text">
    Félicitations ! Votre compte AfriCode a été créé avec succès. Vous faites maintenant partie de notre communauté tech africaine qui façonne l'avenir numérique du continent.
</p>

<div class="info-card">
    <p class="info-card-title">Vos informations de compte</p>
    <div class="info-row">
        <span class="info-label">Nom complet</span>
        <span class="info-value">{{ $user->first_name }} {{ $user->last_name }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Email</span>
        <span class="info-value">{{ $user->email }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Profil</span>
        <span class="info-value">{{ $roleText }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Inscrit le</span>
        <span class="info-value">{{ $user->created_at->format('d/m/Y') }}</span>
    </div>
</div>

<p class="steps-title">Prochaines étapes</p>
<div class="step"><div class="step-num">1</div><div class="step-text">Accédez à votre tableau de bord pour découvrir nos formations</div></div>
<div class="step"><div class="step-num">2</div><div class="step-text">Parcourez notre catalogue de cours et certifications</div></div>
<div class="step"><div class="step-num">3</div><div class="step-text">Rejoignez notre communauté sur les réseaux sociaux</div></div>
<div class="step"><div class="step-num">4</div><div class="step-text">Participez aux compétitions et challenges</div></div>

<div class="btn-wrap">
    <a href="{{ $dashboardUrl }}" class="btn">Accéder à mon tableau de bord</a>
</div>

<div class="tip-box">
    <p class="tip-box-title">Conseils pour bien démarrer</p>
    <ul>
        <li>Complétez votre profil avec une photo et une bio</li>
        <li>Explorez les formations gratuites pour commencer</li>
        <li>Rejoignez nos forums pour échanger avec la communauté</li>
        <li>Suivez nos actualités sur les réseaux sociaux</li>
    </ul>
</div>

<div style="text-align:center;margin:24px 0 8px;">
    <p style="font-size:14px;color:#4a5568;margin-bottom:14px;font-weight:600;">Suivez-nous sur les réseaux sociaux</p>
    <a href="https://www.facebook.com/profile.php?id=61574681464943" target="_blank"
       style="background:#1877f2;color:#fff;padding:10px 14px;border-radius:50%;text-decoration:none;display:inline-block;margin:0 4px;font-weight:700;">f</a>
    <a href="https://www.instagram.com/africode1?igsh=YzljYTk1ODg3Zg==" target="_blank"
       style="background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);color:#fff;padding:10px 12px;border-radius:50%;text-decoration:none;display:inline-block;margin:0 4px;font-weight:700;">ig</a>
    <a href="https://www.linkedin.com/company/africode-no-code-no-future/" target="_blank"
       style="background:#0077b5;color:#fff;padding:10px 12px;border-radius:50%;text-decoration:none;display:inline-block;margin:0 4px;font-weight:700;">in</a>
    <a href="https://youtube.com/@africodetechsm?si=k8Y49FJR3tib1KNc" target="_blank"
       style="background:#ff0000;color:#fff;padding:10px 11px;border-radius:50%;text-decoration:none;display:inline-block;margin:0 4px;font-weight:700;">▶</a>
</div>

<hr>
<div class="url-box">
    <p>Besoin d'aide ? Notre équipe est disponible du Lun au Ven 8h–18h</p>
    <a href="mailto:contact@africode.tech">contact@africode.tech</a>
</div>
@endsection
