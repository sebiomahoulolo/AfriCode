@extends('emails.layout')

@section('title', 'Bienvenue à la newsletter AfriCode')

@section('header_content')
<div class="header-icon">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="white"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
</div>
<p class="header-title">Bienvenue à la newsletter AfriCode !</p>
<p class="header-subtitle">Vous êtes maintenant abonné(e)</p>
@endsection

@section('content')
<p class="greeting">Bonjour,</p>
<p class="text">
    Merci de vous être inscrit(e) à notre newsletter ! Vous recevrez désormais nos dernières actualités, conseils de programmation et offres exclusives directement dans votre boîte mail.
</p>

<div class="next-box">
    <p class="next-box-title">Ce que vous recevrez</p>
    <ul>
        <li>Nouveaux cours et formations</li>
        <li>Compétitions et challenges</li>
        <li>Conseils et astuces de programmation</li>
        <li>Actualités du monde tech africain</li>
        <li>Offres exclusives pour nos abonnés</li>
    </ul>
</div>

<div class="btn-wrap">
    <a href="{{ $coursesUrl }}" class="btn">Découvrir nos formations</a>
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
@endsection

@section('footer_extra')
<p style="font-size:12px;color:#9ca3af;text-align:center;margin-bottom:8px;">
    Vous ne souhaitez plus recevoir nos emails ?
    <a href="{{ $unsubscribeUrl }}" style="color:#1EA38B;">Se désabonner</a>
</p>
@endsection
