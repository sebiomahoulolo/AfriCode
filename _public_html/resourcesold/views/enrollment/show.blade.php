@extends('layouts.layout')

@section('title', 'Inscription — ' . $course->title . ' | AfriCode')

@section('content')
<div class="enroll-page">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-xl-6">

                {{-- Alerte session --}}
                @if(session('info'))
                <div class="alert alert-info d-flex align-items-center gap-3 mb-4 rounded-3 border-0 shadow-sm">
                    <i class="fas fa-info-circle fa-lg flex-shrink-0"></i>
                    <span>{{ session('info') }}</span>
                </div>
                @endif

                {{-- Carte récapitulatif du cours --}}
                <div class="course-recap mb-4">
                    <div class="d-flex align-items-center gap-3">
                        @if($course->cover_image_path)
                            <img src="{{ asset($course->cover_image_path) }}"
                                 alt="{{ $course->title }}"
                                 class="course-thumb">
                        @else
                            <div class="course-thumb-placeholder">
                                <i class="fas fa-book-open"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge-level">{{ ucfirst($course->level) }}</span>
                                @if($course->is_certifying)
                                    <span class="badge-cert"><i class="fas fa-certificate me-1"></i>Certifiant</span>
                                @endif
                            </div>
                            <h2 class="course-recap-title">{{ $course->title }}</h2>
                            <p class="course-recap-instructor">
                                <i class="fas fa-user-tie me-1"></i>
                                {{ $course->formateur->first_name }} {{ $course->formateur->last_name }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Contenu selon cours gratuit ou payant --}}
                @if($course->price == 0)

                    {{-- CAS GRATUIT --}}
                    <div class="checkout-card text-center">
                        <div class="free-badge-large mb-3">
                            <i class="fas fa-gift"></i>
                        </div>
                        <h3 class="fw-bold mb-1">Formation 100% gratuite</h3>
                        <p class="text-muted mb-4">Accédez immédiatement à tout le contenu sans frais.</p>

                        <form action="{{ route('enrollment.process-payment', $course) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-enroll-free w-100" id="enrollBtn">
                                <i class="fas fa-rocket me-2"></i>Commencer la formation
                            </button>
                        </form>
                    </div>

                @else

                    {{-- CAS PAYANT --}}
                    <div class="checkout-card">

                        {{-- Récapitulatif prix --}}
                        <div class="price-summary mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price-label">Total à payer</span>
                                <span class="price-amount">
                                    {{ number_format($course->price, 0, ',', ' ') }}
                                    <span class="price-currency">{{ $course->currency ?? 'XOF' }}</span>
                                </span>
                            </div>
                            <div class="price-divider mt-3"></div>
                        </div>

                        {{-- Ce que vous obtenez --}}
                        <ul class="benefits-list mb-4">
                            <li><i class="fas fa-infinity"></i> Accès à vie au cours</li>
                            @if($course->modules_count ?? $course->modules->count())
                                <li><i class="fas fa-layer-group"></i> {{ $course->modules_count ?? $course->modules->count() }} modules de formation</li>
                            @endif
                            @if($course->is_certifying)
                                <li><i class="fas fa-certificate"></i> Certificat de réussite inclus</li>
                            @endif
                            <li><i class="fas fa-mobile-alt"></i> Accessible sur mobile et PC</li>
                        </ul>

                        {{-- Formulaire avec hidden input --}}
                        <form action="{{ route('enrollment.process-payment', $course) }}" method="POST" id="enrollmentForm">
                            @csrf
                            <input type="hidden" name="payment_method" value="moneroo">

                            {{-- Méthode de paiement affichée (non modifiable) --}}
                            <div class="payment-method-display mb-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="payment-method-icon">
                                        <i class="fas fa-mobile-alt"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">Mobile Money &amp; Carte bancaire</div>
                                        <small class="text-muted">MTN, Moov, Wave, Orange · Visa, Mastercard</small>
                                    </div>
                                    <span class="ms-auto badge bg-success-subtle text-success">Disponible</span>
                                </div>
                            </div>

                            {{-- Bouton principal --}}
                            <button type="submit" class="btn-enroll-pay w-100" id="paymentBtn">
                                <span class="btn-main-text">
                                    <i class="fas fa-lock me-2"></i>
                                    Payer {{ number_format($course->price, 0, ',', ' ') }} {{ $course->currency ?? 'XOF' }}
                                </span>
                                <span class="btn-loading" style="display:none;">
                                    <i class="fas fa-spinner fa-spin me-2"></i>Redirection…
                                </span>
                            </button>
                        </form>

                        {{-- Garanties --}}
                        <div class="trust-row mt-4">
                            <div class="trust-item">
                                <i class="fas fa-shield-alt"></i>
                                <span>Sécurisé</span>
                            </div>
                            <div class="trust-item">
                                <i class="fas fa-lock"></i>
                                <span>Chiffré</span>
                            </div>
                            <div class="trust-item">
                                <i class="fas fa-undo"></i>
                                <span>Remboursement 30j</span>
                            </div>
                        </div>
                    </div>

                @endif

                {{-- Lien retour --}}
                <div class="text-center mt-4">
                    <a href="{{ route('courses.show', $course->slug) }}" class="back-link">
                        <i class="fas fa-arrow-left me-1"></i>Retour à la formation
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
.enroll-page {
    background: #f4f6f9;
    min-height: 80vh;
}

