@extends('layouts.layout')

@section('title', 'Confirmer le mot de passe — AfriCode')
@section('meta_robots', 'noindex, nofollow')

@section('content')
<div style="min-height:80vh;display:flex;align-items:center;justify-content:center;padding:40px 16px;background:#f0f2f5;">
<div style="width:100%;max-width:440px;">

    <div style="background:#fff;border-radius:20px;box-shadow:0 8px 40px rgba(0,0,0,.1);overflow:hidden;">

        {{-- Header --}}
        <div style="background:linear-gradient(135deg,#1EA38B,#27B371);padding:28px 32px 24px;text-align:center;">
            <div style="display:flex;align-items:flex-end;justify-content:center;gap:5px;margin-bottom:6px;">
                <span style="display:block;width:7px;height:15px;border-radius:3px;background:#FF8E2A;"></span>
                <span style="display:block;width:7px;height:22px;border-radius:3px;background:#E32D31;"></span>
                <span style="display:block;width:7px;height:30px;border-radius:3px;background:#4ade80;"></span>
            </div>
            <span style="font-size:15px;font-weight:900;letter-spacing:.18em;color:#fff;display:block;margin-bottom:16px;">AFRICODE</span>
            <div style="width:52px;height:52px;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                <i class="fas fa-shield-alt" style="color:#fff;font-size:1.3rem;"></i>
            </div>
            <h1 style="font-size:1.1rem;font-weight:800;color:#fff;margin:0 0 4px;">Zone sécurisée</h1>
            <p style="font-size:.78rem;color:rgba(255,255,255,.8);margin:0;">Confirmez votre mot de passe pour continuer</p>
        </div>

        {{-- Body --}}
        <div style="padding:28px 32px;">

            <p style="font-size:.88rem;color:#6b7280;margin-bottom:20px;line-height:1.6;text-align:center;">
                Cette section est sécurisée. Veuillez confirmer votre mot de passe avant de continuer.
            </p>

            @if ($errors->any())
                <div style="display:flex;align-items:center;gap:.65rem;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:.75rem 1rem;font-size:.87rem;color:#dc2626;margin-bottom:1.25rem;">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.confirm') }}" id="confirmForm">
                @csrf

                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:.83rem;font-weight:600;color:#374151;margin-bottom:6px;">
                        Mot de passe
                    </label>
                    <div style="position:relative;">
                        <span style="position:absolute;left:.9rem;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:.85rem;pointer-events:none;">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" id="password" name="password"
                               style="width:100%;padding:.75rem 2.8rem .75rem 2.4rem;border:1.5px solid #e5e7eb;border-radius:10px;font-size:.9rem;color:#1a1a2e;background:#fff;outline:none;transition:border-color .2s,box-shadow .2s;"
                               autocomplete="current-password" required autofocus
                               onfocus="this.style.borderColor='#1EA38B';this.style.boxShadow='0 0 0 3px rgba(30,163,139,.12)'"
                               onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
                        <button type="button" tabindex="-1" onclick="togglePwd('password','eye_confirm_pwd')"
                                style="position:absolute;top:50%;right:0.75rem;transform:translateY(-50%);background:none;border:none;padding:0;line-height:1;color:#6c757d;cursor:pointer;">
                            <i class="fas fa-eye" id="eye_confirm_pwd"></i>
                        </button>
                    </div>
                    @error('password')
                        <p style="font-size:.75rem;color:#E32D31;margin-top:4px;">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    style="width:100%;background:linear-gradient(135deg,#1EA38B,#27B371);color:#fff;border:none;padding:.85rem 1.5rem;border-radius:12px;font-size:1rem;font-weight:700;cursor:pointer;box-shadow:0 6px 20px rgba(30,163,139,.35);transition:all .25s ease;"
                    onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 10px 28px rgba(30,163,139,.5)'"
                    onmouseout="this.style.transform='none';this.style.boxShadow='0 6px 20px rgba(30,163,139,.35)'">
                    <i class="fas fa-check-circle me-2"></i>Confirmer
                </button>
            </form>

            <div style="text-align:center;margin-top:20px;">
                <a href="{{ route('login') }}" style="font-size:.8rem;color:#9ca3af;text-decoration:none;">
                    <i class="fas fa-arrow-left me-1"></i> Retour à la connexion
                </a>
            </div>
        </div>
    </div>

</div>
</div>
@endsection

@push('scripts')
<script>
function togglePwd(inputId, eyeId) {
    const input = document.getElementById(inputId);
    const eye   = document.getElementById(eyeId);
    if (input.type === 'password') { input.type = 'text'; eye.classList.replace('fa-eye','fa-eye-slash'); }
    else { input.type = 'password'; eye.classList.replace('fa-eye-slash','fa-eye'); }
}
</script>
@endpush
