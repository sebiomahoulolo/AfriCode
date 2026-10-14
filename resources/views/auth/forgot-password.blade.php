@extends('layouts.layout')

@section('title', 'Mot de passe oublié — AfriCode')

@section('content')
<div class="fp-page">
    <div class="fp-container">

        {{-- ═══ PANNEAU GAUCHE — branding ═══ --}}
        <div class="fp-brand-panel" aria-hidden="true">
            <div class="fp-brand-inner">

                {{-- Logo AfriCode --}}
                <div class="fp-brand-logo">
                    <a href="{{ url('/') }}" style="text-decoration:none;">
                        <div class="fp-logo-bars">
                            <span class="fp-bar fp-bar-1"></span>
                            <span class="fp-bar fp-bar-2"></span>
                            <span class="fp-bar fp-bar-3"></span>
                        </div>
                        <span class="fp-logo-name">AFRICODE</span>
                    </a>
                </div>

                <div class="fp-brand-message">
                    <div class="fp-lock-anim">
                        <i class="fas fa-lock-open"></i>
                    </div>
                    <h2 class="fp-brand-title">Retrouvez l'accès à votre compte.</h2>
                    <p class="fp-brand-sub">
                        Pas de panique — cela arrive à tout le monde. Un email sécurisé vous permettra de réinitialiser votre mot de passe en quelques secondes.
                    </p>
                </div>

                <ul class="fp-steps">
                    <li>
                        <div class="fp-step-num">1</div>
                        <div>
                            <strong>Entrez votre email</strong>
                            <span>L'adresse utilisée pour créer votre compte AfriCode</span>
                        </div>
                    </li>
                    <li>
                        <div class="fp-step-num">2</div>
                        <div>
                            <strong>Vérifiez votre boîte mail</strong>
                            <span>Un lien sécurisé vous sera envoyé dans quelques secondes</span>
                        </div>
                    </li>
                    <li>
                        <div class="fp-step-num">3</div>
                        <div>
                            <strong>Créez un nouveau mot de passe</strong>
                            <span>Le lien est valable <strong style="color:#FF8E2A;">60 minutes</strong></span>
                        </div>
                    </li>
                </ul>

                <p class="fp-tagline">No Code, No Future</p>

                <div class="deco-circle deco-1"></div>
                <div class="deco-circle deco-2"></div>
                <div class="deco-circle deco-3"></div>
            </div>
        </div>

        {{-- ═══ PANNEAU DROIT — formulaire ═══ --}}
        <div class="fp-form-panel">
            <div class="fp-form-inner">

                {{-- Logo mobile --}}
                <div class="fp-mobile-logo">
                    <a href="{{ url('/') }}" style="text-decoration:none;">
                        <div class="fp-logo-bars" style="justify-content:center;">
                            <span class="fp-bar fp-bar-1" style="background:#FF8E2A;"></span>
                            <span class="fp-bar fp-bar-2" style="background:#E32D31;"></span>
                            <span class="fp-bar fp-bar-3" style="background:#1EA38B;"></span>
                        </div>
                        <span class="fp-logo-name" style="color:#1a1a2e;">AFRICODE</span>
                    </a>
                </div>

                @if (session('status'))
                    {{-- ─── État succès ─── --}}
                    <div class="fp-success-state">
                        <div class="fp-success-icon">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        <h1 class="fp-title">Email envoyé !</h1>
                        <p class="fp-subtitle">
                            Vérifiez votre boîte de réception. Si vous ne le trouvez pas dans quelques minutes, vérifiez votre dossier <strong>Spams</strong>.
                        </p>
                        <div class="fp-success-msg">
                            <i class="fas fa-check-circle"></i>
                            {{ session('status') }}
                        </div>
                        <div class="fp-success-hints">
                            <div class="fp-hint"><i class="fas fa-clock"></i> Le lien expire dans <strong>60 minutes</strong></div>
                            <div class="fp-hint"><i class="fas fa-folder-open"></i> Pensez à vérifier les <strong>Spams</strong></div>
                        </div>
                        <a href="{{ route('login') }}" class="fp-btn-submit" style="display:flex;align-items:center;justify-content:center;text-decoration:none;">
                            <i class="fas fa-sign-in-alt me-2"></i>Retour à la connexion
                        </a>
                        <p class="fp-resend-hint">
                            Vous n'avez pas reçu l'email ?
                            <a href="{{ route('password.request') }}" class="fp-link">Renvoyer</a>
                        </p>
                    </div>

                @else
                    {{-- ─── Formulaire ─── --}}
                    <h1 class="fp-title">Mot de passe oublié ?</h1>
                    <p class="fp-subtitle">
                        Pas de problème — entrez votre adresse email et nous vous enverrons un lien de réinitialisation.
                    </p>

                    {{-- Erreur --}}
                    @if ($errors->any())
                        <div class="fp-alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" id="fpForm">
                        @csrf

                        <div class="fp-field-group">
                            <label for="email" class="fp-label">Adresse email</label>
                            <div class="fp-field-wrap">
                                <i class="fas fa-envelope fp-field-icon"></i>
                                <input id="email"
                                       type="email"
                                       name="email"
                                       class="fp-input @error('email') fp-input-error @enderror"
                                       value="{{ old('email') }}"
                                       inputmode="email"
                                       autocomplete="username email"
                                       placeholder="vous@exemple.com"
                                       required autofocus>
                            </div>
                            @error('email')
                                <p class="fp-field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="fp-btn-submit" id="fpBtn">
                            <span class="fp-btn-text">
                                <i class="fas fa-paper-plane me-2"></i>Envoyer le lien de réinitialisation
                            </span>
                            <span class="fp-btn-loading" style="display:none;">
                                <i class="fas fa-spinner fa-spin me-2"></i>Envoi en cours…
                            </span>
                        </button>

                        <p class="fp-redirect-hint">
                            <i class="fas fa-shield-alt me-1"></i>
                            Le lien envoyé est valable <strong>60 minutes</strong> et usage unique.
                        </p>
                    </form>

                    <div class="fp-divider"></div>

                    <div class="fp-bottom-links">
                        <a href="{{ route('login') }}" class="fp-back-link">
                            <i class="fas fa-arrow-left me-1"></i>Retour à la connexion
                        </a>
                        <span class="fp-sep">·</span>
                        <a href="{{ route('pages.contact') }}" class="fp-back-link">
                            <i class="fas fa-headset me-1"></i>Contacter le support
                        </a>
                    </div>

                    <p class="fp-no-email-hint">
                        <i class="fas fa-question-circle me-1"></i>
                        Vous n'avez plus accès à votre email ?
                        <a href="{{ route('pages.contact') }}" class="fp-link">Contactez notre support</a>
                    </p>
                @endif

            </div>
        </div>

    </div>
