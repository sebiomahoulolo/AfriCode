@extends('layouts.layout')
@section('title', 'Paiement réussi — AfriCode')
@section('meta_robots', 'noindex, nofollow')

@section('content')
<div style="min-height:80vh;display:flex;align-items:center;justify-content:center;padding:48px 16px;background:linear-gradient(135deg,#f0fffe 0%,#f8fafc 100%);">
<div style="width:100%;max-width:520px;">

    {{-- Confetti animation --}}
    <div id="confettiWrap" style="position:fixed;inset:0;pointer-events:none;z-index:9999;overflow:hidden;"></div>

    {{-- Card --}}
    <div style="background:#fff;border-radius:24px;box-shadow:0 12px 48px rgba(0,0,0,.1);overflow:hidden;">

        {{-- Header vert --}}
        <div style="background:linear-gradient(135deg,#11c06d,#239e79);padding:36px 32px;text-align:center;position:relative;overflow:hidden;">
            {{-- Logo --}}
            <div style="display:flex;align-items:center;justify-content:center;gap:8px;margin-bottom:20px;">
                <div style="display:flex;gap:3px;align-items:flex-end;">
                    <span style="display:block;width:5px;height:14px;border-radius:3px;background:#fea837;opacity:.9;"></span>
                    <span style="display:block;width:5px;height:20px;border-radius:3px;background:#fa1c39;opacity:.9;"></span>
                    <span style="display:block;width:5px;height:26px;border-radius:3px;background:#fff;"></span>
                </div>
                <span style="font-size:1.1rem;font-weight:800;color:#fff;letter-spacing:.3px;">Afri<span style="color:rgba(255,255,255,.75);">Code</span></span>
            </div>
            {{-- Cercle succès --}}
            <div style="width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,.2);border:3px solid rgba(255,255,255,.5);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;animation:popIn .5s cubic-bezier(.175,.885,.32,1.275);">
                <i class="fas fa-check" style="font-size:2rem;color:#fff;"></i>
            </div>
            <h1 style="font-size:1.4rem;font-weight:800;color:#fff;margin:0 0 6px;">Paiement réussi !</h1>
            <p style="font-size:.88rem;color:rgba(255,255,255,.8);margin:0;">Votre inscription est confirmée — bienvenue dans le cours.</p>
        </div>

        {{-- Body --}}
        <div style="padding:28px 32px;">

            @if($payment && $payment->enrollment && $payment->enrollment->course)
            {{-- Cours --}}
            <div style="background:linear-gradient(135deg,rgba(17,192,109,.06),rgba(35,158,121,.04));border:1.5px solid rgba(17,192,109,.15);border-radius:14px;padding:16px 18px;margin-bottom:20px;display:flex;align-items:center;gap:14px;">
                @if($payment->enrollment->course->cover_image)
                    <img src="{{ asset('storage/' . $payment->enrollment->course->cover_image) }}"
                         alt="{{ $payment->enrollment->course->title }}"
                         style="width:56px;height:56px;border-radius:10px;object-fit:cover;flex-shrink:0;">
                @else
                    <div style="width:56px;height:56px;border-radius:10px;background:linear-gradient(135deg,#11c06d,#239e79);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-book" style="color:#fff;font-size:1.3rem;"></i>
                    </div>
                @endif
                <div>
                    <div style="font-size:.92rem;font-weight:800;color:#111827;margin-bottom:2px;">{{ $payment->enrollment->course->title }}</div>
                    <div style="font-size:.78rem;color:#6b7280;">{{ $payment->enrollment->course->category->name ?? 'Formation' }}</div>
                </div>
            </div>

            {{-- Détails paiement --}}
            <div style="background:#f9fafb;border-radius:12px;padding:14px 18px;margin-bottom:20px;">
                <div style="font-size:.78rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px;">Détails du paiement</div>
                <div style="display:flex;flex-direction:column;gap:8px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:.83rem;color:#6b7280;">Montant payé</span>
                        <span style="font-size:.92rem;font-weight:800;color:#239e79;">{{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency ?? 'XOF' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:.83rem;color:#6b7280;">Méthode</span>
                        <span style="font-size:.83rem;font-weight:600;color:#374151;">
                            @if($payment->payment_gateway === 'moneroo')
                                <i class="fas fa-mobile-alt" style="color:#fea837;margin-right:5px;"></i>Mobile Money
                            @elseif($payment->payment_gateway === 'stripe')
                                <i class="fab fa-cc-stripe" style="color:#6772e5;margin-right:5px;"></i>Carte bancaire
                            @else
                                <i class="fas fa-credit-card" style="margin-right:5px;"></i>{{ ucfirst($payment->payment_gateway ?? '') }}
                            @endif
                        </span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:.83rem;color:#6b7280;">Date</span>
                        <span style="font-size:.83rem;font-weight:600;color:#374151;">{{ ($payment->paid_at ?? $payment->created_at)->format('d/m/Y à H:i') }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:.83rem;color:#6b7280;">Statut</span>
                        <span style="background:rgba(35,158,121,.1);color:#239e79;font-size:.75rem;font-weight:800;padding:3px 10px;border-radius:10px;">
                            <i class="fas fa-check-circle" style="margin-right:4px;"></i>Confirmé
                        </span>
                    </div>
                </div>
            </div>
            @endif

            {{-- Prochaines étapes --}}
            <div style="background:linear-gradient(135deg,rgba(17,192,109,.06),rgba(35,158,121,.04));border-radius:12px;padding:16px 18px;margin-bottom:24px;">
                <div style="font-size:.78rem;font-weight:700;color:#11c06d;text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px;">
                    <i class="fas fa-rocket" style="margin-right:5px;"></i>Prochaines étapes
                </div>
                <div style="display:flex;flex-direction:column;gap:8px;">
                    <div style="display:flex;align-items:center;gap:10px;font-size:.82rem;color:#374151;">
                        <i class="fas fa-check-circle" style="color:#239e79;flex-shrink:0;"></i>
                        Accédez immédiatement à tout le contenu du cours
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;font-size:.82rem;color:#374151;">
                        <i class="fas fa-check-circle" style="color:#239e79;flex-shrink:0;"></i>
                        Un email de confirmation vous a été envoyé
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;font-size:.82rem;color:#374151;">
                        <i class="fas fa-check-circle" style="color:#239e79;flex-shrink:0;"></i>
                        Complétez le cours pour obtenir votre certificat
                    </div>
                </div>
            </div>

            {{-- CTA --}}
            <a href="{{ route('dashboard') }}"
               style="display:block;width:100%;text-align:center;background:linear-gradient(135deg,#11c06d,#239e79);color:#fff;border:none;padding:14px 20px;border-radius:12px;font-size:.95rem;font-weight:800;text-decoration:none;box-shadow:0 4px 16px rgba(17,192,109,.35);transition:opacity .2s;"
               onmouseover="this.style.opacity='.88'" onmouseout="this.style.opacity='1'">
                <i class="fas fa-play" style="margin-right:8px;"></i>Commencer le cours maintenant
            </a>

            <div style="text-align:center;margin-top:16px;">
                <a href="{{ route('courses.index') }}" style="font-size:.8rem;color:#9ca3af;text-decoration:none;">
                    <i class="fas fa-th-large" style="margin-right:4px;"></i>Voir tous les cours
                </a>
            </div>
        </div>
    </div>

</div>
</div>

<style>
@keyframes popIn {
    0% { transform:scale(.3); opacity:0; }
    60% { transform:scale(1.1); }
    100% { transform:scale(1); opacity:1; }
}
</style>

<script>
(function() {
    /* Confetti simple */
    var wrap = document.getElementById('confettiWrap');
    var colors = ['#11c06d','#239e79','#fea837','#fa1c39','#11c06d','#f59e0b'];
    for (var i = 0; i < 80; i++) {
        (function(i) {
            setTimeout(function() {
                var c = document.createElement('div');
                var size = Math.random() * 8 + 5;
                c.style.cssText = [
                    'position:absolute',
                    'width:' + size + 'px',
                    'height:' + size + 'px',
                    'background:' + colors[Math.floor(Math.random()*colors.length)],
                    'border-radius:' + (Math.random() > .5 ? '50%' : '2px'),
                    'left:' + Math.random() * 100 + 'vw',
                    'top:-10px',
                    'opacity:.9',
                    'transition:all ' + (Math.random() * 2 + 1.5) + 's ease-in'
                ].join(';');
                wrap.appendChild(c);
                setTimeout(function() {
                    c.style.top = (Math.random() * 80 + 80) + 'vh';
                    c.style.left = (parseFloat(c.style.left) + (Math.random() * 100 - 50)) + 'px';
                    c.style.opacity = '0';
                    c.style.transform = 'rotate(' + (Math.random() * 360) + 'deg)';
                }, 50);
                setTimeout(function() { c.remove(); }, 4000);
            }, i * 30);
        })(i);
    }
})();
</script>

@if($payment && $payment->enrollment && $payment->enrollment->course && config('tracking.enabled'))
@php
    $trackPayload = [
        'value'        => $payment->amount,
        'currency'     => $payment->currency ?? 'XOF',
        'content_name' => $payment->enrollment->course->title,
        'content_ids'  => [(string) $payment->enrollment->course->id],
        'content_type' => 'product',
        'num_items'    => 1,
    ];
@endphp
<script>
(function() {
    var payload = @json($trackPayload);
    if (window.AfriCodeTracking && window.AfriCodeTracking.track) {
        window.AfriCodeTracking.track('purchase', payload);
    }
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({ event: 'purchase', ...payload });
})();
</script>
@endif
@endsection
