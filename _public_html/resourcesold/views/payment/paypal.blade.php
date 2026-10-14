@extends('layouts.layout')

@section('title', 'Paiement PayPal - ' . $course->title)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="row">
                <!-- Informations du cours -->
                <div class="col-md-5">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Récapitulatif de commande</h5>
                            <hr>
                            <div class="d-flex align-items-center mb-3">
                                @if($course->thumbnail)
                                    <img src="{{ Storage::url($course->thumbnail) }}" 
                                         alt="{{ $course->title }}" 
                                         class="rounded me-3" 
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-primary rounded me-3 d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-book text-white"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-1">{{ $course->title }}</h6>
                                    <small class="text-muted">{{ $course->category->name ?? 'Général' }}</small>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Prix du cours</span>
                                <span>{{ number_format($course->price, 0, ',', ' ') }} {{ $course->currency ?? 'EUR' }}</span>
                            </div>
                            
                            <hr>
                            
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total</span>
                                <span class="text-primary">{{ number_format($course->price, 0, ',', ' ') }} {{ $course->currency ?? 'EUR' }}</span>
                            </div>
                            
                            <div class="mt-3 p-3 bg-light rounded">
                                <div class="d-flex align-items-center">
                                    <i class="fab fa-paypal text-primary me-2"></i>
                                    <small>Paiement sécurisé par PayPal</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulaire de paiement PayPal -->
                <div class="col-md-7">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4">
                                <i class="fab fa-paypal me-2 text-primary"></i>
                                Paiement PayPal
                            </h5>
                            
                            <div class="text-center mb-4">
                                <p class="text-muted">Cliquez sur le bouton ci-dessous pour procéder au paiement via PayPal</p>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="accept-terms" required>
                                    <label class="form-check-label" for="accept-terms">
                                        J'accepte les <a href="#" target="_blank">conditions générales</a> et la <a href="#" target="_blank">politique de confidentialité</a>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <p class="text-muted mb-3">Cliquez sur le bouton ci-dessous pour être redirigé vers PayPal</p>
                                
                                <a href="#" id="paypal-button" class="btn btn-primary btn-lg w-100" onclick="initiatePayPalPayment()">
                                    <i class="fab fa-paypal me-2"></i>
                                    Payer {{ number_format($course->price, 0, ',', ' ') }} {{ $course->currency ?? 'USD' }} avec PayPal
                                </a>
                                
                                <div id="spinner" class="d-none mt-3">
                                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                    Redirection vers PayPal...
                                </div>
                            </div>
                            
                            <div class="mt-4 text-center">
                                <div class="row text-muted small">
                                    <div class="col-4">
                                        <i class="fas fa-shield-alt"></i> Sécurisé
                                    </div>
                                    <div class="col-4">
                                        <i class="fas fa-lock"></i> SSL
                                    </div>
                                    <div class="col-4">
                                        <i class="fas fa-credit-card"></i> PayPal
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-3 p-3 bg-info bg-opacity-10 rounded">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle text-info me-2"></i>
                                    <small class="text-info">
                                        Vous serez redirigé vers PayPal pour finaliser votre paiement en toute sécurité.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-link">
                            <i class="fas fa-arrow-left me-2"></i>Retour au cours
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function initiatePayPalPayment() {
    const acceptTerms = document.getElementById('accept-terms');
    if (!acceptTerms.checked) {
        alert('Veuillez accepter les conditions générales pour continuer.');
        return;
    }
    
    const paypalButton = document.getElementById('paypal-button');
    const spinner = document.getElementById('spinner');
    
    // Désactiver le bouton et afficher le spinner
    paypalButton.style.display = 'none';
    spinner.classList.remove('d-none');
    
    // Simuler une redirection vers PayPal (pour l'instant, on redirige vers le succès)
    // Dans un vrai système PayPal, vous feriez un appel API pour créer l'ordre PayPal
    setTimeout(() => {
        window.location.href = '{{ $returnUrl }}';
    }, 2000);
}
</script>
@endsection
