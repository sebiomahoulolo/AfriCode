@extends('layouts.layout')
@section('title', 'Nouveau mot de passe — AfriCode')
@section('meta_robots', 'noindex, nofollow')

@section('content')
<div style="min-height:80vh;display:flex;align-items:center;justify-content:center;padding:40px 16px;background:#f8fafc;">
<div style="width:100%;max-width:480px;">

    {{-- Card --}}
    <div style="background:#fff;border-radius:20px;box-shadow:0 8px 40px rgba(0,0,0,.1);overflow:hidden;">

        {{-- Header --}}
        <div style="background:linear-gradient(135deg,#1a1a2e,#0f3460);padding:28px 32px 24px;text-align:center;">
            <div style="display:flex;align-items:center;justify-content:center;gap:10px;margin-bottom:16px;">
                <div style="display:flex;gap:3px;align-items:flex-end;">
                    <span style="display:block;width:5px;height:14px;border-radius:3px;background:#fea837;"></span>
                    <span style="display:block;width:5px;height:20px;border-radius:3px;background:#fa1c39;"></span>
                    <span style="display:block;width:5px;height:26px;border-radius:3px;background:#11c06d;"></span>
                </div>
                <span style="font-size:1.2rem;font-weight:800;color:#fff;letter-spacing:.3px;">Afri<span style="color:#11c06d;">Code</span></span>
            </div>
            <div style="width:52px;height:52px;border-radius:15px;background:rgba(254,168,55,.15);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                <i class="fas fa-key" style="color:#fea837;font-size:1.3rem;"></i>
            </div>
            <h1 style="font-size:1.15rem;font-weight:800;color:#fff;margin:0 0 4px;">Choisissez un nouveau mot de passe</h1>
            <p style="font-size:.78rem;color:rgba(255,255,255,.55);margin:0;">Pour votre compte AfriCode</p>
        </div>

        {{-- Body --}}
        <div style="padding:28px 32px;">
            <form method="POST" action="{{ route('password.store') }}" id="resetForm" novalidate>
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                {{-- Email --}}
                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:6px;">
                        Email
                    </label>
                    <div style="display:flex;border:1.5px solid #e5e7eb;border-radius:10px;overflow:hidden;background:#f9fafb;">
                        <div style="padding:11px 14px;background:#f3f4f6;border-right:1px solid #e5e7eb;color:#9ca3af;font-size:.85rem;">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email', $request->email) }}"
                               style="flex:1;border:none;background:transparent;padding:11px 14px;font-size:.88rem;color:#6b7280;outline:none;"
                               readonly>
                    </div>
                    @error('email')
                        <p style="font-size:.75rem;color:#fa1c39;margin-top:4px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nouveau mot de passe --}}
                <div style="margin-bottom:16px;" id="pwField">
                    <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:6px;">
                        Nouveau mot de passe <span style="color:#fa1c39;">*</span>
                    </label>
                    <div style="display:flex;border:1.5px solid #e5e7eb;border-radius:10px;overflow:hidden;background:#fff;transition:border-color .2s;" id="pwWrap">
                        <div style="padding:11px 14px;background:#f3f4f6;border-right:1px solid #e5e7eb;color:#fea837;font-size:.85rem;">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" name="password" id="password"
                               style="flex:1;border:none;background:transparent;padding:11px 14px;font-size:.88rem;color:#111827;outline:none;"
                               autocomplete="new-password" required>
                        <button type="button" id="togglePw"
                                style="padding:11px 14px;background:none;border:none;color:#9ca3af;cursor:pointer;font-size:.85rem;"
                                title="Voir/Masquer">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>

                    {{-- Barre de force --}}
                    <div style="margin-top:8px;">
                        <div style="height:5px;border-radius:4px;background:#f3f4f6;overflow:hidden;">
                            <div id="strengthBar" style="height:100%;width:0%;border-radius:4px;transition:width .3s,background .3s;"></div>
                        </div>
                        <p id="strengthLabel" style="font-size:.72rem;color:#9ca3af;margin:4px 0 0;font-weight:600;"></p>
                    </div>

                    {{-- Conditions --}}
                    <div style="margin-top:10px;display:grid;grid-template-columns:1fr 1fr;gap:4px 12px;" id="conditions">
                        <div class="pw-cond" data-rule="length" style="display:flex;align-items:center;gap:5px;font-size:.72rem;color:#9ca3af;">
                            <i class="fas fa-circle" style="font-size:.45rem;"></i> 8 caractères minimum
                        </div>
                        <div class="pw-cond" data-rule="upper" style="display:flex;align-items:center;gap:5px;font-size:.72rem;color:#9ca3af;">
                            <i class="fas fa-circle" style="font-size:.45rem;"></i> Une majuscule
                        </div>
                        <div class="pw-cond" data-rule="digit" style="display:flex;align-items:center;gap:5px;font-size:.72rem;color:#9ca3af;">
                            <i class="fas fa-circle" style="font-size:.45rem;"></i> Un chiffre
                        </div>
                        <div class="pw-cond" data-rule="special" style="display:flex;align-items:center;gap:5px;font-size:.72rem;color:#9ca3af;">
                            <i class="fas fa-circle" style="font-size:.45rem;"></i> Un caractère spécial
                        </div>
                    </div>

                    @error('password')
                        <p style="font-size:.75rem;color:#fa1c39;margin-top:6px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirmer --}}
                <div style="margin-bottom:24px;">
                    <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:6px;">
                        Confirmer le mot de passe <span style="color:#fa1c39;">*</span>
                    </label>
                    <div style="display:flex;border:1.5px solid #e5e7eb;border-radius:10px;overflow:hidden;background:#fff;transition:border-color .2s;" id="confWrap">
                        <div style="padding:11px 14px;background:#f3f4f6;border-right:1px solid #e5e7eb;color:#fea837;font-size:.85rem;">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               style="flex:1;border:none;background:transparent;padding:11px 14px;font-size:.88rem;color:#111827;outline:none;"
                               autocomplete="new-password" required>
                        <button type="button" id="toggleConf"
                                style="padding:11px 14px;background:none;border:none;color:#9ca3af;cursor:pointer;font-size:.85rem;"
                                title="Voir/Masquer">
                            <i class="fas fa-eye" id="eyeIconConf"></i>
                        </button>
                    </div>
                    {{-- Feedback correspondance --}}
                    <div id="matchFeedback" style="display:none;margin-top:6px;font-size:.75rem;font-weight:700;display:flex;align-items:center;gap:5px;"></div>
                </div>

                {{-- Submit --}}
                <button type="submit" id="submitBtn"
                    style="width:100%;background:linear-gradient(135deg,#11c06d,#239e79);color:#fff;border:none;
                           padding:13px 20px;border-radius:12px;font-size:.92rem;font-weight:800;cursor:pointer;
                           box-shadow:0 4px 14px rgba(17,192,109,.3);transition:opacity .2s,transform .15s;">
                    <i class="fas fa-shield-alt me-2"></i> Réinitialiser le mot de passe
                </button>
            </form>

            {{-- Back to login --}}
            <div style="text-align:center;margin-top:20px;">
                <a href="{{ route('login') }}" style="font-size:.8rem;color:#9ca3af;text-decoration:none;">
                    <i class="fas fa-arrow-left me-1"></i> Retour à la connexion
                </a>
            </div>
        </div>
    </div>

