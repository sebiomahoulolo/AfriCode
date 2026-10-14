@extends('layouts.layout')

@section('title', 'Créer un compte — AfriCode | Rejoignez le Hub Numérique Africain')
@section('meta_description', 'Créez votre compte AfriCode gratuitement et accédez à des formations en développement web, cybersécurité, IA et plus. Rejoignez des milliers d\'apprenants africains.')
@section('meta_robots', 'index, follow')
@section('og_title', 'Rejoignez AfriCode — Hub Numérique Africain')
@section('og_description', 'Créez votre compte gratuit et démarrez votre parcours numérique avec AfriCode. Formations certifiantes, projets pratiques, communauté active.')

@section('content')
<div class="register-page">
    <div class="register-container">

        {{-- ═══════════════════════════════════════
             PANNEAU GAUCHE — branding (desktop)
        ═══════════════════════════════════════ --}}
        <div class="reg-brand-panel" aria-hidden="true">
            <div class="brand-panel-inner">
                <div class="brand-logo mb-3">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('assets/images/logo.png') }}"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='block';"
                             alt="AfriCode" style="height:42px;">
                        <span class="logo-text" style="display:none;">Afri<span>Code</span></span>
                    </a>
                </div>

                {{-- ── Illustration ── --}}
                <div class="brand-illustration">
                    <svg viewBox="0 0 300 175" xmlns="http://www.w3.org/2000/svg" fill="none" class="brand-illus-svg" aria-hidden="true">
                      <!-- Africa continent silhouette -->
                      <path d="M150 22 C145 20 138 16 128 18 C118 20 110 28 106 38 C101 50 100 64 98 76 C94 90 88 98 86 110 C84 122 86 136 88 148 C92 162 96 172 104 180 C112 186 124 188 134 184 C144 180 152 170 156 158 C160 146 158 132 162 120 C166 110 172 102 172 90 C172 78 166 68 162 56 C160 46 162 34 158 26 C155 20 152 20 150 22Z" fill="rgba(255,255,255,0.08)" stroke="rgba(255,255,255,0.14)" stroke-width="1"/>

                      <!-- Person node: Dakar (top-left) -->
                      <g transform="translate(96,44)">
                        <circle r="22" fill="rgba(255,255,255,0.13)" stroke="rgba(255,255,255,0.28)" stroke-width="1.5"/>
                        <circle r="13" fill="rgba(255,255,255,0.2)"/>
                        <circle cy="-4" r="6" fill="rgba(255,215,180,0.9)"/>
                        <path d="M-7 11 Q0 6 7 11 L9 19 L-9 19Z" fill="rgba(255,215,180,0.85)"/>
                      </g>

                      <!-- Person node: Lagos (top-right) -->
                      <g transform="translate(190,65)">
                        <circle r="22" fill="rgba(255,255,255,0.11)" stroke="rgba(255,255,255,0.26)" stroke-width="1.5"/>
                        <circle r="13" fill="rgba(255,255,255,0.17)"/>
                        <circle cy="-4" r="6" fill="rgba(200,155,115,0.9)"/>
                        <path d="M-7 11 Q0 6 7 11 L9 19 L-9 19Z" fill="rgba(200,155,115,0.85)"/>
                      </g>

                      <!-- Person node: Abidjan (mid-left) -->
                      <g transform="translate(84,118)">
                        <circle r="20" fill="rgba(255,255,255,0.11)" stroke="rgba(255,255,255,0.25)" stroke-width="1.5"/>
                        <circle r="12" fill="rgba(255,255,255,0.17)"/>
                        <circle cy="-3.5" r="5.5" fill="rgba(230,185,145,0.9)"/>
                        <path d="M-6.5 10 Q0 5.5 6.5 10 L8 17 L-8 17Z" fill="rgba(230,185,145,0.85)"/>
                      </g>

                      <!-- Person node: Nairobi (mid-right) -->
                      <g transform="translate(206,122)">
                        <circle r="20" fill="rgba(255,255,255,0.11)" stroke="rgba(255,255,255,0.25)" stroke-width="1.5"/>
                        <circle r="12" fill="rgba(255,255,255,0.17)"/>
                        <circle cy="-3.5" r="5.5" fill="rgba(210,168,128,0.9)"/>
                        <path d="M-6.5 10 Q0 5.5 6.5 10 L8 17 L-8 17Z" fill="rgba(210,168,128,0.85)"/>
                      </g>

                      <!-- Person node: Joburg (bottom) -->
                      <g transform="translate(143,158)">
                        <circle r="20" fill="rgba(255,255,255,0.11)" stroke="rgba(255,255,255,0.25)" stroke-width="1.5"/>
                        <circle r="12" fill="rgba(255,255,255,0.17)"/>
                        <circle cy="-3.5" r="5.5" fill="rgba(255,215,180,0.9)"/>
                        <path d="M-6.5 10 Q0 5.5 6.5 10 L8 17 L-8 17Z" fill="rgba(255,215,180,0.85)"/>
                      </g>

                      <!-- Connection lines -->
                      <path d="M118 44 L168 65" stroke="rgba(255,255,255,0.22)" stroke-width="1.2" stroke-dasharray="4,3"/>
                      <path d="M96 66 L88 98" stroke="rgba(255,255,255,0.2)" stroke-width="1.2" stroke-dasharray="4,3"/>
                      <path d="M190 87 L204 102" stroke="rgba(255,255,255,0.2)" stroke-width="1.2" stroke-dasharray="4,3"/>
                      <path d="M96 136 L126 148" stroke="rgba(255,255,255,0.18)" stroke-width="1.2" stroke-dasharray="4,3"/>
                      <path d="M194 140 L165 148" stroke="rgba(255,255,255,0.18)" stroke-width="1.2" stroke-dasharray="4,3"/>

                      <!-- Connection pulse dots -->
                      <circle cx="142" cy="54" r="3" fill="rgba(255,255,255,0.45)"/>
                      <circle cx="148" cy="118" r="2.5" fill="rgba(255,255,255,0.4)"/>
                      <circle cx="144" cy="148" r="2.5" fill="rgba(255,255,255,0.42)"/>

                      <!-- Certificate card (top right) -->
                      <g transform="translate(252,14)">
                        <rect width="44" height="33" rx="6" fill="rgba(255,255,255,0.17)" stroke="rgba(255,255,255,0.36)" stroke-width="1"/>
                        <rect x="5" y="6" width="34" height="3" rx="1.5" fill="rgba(255,255,255,0.68)"/>
                        <rect x="5" y="12" width="22" height="2.5" rx="1.25" fill="rgba(255,255,255,0.42)"/>
                        <circle cx="22" cy="24" r="5" fill="none" stroke="rgba(254,168,55,0.92)" stroke-width="1.4"/>
                        <path d="M19.8 24l1.8 1.8 3.4-3.4" stroke="rgba(254,168,55,0.92)" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                      </g>

                      <!-- Graduation cap (bottom right) -->
                      <g transform="translate(255,142)">
                        <polygon points="20,5 37,12 20,19 3,12" fill="rgba(255,255,255,0.3)"/>
                        <path d="M13 12 L13 20 Q20 24 27 20 L27 12" fill="rgba(255,255,255,0.14)"/>
                        <line x1="37" y1="12" x2="37" y2="20" stroke="rgba(255,255,255,0.44)" stroke-width="1.5"/>
                        <circle cx="37" cy="22" r="2.5" fill="rgba(254,168,55,0.8)"/>
                      </g>

                      <!-- Code brackets (top left) -->
                      <text x="12" y="38" font-size="18" fill="rgba(255,255,255,0.16)" font-family="monospace" font-weight="700">&lt;/&gt;</text>

                      <!-- Stars -->
                      <path d="M235 82 L236.3 85.8 L240.2 87 L236.3 88.2 L235 92 L233.7 88.2 L229.8 87 L233.7 85.8Z" fill="rgba(255,255,255,0.46)"/>
                      <path d="M54 56 L55 59 L58.2 60 L55 61 L54 64 L53 61 L49.8 60 L53 59Z" fill="rgba(254,168,55,0.65)"/>
                      <circle cx="20" cy="88" r="2.2" fill="rgba(255,255,255,0.32)"/>
                      <circle cx="278" cy="95" r="2" fill="rgba(255,255,255,0.36)"/>
                      <circle cx="62" cy="145" r="1.8" fill="rgba(255,255,255,0.3)"/>
                      <circle cx="270" cy="55" r="2" fill="rgba(255,255,255,0.38)"/>
                      <circle cx="16" cy="125" r="1.5" fill="rgba(255,255,255,0.28)"/>
                    </svg>
                </div>

                <div class="brand-message">
                    <h2 class="brand-title">
                        Rejoignez la communauté qui façonne l'avenir numérique de l'Afrique.
                    </h2>
                    <p class="brand-sub">
                        Créez votre compte gratuitement et commencez votre transformation tech dès aujourd'hui.
                    </p>
                </div>

                <ul class="brand-benefits">
                    <li><i class="fas fa-check-circle"></i> Accès à des formations certifiantes</li>
                    <li><i class="fas fa-check-circle"></i> Accompagnement par des experts africains</li>
                    <li><i class="fas fa-check-circle"></i> Communauté de 200+ apprenants actifs</li>
                    <li><i class="fas fa-check-circle"></i> 50+ formateurs experts disponibles</li>
                    <li><i class="fas fa-check-circle"></i> Certifications reconnues par l'industrie</li>
                    <li><i class="fas fa-check-circle"></i> Présent dans 15 pays africains</li>
                </ul>

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

                <div class="deco-circle deco-1"></div>
                <div class="deco-circle deco-2"></div>
                <div class="deco-circle deco-3"></div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════
             PANNEAU DROIT — formulaire
        ═══════════════════════════════════════ --}}
        <div class="reg-form-panel">
            <div class="reg-form-inner">

                {{-- Logo mobile --}}
                <div class="mobile-brand-header">
                    <a href="{{ url('/') }}" class="mobile-logo-link">
                        <span class="logo-text-mobile">Afri<span>Code</span></span>
                    </a>
                </div>

                <h1 class="reg-title">Créer un compte</h1>
                <p class="reg-subtitle">C'est gratuit et prend moins de 2 minutes.</p>

                {{-- Erreurs générales --}}
                @if (isset($errors) && $errors instanceof \Illuminate\Support\ViewErrorBag && $errors->any())
                    <div class="alert-error mb-4">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- Connexion sociale --}}
                @include('auth.partials.social-buttons')

                {{-- Séparateur --}}
                <div class="divider"><span>ou avec votre email</span></div>

                {{-- ══ Formulaire ══ --}}
                <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
                    @csrf

                    {{-- Prénom + Nom côte à côte --}}
                    <div class="two-cols mb-3">
                        <div class="field-group">
                            <label for="first_name" class="field-label">Prénom</label>
                            <div class="field-wrap">
                                <i class="fas fa-user field-icon"></i>
                                <input id="first_name" type="text" name="first_name"
                                       class="field-input @error('first_name') input-error @enderror"
                                       value="{{ old('first_name') }}"
                                       placeholder="Marie"
                                       autocomplete="given-name" required autofocus>
                                <span class="field-feedback"></span>
                            </div>
                            @error('first_name')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="field-group">
                            <label for="last_name" class="field-label">Nom</label>
                            <div class="field-wrap">
                                <i class="fas fa-user field-icon"></i>
                                <input id="last_name" type="text" name="last_name"
                                       class="field-input @error('last_name') input-error @enderror"
                                       value="{{ old('last_name') }}"
                                       placeholder="Kouassi"
                                       autocomplete="family-name" required>
                                <span class="field-feedback"></span>
                            </div>
                            @error('last_name')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="field-group mb-3">
                        <label for="email" class="field-label">Adresse email</label>
                        <div class="field-wrap">
                            <i class="fas fa-envelope field-icon"></i>
                            <input id="email" type="email" name="email"
                                   class="field-input @error('email') input-error @enderror"
                                   value="{{ old('email') }}"
                                   inputmode="email"
                                   placeholder="vous@exemple.com"
                                   autocomplete="username email" required>
                            <span class="field-feedback" id="email-feedback"></span>
                        </div>
                        @error('email')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Rôle — cartes cliquables --}}
                    <div class="field-group mb-4">
                        <label class="field-label">Vous êtes</label>
                        <div class="role-cards">
                            <label class="role-card" for="role-apprenant">
                                <input type="radio" name="role" id="role-apprenant" value="apprenant"
                                       {{ old('role', 'apprenant') == 'apprenant' ? 'checked' : '' }} required>
                                <div class="role-card-inner">
                                    <div class="role-icon"><i class="fas fa-graduation-cap"></i></div>
                                    <div>
                                        <strong>Apprenant</strong>
                                        <small>J'accède aux formations et obtiens des certifications</small>
                                    </div>
                                </div>
                                <span class="role-check"><i class="fas fa-check"></i></span>
                            </label>
                            <label class="role-card" for="role-formateur">
                                <input type="radio" name="role" id="role-formateur" value="formateur"
                                       {{ old('role') == 'formateur' ? 'checked' : '' }}>
                                <div class="role-card-inner">
                                    <div class="role-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                                    <div>
                                        <strong>Formateur</strong>
                                        <small>Je crée et partage mes cours avec la communauté</small>
                                    </div>
                                </div>
                                <span class="role-check"><i class="fas fa-check"></i></span>
                            </label>
                        </div>
                        @error('role')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Mot de passe --}}
                    <div class="field-group mb-3">
                        <label for="password" class="field-label">Mot de passe</label>
                        <div class="field-wrap">
                            <i class="fas fa-lock field-icon"></i>
                            <input id="password" type="password" name="password"
                                   class="field-input @error('password') input-error @enderror"
                                   placeholder="••••••••"
                                   autocomplete="new-password" required>
                            <button type="button" class="toggle-password"
                                    onclick="togglePwd('password','eye_pwd')"
                                    aria-label="Voir le mot de passe">
                                <i class="fas fa-eye" id="eye_pwd"></i>
                            </button>
                        </div>
                        {{-- Barre de force --}}
                        <div class="strength-bar mt-2" id="strength-bar">
                            <div class="strength-track">
                                <div class="strength-fill" id="strength-fill"></div>
                            </div>
                            <span class="strength-label" id="strength-label"></span>
                        </div>
                        {{-- Critères --}}
                        <ul class="pwd-rules" id="pwd-rules">
                            <li id="rule-len"><i class="fas fa-circle"></i> 8 caractères minimum</li>
                            <li id="rule-upper"><i class="fas fa-circle"></i> Une lettre majuscule</li>
                            <li id="rule-num"><i class="fas fa-circle"></i> Un chiffre</li>
                        </ul>
                        @error('password')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirmer mot de passe --}}
                    <div class="field-group mb-4">
                        <label for="password_confirmation" class="field-label">Confirmer le mot de passe</label>
                        <div class="field-wrap">
                            <i class="fas fa-lock field-icon"></i>
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                   class="field-input"
                                   placeholder="••••••••"
                                   autocomplete="new-password" required>
                            <button type="button" class="toggle-password"
                                    onclick="togglePwd('password_confirmation','eye_pwd2')"
                                    aria-label="Voir le mot de passe">
                                <i class="fas fa-eye" id="eye_pwd2"></i>
                            </button>
                            <span class="field-feedback" id="confirm-feedback"></span>
                        </div>
                    </div>

                    {{-- CGU --}}
                    <div class="d-flex align-items-start gap-2 mb-4">
                        <input class="remember-check mt-1" type="checkbox" name="terms" id="terms" required>
                        <label class="remember-label" for="terms">
                            J'accepte les
                            <a href="{{ route('pages.terms') }}" target="_blank" class="text-link">conditions d'utilisation</a>
                            et la
                            <a href="{{ route('pages.privacy') }}" target="_blank" class="text-link">politique de confidentialité</a>
                            d'AfriCode.
                        </label>
                    </div>

                    {{-- Bouton --}}
                    <button type="submit" class="btn-register" id="registerBtn">
                        <span class="btn-text">
                            <i class="fas fa-rocket me-2"></i>Créer mon compte gratuitement
                        </span>
                        <span class="btn-loading" style="display:none;">
                            <i class="fas fa-spinner fa-spin me-2"></i>Création en cours…
                        </span>
                    </button>

                    {{-- Sécurité --}}
                    <p class="security-note">
                        <i class="fas fa-shield-alt"></i>
                        Vos données sont sécurisées et ne seront jamais partagées.
                    </p>
                </form>

                {{-- Déjà inscrit --}}
                <div class="login-row">
                    <span>Déjà un compte ?</span>
                    <a href="{{ route('login') }}" class="login-link">Se connecter</a>
                </div>

            </div>
        </div>

    </div>
