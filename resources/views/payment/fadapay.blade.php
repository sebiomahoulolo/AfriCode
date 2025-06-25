@extends('layouts.layout')

@section('title', 'Paiement Mobile Money - ' . $course->title)

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
                                <span>{{ number_format($course->price, 0, ',', ' ') }} {{ $course->currency ?? 'XOF' }}</span>
                            </div>
                            
                            <hr>
                            
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total</span>
                                <span class="text-primary">{{ number_format($course->price, 0, ',', ' ') }} {{ $course->currency ?? 'XOF' }}</span>
                            </div>
                            
                            <div class="mt-3 p-3 bg-light rounded">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-mobile-alt text-success me-2"></i>
                                    <small>Paiement sécurisé par FadaPay</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulaire de paiement FadaPay -->
                <div class="col-md-7">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4">
                                <i class="fas fa-mobile-alt me-2"></i>
                                Paiement Mobile Money
                            </h5>
                            
                            <form id="fadapay-form" method="POST" action="{{ route('enrollment.process-payment', $course) }}">
                                @csrf
                                <input type="hidden" name="enrollment_id" value="{{ $enrollment->id }}">
                                <input type="hidden" name="payment_method" value="fadapay">
                                <input type="hidden" name="transaction_id" value="{{ $payment->transaction_id }}">
                                
                                <div class="mb-4">
                                    <label class="form-label">Choisissez votre opérateur</label>
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="operator-option">
                                                <input type="radio" name="operator" value="orange" id="orange" class="operator-radio" required>
                                                <label for="orange" class="operator-label w-100">
                                                    <div class="operator-card text-center p-3 border rounded">
                                                        <div class="operator-logo mb-2" style="color: #FF6600;">
                                                            <i class="fas fa-mobile-alt fa-2x"></i>
                                                        </div>
                                                        <div class="operator-name fw-bold">Orange Money</div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="operator-option">
                                                <input type="radio" name="operator" value="mtn" id="mtn" class="operator-radio" required>
                                                <label for="mtn" class="operator-label w-100">
                                                    <div class="operator-card text-center p-3 border rounded">
                                                        <div class="operator-logo mb-2" style="color: #FFCC00;">
                                                            <i class="fas fa-mobile-alt fa-2x"></i>
                                                        </div>
                                                        <div class="operator-name fw-bold">MTN Mobile Money</div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="operator-option">
                                                <input type="radio" name="operator" value="moov" id="moov" class="operator-radio" required>
                                                <label for="moov" class="operator-label w-100">
                                                    <div class="operator-card text-center p-3 border rounded">
                                                        <div class="operator-logo mb-2" style="color: #0099CC;">
                                                            <i class="fas fa-mobile-alt fa-2x"></i>
                                                        </div>
                                                        <div class="operator-name fw-bold">Moov Money</div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="operator-option">
                                                <input type="radio" name="operator" value="wave" id="wave" class="operator-radio" required>
                                                <label for="wave" class="operator-label w-100">
                                                    <div class="operator-card text-center p-3 border rounded">
                                                        <div class="operator-logo mb-2" style="color: #00D4AA;">
                                                            <i class="fas fa-mobile-alt fa-2x"></i>
                                                        </div>
                                                        <div class="operator-name fw-bold">Wave</div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Numéro de téléphone</label>
                                    <div class="input-group">
                                        <span class="input-group-text">+225</span>
                                        <input type="tel" 
                                               class="form-control" 
                                               id="phone" 
                                               name="phone" 
                                               placeholder="01 23 45 67 89"
                                               pattern="[0-9]{2} [0-9]{2} [0-9]{2} [0-9]{2} [0-9]{2}"
                                               required>
                                    </div>
                                    <div class="form-text">Entrez le numéro associé à votre compte Mobile Money</div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="accept-terms" required>
                                        <label class="form-check-label" for="accept-terms">
                                            J'accepte les <a href="#" target="_blank">conditions générales</a> et la <a href="#" target="_blank">politique de confidentialité</a>
                                        </label>
                                    </div>
                                </div>
                                
                                <button type="submit" id="submit-payment" class="btn btn-primary btn-lg w-100">
                                    <span id="button-text">
                                        <i class="fas fa-mobile-alt me-2"></i>
                                        Payer {{ number_format($course->price, 0, ',', ' ') }} {{ $course->currency ?? 'XOF' }}
                                    </span>
                                    <span id="spinner" class="d-none">
                                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                        Traitement...
                                    </span>
                                </button>
                            </form>
                            
                            <div class="mt-4">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-info-circle me-2"></i>Comment ça marche ?
                                    </h6>
                                    <ol class="mb-0 small">
                                        <li>Sélectionnez votre opérateur Mobile Money</li>
                                        <li>Entrez votre numéro de téléphone</li>
                                        <li>Vous recevrez un SMS avec un code de confirmation</li>
                                        <li>Validez le paiement avec votre code PIN</li>
                                    </ol>
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

<style>
.operator-option {
    position: relative;
}

.operator-radio {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.operator-card {
    cursor: pointer;
    transition: all 0.3s ease;
}

.operator-card:hover {
    border-color: #0d6efd;
    box-shadow: 0 2px 4px rgba(13, 110, 253, 0.2);
}

.operator-radio:checked + .operator-label .operator-card {
    border-color: #0d6efd;
    background-color: rgba(13, 110, 253, 0.1);
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
}

.operator-radio:checked + .operator-label .operator-card::after {
    content: '\f00c';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    position: absolute;
    top: 8px;
    right: 8px;
    color: #0d6efd;
    font-size: 14px;
}
</style>

<script>
document.getElementById('fadapay-form').addEventListener('submit', function(e) {
    const submitButton = document.getElementById('submit-payment');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');
    
    // Désactiver le bouton et afficher le spinner
    submitButton.disabled = true;
    buttonText.classList.add('d-none');
    spinner.classList.remove('d-none');
});

// Formater le numéro de téléphone
document.getElementById('phone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    let formattedValue = '';
    
    for (let i = 0; i < value.length && i < 10; i++) {
        if (i > 0 && i % 2 === 0) {
            formattedValue += ' ';
        }
        formattedValue += value[i];
    }
    
    e.target.value = formattedValue;
});
</script>
@endsection