</div>
</div>

<script>
(function () {
    var pwInput   = document.getElementById('password');
    var confInput = document.getElementById('password_confirmation');
    var bar       = document.getElementById('strengthBar');
    var label     = document.getElementById('strengthLabel');
    var matchDiv  = document.getElementById('matchFeedback');
    var pwWrap    = document.getElementById('pwWrap');
    var confWrap  = document.getElementById('confWrap');

    /* ── Toggle visibility ────────────── */
    function makeToggle(btnId, inputEl, iconId) {
        document.getElementById(btnId).addEventListener('click', function () {
            var show = inputEl.type === 'password';
            inputEl.type = show ? 'text' : 'password';
            var icon = document.getElementById(iconId);
            icon.className = show ? 'fas fa-eye-slash' : 'fas fa-eye';
        });
    }
    makeToggle('togglePw',   pwInput,   'eyeIcon');
    makeToggle('toggleConf', confInput, 'eyeIconConf');

    /* ── Rules ────────────────────────── */
    var rules = {
        length:  function (v) { return v.length >= 8; },
        upper:   function (v) { return /[A-Z]/.test(v); },
        digit:   function (v) { return /[0-9]/.test(v); },
        special: function (v) { return /[^A-Za-z0-9]/.test(v); },
    };

    var strengthColors = ['#fa1c39', '#fea837', '#f59e0b', '#239e79', '#11c06d'];
    var strengthLabels = ['Très faible', 'Faible', 'Moyen', 'Fort', 'Très fort'];

    /* ── Update strength ──────────────── */
    function updateStrength() {
        var val   = pwInput.value;
        var score = 0;

        document.querySelectorAll('.pw-cond').forEach(function (el) {
            var ok = rules[el.dataset.rule](val);
            if (ok) score++;
            el.style.color  = ok ? '#239e79' : '#9ca3af';
            var icon = el.querySelector('i');
            icon.className = ok ? 'fas fa-check-circle' : 'fas fa-circle';
            icon.style.fontSize = ok ? '.75rem' : '.45rem';
        });

        var pct = score === 0 ? 0 : Math.round((score / 4) * 100);
        bar.style.width      = pct + '%';
        bar.style.background = score === 0 ? '#f3f4f6' : strengthColors[score - 1];
        label.textContent    = val.length === 0 ? '' : strengthLabels[score - 1] || '';
        label.style.color    = score === 0 ? '#9ca3af' : strengthColors[score - 1];

        pwWrap.style.borderColor = val.length === 0
            ? '#e5e7eb'
            : score >= 3 ? '#239e79' : score >= 2 ? '#fea837' : '#fa1c39';

        checkMatch();
    }

    /* ── Check match ──────────────────── */
    function checkMatch() {
        var pw   = pwInput.value;
        var conf = confInput.value;

        if (conf.length === 0) {
            matchDiv.style.display = 'none';
            confWrap.style.borderColor = '#e5e7eb';
            return;
        }

        matchDiv.style.display = 'flex';

        if (pw === conf) {
            matchDiv.innerHTML = '<i class="fas fa-check-circle" style="color:#239e79;"></i> <span style="color:#239e79;">Les mots de passe correspondent</span>';
            confWrap.style.borderColor = '#239e79';
        } else {
            matchDiv.innerHTML = '<i class="fas fa-times-circle" style="color:#fa1c39;"></i> <span style="color:#fa1c39;">Les mots de passe ne correspondent pas</span>';
            confWrap.style.borderColor = '#fa1c39';
        }
    }

    /* ── Events ───────────────────────── */
    pwInput.addEventListener('input', updateStrength);
    confInput.addEventListener('input', checkMatch);

    /* ── Prevent submit if mismatch ───── */
    document.getElementById('resetForm').addEventListener('submit', function (e) {
        if (pwInput.value !== confInput.value) {
            e.preventDefault();
            confWrap.style.borderColor = '#fa1c39';
            matchDiv.style.display = 'flex';
            matchDiv.innerHTML = '<i class="fas fa-times-circle" style="color:#fa1c39;"></i> <span style="color:#fa1c39;">Les mots de passe ne correspondent pas — vérifiez avant de continuer.</span>';
            confInput.focus();
        }
    });

    /* ── Focus glow ───────────────────── */
    [pwWrap, confWrap].forEach(function (wrap) {
        var input = wrap.querySelector('input');
        input.addEventListener('focus', function () {
            if (!wrap.style.borderColor || wrap.style.borderColor === 'rgb(229, 231, 235)') {
                wrap.style.borderColor = '#11c06d';
                wrap.style.boxShadow = '0 0 0 3px rgba(17,192,109,.1)';
            }
        });
        input.addEventListener('blur', function () {
            wrap.style.boxShadow = 'none';
        });
    });
})();
</script>
@endsection
