@extends('layouts.layout')

@section('title', 'Connexion — AfriCode')
@section('meta_description', 'Connectez-vous à votre espace AfriCode pour accéder à vos formations, certifications et tableau de bord apprenant.')
@section('meta_robots', 'noindex, nofollow')

@section('content')
<div class="login-page">
    <div class="login-container">

        {{-- ═══════════════════════════════════════
             PANNEAU GAUCHE  — branding (desktop)
        ═══════════════════════════════════════ --}}
        <div class="login-brand-panel" aria-hidden="true">
            <div class="brand-panel-inner">
                <div class="brand-logo mb-3">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('assets/images/logo.png') }}"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                             alt="AfriCode" style="height:42px;">
                        <div class="logo-fallback" style="display:none;">
                            <span class="logo-text">Afri<span>Code</span></span>
                        </div>
                    </a>
                </div>

                {{-- ── Illustration ── --}}
                <div class="brand-illustration">
                    <svg viewBox="0 0 300 195" xmlns="http://www.w3.org/2000/svg" fill="none" class="brand-illus-svg" aria-hidden="true">
                      <!-- Laptop screen -->
                      <rect x="72" y="28" width="158" height="110" rx="10" fill="rgba(6,24,50,0.7)" stroke="rgba(255,255,255,0.28)" stroke-width="1.5"/>
                      <rect x="80" y="36" width="142" height="94" rx="5" fill="rgba(255,255,255,0.04)"/>
                      <!-- Title bar -->
                      <rect x="80" y="36" width="142" height="13" rx="5" fill="rgba(255,255,255,0.09)"/>
                      <circle cx="89" cy="42.5" r="2.5" fill="rgba(255,80,80,0.65)"/>
                      <circle cx="96.5" cy="42.5" r="2.5" fill="rgba(255,200,60,0.65)"/>
                      <circle cx="104" cy="42.5" r="2.5" fill="rgba(60,210,100,0.65)"/>
                      <rect x="112" y="39.5" width="72" height="6" rx="3" fill="rgba(255,255,255,0.07)"/>
                      <!-- Code editor gutter -->
                      <rect x="80" y="49" width="14" height="81" fill="rgba(255,255,255,0.035)"/>
                      <text x="83" y="58" font-size="5" fill="rgba(255,255,255,0.22)" font-family="monospace">1</text>
                      <text x="83" y="67" font-size="5" fill="rgba(255,255,255,0.22)" font-family="monospace">2</text>
                      <text x="83" y="76" font-size="5" fill="rgba(255,255,255,0.22)" font-family="monospace">3</text>
                      <text x="83" y="85" font-size="5" fill="rgba(255,255,255,0.22)" font-family="monospace">4</text>
                      <text x="83" y="94" font-size="5" fill="rgba(255,255,255,0.22)" font-family="monospace">5</text>
                      <text x="83" y="103" font-size="5" fill="rgba(255,255,255,0.22)" font-family="monospace">6</text>
                      <text x="83" y="112" font-size="5" fill="rgba(255,255,255,0.22)" font-family="monospace">7</text>
                      <text x="83" y="121" font-size="5" fill="rgba(255,255,255,0.22)" font-family="monospace">8</text>
                      <!-- Code lines -->
                      <rect x="98" y="55" width="46" height="4" rx="2" fill="rgba(254,168,55,0.92)"/>
                      <rect x="98" y="64" width="76" height="3.5" rx="1.75" fill="rgba(100,210,255,0.55)"/>
                      <rect x="104" y="73" width="58" height="3.5" rx="1.75" fill="rgba(255,255,255,0.35)"/>
                      <rect x="104" y="82" width="44" height="3.5" rx="1.75" fill="rgba(255,255,255,0.28)"/>
                      <rect x="98" y="91" width="68" height="3.5" rx="1.75" fill="rgba(255,255,255,0.45)"/>
                      <rect x="98" y="100" width="34" height="3.5" rx="1.75" fill="rgba(254,168,55,0.62)"/>
                      <rect x="104" y="109" width="50" height="3.5" rx="1.75" fill="rgba(100,210,255,0.4)"/>
                      <rect x="98" y="118" width="62" height="3.5" rx="1.75" fill="rgba(255,255,255,0.32)"/>
                      <rect x="136" y="118" width="1.5" height="6" rx="0.75" fill="rgba(255,255,255,0.72)"/>
                      <!-- Keyboard base -->
                      <rect x="62" y="138" width="178" height="9" rx="4.5" fill="rgba(255,255,255,0.22)"/>
                      <rect x="98" y="147" width="106" height="4.5" rx="2.25" fill="rgba(255,255,255,0.12)"/>

                      <!-- Person (right) -->
                      <ellipse cx="248" cy="162" rx="26" ry="5" fill="rgba(0,0,0,0.08)"/>
                      <path d="M235 122 L230 158 L240 158 L242 128Z" fill="rgba(255,255,255,0.16)"/>
                      <path d="M258 122 L263 158 L253 158 L251 128Z" fill="rgba(255,255,255,0.16)"/>
                      <rect x="228" y="118" width="40" height="6" rx="3" fill="rgba(255,255,255,0.18)"/>
                      <path d="M228 82 Q235 77 246 77 Q257 77 264 82 L267 122 L225 122Z" fill="rgba(255,255,255,0.2)"/>
                      <rect x="241" y="68" width="10" height="12" rx="4" fill="rgba(255,215,180,0.8)"/>
                      <circle cx="246" cy="54" r="17" fill="rgba(255,215,180,0.85)"/>
                      <ellipse cx="246" cy="39" rx="16" ry="8" fill="rgba(25,12,5,0.55)"/>
                      <path d="M229 50 Q228 58 231 58" fill="none" stroke="rgba(25,12,5,0.5)" stroke-width="1.5" stroke-linecap="round"/>
                      <path d="M263 50 Q264 58 261 58" fill="none" stroke="rgba(25,12,5,0.5)" stroke-width="1.5" stroke-linecap="round"/>
                      <circle cx="241" cy="55" r="1.4" fill="rgba(80,40,15,0.38)"/>
                      <circle cx="251" cy="55" r="1.4" fill="rgba(80,40,15,0.38)"/>
                      <path d="M242 61 Q246 64 250 61" stroke="rgba(80,40,15,0.35)" stroke-width="1.2" fill="none" stroke-linecap="round"/>
                      <path d="M230 88 Q215 102 196 115" stroke="rgba(255,215,180,0.8)" stroke-width="8" stroke-linecap="round"/>
                      <ellipse cx="193" cy="117" rx="7" ry="5" fill="rgba(255,215,180,0.8)"/>
                      <path d="M262 88 Q272 102 272 120" stroke="rgba(255,215,180,0.7)" stroke-width="7" stroke-linecap="round"/>

                      <!-- Certificate card -->
                      <g transform="translate(262,10)">
                        <rect width="56" height="40" rx="7.5" fill="rgba(255,255,255,0.18)" stroke="rgba(255,255,255,0.4)" stroke-width="1.2"/>
                        <rect x="7" y="8" width="42" height="3.5" rx="1.75" fill="rgba(255,255,255,0.7)"/>
                        <rect x="7" y="15" width="27" height="3" rx="1.5" fill="rgba(255,255,255,0.42)"/>
                        <circle cx="28" cy="30" r="6.5" fill="none" stroke="rgba(254,168,55,0.95)" stroke-width="1.5"/>
                        <path d="M25.3 30l2.2 2.2 4.2-4.2" stroke="rgba(254,168,55,0.95)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                      </g>

                      <!-- Graduation cap -->
                      <g transform="translate(8,15)">
                        <polygon points="25,7 46,16 25,25 4,16" fill="rgba(255,255,255,0.32)"/>
                        <path d="M17 16 L17 28 Q25 33 33 28 L33 16" fill="rgba(255,255,255,0.16)"/>
                        <line x1="46" y1="16" x2="46" y2="27" stroke="rgba(255,255,255,0.48)" stroke-width="1.8"/>
                        <circle cx="46" cy="29.5" r="3" fill="rgba(254,168,55,0.82)"/>
                      </g>

                      <!-- Code brackets -->
                      <text x="8" y="82" font-size="20" fill="rgba(255,255,255,0.15)" font-family="monospace" font-weight="700">&lt;/&gt;</text>

                      <!-- Progress bar -->
                      <g transform="translate(76,168)">
                        <text x="0" y="-3" font-size="6" fill="rgba(255,255,255,0.5)" font-family="sans-serif" font-weight="600">Progression du cours</text>
                        <rect width="148" height="7" rx="3.5" fill="rgba(255,255,255,0.1)"/>
                        <rect width="99" height="7" rx="3.5" fill="rgba(255,255,255,0.42)"/>
                        <text x="152" y="5.5" font-size="6.5" fill="rgba(255,255,255,0.68)" font-family="sans-serif" font-weight="700">67%</text>
                      </g>

                      <!-- Stars/sparkles -->
                      <path d="M270 100 L271.4 103.9 L275.4 105.2 L271.4 106.5 L270 110.4 L268.6 106.5 L264.6 105.2 L268.6 103.9Z" fill="rgba(255,255,255,0.5)"/>
                      <path d="M40 44 L41.1 47.3 L44.6 48.4 L41.1 49.5 L40 52.8 L38.9 49.5 L35.4 48.4 L38.9 47.3Z" fill="rgba(254,168,55,0.68)"/>
                      <circle cx="24" cy="98" r="2.5" fill="rgba(255,255,255,0.35)"/>
                      <circle cx="44" cy="142" r="2" fill="rgba(255,255,255,0.32)"/>
                      <circle cx="282" cy="148" r="2.5" fill="rgba(255,255,255,0.38)"/>
                      <circle cx="268" cy="28" r="1.8" fill="rgba(255,255,255,0.42)"/>
                      <circle cx="16" cy="52" r="1.5" fill="rgba(255,255,255,0.35)"/>
                      <circle cx="292" cy="56" r="2" fill="rgba(255,255,255,0.4)"/>
                      <circle cx="58" cy="170" r="1.5" fill="rgba(255,255,255,0.28)"/>
                    </svg>
                </div>

                <div class="brand-quote mb-4">
                    <p class="brand-tagline">
                        "Bienvenue dans votre espace de transformation."
                    </p>
                    <p class="brand-sub">
                        Connectez-vous pour poursuivre votre parcours vers l'excellence tech.
                    </p>
                </div>

                <div class="brand-stats">
                    <div class="bstat">
                        <span class="bstat-num">200+</span>
                        <span class="bstat-label">Apprenants</span>
                    </div>
                    <div class="bstat">
                        <span class="bstat-num">60+</span>
                        <span class="bstat-label">Certifiés</span>
                    </div>
                    <div class="bstat">
                        <span class="bstat-num">50+</span>
                        <span class="bstat-label">Formateurs</span>
                    </div>
                </div>

                {{-- Éléments décoratifs --}}
                <div class="deco-circle deco-1"></div>
                <div class="deco-circle deco-2"></div>
                <div class="deco-circle deco-3"></div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════
             PANNEAU DROIT — formulaire
        ═══════════════════════════════════════ --}}
        <div class="login-form-panel">
            <div class="login-form-inner">

                {{-- En-tête mobile (logo visible uniquement sur mobile) --}}
                <div class="mobile-brand-header">
                    <a href="{{ url('/') }}" class="mobile-logo-link">
                        <span class="logo-text-mobile">Afri<span>Code</span></span>
                    </a>
                </div>

                <h1 class="login-title">Connexion</h1>
                <p class="login-subtitle">
                    Bon retour ! Connectez-vous à votre espace.
                </p>

                {{-- Statut session --}}
                @if (session('status'))
                    <div class="alert alert-success alert-sm mb-4 rounded-3">
                        <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                    </div>
                @endif

                {{-- Erreurs générales --}}
                @if (isset($errors) && $errors instanceof \Illuminate\Support\ViewErrorBag && $errors->any())
                    <div class="alert alert-danger alert-sm mb-4 rounded-3">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- ── Connexion sociale ── --}}
                @include('auth.partials.social-buttons')

                {{-- Séparateur --}}
                <div class="divider">
                    <span>ou par email</span>
                </div>

                {{-- ── Formulaire email/mot de passe ── --}}
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    {{-- Email --}}
                    <div class="field-group mb-3">
                        <label for="email" class="field-label">Adresse email</label>
                        <div class="field-wrap">
                            <i class="fas fa-envelope field-icon"></i>
                            <input id="email"
                                   type="email"
                                   name="email"
                                   class="field-input @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}"
                                   inputmode="email"
                                   autocomplete="username email"
                                   placeholder="vous@exemple.com"
                                   required autofocus>
                        </div>
                        @error('email')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Mot de passe --}}
                    <div class="field-group mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="password" class="field-label">Mot de passe</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="forgot-link">
                                    Mot de passe oublié ?
                                </a>
                            @endif
                        </div>
                        <div class="field-wrap">
                            <i class="fas fa-lock field-icon"></i>
                            <input id="password"
                                   type="password"
                                   name="password"
                                   class="field-input @error('password') is-invalid @enderror"
                                   autocomplete="current-password"
                                   placeholder="••••••••"
                                   required>
                            <button type="button"
                                    class="toggle-password"
                                    onclick="togglePasswordVisibility('password','eye_password')"
                                    aria-label="Afficher/masquer le mot de passe">
                                <i class="fas fa-eye" id="eye_password"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Se souvenir --}}
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <input class="remember-check" type="checkbox"
                               name="remember" id="remember_me"
                               {{ old('remember') ? 'checked' : '' }}>
                        <label class="remember-label" for="remember_me">
                            Se souvenir de moi
                        </label>
                    </div>

                    {{-- Bouton principal --}}
                    <button type="submit" class="btn-login" id="loginBtn">
                        <span class="btn-login-text">
                            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                        </span>
                        <span class="btn-login-loading" style="display:none;">
                            <i class="fas fa-spinner fa-spin me-2"></i>Connexion…
                        </span>
                    </button>

                    {{-- Contexte de redirection --}}
                    <p class="redirect-hint">
                        <i class="fas fa-arrow-right me-1"></i>
                        Vous serez redirigé vers votre tableau de bord.
                    </p>
                </form>

                {{-- Séparateur inscription --}}
                <div class="signup-row">
                    <span>Pas encore de compte ?</span>
                    <a href="{{ route('register') }}" class="signup-link">Créer un compte gratuit</a>
                </div>

                {{-- Badge sécurité --}}
                <div class="security-badge">
                    <i class="fas fa-shield-alt"></i>
                    <span>Connexion sécurisée SSL — Vos données ne sont jamais partagées</span>
                </div>

            </div>
        </div>

    </div>
