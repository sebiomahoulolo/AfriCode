@extends('layouts.layout')
@section('title', 'Paiement échoué — AfriCode')
@section('meta_robots', 'noindex, nofollow')

@section('content')
@php
    $payment = $payment ?? (request('transaction_id')
        ? \App\Models\Payment::where('id', request('transaction_id'))
            ->orWhere('transaction_id', request('transaction_id'))
            ->with(['enrollment.course'])
            ->first()
        : null);
    $infoMsg = session('info');
    $errMsg  = session('error');
@endphp

<div style="min-height:80vh;display:flex;align-items:center;justify-content:center;padding:48px 16px;background:linear-gradient(135deg,#fff8f8 0%,#f8fafc 100%);">
<div style="width:100%;max-width:520px;">

    {{-- Card --}}
    <div style="background:#fff;border-radius:24px;box-shadow:0 12px 48px rgba(0,0,0,.1);overflow:hidden;">

        {{-- Header rouge/orange --}}
        @if($infoMsg)
        <div style="background:linear-gradient(135deg,#fea837,#f59e0b);padding:36px 32px;text-align:center;">
        @else
        <div style="background:linear-gradient(135deg,#fa1c39,#ff6b6b);padding:36px 32px;text-align:center;">
        @endif
            {{-- Logo --}}
            <div style="display:flex;align-items:center;justify-content:center;gap:8px;margin-bottom:20px;">
                <div style="display:flex;gap:3px;align-items:flex-end;">
                    <span style="display:block;width:5px;height:14px;border-radius:3px;background:#fea837;opacity:.9;"></span>
                    <span style="display:block;width:5px;height:20px;border-radius:3px;background:rgba(255,255,255,.8);"></span>
                    <span style="display:block;width:5px;height:26px;border-radius:3px;background:#11c06d;opacity:.9;"></span>
                </div>
                <span style="font-size:1.1rem;font-weight:800;color:#fff;letter-spacing:.3px;">Afri<span style="color:rgba(255,255,255,.75);">Code</span></span>
            </div>
            {{-- Icône --}}
            <div style="width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,.2);border:3px solid rgba(255,255,255,.5);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                @if($infoMsg)
                    <i class="fas fa-hourglass-half" style="font-size:2rem;color:#fff;"></i>
                @else
                    <i class="fas fa-times" style="font-size:2rem;color:#fff;"></i>
                @endif
            </div>
            @if($infoMsg)
                <h1 style="font-size:1.3rem;font-weight:800;color:#fff;margin:0 0 6px;">Paiement en cours de traitement</h1>
                <p style="font-size:.88rem;color:rgba(255,255,255,.85);margin:0;">Votre paiement est en attente de confirmation.</p>
            @else
                <h1 style="font-size:1.3rem;font-weight:800;color:#fff;margin:0 0 6px;">Paiement non abouti</h1>
                <p style="font-size:.88rem;color:rgba(255,255,255,.85);margin:0;">Ne vous inquiétez pas — vous n'avez pas été débité.</p>
            @endif
        </div>

        {{-- Body --}}
        <div style="padding:28px 32px;">

            {{-- Message spécifique --}}
            @if($infoMsg)
            <div style="background:rgba(254,168,55,.08);border:1.5px solid rgba(254,168,55,.25);border-radius:12px;padding:14px 16px;margin-bottom:20px;display:flex;align-items:flex-start;gap:10px;">
                <i class="fas fa-info-circle" style="color:#fea837;margin-top:2px;flex-shrink:0;"></i>
                <p style="font-size:.85rem;color:#374151;margin:0;line-height:1.6;">{{ $infoMsg }}</p>
            </div>
            @elseif($errMsg)
            <div style="background:rgba(250,28,57,.06);border:1.5px solid rgba(250,28,57,.2);border-radius:12px;padding:14px 16px;margin-bottom:20px;display:flex;align-items:flex-start;gap:10px;">
                <i class="fas fa-exclamation-circle" style="color:#fa1c39;margin-top:2px;flex-shrink:0;"></i>
                <p style="font-size:.85rem;color:#374151;margin:0;line-height:1.6;">{{ $errMsg }}</p>
            </div>
            @endif

            @if($payment && $payment->enrollment && $payment->enrollment->course)
            {{-- Cours concerné --}}
            <div style="background:#f9fafb;border-radius:12px;padding:14px 18px;margin-bottom:20px;display:flex;align-items:center;gap:14px;">
                @if($payment->enrollment->course->cover_image)
                    <img src="{{ asset('storage/' . $payment->enrollment->course->cover_image) }}"
                         style="width:48px;height:48px;border-radius:10px;object-fit:cover;flex-shrink:0;"
                         alt="{{ $payment->enrollment->course->title }}">
                @else
                    <div style="width:48px;height:48px;border-radius:10px;background:linear-gradient(135deg,#fa1c39,#ff6b6b);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-book" style="color:#fff;"></i>
                    </div>
                @endif
                <div>
                    <div style="font-size:.88rem;font-weight:700;color:#111827;">{{ $payment->enrollment->course->title }}</div>
                    <div style="font-size:.78rem;color:#6b7280;margin-top:2px;">
                        {{ number_format($payment->enrollment->course->price, 0, ',', ' ') }} {{ $payment->enrollment->course->currency ?? 'XOF' }}
                    </div>
                </div>
            </div>
            @endif

            @if(!$infoMsg)
            {{-- Raisons possibles --}}
            <div style="border:1.5px solid #f0f0f0;border-radius:12px;padding:16px 18px;margin-bottom:20px;">
                <div style="font-size:.78rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px;">
                    <i class="fas fa-lightbulb" style="color:#fea837;margin-right:5px;"></i>Causes possibles
                </div>
                <div style="display:flex;flex-direction:column;gap:7px;">
                    @foreach([
                        'Solde insuffisant sur le compte Mobile Money / carte',
                        'Transaction annulée avant validation du code OTP',
                        'Problème de réseau temporaire pendant le paiement',
                        'Plafond de transaction journalier atteint',
                        'Numéro de téléphone ou informations incorrects',
                    ] as $r)
                    <div style="display:flex;align-items:center;gap:8px;font-size:.82rem;color:#6b7280;">
                        <i class="fas fa-circle" style="font-size:.4rem;color:#d1d5db;flex-shrink:0;"></i>{{ $r }}
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Actions --}}
            <div style="display:flex;flex-direction:column;gap:10px;">
                @if($payment && $payment->enrollment && $payment->enrollment->course)
                <a href="{{ route('enrollment.show', $payment->enrollment->course) }}"
                   style="display:block;text-align:center;background:linear-gradient(135deg,#11c06d,#239e79);color:#fff;padding:13px 20px;border-radius:12px;font-size:.9rem;font-weight:800;text-decoration:none;box-shadow:0 4px 14px rgba(17,192,109,.3);">
                    <i class="fas fa-redo" style="margin-right:8px;"></i>Réessayer le paiement
                </a>
                @endif

                <a href="{{ route('courses.index') }}"
                   style="display:block;text-align:center;background:rgba(17,192,109,.06);border:1.5px solid rgba(17,192,109,.2);color:#11c06d;padding:11px 20px;border-radius:12px;font-size:.88rem;font-weight:700;text-decoration:none;">
                    <i class="fas fa-th-large" style="margin-right:8px;"></i>Voir tous les cours
                </a>

                <a href="{{ route('pages.contact') }}"
                   style="display:block;text-align:center;background:#f9fafb;border:1.5px solid #e5e7eb;color:#6b7280;padding:11px 20px;border-radius:12px;font-size:.85rem;font-weight:600;text-decoration:none;">
                    <i class="fas fa-headset" style="margin-right:8px;"></i>Contacter le support
                </a>
            </div>

            {{-- Conseils --}}
            @if(!$infoMsg)
            <div style="margin-top:20px;background:rgba(17,192,109,.05);border-radius:12px;padding:14px 18px;">
                <div style="font-size:.78rem;font-weight:700;color:#11c06d;margin-bottom:8px;">
                    <i class="fas fa-check-double" style="margin-right:5px;"></i>Pour réussir votre prochain paiement
                </div>
                <div style="display:flex;flex-direction:column;gap:6px;">
                    @foreach([
                        'Vérifiez votre solde avant de lancer le paiement',
                        'Ayez votre téléphone à portée pour valider le code OTP',
                        'Assurez-vous d\'une connexion internet stable',
                    ] as $t)
                    <div style="display:flex;align-items:center;gap:8px;font-size:.8rem;color:#374151;">
                        <i class="fas fa-check-circle" style="color:#239e79;flex-shrink:0;font-size:.75rem;"></i>{{ $t }}
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

</div>
</div>
@endsection
