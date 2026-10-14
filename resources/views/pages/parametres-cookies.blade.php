@extends('layouts.layout')

@section('title', 'Paramètres des cookies — AfriCode')

@section('content')
<div class="ck-page">

    <div class="ck-hero">
        <div class="container">
            <div class="ck-hero-inner">
                <div class="ck-hero-icon"><i class="fas fa-cookie-bite"></i></div>
                <div>
                    <h1 class="ck-hero-title">Paramètres des cookies</h1>
                    <p class="ck-hero-sub">Gérez vos préférences de confidentialité sur AfriCode</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container ck-container">
        <div class="row justify-content-center">
            <div class="col-lg-7">

                @if(session('success'))
                    <div class="ck-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="ck-intro">
                    <div class="ck-intro-icon"><i class="fas fa-info-circle"></i></div>
                    <div>
                        <strong>À propos des cookies</strong>
                        <p>Les cookies sont de petits fichiers stockés sur votre appareil qui nous permettent d'améliorer votre expérience. Vous pouvez choisir lesquels activer ci-dessous.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('parametres.cookies.save') }}">
                    @csrf

                    {{-- Nécessaires (toujours actifs) --}}
                    <div class="ck-card ck-card-locked">
                        <div class="ck-card-header">
                            <div class="ck-card-icon" style="background:rgba(30,163,139,.12);color:#1EA38B;">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="ck-card-info">
                                <strong>Cookies essentiels</strong>
                                <p>Nécessaires au fonctionnement du site — authentification, sécurité, préférences de session.</p>
                            </div>
                            <div class="ck-toggle-wrap">
                                <span class="ck-badge-active">Toujours actifs</span>
                            </div>
                        </div>
                        <input type="hidden" name="cookies_essentiels" value="1">
                    </div>

                    {{-- Analytiques --}}
                    <div class="ck-card">
                        <div class="ck-card-header">
                            <div class="ck-card-icon" style="background:rgba(59,130,246,.1);color:#3b82f6;">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="ck-card-info">
                                <strong>Cookies analytiques</strong>
                                <p>Nous aident à comprendre comment les visiteurs utilisent le site (pages vues, durée, parcours). Données anonymisées.</p>
                            </div>
                            <div class="ck-toggle-wrap">
                                <label class="ck-switch">
                                    <input type="checkbox" name="cookies_analytiques"
                                           {{ old('cookies_analytiques', Cookie::get('cookies_analytiques')) ? 'checked' : '' }}>
                                    <span class="ck-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Marketing --}}
                    <div class="ck-card">
                        <div class="ck-card-header">
                            <div class="ck-card-icon" style="background:rgba(245,158,11,.1);color:#f59e0b;">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div class="ck-card-info">
                                <strong>Cookies marketing</strong>
                                <p>Permettent de personnaliser les recommandations de cours et les communications adaptées à votre profil.</p>
                            </div>
                            <div class="ck-toggle-wrap">
                                <label class="ck-switch">
                                    <input type="checkbox" name="cookies_marketing"
                                           {{ old('cookies_marketing', Cookie::get('cookies_marketing')) ? 'checked' : '' }}>
                                    <span class="ck-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="ck-actions">
                        <button type="submit" class="ck-btn-save">
                            <i class="fas fa-save me-2"></i>Enregistrer mes préférences
                        </button>
                        <a href="{{ route('pages.privacy') }}" class="ck-btn-link">
                            Politique de confidentialité
                        </a>
                    </div>
                </form>

                <p class="ck-note">Vous pouvez modifier vos préférences à tout moment depuis cette page.</p>

            </div>
        </div>
    </div>
</div>