</div>

<style>
/* ─── Layout ─────────────────────────────── */
.login-page {
    min-height: 100vh;
    background: #f4f6f9;
    display: flex;
    align-items: stretch;
}

.login-container {
    width: 100%;
    display: flex;
    min-height: calc(100vh - 70px);
}

/* ─── Panneau branding (gauche) ───────────── */
.login-brand-panel {
    display: none;
    width: 42%;
    background: linear-gradient(145deg, #0d7a63 0%, #11c06d 45%, #239e79 100%);
    position: relative;
    overflow: hidden;
}

@media (min-width: 992px) {
    .login-brand-panel { display: flex; }
}

.brand-panel-inner {
    width: 100%;
    padding: 3rem 3rem;
    display: flex;
    flex-direction: column;
    position: relative;
    z-index: 2;
}

.brand-logo a {
    display: inline-block;
    text-decoration: none;
}

.logo-fallback, .logo-text-mobile span {
    color: #fea837;
}

.logo-fallback {
    align-items: center;
}

.logo-text {
    font-size: 1.8rem;
    font-weight: 800;
    color: white;
    letter-spacing: -1px;
}

.logo-text span { color: #fea837; }

.brand-tagline {
    font-size: 1.45rem;
    font-weight: 700;
    color: white;
    line-height: 1.4;
    margin-bottom: .75rem;
}

.brand-sub {
    font-size: .95rem;
    color: rgba(255,255,255,.8);
    line-height: 1.6;
}

.brand-stats {
    display: flex;
    gap: 2rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255,255,255,.2);
}

.bstat {
    display: flex;
    flex-direction: column;
    gap: .2rem;
}

.bstat-num {
    font-size: 1.6rem;
    font-weight: 800;
    color: white;
    line-height: 1;
}

.bstat-label {
    font-size: .75rem;
    color: rgba(255,255,255,.7);
    text-transform: uppercase;
    letter-spacing: .5px;
}

/* Cercles décoratifs */
.deco-circle {
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,.05);
}
.deco-1 { width: 320px; height: 320px; bottom: -80px; right: -80px; }
.deco-2 { width: 200px; height: 200px; top: 60px; right: -60px; background: rgba(255,255,255,.07); }
.deco-3 { width: 100px; height: 100px; bottom: 160px; left: 30px; }

