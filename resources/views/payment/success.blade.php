@extends('layouts.layout')

@section('title', 'Paiement réussi - AfriCode')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center p-5">
                    <!-- Icône de succès -->
                    <div class="success-icon mb-4">
                        <div class="rounded-circle bg-success d-inline-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-check text-white fa-3x"></i>
                        </div>
                    </div>
                    
                    <!-- Message de succès -->
                    <h2 class="text-success mb-3">Paiement réussi !</h2>
                    <p class="text-muted mb-4">
                        Félicitations ! Votre inscription au cours a été confirmée.
                    </p>
                    
                    @if($payment && $payment->enrollment && $payment->enrollment->course)
                    <!-- Détails du cours -->
                    <div class="course-details bg-light rounded p-4 mb-4">
                        <div class="d-flex align-items-center">
                            @if($payment->enrollment->course->thumbnail)
                                <img src="{{ Storage::url($payment->enrollment->course->thumbnail) }}" 
                                     alt="{{ $payment->enrollment->course->title }}" 
                                     class="rounded me-3" 
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-primary rounded me-3 d-flex align-items-center justify-content-center" 
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-book text-white"></i>
                                </div>
                            @endif
                            <div class="text-start">
                                <h6 class="mb-1">{{ $payment->enrollment->course->title }}</h6>
                                <small class="text-muted">{{ $payment->enrollment->course->category->name ?? 'Général' }}</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Détails du paiement -->
                    <div class="payment-details text-start mb-4">
                        <h6 class="mb-3">Détails du paiement</h6>
                        <div class="row g-2 small">
                            <div class="col-6">
                                <strong>Montant payé :</strong>
                            </div>
                            <div class="col-6 text-end">
                                {{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency }}
                            </div>
                            <div class="col-6">
                                <strong>Méthode :</strong>
                            </div>
                            <div class="col-6 text-end">
                                @if($payment->payment_gateway === 'stripe')
                                    <i class="fab fa-cc-stripe me-1"></i>Carte bancaire
                                @elseif($payment->payment_gateway === 'fadapay')
                                    <i class="fas fa-mobile-alt me-1"></i>Mobile Money
                                @endif
                            </div>
                            <div class="col-6">
                                <strong>Transaction :</strong>
                            </div>
                            <div class="col-6 text-end">
                                <small class="text-muted">{{ substr($payment->transaction_id, 0, 16) }}...</small>
                            </div>
                            <div class="col-6">
                                <strong>Date :</strong>
                            </div>
                            <div class="col-6 text-end">
                                {{ $payment->paid_at ? $payment->paid_at->format('d/m/Y à H:i') : $payment->created_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Actions -->
                    <div class="d-grid gap-2">
                        @if($payment && $payment->enrollment && $payment->enrollment->course)
                        <a href="{{ route('courses.show', $payment->enrollment->course) }}" 
                           class="btn btn-primary btn-lg">
                            <i class="fas fa-play me-2"></i>
                            Commencer le cours
                        </a>
                        @endif
                        
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Aller au tableau de bord
                        </a>
                    </div>
                    
                    <!-- Informations supplémentaires -->
                    <div class="mt-4 p-3 bg-info bg-opacity-10 rounded">
                        <h6 class="text-info mb-2">
                            <i class="fas fa-info-circle me-2"></i>
                            Prochaines étapes
                        </h6>
                        <ul class="list-unstyled mb-0 small text-start">
                            <li class="mb-1">
                                <i class="fas fa-check text-success me-2"></i>
                                Vous pouvez maintenant accéder à tout le contenu du cours
                            </li>
                            <li class="mb-1">
                                <i class="fas fa-certificate text-warning me-2"></i>
                                Complétez le cours pour obtenir votre certificat
                            </li>
                            <li class="mb-1">
                                <i class="fas fa-envelope text-info me-2"></i>
                                Un email de confirmation vous a été envoyé
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.success-icon {
    animation: bounceIn 0.6s ease-out;
}

@keyframes bounceIn {
    0% {
        transform: scale(0.3);
        opacity: 0;
    }
    50% {
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.course-details {
    border-left: 4px solid #28a745;
}
</style>
@endsection
