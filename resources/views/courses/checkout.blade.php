@extends('layouts.app')

@section('title', 'Acheter le cours - ' . $course->title)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- En-tête -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="checkout-step-indicator me-4">
                                    <div class="step active">1</div>
                                    <span class="step-label">Paiement</span>
                                </div>
                                <div class="checkout-step-indicator">
                                    <div class="step">2</div>
                                    <span class="step-label">Confirmation</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour au cours
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Détails du cours -->
                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm sticky-top" style="top: 2rem;">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold mb-3">Récapitulatif de la commande</h5>
                            
                            <div class="course-summary">
                                @if($course->cover_image_path)
                                    <img src="{{ Storage::url($course->cover_image_path) }}" 
                                         class="img-fluid rounded mb-3" alt="{{ $course->title }}">
                                @endif
                                
                                <h6 class="fw-bold mb-2">{{ $course->title }}</h6>
                                <p class="text-muted small mb-3">{{ Str::limit($course->short_description, 120) }}</p>
                                
                                <div class="course-details mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Formateur:</span>
                                        <span class="fw-medium">{{ $course->formateur->first_name }} {{ $course->formateur->last_name }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Durée:</span>
                                        <span class="fw-medium">{{ $course->duration }} heures</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Niveau:</span>
                                        <span class="fw-medium text-capitalize">{{ $course->level }}</span>
                                    </div>
                                    @if($course->is_certifying)
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Certification:</span>
                                            <span class="badge bg-success">Incluse</span>
                                        </div>
                                    @endif
                                </div>

                                <hr>

                                <div class="pricing-summary">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Prix du cours:</span>
                                        <span class="fw-bold">{{ number_format($course->price, 0, ',', ' ') }} {{ $course->currency ?? 'XOF' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2 text-muted" id="fees-display" style="display: none !important;">
                                        <span>Frais de transaction:</span>
                                        <span id="fees-amount">0 XOF</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between fw-bold total-amount">
                                        <span>Total à payer:</span>
                                        <span id="total-amount">{{ number_format($course->price, 0, ',', ' ') }} {{ $course->currency ?? 'XOF' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Méthodes de paiement -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold mb-4">
                                <i class="fas fa-credit-card me-2 text-primary"></i>Choisir un moyen de paiement
                            </h5>

                            @if($availableGateways->isEmpty())
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Aucun moyen de paiement disponible pour ce cours.
                                </div>
                            @else
                                <div class="payment-methods">
                                    @foreach($availableGateways as $gateway)
                                        <div class="payment-method" data-gateway-id="{{ $gateway->id }}">
                                            <input type="radio" name="payment_gateway" value="{{ $gateway->name }}" 
                                                   id="gateway_{{ $gateway->id }}" class="payment-method-radio d-none">
                                            <label for="gateway_{{ $gateway->id }}" class="payment-method-card">
                                                <div class="d-flex align-items-center">
                                                    <div class="payment-icon me-3">
                                                        <i class="{{ $gateway->icon }} fa-2x"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-bold">{{ $gateway->display_name }}</h6>
                                                        <p class="mb-1 text-muted small">{{ $gateway->description }}</p>
                                                        @if($gateway->test_mode)
                                                            <span class="badge bg-warning">Mode test</span>
                                                        @endif
                                                        @if($gateway->fees_percentage > 0 || $gateway->fees_fixed > 0)
                                                            <div class="fees-info text-muted small">
                                                                Frais: 
                                                                @if($gateway->fees_percentage > 0)
                                                                    {{ $gateway->fees_percentage }}%
                                                                @endif
                                                                @if($gateway->fees_fixed > 0)
                                                                    @if($gateway->fees_percentage > 0) + @endif
                                                                    {{ number_format($gateway->fees_fixed, 0, ',', ' ') }} XOF
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="payment-check">
                                                        <i class="fas fa-check-circle"></i>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Zone de paiement spécifique -->
                                <div id="payment-form-container" class="mt-4" style="display: none;">
                                    <!-- Le contenu sera injecté par JavaScript selon la passerelle choisie -->
                                </div>

                                <!-- Bouton de paiement -->
                                <div class="text-center mt-4">
                                    <button id="pay-button" class="btn btn-primary btn-lg px-5" disabled>
                                        <i class="fas fa-lock me-2"></i>
                                        <span id="pay-button-text">Choisir un moyen de paiement</span>
                                    </button>
                                </div>

                                <!-- Informations de sécurité -->
                                <div class="security-info mt-4 p-3 bg-light rounded">
                                    <h6 class="fw-bold mb-2">
                                        <i class="fas fa-shield-alt text-success me-2"></i>Paiement sécurisé
                                    </h6>
                                    <ul class="list-unstyled mb-0 small text-muted">
                                        <li><i class="fas fa-check text-success me-2"></i>Vos données de paiement sont cryptées</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Transactions sécurisées SSL</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Accès immédiat après paiement</li>
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <h5>Traitement du paiement...</h5>
                <p class="text-muted mb-0">Veuillez patienter, ne fermez pas cette page.</p>
            </div>
        </div>
    </div>
</div>

<style>
.checkout-step-indicator {
    display: flex;
    align-items: center;
    margin-right: 2rem;
}

.step {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 0.5rem;
}

.step.active {
    background: var(--primary);
    color: white;
}

.step-label {
    font-size: 0.9rem;
    color: #6c757d;
}

.payment-method {
    margin-bottom: 1rem;
}

.payment-method-card {
    display: block;
    padding: 1.5rem;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    color: inherit;
    background: white;
}

.payment-method-card:hover {
    border-color: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.payment-method-radio:checked + .payment-method-card {
    border-color: var(--primary);
    background: linear-gradient(135deg, rgba(30, 163, 139, 0.1), rgba(30, 163, 139, 0.05));
    box-shadow: 0 4px 12px rgba(30, 163, 139, 0.2);
}

.payment-icon {
    width: 64px;
    height: 64px;
    background: var(--gray-100);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
}

.payment-check {
    opacity: 0;
    color: var(--primary);
    font-size: 1.5rem;
    transition: opacity 0.3s ease;
}

.payment-method-radio:checked + .payment-method-card .payment-check {
    opacity: 1;
}

.total-amount {
    font-size: 1.2rem;
    color: var(--primary);
}

.security-info {
    border-left: 4px solid var(--success);
}

#pay-button {
    min-width: 250px;
    font-weight: 600;
}

#pay-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>
@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const course = @json($course);
    const paymentMethods = document.querySelectorAll('.payment-method-radio');
    const payButton = document.getElementById('pay-button');
    const payButtonText = document.getElementById('pay-button-text');
    const feesDisplay = document.getElementById('fees-display');
    const feesAmount = document.getElementById('fees-amount');
    const totalAmount = document.getElementById('total-amount');
    const formContainer = document.getElementById('payment-form-container');
    
    let selectedGateway = null;
    let stripe = null;
    
    // Gestionnaire pour la sélection de méthode de paiement
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            if (this.checked) {
                selectedGateway = this.value;
                updatePaymentForm(this.closest('.payment-method').dataset.gatewayId);
                updatePricing(this.closest('.payment-method').dataset.gatewayId);
                
                payButton.disabled = false;
                payButtonText.textContent = `Payer avec ${this.closest('.payment-method-card').querySelector('h6').textContent}`;
            }
        });
    });
    
    // Mettre à jour le formulaire de paiement selon la passerelle
    function updatePaymentForm(gatewayId) {
        formContainer.style.display = 'none';
        formContainer.innerHTML = '';
        
        fetch(`/api/payment-gateways/${gatewayId}/details`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const gateway = data.gateway;
                    
                    switch(gateway.name) {
                        case 'stripe':
                            setupStripeForm(gateway);
                            break;
                        case 'orange_money':
                            setupOrangeMoneyForm(gateway);
                            break;
                        default:
                            setupGenericForm(gateway);
                    }
                    
                    formContainer.style.display = 'block';
                }
            })
            .catch(error => console.error('Erreur:', error));
    }
    
    // Configuration du formulaire Stripe
    function setupStripeForm(gateway) {
        stripe = Stripe(gateway.config.publishable_key);
        
        const elements = stripe.elements({
            appearance: {
                theme: 'stripe',
                variables: {
                    colorPrimary: '#1ea38b',
                }
            }
        });
        
        formContainer.innerHTML = `
            <div class="stripe-form">
                <h6 class="mb-3">Informations de carte bancaire</h6>
                <div id="card-element" class="form-control" style="padding: 12px;"></div>
                <div id="card-errors" class="text-danger mt-2" style="display: none;"></div>
            </div>
        `;
        
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');
        
        cardElement.on('change', function(event) {
            const errorElement = document.getElementById('card-errors');
            if (event.error) {
                errorElement.textContent = event.error.message;
                errorElement.style.display = 'block';
            } else {
                errorElement.style.display = 'none';
            }
        });
    }
    
    // Configuration pour Orange Money
    function setupOrangeMoneyForm(gateway) {
        formContainer.innerHTML = `
            <div class="orange-money-form">
                <div class="alert alert-info">
                    <i class="fas fa-mobile-alt me-2"></i>
                    Vous serez redirigé vers la page Orange Money pour finaliser le paiement.
                </div>
            </div>
        `;
    }
    
    // Configuration générique
    function setupGenericForm(gateway) {
        formContainer.innerHTML = `
            <div class="generic-form">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Vous serez redirigé vers ${gateway.display_name} pour finaliser le paiement.
                </div>
            </div>
        `;
    }
    
    // Mettre à jour les prix avec les frais
    function updatePricing(gatewayId) {
        fetch(`/api/payment/calculate-fees`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                amount: course.price,
                gateway_id: gatewayId,
                currency: course.currency || 'XOF'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.fees > 0) {
                    feesDisplay.style.display = 'flex';
                    feesAmount.textContent = `${data.fees.toLocaleString()} ${data.currency}`;
                } else {
                    feesDisplay.style.display = 'none';
                }
                
                totalAmount.textContent = `${data.total.toLocaleString()} ${data.currency}`;
            }
        })
        .catch(error => console.error('Erreur calcul frais:', error));
    }
    
    // Gestionnaire du bouton de paiement
    payButton.addEventListener('click', function() {
        if (!selectedGateway) {
            alert('Veuillez choisir un moyen de paiement');
            return;
        }
        
        showLoading();
        
        // Initier le paiement
        fetch(`/courses/${course.id}/payment/initiate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                payment_gateway: selectedGateway
            })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            
            if (data.success) {
                handlePaymentResponse(data);
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        });
    });
    
    // Gérer la réponse selon le type de passerelle
    function handlePaymentResponse(data) {
        switch(selectedGateway) {
            case 'stripe':
                handleStripePayment(data);
                break;
            default:
                // Redirection pour les autres passerelles
                if (data.data.payment_url) {
                    window.location.href = data.data.payment_url;
                }
        }
    }
    
    // Traitement spécifique Stripe
    function handleStripePayment(data) {
        stripe.confirmCardPayment(data.data.client_secret, {
            payment_method: {
                card: stripe.elements().getElement('card'),
                billing_details: {
                    email: '{{ Auth::user()->email }}'
                }
            }
        }).then(function(result) {
            if (result.error) {
                alert('Erreur de paiement: ' + result.error.message);
            } else {
                // Succès - rediriger vers la page de confirmation
                window.location.href = `/payment/success/${data.payment_id}`;
            }
        });
    }
    
    function showLoading() {
        const modal = new bootstrap.Modal(document.getElementById('loadingModal'));
        modal.show();
    }
    
    function hideLoading() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('loadingModal'));
        if (modal) modal.hide();
    }
});
</script>
@endpush