/* --- Récapitulatif cours --- */
.course-recap {
    background: white;
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    border: 1px solid #e8ecf0;
}

.course-thumb {
    width: 72px;
    height: 72px;
    object-fit: cover;
    border-radius: 12px;
    flex-shrink: 0;
}

.course-thumb-placeholder {
    width: 72px;
    height: 72px;
    border-radius: 12px;
    background: linear-gradient(135deg, #11c06d22, #11c06d44);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    color: #11c06d;
    flex-shrink: 0;
}

.badge-level {
    background: #e8f4f2;
    color: #11c06d;
    border-radius: 20px;
    padding: 2px 10px;
    font-size: .72rem;
    font-weight: 600;
    text-transform: capitalize;
}

.badge-cert {
    background: #fff3cd;
    color: #856404;
    border-radius: 20px;
    padding: 2px 10px;
    font-size: .72rem;
    font-weight: 600;
}

.course-recap-title {
    font-size: 1rem;
    font-weight: 700;
    color: #1a1a2e;
    margin: 0 0 .2rem;
    line-height: 1.3;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.course-recap-instructor {
    font-size: .82rem;
    color: #6b7280;
    margin: 0;
}

/* --- Carte checkout --- */
.checkout-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 4px 24px rgba(0,0,0,.08);
    border: 1px solid #e8ecf0;
}

/* --- Prix --- */
.price-summary {}
.price-label {
    font-size: .9rem;
    color: #6b7280;
    font-weight: 500;
}
.price-amount {
    font-size: 1.9rem;
    font-weight: 800;
    color: #1a1a2e;
    letter-spacing: -.5px;
}
.price-currency {
    font-size: 1rem;
    font-weight: 600;
    color: #6b7280;
}
.price-divider {
    height: 1px;
    background: #f0f0f0;
}

/* --- Bénéfices --- */
.benefits-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: .6rem;
}
.benefits-list li {
    display: flex;
    align-items: center;
    gap: .65rem;
    font-size: .9rem;
    color: #374151;
}
.benefits-list li i {
    width: 18px;
    text-align: center;
    color: #11c06d;
    flex-shrink: 0;
}

/* --- Méthode de paiement affichée --- */
.payment-method-display {
    background: #f8fffe;
    border: 1.5px solid #11c06d33;
    border-radius: 12px;
    padding: 1rem 1.25rem;
}
.payment-method-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    background: linear-gradient(135deg, #11c06d, #239e79);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.bg-success-subtle { background: #d1fae5 !important; }

/* --- Boutons principaux --- */
.btn-enroll-pay {
    display: block;
    background: linear-gradient(135deg, #11c06d, #239e79);
    color: white;
    border: none;
    border-radius: 14px;
    padding: 1rem 1.5rem;
    font-size: 1.05rem;
    font-weight: 700;
    cursor: pointer;
    transition: all .25s ease;
    box-shadow: 0 6px 20px rgba(17,192,109,.35);
    text-align: center;
}
.btn-enroll-pay:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(17,192,109,.45);
}
.btn-enroll-pay:active {
    transform: translateY(0);
}
.btn-enroll-pay:disabled {
    opacity: .7;
    cursor: not-allowed;
    transform: none;
}

.btn-enroll-free {
    display: block;
    background: linear-gradient(135deg, #fea837, #e67e22);
    color: white;
    border: none;
    border-radius: 14px;
    padding: 1rem 1.5rem;
    font-size: 1.05rem;
    font-weight: 700;
    cursor: pointer;
    transition: all .25s ease;
    box-shadow: 0 6px 20px rgba(254,168,55,.35);
    text-align: center;
}
.btn-enroll-free:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(254,168,55,.45);
}
.btn-enroll-free:disabled {
    opacity: .7;
    cursor: not-allowed;
    transform: none;
}

/* --- Garanties --- */
.trust-row {
    display: flex;
    justify-content: center;
    gap: 2rem;
    padding-top: .5rem;
    border-top: 1px solid #f0f0f0;
}
.trust-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .3rem;
    font-size: .78rem;
    color: #9ca3af;
}
.trust-item i {
    font-size: 1rem;
    color: #11c06d;
}

/* --- Badge gratuit --- */
.free-badge-large {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: linear-gradient(135deg, #fea83722, #fea83744);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: #fea837;
    margin: 0 auto;
}

/* --- Lien retour --- */
.back-link {
    color: #9ca3af;
    font-size: .88rem;
    text-decoration: none;
    transition: color .2s;
}
.back-link:hover { color: #11c06d; }

@media (max-width: 576px) {
    .checkout-card { padding: 1.5rem 1.25rem; }
    .price-amount { font-size: 1.6rem; }
    .trust-row { gap: 1.2rem; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('enrollmentForm');
    const btn  = document.getElementById('paymentBtn');
    if (!form || !btn) return;

    form.addEventListener('submit', function () {
        btn.disabled = true;
        btn.querySelector('.btn-main-text').style.display = 'none';
        btn.querySelector('.btn-loading').style.display   = 'inline';
    });

    const enrollBtn = document.getElementById('enrollBtn');
    if (enrollBtn) {
        enrollBtn.closest('form').addEventListener('submit', function () {
            enrollBtn.disabled = true;
            enrollBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Traitement…';
        });
    }
});
</script>
@endsection