</div>

<style>
/* ─── Layout ──────────────────────────────── */
.fp-page {
    min-height: 100vh;
    background: #f0f2f5;
    display: flex;
    align-items: stretch;
}
.fp-container {
    width: 100%;
    display: flex;
    min-height: calc(100vh - 70px);
}

/* ─── Panneau branding (gauche) ───────────── */
.fp-brand-panel {
    display: none;
    width: 42%;
    background: linear-gradient(145deg, #0d7a63 0%, #1EA38B 50%, #27B371 100%);
    position: relative;
    overflow: hidden;
}
@media (min-width: 992px) { .fp-brand-panel { display: flex; } }

.fp-brand-inner {
    width: 100%;
    padding: 3rem;
    display: flex;
    flex-direction: column;
    position: relative;
    z-index: 2;
}

/* ── Logo AfriCode ── */
.fp-brand-logo { margin-bottom: 2rem; }
.fp-logo-bars {
    display: inline-flex;
    align-items: flex-end;
    gap: 5px;
    height: 32px;
    margin-bottom: 6px;
}
.fp-bar { display: inline-block; border-radius: 3px; }
.fp-bar-1 { width: 7px; height: 15px; background: #FF8E2A; }
.fp-bar-2 { width: 7px; height: 22px; background: #E32D31; }
.fp-bar-3 { width: 7px; height: 32px; background: #4ade80; }
.fp-logo-name {
    font-size: 16px;
    font-weight: 900;
    letter-spacing: .18em;
    color: #fff;
    display: block;
}

/* ── Icône verrou animée ── */
.fp-lock-anim {
    width: 68px;
    height: 68px;
    background: rgba(255,255,255,.18);
    border: 1.5px solid rgba(255,255,255,.3);
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: #fff;
    margin: 0 0 1.5rem;
    animation: lockPulse 2.5s ease-in-out infinite;
}
@keyframes lockPulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(255,255,255,.25); }
    50%       { box-shadow: 0 0 0 14px rgba(255,255,255,.0); }
}

.fp-brand-title {
    font-size: 1.4rem;
    font-weight: 800;
    color: #fff;
    line-height: 1.4;
    margin-bottom: .75rem;
}
.fp-brand-sub {
    font-size: .88rem;
    color: rgba(255,255,255,.8);
    line-height: 1.65;
    margin-bottom: 2rem;
}

/* ── Étapes ── */
.fp-steps { list-style: none; padding: 0; margin: 0 0 auto; display: flex; flex-direction: column; gap: 1rem; }
.fp-steps li { display: flex; align-items: flex-start; gap: .9rem; }
.fp-step-num {
    width: 30px;
    height: 30px;
    background: rgba(255,255,255,.2);
    border: 1.5px solid rgba(255,255,255,.45);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .8rem;
    font-weight: 800;
    color: #fff;
    flex-shrink: 0;
    margin-top: .1rem;
}
.fp-steps li strong { display: block; font-size: .88rem; color: #fff; margin-bottom: .2rem; }
.fp-steps li span   { font-size: .8rem; color: rgba(255,255,255,.7); line-height: 1.4; }

/* ── Tagline ── */
.fp-tagline {
    font-size: .8rem;
    font-weight: 700;
    letter-spacing: .1em;
    color: rgba(255,255,255,.55);
    text-transform: uppercase;
    margin-top: 2rem;
}

/* ── Cercles décoratifs ── */
.deco-circle { position: absolute; border-radius: 50%; }
.deco-1 { width: 340px; height: 340px; bottom: -100px; right: -100px; background: rgba(255,255,255,.06); }
.deco-2 { width: 180px; height: 180px; top:  30px;  right: -55px;  background: rgba(255,255,255,.05); }
.deco-3 { width: 100px; height: 100px; top: 45%;  left: -30px;   background: rgba(0,0,0,.06); }

/* ─── Panneau formulaire (droite) ─────────── */
.fp-form-panel {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1.5rem;
    background: #f0f2f5;
}
.fp-form-inner { width: 100%; max-width: 420px; }

/* ── Logo mobile ── */
.fp-mobile-logo { text-align: center; margin-bottom: 1.5rem; }
@media (min-width: 992px) { .fp-mobile-logo { display: none; } }

/* ─── Titres ──────────────────────────────── */
.fp-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: #1a1a2e;
    margin-bottom: .35rem;
}
.fp-subtitle {
    font-size: .9rem;
    color: #6b7280;
    margin-bottom: 1.5rem;
    line-height: 1.55;
}

/* ─── Alerte erreur ───────────────────────── */
.fp-alert-error {
    display: flex;
    align-items: center;
    gap: .65rem;
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 10px;
    padding: .75rem 1rem;
    font-size: .87rem;
    color: #dc2626;
    margin-bottom: 1.25rem;
}

/* ─── Champ ───────────────────────────────── */
.fp-field-group { margin-bottom: 1.25rem; }
.fp-label { font-size: .83rem; font-weight: 600; color: #374151; margin-bottom: .4rem; display: block; }
.fp-field-wrap { position: relative; }
.fp-field-icon {
    position: absolute;
    left: .9rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: .85rem;
    pointer-events: none;
}
.fp-input {
    width: 100%;
    padding: .75rem .9rem .75rem 2.4rem;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    font-size: .9rem;
    color: #1a1a2e;
    background: #fff;
    transition: border-color .2s, box-shadow .2s;
    outline: none;
}
.fp-input:focus {
    border-color: #1EA38B;
    box-shadow: 0 0 0 3px rgba(30,163,139,.12);
}
.fp-input-error { border-color: #ef4444; }
.fp-input::placeholder { color: #c4c9d4; }
.fp-field-error { font-size: .78rem; color: #ef4444; margin-top: .3rem; margin-bottom: 0; }

/* ─── Bouton submit (charte AfriCode) ──────── */
.fp-btn-submit {
    width: 100%;
    padding: .85rem 1.5rem;
    background: linear-gradient(135deg, #1EA38B 0%, #27B371 100%);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all .25s ease;
    box-shadow: 0 6px 20px rgba(30,163,139,.35);
    margin-bottom: .6rem;
}
.fp-btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(30,163,139,.5);
}
.fp-btn-submit:active  { transform: translateY(0); }
.fp-btn-submit:disabled { opacity: .7; cursor: not-allowed; transform: none; }

/* ─── Hint sécurité ───────────────────────── */
.fp-redirect-hint {
    font-size: .78rem;
    color: #9ca3af;
    text-align: center;
    margin-top: .2rem;
    margin-bottom: 0;
}

/* ─── Divider ─────────────────────────────── */
.fp-divider { height: 1px; background: #e5e7eb; margin: 1.5rem 0 1rem; }

/* ─── Liens bas ───────────────────────────── */
.fp-bottom-links {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .75rem;
    margin-bottom: .75rem;
}
.fp-back-link {
    font-size: .84rem;
    color: #1EA38B;
    text-decoration: none;
    font-weight: 600;
    transition: color .2s;
}
.fp-back-link:hover { color: #0d7a63; }
.fp-sep { color: #d1d5db; font-size: .8rem; }

/* ─── Hint sans email ─────────────────────── */
.fp-no-email-hint {
    font-size: .78rem;
    color: #9ca3af;
    text-align: center;
    margin-top: .25rem;
    margin-bottom: 0;
}
.fp-link { color: #1EA38B; text-decoration: none; font-weight: 600; }
.fp-link:hover { text-decoration: underline; }

/* ─── État succès ─────────────────────────── */
.fp-success-state { text-align: center; }
.fp-success-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #1EA38B, #27B371);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #fff;
    margin: 0 auto 1.5rem;
    animation: successPop .5s ease-out;
    box-shadow: 0 8px 24px rgba(30,163,139,.35);
}
@keyframes successPop {
    0%   { transform: scale(.6); opacity:0; }
    70%  { transform: scale(1.1); }
    100% { transform: scale(1); opacity:1; }
}
.fp-success-msg {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .6rem;
    background: #f0fdf4;
    border: 1.5px solid #bbf7d0;
    border-radius: 10px;
    padding: .85rem 1.25rem;
    font-size: .88rem;
    color: #16a34a;
    margin-bottom: 1.25rem;
    font-weight: 500;
}
.fp-success-msg i { color: #22c55e; font-size: 1.1rem; }
.fp-success-hints {
    display: flex;
    gap: .75rem;
    justify-content: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}
.fp-hint {
    display: flex;
    align-items: center;
    gap: .45rem;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: .5rem .9rem;
    font-size: .8rem;
    color: #6b7280;
}
.fp-hint i { color: #1EA38B; font-size: .85rem; }
.fp-resend-hint { font-size: .82rem; color: #9ca3af; text-align: center; margin: .75rem 0 0; }

/* ─── Responsive ──────────────────────────── */
@media (max-width: 576px) {
    .fp-form-panel { padding: 1.5rem 1rem; }
    .fp-title      { font-size: 1.45rem; }
}
</style>

<script>
document.getElementById('fpForm')?.addEventListener('submit', function () {
    const btn = document.getElementById('fpBtn');
    btn.disabled = true;
    btn.querySelector('.fp-btn-text').style.display    = 'none';
    btn.querySelector('.fp-btn-loading').style.display = 'inline';
});
</script>
@endsection