/* ─── Panneau formulaire (droite) ─────────── */
.login-form-panel {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1.5rem;
    background: #f4f6f9;
}

.login-form-inner {
    width: 100%;
    max-width: 420px;
}

/* Logo mobile */
.mobile-brand-header {
    text-align: center;
    margin-bottom: 1.5rem;
}

.mobile-logo-link { text-decoration: none; }

.logo-text-mobile {
    font-size: 1.6rem;
    font-weight: 800;
    color: #1a1a2e;
    letter-spacing: -1px;
}

@media (min-width: 992px) {
    .mobile-brand-header { display: none; }
}

/* ─── Titres ──────────────────────────────── */
.login-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: #1a1a2e;
    margin-bottom: .35rem;
}

.login-subtitle {
    font-size: .9rem;
    color: #6b7280;
    margin-bottom: 1.5rem;
}

/* ─── Boutons sociaux ─────────────────────── */
.social-buttons {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: .5rem;
    margin-bottom: 1.25rem;
}

.social-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: .35rem;
    padding: .75rem .4rem;
    border-radius: 10px;
    border: 1.5px solid #e5e7eb;
    background: white;
    color: #1a1a2e;
    font-size: .76rem;
    font-weight: 600;
    text-decoration: none;
    transition: all .2s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,.06);
    text-align: center;
    line-height: 1.2;
}

