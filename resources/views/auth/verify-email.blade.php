@extends('layouts.layout')

@section('title', 'Vérification de l\'email — AfriCode')
@section('meta_robots', 'noindex, nofollow')

@section('content')
<div class="ve-page">
<div class="ve-card">

    {{-- Header --}}
    <div class="ve-header">
        <div class="ve-logo-bars">
            <span class="ve-bar ve-bar-1"></span>
            <span class="ve-bar ve-bar-2"></span>
            <span class="ve-bar ve-bar-3"></span>
        </div>
        <span class="ve-logo-name">AFRICODE</span>
        <div class="ve-icon">
            <i class="fas fa-envelope-open-text"></i>
        </div>
        <h1 class="ve-title">Vérifiez votre adresse email</h1>
        <p class="ve-subtitle">Une étape rapide pour sécuriser votre compte</p>
    </div>

    {{-- Body --}}
    <div class="ve-body">

        <p class="ve-text">
            Merci de vous être inscrit sur <strong>AfriCode</strong> ! Avant de commencer votre parcours,
            veuillez cliquer sur le lien de vérification que nous venons de vous envoyer.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="ve-success">
                <i class="fas fa-check-circle"></i>
                Un nouveau lien de vérification a été envoyé à votre adresse email.
            </div>
        @endif

        <div class="ve-info-box">
            <p class="ve-info-title"><i class="fas fa-star me-2" style="color:#FF8E2A;"></i>Après la vérification, vous pourrez :</p>
            <ul class="ve-list">
                <li><i class="fas fa-check-circle"></i> Accéder à votre tableau de bord personnalisé</li>
                <li><i class="fas fa-check-circle"></i> Parcourir et suivre nos formations</li>
                <li><i class="fas fa-check-circle"></i> Obtenir des certifications reconnues</li>
                <li><i class="fas fa-check-circle"></i> Rejoindre la communauté tech africaine</li>
            </ul>
        </div>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="ve-btn">
                <i class="fas fa-paper-plane me-2"></i>Renvoyer l'email de vérification
            </button>
        </form>

        <div class="ve-hints">
            <div class="ve-hint"><i class="fas fa-folder-open"></i> Vérifiez vos <strong>Spams</strong></div>
            <div class="ve-hint"><i class="fas fa-clock"></i> Le lien est valable <strong>60 min</strong></div>
        </div>

        <div class="ve-divider"></div>

        <form method="POST" action="{{ route('logout') }}" style="text-align:center;">
            @csrf
            <button type="submit" class="ve-logout">
                <i class="fas fa-sign-out-alt me-1"></i>Se déconnecter
            </button>
        </form>
    </div>

</div>
</div>

<style>
.ve-page { min-height:80vh; display:flex; align-items:center; justify-content:center; padding:40px 16px; background:#f0f2f5; }
.ve-card { width:100%; max-width:480px; background:#fff; border-radius:20px; box-shadow:0 8px 40px rgba(0,0,0,.1); overflow:hidden; }

/* Header */
.ve-header { background:linear-gradient(135deg,#1EA38B,#27B371); padding:28px 32px 24px; text-align:center; }
.ve-logo-bars { display:inline-flex; align-items:flex-end; gap:5px; height:30px; margin-bottom:6px; }
.ve-bar { display:inline-block; border-radius:3px; }
.ve-bar-1 { width:7px; height:15px; background:#FF8E2A; }
.ve-bar-2 { width:7px; height:22px; background:#E32D31; }
.ve-bar-3 { width:7px; height:30px; background:#4ade80; }
.ve-logo-name { font-size:15px; font-weight:900; letter-spacing:.18em; color:#fff; display:block; margin-bottom:16px; }
.ve-icon { width:56px; height:56px; border-radius:50%; background:rgba(255,255,255,.2); display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#fff; margin:0 auto 12px; }
.ve-title { font-size:1.15rem; font-weight:800; color:#fff; margin:0 0 4px; }
.ve-subtitle { font-size:.78rem; color:rgba(255,255,255,.8); margin:0; }

/* Body */
.ve-body { padding:28px 32px; }
.ve-text { font-size:.9rem; color:#4a5568; line-height:1.7; margin-bottom:20px; text-align:center; }

/* Success */
.ve-success { display:flex; align-items:center; gap:.65rem; background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:10px; padding:.85rem 1.25rem; font-size:.87rem; color:#16a34a; margin-bottom:16px; font-weight:500; }
.ve-success i { color:#22c55e; font-size:1.1rem; flex-shrink:0; }

/* Info box */
.ve-info-box { background:#f0fdf9; border:1px solid #99f6e4; border-radius:10px; padding:16px 20px; margin-bottom:20px; }
.ve-info-title { font-size:.88rem; font-weight:600; color:#1a202c; margin:0 0 12px; }
.ve-list { list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:.4rem; }
.ve-list li { display:flex; align-items:center; gap:.6rem; font-size:.85rem; color:#374151; }
.ve-list li i { color:#1EA38B; font-size:.8rem; flex-shrink:0; }

/* Button */
.ve-btn { width:100%; background:linear-gradient(135deg,#1EA38B,#27B371); color:#fff; border:none; padding:.85rem 1.5rem; border-radius:12px; font-size:1rem; font-weight:700; cursor:pointer; box-shadow:0 6px 20px rgba(30,163,139,.35); transition:all .25s ease; margin-bottom:16px; }
.ve-btn:hover { transform:translateY(-2px); box-shadow:0 10px 28px rgba(30,163,139,.5); }

/* Hints */
.ve-hints { display:flex; gap:.75rem; justify-content:center; flex-wrap:wrap; margin-bottom:4px; }
.ve-hint { display:flex; align-items:center; gap:.45rem; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; padding:.5rem .9rem; font-size:.8rem; color:#6b7280; }
.ve-hint i { color:#1EA38B; font-size:.85rem; }

/* Divider */
.ve-divider { height:1px; background:#f3f4f6; margin:20px 0 16px; }

/* Logout */
.ve-logout { background:none; border:none; color:#9ca3af; font-size:.85rem; cursor:pointer; padding:.4rem .8rem; border-radius:8px; transition:color .2s; }
.ve-logout:hover { color:#1EA38B; }

@media (max-width:480px) { .ve-body { padding:24px 20px; } .ve-header { padding:24px 20px; } }
</style>
@endsection
