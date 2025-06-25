@extends('layouts.layout')

@section('title', 'Échec du paiement - AfriCode')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center p-5">
                    <!-- Icône d'échec -->
                    <div class="error-icon mb-4">
                        <div class="rounded-circle bg-danger d-inline-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-times text-white fa-3x"></i>
                        </div>
                    </div>
                    
                    <!-- Message d'erreur -->
                    <h2 class="text-danger mb-3">Paiement échoué</h2>
                    <p class="text-muted mb-4">
                        Désolé, votre paiement n'a pas pu être traité. Veuillez réessayer ou utiliser une autre méthode de paiement.
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
                                <br>
                                <span class="badge bg-primary">
                                    {{ number_format($payment->enrollment->course->price, 0, ',', ' ') }} {{ $payment->enrollment->course->currency ?? 'XOF' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($payment)
                    <!-- Détails du paiement échoué -->
                    <div class="payment-details text-start mb-4">
                        <h6 class="mb-3">Détails de la transaction</h6>
                        <div class="row g-2 small">
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
                                <strong>Statut :</strong>
                            </div>
                            <div class="col-6 text-end">
                                <span class="badge bg-danger">Échoué</span>
                            </div>
                            <div class="col-6">
                                <strong>Transaction :</strong>
                            </div>
                            <div class="col-6 text-end">
                                <small class="text-muted">{{ substr($payment->transaction_id ?? 'N/A', 0, 16) }}...</small>
                            </div>
                            <div class="col-6">
                                <strong>Date :</strong>
                            </div>
                            <div class="col-6 text-end">
                                {{ $payment->created_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Raisons possibles de l'échec -->
                    <div class="alert alert-warning text-start mb-4">
                        <h6 class="alert-heading">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Raisons possibles de l'échec
                        </h6>
                        <ul class="mb-0 small">
                            <li>Fonds insuffisants sur votre compte</li>
                            <li>Informations de carte incorrectes</li>
                            <li>Problème de réseau temporaire</li>
                            <li>Limite de transaction dépassée</li>
                            <li>Compte Mobile Money non activé</li>
                        </ul>
                    </div>
                    
                    <!-- Actions -->
                    <div class="d-grid gap-2">
                        @if($payment && $payment->enrollment && $payment->enrollment->course)
                        <a href="{{ route('enrollment.show', $payment->enrollment->course) }}" 
                           class="btn btn-primary btn-lg">
                            <i class="fas fa-retry me-2"></i>
                            Réessayer le paiement
                        </a>
                        @endif
                        
                        <a href="{{ route('courses.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Retour aux cours
                        </a>
                        
                        <a href="{{ route('contact') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-question-circle me-2"></i>
                            Contacter le support
                        </a>
                    </div>
                    
                    <!-- Conseils -->
                    <div class="mt-4 p-3 bg-info bg-opacity-10 rounded">
                        <h6 class="text-info mb-2">
                            <i class="fas fa-lightbulb me-2"></i>
                            Conseils pour réussir votre paiement
                        </h6>
                        <ul class="list-unstyled mb-0 small text-start">
                            <li class="mb-1">
                                <i class="fas fa-check text-success me-2"></i>
                                Vérifiez que votre solde est suffisant
                            </li>
                            <li class="mb-1">
                                <i class="fas fa-check text-success me-2"></i>
                                Assurez-vous d'avoir une connexion internet stable
                            </li>
                            <li class="mb-1">
                                <i class="fas fa-check text-success me-2"></i>
                                Vérifiez les informations de votre carte/compte
                            </li>
                            <li class="mb-1">
                                <i class="fas fa-check text-success me-2"></i>
                                Contactez-nous si le problème persiste
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-icon {
    animation: shake 0.6s ease-out;
}

@keyframes shake {
    0%, 20%, 40%, 60%, 80%, 100% {
        transform: translateX(0);
    }
    10%, 30%, 50%, 70%, 90% {
        transform: translateX(-5px);
    }
}

.course-details {
    border-left: 4px solid #dc3545;
}
</style>
@endsection
