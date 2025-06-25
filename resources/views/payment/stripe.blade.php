@extends('layouts.layout')

@section('title', 'Paiement par carte bancaire - ' . $course->title)

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
                                    <i class="fas fa-shield-alt text-success me-2"></i>
                                    <small>Paiement sécurisé par Stripe</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulaire de paiement Stripe -->
                <div class="col-md-7">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4">
                                <i class="fab fa-cc-stripe me-2"></i>
                                Paiement par carte bancaire
                            </h5>
                            
                            <form id="payment-form">
                                <div class="mb-3">
                                    <label class="form-label">Informations de carte</label>
                                    <div id="card-element" class="form-control" style="height: 50px; padding: 12px;">
                                        <!-- Stripe Elements will create form elements here -->
                                    </div>
                                    <div id="card-errors" role="alert" class="text-danger mt-2"></div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="save-card" required>
                                        <label class="form-check-label" for="save-card">
                                            J'accepte les <a href="#" target="_blank">conditions générales</a> et la <a href="#" target="_blank">politique de confidentialité</a>
                                        </label>
                                    </div>
                                </div>
                                
                                <button type="submit" id="submit-payment" class="btn btn-primary btn-lg w-100">
                                    <span id="button-text">
                                        <i class="fas fa-lock me-2"></i>
                                        Payer {{ number_format($course->price, 0, ',', ' ') }} {{ $course->currency ?? 'EUR' }}
                                    </span>
                                    <span id="spinner" class="d-none">
                                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                        Traitement...
                                    </span>
                                </button>
                            </form>
                            
                            <div class="mt-4 text-center">
                                <div class="row text-muted small">
                                    <div class="col-4">
                                        <i class="fab fa-cc-visa"></i>
                                        <i class="fab fa-cc-mastercard ms-1"></i>
                                    </div>
                                    <div class="col-4">
                                        <i class="fas fa-shield-alt"></i> SSL
                                    </div>
                                    <div class="col-4">
                                        <i class="fas fa-lock"></i> Sécurisé
                                    </div>
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

<!-- Stripe JS -->
<script src="https://js.stripe.com/v3/"></script>
<script>
// Initialiser Stripe
const stripe = Stripe('{{ $publishableKey }}');
const elements = stripe.elements();

// Créer l'élément de carte
const cardElement = elements.create('card', {
    style: {
        base: {
            fontSize: '16px',
            color: '#424770',
            '::placeholder': {
                color: '#aab7c4',
            },
        },
        invalid: {
            color: '#9e2146',
        },
    },
});

cardElement.mount('#card-element');

// Gérer les erreurs en temps réel
cardElement.on('change', ({error}) => {
    const displayError = document.getElementById('card-errors');
    if (error) {
        displayError.textContent = error.message;
    } else {
        displayError.textContent = '';
    }
});

// Gérer la soumission du formulaire
const form = document.getElementById('payment-form');
form.addEventListener('submit', async (event) => {
    event.preventDefault();
    
    const submitButton = document.getElementById('submit-payment');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');
    
    // Désactiver le bouton et afficher le spinner
    submitButton.disabled = true;
    buttonText.classList.add('d-none');
    spinner.classList.remove('d-none');

    const {token, error} = await stripe.createToken(cardElement);

    if (error) {
        // Afficher l'erreur à l'utilisateur
        const errorElement = document.getElementById('card-errors');
        errorElement.textContent = error.message;
        
        // Réactiver le bouton
        submitButton.disabled = false;
        buttonText.classList.remove('d-none');
        spinner.classList.add('d-none');
    } else {
        // Confirmer le paiement avec le client secret
        const {error: confirmError} = await stripe.confirmCardPayment(
            '{{ $clientSecret }}',
            {
                payment_method: {
                    card: cardElement,
                }
            }
        );

        if (confirmError) {
            // Afficher l'erreur
            const errorElement = document.getElementById('card-errors');
            errorElement.textContent = confirmError.message;
            
            // Réactiver le bouton
            submitButton.disabled = false;
            buttonText.classList.remove('d-none');
            spinner.classList.add('d-none');
        } else {
            // Le paiement a réussi
            window.location.href = '{{ route("payment.success") }}?transaction_id={{ $clientSecret }}';
        }
    }
});
</script>
@endsection