</div>

<style>
/* ─── Layout ────────────────────────────── */
.register-page {
    background: #f4f6f9;
    min-height: 100vh;
}
.register-container {
    display: flex;
    min-height: calc(100vh - 70px);
}

/* ─── Panneau branding ────────────────── */
.reg-brand-panel {
    display: none;
    width: 42%;
    background: linear-gradient(145deg, #0d7a63 0%, #11c06d 50%, #16a085 100%);
    position: relative;
    overflow: hidden;
}
@media (min-width: 992px) { .reg-brand-panel { display: flex; } }

.brand-panel-inner {
    width: 100%;
    padding: 3rem;
    display: flex;
    flex-direction: column;
    position: relative;
    z-index: 2;
}

.logo-text { font-size: 1.8rem; font-weight: 800; color: white; }
.logo-text span, .logo-text-mobile span { color: #fea837; }

.brand-title {
    font-size: 1.35rem;
    font-weight: 700;
    color: white;
    line-height: 1.45;
    margin-bottom: .75rem;
}
.brand-sub { font-size: .9rem; color: rgba(255,255,255,.8); line-height: 1.6; margin-bottom: 1.75rem; }

.brand-benefits {
    list-style: none;
    padding: 0;
    margin: 0 0 2rem;
    display: flex;
    flex-direction: column;
    gap: .6rem;
}
.brand-benefits li {
    display: flex;
    align-items: center;
    gap: .65rem;
    font-size: .88rem;
    color: rgba(255,255,255,.9);
}
.brand-benefits li i { color: #a7f3d0; font-size: .85rem; }

.brand-stats {
    display: flex;
    gap: 2rem;
    padding-top: 1.25rem;
    border-top: 1px solid rgba(255,255,255,.2);
    margin-top: auto;
}
.bstat { display: flex; flex-direction: column; gap: .2rem; }
.bstat-num { font-size: 1.5rem; font-weight: 800; color: white; line-height: 1; }
.bstat-label { font-size: .72rem; color: rgba(255,255,255,.7); text-transform: uppercase; letter-spacing: .5px; }

.deco-circle { position: absolute; border-radius: 50%; background: rgba(255,255,255,.05); }
.deco-1 { width: 300px; height: 300px; bottom: -70px; right: -70px; }
.deco-2 { width: 180px; height: 180px; top: 50px; right: -50px; background: rgba(255,255,255,.07); }
.deco-3 { width: 90px;  height: 90px;  bottom: 140px; left: 25px; }

/* ─── Panneau formulaire ──────────────── */
.reg-form-panel {
    flex: 1;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding: 2.5rem 1.5rem;
    overflow-y: auto;
}
.reg-form-inner { width: 100%; max-width: 480px; }

.mobile-brand-header { text-align: center; margin-bottom: 1.5rem; }
.mobile-logo-link { text-decoration: none; }
.logo-text-mobile { font-size: 1.6rem; font-weight: 800; color: #1a1a2e; letter-spacing: -1px; }
@media (min-width: 992px) { .mobile-brand-header { display: none; } }

.reg-title { font-size: 1.65rem; font-weight: 800; color: #1a1a2e; margin-bottom: .3rem; }
.reg-subtitle { font-size: .88rem; color: #6b7280; margin-bottom: 1.25rem; }

/* ─── Alerte erreur ───────────────────── */
.alert-error {
    display: flex;
    align-items: center;
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 10px;
    padding: .75rem 1rem;
    font-size: .87rem;
    color: #dc2626;
}

/* ─── Boutons sociaux ─────────────────── */
.social-buttons { display: grid; grid-template-columns: repeat(3, 1fr); gap: .5rem; margin-bottom: 1.1rem; }
.social-btn {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: .35rem; padding: .75rem .4rem; border-radius: 10px;
    border: 1.5px solid #e5e7eb; background: white;
    color: #1a1a2e; font-size: .76rem; font-weight: 600;
    text-decoration: none; transition: all .2s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,.05); text-align: center; line-height: 1.2;
}
.social-btn:hover { border-color: #11c06d; box-shadow: 0 3px 10px rgba(17,192,109,.15); color: #1a1a2e; transform: translateY(-1px); }
.social-btn i, .social-btn svg { font-size: 1.3rem; flex-shrink: 0; }
.social-google i, .social-google svg { color: #EA4335; }
.social-github i { color: #333; }
.social-linkedin i { color: #0A66C2; }

/* ─── Séparateur ──────────────────────── */
.divider { display: flex; align-items: center; gap: .75rem; margin: 1rem 0; color: #9ca3af; font-size: .8rem; }
.divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #e5e7eb; }

/* ─── Champs ──────────────────────────── */
.two-cols { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }
@media (max-width: 400px) { .two-cols { grid-template-columns: 1fr; } }

.field-label { font-size: .82rem; font-weight: 600; color: #374151; margin-bottom: .35rem; display: block; }
.field-wrap { position: relative; }
.field-icon { position: absolute; left: .9rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: .82rem; pointer-events: none; }
.field-input {
    width: 100%; padding: .68rem .9rem .68rem 2.3rem;
    border: 1.5px solid #e5e7eb; border-radius: 10px;
    font-size: .88rem; color: #1a1a2e; background: white;
    transition: border-color .2s, box-shadow .2s; outline: none;
}
.field-input:focus { border-color: #11c06d; box-shadow: 0 0 0 3px rgba(17,192,109,.12); }
.field-input.input-error { border-color: #ef4444; }
.field-input.input-valid { border-color: #22c55e; }
.field-input::placeholder { color: #c4c9d4; }

.field-feedback {
    position: absolute; right: .8rem; top: 50%; transform: translateY(-50%);
    font-size: .9rem; pointer-events: none;
}

.toggle-password { position: absolute; right: .75rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: #9ca3af; cursor: pointer; padding: .25rem; transition: color .2s; }
.toggle-password:hover { color: #11c06d; }

.field-error { font-size: .76rem; color: #ef4444; margin-top: .3rem; margin-bottom: 0; }

/* ─── Cartes de rôle ──────────────────── */
.role-cards { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }
@media (max-width: 400px) { .role-cards { grid-template-columns: 1fr; } }

.role-card { cursor: pointer; position: relative; }
.role-card input[type="radio"] { position: absolute; opacity: 0; pointer-events: none; }

.role-card-inner {
    display: flex; align-items: center; gap: .75rem;
    padding: .9rem 1rem; border: 1.5px solid #e5e7eb;
    border-radius: 12px; background: white; transition: all .2s ease;
}
.role-card-inner strong { display: block; font-size: .9rem; color: #1a1a2e; line-height: 1.2; }
.role-card-inner small { font-size: .75rem; color: #9ca3af; line-height: 1.3; display: block; margin-top: .15rem; }

.role-icon {
    width: 38px; height: 38px; border-radius: 10px;
    background: #f3f4f6; display: flex; align-items: center;
    justify-content: center; font-size: 1.1rem; color: #6b7280;
    flex-shrink: 0; transition: all .2s;
}

.role-check {
    position: absolute; top: .6rem; right: .6rem;
    width: 18px; height: 18px; border-radius: 50%;
    background: #11c06d; color: white; font-size: .6rem;
    display: none; align-items: center; justify-content: center;
}

.role-card input:checked + .role-card-inner {
    border-color: #11c06d;
    background: #f0fdf8;
    box-shadow: 0 0 0 3px rgba(17,192,109,.1);
}
.role-card input:checked ~ .role-check { display: flex; }
.role-card input:checked + .role-card-inner .role-icon {
    background: #11c06d;
    color: white;
}
.role-card:hover .role-card-inner { border-color: #11c06d; }

/* ─── Force du mot de passe ───────────── */
.strength-bar { display: flex; align-items: center; gap: .5rem; }
.strength-track { flex: 1; height: 4px; background: #e5e7eb; border-radius: 4px; overflow: hidden; }
.strength-fill { height: 100%; width: 0; border-radius: 4px; transition: width .3s, background .3s; }
.strength-label { font-size: .75rem; font-weight: 600; min-width: 50px; }

.pwd-rules { list-style: none; padding: 0; margin: .5rem 0 0; display: flex; flex-direction: row; flex-wrap: wrap; gap: .3rem .75rem; }
.pwd-rules li { font-size: .75rem; color: #9ca3af; display: flex; align-items: center; gap: .35rem; transition: color .2s; white-space: nowrap; }
.pwd-rules li i { font-size: .55rem; }
.pwd-rules li.valid { color: #22c55e; }
.pwd-rules li.valid i { color: #22c55e; }

/* ─── Remember / CGU ──────────────────── */
.remember-check { width: 16px; height: 16px; accent-color: #11c06d; cursor: pointer; flex-shrink: 0; }
.remember-label { font-size: .83rem; color: #6b7280; cursor: pointer; margin: 0; line-height: 1.5; }
.text-link { color: #11c06d; text-decoration: none; font-weight: 500; }
.text-link:hover { text-decoration: underline; }

/* ─── Bouton inscription ──────────────── */
.btn-register {
    width: 100%; padding: .85rem 1.5rem;
    background: linear-gradient(135deg, #11c06d, #239e79);
    color: white; border: none; border-radius: 12px;
    font-size: 1rem; font-weight: 700; cursor: pointer;
    transition: all .25s ease;
    box-shadow: 0 6px 20px rgba(17,192,109,.35);
}
.btn-register:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(17,192,109,.45); }
.btn-register:active { transform: translateY(0); }
.btn-register:disabled { opacity: .7; cursor: not-allowed; transform: none; }

/* ─── Note sécurité ───────────────────── */
.security-note { display: flex; align-items: center; justify-content: center; gap: .4rem; margin-top: .6rem; font-size: .74rem; color: #9ca3af; }
.security-note i { color: #11c06d; }

/* ─── Déjà inscrit ────────────────────── */
.login-row { display: flex; align-items: center; justify-content: center; gap: .5rem; margin-top: 1.25rem; padding-top: 1.25rem; border-top: 1px solid #e5e7eb; font-size: .87rem; color: #6b7280; }
.login-link { font-weight: 700; color: #11c06d; text-decoration: none; }
.login-link:hover { text-decoration: underline; }

@media (max-width: 576px) {
    .reg-form-panel { padding: 1.5rem 1rem; }
    .reg-title { font-size: 1.4rem; }
}

/* ─── Illustration ─────────────────────── */
.brand-illustration {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: .5rem 0 .75rem;
}
.brand-illus-svg {
    width: 100%;
    max-width: 280px;
    height: auto;
    filter: drop-shadow(0 6px 20px rgba(0,0,0,0.15));
}
</style>

<script>
/* ── Toggle visibilité mot de passe ── */
function togglePwd(inputId, eyeId) {
    const input = document.getElementById(inputId);
    const eye   = document.getElementById(eyeId);
    const hidden = input.type === 'password';
    input.type = hidden ? 'text' : 'password';
    eye.classList.toggle('fa-eye', !hidden);
    eye.classList.toggle('fa-eye-slash', hidden);
}

document.addEventListener('DOMContentLoaded', function () {

    /* ── Validation email en temps réel ── */
    const emailInput    = document.getElementById('email');
    const emailFeedback = document.getElementById('email-feedback');
    const emailRe       = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    let emailCheckTimer = null;

    emailInput.addEventListener('blur', function () {
        checkEmailAvailability(this.value);
    });

    emailInput.addEventListener('input', function () {
        clearTimeout(emailCheckTimer);
        const val = this.value;
        if (!val) {
            this.classList.remove('input-valid', 'input-error');
            emailFeedback.innerHTML = '';
            return;
        }
        if (!emailRe.test(val)) {
            this.classList.remove('input-valid');
            this.classList.add('input-error');
            emailFeedback.innerHTML = '<i class="fas fa-times-circle" style="color:#ef4444"></i>';
            return;
        }
        emailFeedback.innerHTML = '<i class="fas fa-spinner fa-spin" style="color:#9ca3af"></i>';
        emailCheckTimer = setTimeout(() => checkEmailAvailability(val), 700);
    });

    function checkEmailAvailability(email) {
        if (!email || !emailRe.test(email)) {
            emailInput.classList.toggle('input-valid', false);
            emailInput.classList.toggle('input-error', !email ? false : true);
            emailFeedback.innerHTML = email ? '<i class="fas fa-times-circle" style="color:#ef4444"></i>' : '';
            return;
        }
        emailFeedback.innerHTML = '<i class="fas fa-spinner fa-spin" style="color:#9ca3af"></i>';
        fetch('{{ route("register.checkEmail") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email })
        })
        .then(r => r.json())
        .then(data => {
            emailInput.classList.toggle('input-valid', data.available);
            emailInput.classList.toggle('input-error', !data.available);
            if (data.available) {
                emailFeedback.innerHTML = '<i class="fas fa-check-circle" style="color:#22c55e"></i>';
            } else {
                emailFeedback.innerHTML = '<i class="fas fa-times-circle" style="color:#ef4444" title="' + data.message + '"></i>';
                const errEl = emailInput.closest('.field-group').querySelector('.field-error');
                if (errEl) { errEl.textContent = data.message; }
                else {
                    const p = document.createElement('p');
                    p.className = 'field-error';
                    p.textContent = data.message;
                    emailInput.closest('.field-wrap').after(p);
                }
            }
        })
        .catch(() => {
            emailFeedback.innerHTML = '';
            emailInput.classList.remove('input-valid', 'input-error');
        });
    }

    emailInput.addEventListener('focus', function () {
        this.classList.remove('input-valid', 'input-error');
        emailFeedback.innerHTML = '';
        const errEl = this.closest('.field-group').querySelector('.field-error:not([data-server])');
        if (errEl) errEl.remove();
    });

    /* ── Force du mot de passe ── */
    const pwdInput     = document.getElementById('password');
    const strengthFill = document.getElementById('strength-fill');
    const strengthLbl  = document.getElementById('strength-label');
    const ruleLen      = document.getElementById('rule-len');
    const ruleUpper    = document.getElementById('rule-upper');
    const ruleNum      = document.getElementById('rule-num');

    const levels = [
        { pct: '25%', color: '#ef4444', label: 'Faible'     },
        { pct: '50%', color: '#f59e0b', label: 'Moyen'      },
        { pct: '75%', color: '#11c06d', label: 'Bon'        },
        { pct: '100%',color: '#22c55e', label: 'Excellent'  },
    ];

    pwdInput.addEventListener('input', function () {
        const v     = this.value;
        const hasLen   = v.length >= 8;
        const hasUpper = /[A-Z]/.test(v);
        const hasNum   = /[0-9]/.test(v);

        ruleLen.classList.toggle('valid', hasLen);
        ruleUpper.classList.toggle('valid', hasUpper);
        ruleNum.classList.toggle('valid', hasNum);
        ruleLen.querySelector('i').className   = hasLen   ? 'fas fa-check-circle' : 'fas fa-circle';
        ruleUpper.querySelector('i').className = hasUpper ? 'fas fa-check-circle' : 'fas fa-circle';
        ruleNum.querySelector('i').className   = hasNum   ? 'fas fa-check-circle' : 'fas fa-circle';

        const score = [hasLen, hasUpper, hasNum].filter(Boolean).length;
        if (!v.length) {
            strengthFill.style.width = '0';
            strengthLbl.textContent  = '';
            return;
        }
        // spécial : score 1 = faible, 2 = moyen, 3 = bon/excellent selon longueur
        const idx = score === 3 && v.length >= 12 ? 3 : score - 1;
        const lvl = levels[Math.max(0, idx)];
        strengthFill.style.width      = lvl.pct;
        strengthFill.style.background = lvl.color;
        strengthLbl.style.color       = lvl.color;
        strengthLbl.textContent        = lvl.label;

        // Validation croisée confirmer
        checkConfirm();
    });

    /* ── Vérification confirmation mot de passe ── */
    const pwdConfirm    = document.getElementById('password_confirmation');
    const confirmFeedback = document.getElementById('confirm-feedback');

    function checkConfirm() {
        if (!pwdConfirm.value) return;
        const match = pwdInput.value === pwdConfirm.value;
        pwdConfirm.classList.toggle('input-valid', match);
        pwdConfirm.classList.toggle('input-error', !match);
        confirmFeedback.innerHTML = match
            ? '<i class="fas fa-check-circle" style="color:#22c55e"></i>'
            : '<i class="fas fa-times-circle" style="color:#ef4444"></i>';
    }

    pwdConfirm.addEventListener('input', checkConfirm);
    pwdConfirm.addEventListener('blur',  checkConfirm);

    /* ── Soumission ── */
    document.getElementById('registerForm').addEventListener('submit', function () {
        const btn = document.getElementById('registerBtn');
        btn.disabled = true;
        btn.querySelector('.btn-text').style.display    = 'none';
        btn.querySelector('.btn-loading').style.display = 'inline';
    });
});
</script>
@endsection
