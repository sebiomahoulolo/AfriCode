@extends('layouts.app')

@section('title', 'Paiement - ' . $course->title)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h2 class="mb-2">
                        <i class="fas fa-credit-card me-2"></i>Finaliser votre achat
                    </h2>
                    <p class="mb-0 lead">Paiement sécurisé via FedaPay</p>
                </div>
                
                <div class="card-body p-5">
                    <!-- Course Information -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            @if($course->image)
                                <img src="{{ asset('storage/' . $course->image) }}" 
                                     alt="{{ $course->title }}" 
                                     class="img-fluid rounded shadow-sm">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h4 class="text-primary">{{ $course->title }}</h4>
                            <p class="text-muted mb-3">{{ Str::limit($course->description, 120) }}</p>
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <strong>Prix:</strong> 
                                    <span class="text-success fs-5">
                                        {{ number_format($payment->converted_amount ?? $payment->amount) }} 
                                        {{ $payment->converted_currency ?? $payment->currency }}
                                    </span>
                                </div>
                                <div class="col-sm-6">
                                    <strong>Instructeur:</strong> {{ $course->instructor->name ?? 'AfriCode' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Payment Section -->
                    <div class="text-center mb-4">
                        <h5 class="text-dark mb-3">Paiement Mobile Money</h5>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="payment-methods">
                                    <div class="payment-option mb-4 p-4 border rounded bg-light">
                                        <div class="text-center mb-3">
                                            <i class="fas fa-mobile-alt text-success me-2 fs-3"></i>
                                            <h6 class="mb-2"><strong>Mobile Money</strong></h6>
                                            <small class="text-muted">Orange Money, MTN Mobile Money, Moov Money, Wave</small>
                                        </div>
                                        
                                        <!-- Formulaire numéro de téléphone -->
                                        <div class="phone-input-section mt-3">
                                            <label for="phone_number" class="form-label">
                                                <i class="fas fa-phone me-1"></i>
                                                Numéro de téléphone
                                            </label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">
                                                    <img src="https://flagcdn.com/w20/bj.png" alt="Bénin" class="me-1">
                                                    +229
                                                </span>
                                                <input type="tel" 
                                                       class="form-control" 
                                                       id="phone_number" 
                                                       placeholder="XX XX XX XX" 
                                                       pattern="[0-9]{8}"
                                                       maxlength="8"
                                                       required>
                                            </div>
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Entrez votre numéro sans l'indicatif pays (+229)
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FedaPay Payment Button Container -->
                    <div class="text-center">
                        <div id="fedapay-payment-container">
                            <button id="fedapay-payment-btn" class="btn btn-success btn-lg px-5 py-3" disabled>
                                <i class="fas fa-lock me-2"></i>
                                Payer {{ number_format($payment->converted_amount ?? $payment->amount) }} 
                                {{ $payment->converted_currency ?? $payment->currency }}
                            </button>
                            <div class="mt-2">
                                <small class="text-muted">Veuillez entrer votre numéro de téléphone</small>
                            </div>
                        </div>
                    </div>

                    <!-- Security Info -->
                    <div class="row mt-5 text-center">
                        <div class="col-md-4">
                            <i class="fas fa-shield-alt text-success mb-2 fs-4"></i>
                            <h6>Paiement Sécurisé</h6>
                            <small class="text-muted">Vos données sont protégées</small>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-clock text-info mb-2 fs-4"></i>
                            <h6>Accès Immédiat</h6>
                            <small class="text-muted">Commencez directement après paiement</small>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-certificate text-warning mb-2 fs-4"></i>
                            <h6>Certificat Inclus</h6>
                            <small class="text-muted">Obtenez votre certificat</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title">Résumé de la commande</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Cours:</span>
                        <span>{{ $course->title }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Prix:</span>
                        <span>{{ number_format($payment->amount) }} {{ $payment->currency }}</span>
                    </div>
                    @if($payment->converted_amount && $payment->converted_currency !== $payment->currency)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Prix converti:</span>
                        <span>{{ number_format($payment->converted_amount) }} {{ $payment->converted_currency }}</span>
                    </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total:</span>
                        <span class="text-success">
                            {{ number_format($payment->converted_amount ?? $payment->amount) }} 
                            {{ $payment->converted_currency ?? $payment->currency }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FedaPay Checkout.js -->
<script src="https://cdn.fedapay.com/checkout.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration FedaPay
    const config = {!! json_encode($checkoutConfig) !!};
    
    console.log('FedaPay Configuration:', config);
    
    // Éléments DOM
    const phoneInput = document.getElementById('phone_number');
    const paymentBtn = document.getElementById('fedapay-payment-btn');
    
    // Validation du numéro de téléphone
    function validatePhoneNumber(phone) {
        // Format: 8 chiffres pour le Bénin
        const phoneRegex = /^[0-9]{8}$/;
        return phoneRegex.test(phone.replace(/\s/g, ''));
    }
    
    // Formater le numéro pendant la saisie
    phoneInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, ''); // Supprimer les non-chiffres
        
        // Limiter à 8 chiffres
        if (value.length > 8) {
            value = value.substring(0, 8);
        }
        
        // Formater XX XX XX XX
        if (value.length >= 2) {
            value = value.match(/.{1,2}/g).join(' ');
        }
        
        this.value = value;
        
        // Valider et activer/désactiver le bouton
        const isValid = validatePhoneNumber(value);
        paymentBtn.disabled = !isValid;
        
        const helpText = paymentBtn.parentElement.querySelector('.mt-2 small');
        if (isValid) {
            helpText.textContent = 'Prêt pour le paiement';
            helpText.className = 'text-success';
            paymentBtn.querySelector('.mt-2 small')?.remove();
        } else {
            helpText.textContent = value.length === 0 ? 'Veuillez entrer votre numéro de téléphone' : 'Numéro invalide (8 chiffres requis)';
            helpText.className = 'text-muted';
        }
    });
    
    // Configuration du checkout
    function getCheckoutConfig(phoneNumber) {
        return {
            public_key: config.public_key,
            environment: config.environment || 'sandbox',
            transaction: {
                ...config.transaction,
                customer: {
                    ...config.customer,
                    phone_number: {
                        number: '+229' + phoneNumber.replace(/\s/g, ''),
                        country: 'bj'
                    }
                }
            },
            onComplete: function(reason, transaction) {
                console.log('FedaPay Payment Complete:', reason, transaction);
                
                if (reason === 'CHECKOUT_COMPLETED') {
                    // Rediriger vers la page de succès
                    window.location.href = "{{ route('payment.success', ['payment' => $payment->id]) }}";
                } else if (reason === 'DIALOG_DISMISSED') {
                    console.log('Payment dialog was dismissed by user');
                }
            },
            onError: function(error) {
                console.error('FedaPay Error:', error);
                alert('Erreur de paiement: ' + error.message);
            }
        };
    }

    // Événement du bouton de paiement
    paymentBtn.addEventListener('click', function() {
        const phoneNumber = phoneInput.value.replace(/\s/g, '');
        
        if (!validatePhoneNumber(phoneNumber)) {
            alert('Veuillez entrer un numéro de téléphone valide (8 chiffres)');
            phoneInput.focus();
            return;
        }
        
        console.log('Opening FedaPay payment dialog for:', '+229' + phoneNumber);
        
        // Vérifier si FedaPay est chargé
        if (typeof FedaPay === 'undefined') {
            console.error('FedaPay not loaded!');
            alert('Erreur: Service de paiement non disponible');
            return;
        }
        
        try {
            // Obtenir la configuration avec le numéro de téléphone
            const checkoutConfig = getCheckoutConfig(phoneNumber);
            console.log('Opening checkout with config:', checkoutConfig);
            
            // Ouvrir le checkout FedaPay
            FedaPay.checkout(checkoutConfig);
        } catch (error) {
            console.error('Error opening FedaPay checkout:', error);
            alert('Erreur lors de l\'ouverture du paiement: ' + error.message);
        }
    });
});
</script>

<style>
.payment-option {
    transition: all 0.3s ease;
    cursor: pointer;
}

.payment-option:hover {
    background-color: #f8f9fa;
    border-color: #007bff !important;
}

#fedapay-payment-btn {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

#fedapay-payment-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
}

.card {
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

@media (max-width: 768px) {
    .container {
        padding: 20px 15px;
    }
    
    .card-body {
        padding: 20px !important;
    }
    
    #fedapay-payment-btn {
        width: 100%;
        font-size: 16px;
    }
}
</style>
@endsection