.social-btn:hover {
    border-color: #11c06d;
    box-shadow: 0 3px 10px rgba(17,192,109,.15);
    color: #1a1a2e;
    transform: translateY(-1px);
}

.social-btn i, .social-btn svg {
    font-size: 1.3rem;
    flex-shrink: 0;
}

.social-google i, .social-google svg { color: #EA4335; }
.social-github i { color: #333; }
.social-linkedin i { color: #0A66C2; }

/* ─── Séparateur ──────────────────────────── */
.divider {
    display: flex;
    align-items: center;
    gap: .75rem;
    margin: 1.25rem 0;
    color: #9ca3af;
    font-size: .82rem;
}

.divider::before, .divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e5e7eb;
}

/* ─── Champs ──────────────────────────────── */
.field-label {
    font-size: .83rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: .4rem;
    display: block;
}

.field-wrap {
    position: relative;
}

.field-icon {
    position: absolute;
    left: .9rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: .85rem;
    pointer-events: none;
}

.field-input {
    width: 100%;
    padding: .7rem .9rem .7rem 2.4rem;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    font-size: .9rem;
    color: #1a1a2e;
    background: white;
    transition: border-color .2s, box-shadow .2s;
    outline: none;
    appearance: none;
}

.field-input:focus {
    border-color: #11c06d;
    box-shadow: 0 0 0 3px rgba(17,192,109,.12);
}

.field-input.is-invalid { border-color: #ef4444; }
.field-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,.12); }

.field-input::placeholder { color: #c4c9d4; }

.toggle-password {
    position: absolute;
    right: .75rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    padding: .25rem;
    transition: color .2s;
}

.toggle-password:hover { color: #11c06d; }

.field-error {
    font-size: .78rem;
    color: #ef4444;
    margin-top: .3rem;
    margin-bottom: 0;
}

/* ─── Se souvenir ─────────────────────────── */
.remember-check {
    width: 16px;
    height: 16px;
    accent-color: #11c06d;
    cursor: pointer;
    flex-shrink: 0;
}

.remember-label {
    font-size: .85rem;
    color: #6b7280;
    cursor: pointer;
    margin: 0;
}

.forgot-link {
    font-size: .82rem;
    color: #11c06d;
    text-decoration: none;
    font-weight: 500;
}

.forgot-link:hover { text-decoration: underline; }

/* ─── Bouton connexion ────────────────────── */
.btn-login {
    width: 100%;
    padding: .85rem 1.5rem;
    background: linear-gradient(135deg, #11c06d, #239e79);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all .25s ease;
    box-shadow: 0 6px 20px rgba(17,192,109,.35);
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(17,192,109,.45);
}

.btn-login:active { transform: translateY(0); }
.btn-login:disabled { opacity: .7; cursor: not-allowed; transform: none; }

/* ─── Contexte de redirection ─────────────── */
.redirect-hint {
    font-size: .78rem;
    color: #9ca3af;
    text-align: center;
    margin-top: .6rem;
    margin-bottom: 0;
}

/* ─── Inscription ─────────────────────────── */
.signup-row {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
    margin-top: 1.5rem;
    padding-top: 1.25rem;
    border-top: 1px solid #e5e7eb;
    font-size: .87rem;
    color: #6b7280;
}

.signup-link {
    font-weight: 700;
    color: #11c06d;
    text-decoration: none;
}

.signup-link:hover { text-decoration: underline; }

/* ─── Badge sécurité ──────────────────────── */
.security-badge {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
    margin-top: 1.5rem;
    font-size: .75rem;
    color: #9ca3af;
}

.security-badge i {
    color: #11c06d;
    font-size: .9rem;
}

/* ─── Responsive ──────────────────────────── */
@media (max-width: 576px) {
    .login-form-panel { padding: 1.5rem 1rem; }
    .login-title { font-size: 1.45rem; }
    .social-btn { font-size: .82rem; padding: .6rem .9rem; }
}

/* ─── Illustration ─────────────────────── */
.brand-illustration {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: .75rem 0;
    min-height: 0;
}
.brand-illus-svg {
    width: 100%;
    max-width: 300px;
    height: auto;
    filter: drop-shadow(0 6px 20px rgba(0,0,0,0.18));
}
</style>

<script>
function togglePasswordVisibility(inputId, eyeId) {
    const input = document.getElementById(inputId);
    const eye   = document.getElementById(eyeId);
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    eye.classList.toggle('fa-eye', !isHidden);
    eye.classList.toggle('fa-eye-slash', isHidden);
}

document.getElementById('loginForm').addEventListener('submit', function () {
    const btn = document.getElementById('loginBtn');
    btn.disabled = true;
    btn.querySelector('.btn-login-text').style.display  = 'none';
    btn.querySelector('.btn-login-loading').style.display = 'inline';
});
</script>
@endsection