<style>
.ck-page { background:#f0f2f5; min-height:100vh; padding-bottom:4rem; }

/* Hero */
.ck-hero { background:linear-gradient(135deg,#1EA38B,#27B371); color:white; padding:3rem 0 2.5rem; }
.ck-hero-inner { display:flex; align-items:center; gap:1.5rem; }
.ck-hero-icon { width:64px; height:64px; background:rgba(255,255,255,.15); border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:1.8rem; flex-shrink:0; }
.ck-hero-title { font-size:2rem; font-weight:800; margin:0 0 .35rem; color:white; }
.ck-hero-sub { font-size:.9rem; color:rgba(255,255,255,.8); margin:0; }

.ck-container { padding-top:2.5rem; }

/* Success */
.ck-success { display:flex; align-items:center; gap:.65rem; background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:10px; padding:.85rem 1.25rem; font-size:.88rem; color:#16a34a; margin-bottom:1.5rem; font-weight:500; }
.ck-success i { color:#22c55e; font-size:1.1rem; flex-shrink:0; }

/* Intro */
.ck-intro { display:flex; gap:1rem; background:#fff; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,.06); padding:1.25rem 1.5rem; margin-bottom:1.25rem; }
.ck-intro-icon { font-size:1.4rem; color:#1EA38B; flex-shrink:0; margin-top:.1rem; }
.ck-intro strong { display:block; font-size:.9rem; color:#1a1a2e; margin-bottom:.25rem; }
.ck-intro p { font-size:.85rem; color:#6b7280; margin:0; line-height:1.5; }

/* Cards */
.ck-card { background:#fff; border-radius:14px; box-shadow:0 2px 12px rgba(0,0,0,.06); margin-bottom:1rem; overflow:hidden; border:1.5px solid transparent; transition:border-color .2s; }
.ck-card:hover { border-color:#e5e7eb; }
.ck-card-locked { border-color:#e5e7eb; }
.ck-card-header { display:flex; align-items:center; gap:1rem; padding:1.25rem 1.5rem; }
.ck-card-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
.ck-card-info { flex:1; }
.ck-card-info strong { display:block; font-size:.9rem; color:#1a1a2e; margin-bottom:.25rem; }
.ck-card-info p { font-size:.82rem; color:#6b7280; margin:0; line-height:1.4; }
.ck-toggle-wrap { flex-shrink:0; }

/* Badge always-on */
.ck-badge-active { display:inline-block; background:#dcfce7; color:#16a34a; font-size:.75rem; font-weight:700; padding:4px 12px; border-radius:20px; white-space:nowrap; }

/* Toggle switch */
.ck-switch { position:relative; display:inline-block; width:44px; height:24px; }
.ck-switch input { opacity:0; width:0; height:0; }
.ck-slider { position:absolute; inset:0; background:#d1d5db; border-radius:24px; cursor:pointer; transition:.3s; }
.ck-slider::before { content:''; position:absolute; height:18px; width:18px; left:3px; bottom:3px; background:white; border-radius:50%; transition:.3s; }
.ck-switch input:checked + .ck-slider { background:#1EA38B; }
.ck-switch input:checked + .ck-slider::before { transform:translateX(20px); }

/* Actions */
.ck-actions { display:flex; align-items:center; gap:1rem; margin-top:1.5rem; flex-wrap:wrap; }
.ck-btn-save { background:linear-gradient(135deg,#1EA38B,#27B371); color:#fff; border:none; padding:.85rem 1.75rem; border-radius:12px; font-size:.95rem; font-weight:700; cursor:pointer; box-shadow:0 4px 14px rgba(30,163,139,.3); transition:all .25s ease; }
.ck-btn-save:hover { transform:translateY(-2px); box-shadow:0 8px 22px rgba(30,163,139,.45); }
.ck-btn-link { color:#1EA38B; font-size:.85rem; font-weight:600; text-decoration:none; }
.ck-btn-link:hover { text-decoration:underline; }

.ck-note { font-size:.78rem; color:#9ca3af; text-align:center; margin-top:1.5rem; }

@media (max-width:576px) { .ck-hero-title { font-size:1.5rem; } .ck-card-header { flex-wrap:wrap; } }
</style>
@endsection
