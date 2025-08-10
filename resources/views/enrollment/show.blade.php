@extends('layouts.layout')

@section('title', __('messages.enrollment_title', ['course' => $course->title]))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Course Info -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            @if($course->cover_image_path)
                                <img src="{{ asset($course->cover_image_path) }}" alt="{{ $course->title }}" class="img-fluid rounded">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 120px;">
                                    <i class="fas fa-book fa-3x " style="color: #e67e22"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <h2 class="h4 mb-2">{{ $course->title }}</h2>
                            <p class="text-muted mb-2">{{ $course->short_description }}</p>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-info me-2">{{ ucfirst($course->level) }}</span>
                                <span class="text-muted">
                                    <i class="fas fa-user me-1" style="color: #e67e22"></i>{{ $course->formateur->first_name }} {{ $course->formateur->last_name }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enrollment Form -->
            <div class="card">
                <div class="card-header">
                    <h3 class="h5 mb-0">
                        <i class="fas fa-graduation-cap me-2" style="color: #e67e22"></i>Inscription au cours
                    </h3>
                </div>
                <div class="card-body">
                    @if($course->price == 0)
                        <!-- Free Course Enrollment -->
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <i class="fas fa-gift fa-3x  mb-3" style="color: #e67e22"></i>
                                <h4>Cours Gratuit</h4>
                                <p class="text-muted">Ce cours est entièrement gratuit. Vous pouvez vous inscrire immédiatement.</p>
                            </div>
                        </div>
                        
                        <form action="{{ route('enrollment.process-payment', $course) }}" method="POST">
                            @csrf
                            <div class="text-center">
                                <button type="submit" class="btn btn-success btn-lg px-5">
                                    <i class="fas fa-check me-2" style="color: #e67e22"></i>S'inscrire gratuitement
                                </button>
                            </div>
                        </form>
                    @else
                        <!-- Paid Course Enrollment -->
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <h3 class="text-primary">
                                    {{ number_format($course->price, 0) }} {{ $course->currency ?? 'XOF' }}
                                </h3>
                                <p class="text-muted">Choisissez votre méthode de paiement</p>
                            </div>
                        </div>

                        <form action="{{ route('enrollment.process-payment', $course) }}" method="POST" id="enrollmentForm">
                            @csrf
                            
                            <!-- Payment Methods -->
                            <div class="payment-methods mb-4">
                                <div class="row g-3 justify-content-center">
                                    <!-- FedaPay Option (Primary) -->
                                    <div class="col-md-8">
                                        <div class="payment-option">
                                            <input type="radio" name="payment_method" value="fedapay" id="fedapay" class="payment-radio" required checked>
                                            <label for="fedapay" class="payment-label">
                                                <div class="payment-card featured">
                                                    <div class="payment-icon">
                                                        <i class="fas fa-mobile-alt"></i>
                                                    </div>
                                                    <div class="payment-info">
                                                        <h5>Mobile Money</h5>
                                                        <p>Orange Money, MTN Money, Moov Money, Wave</p>
                                                        <small class="badge bg-success">Recommandé</small>
                                                    </div>
                                                    <div class="payment-check">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <!-- Stripe Option (Secondary - commented out) -->
                                    <!--
                                    <div class="col-md-6">
                                        <div class="payment-option">
                                            <input type="radio" name="payment_method" value="stripe" id="stripe" class="payment-radio" required>
                                            <label for="stripe" class="payment-label">
                                                <div class="payment-card">
                                                    <div class="payment-icon">
                                                        <i class="fab fa-cc-stripe"></i>
                                                    </div>
                                                    <div class="payment-info">
                                                        <h5>Carte Bancaire</h5>
                                                        <p>Visa, Mastercard, etc.</p>
                                                    </div>
                                                    <div class="payment-check">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    -->
                                </div>
                            </div>

                            <!-- Security Info -->
                            <div class="security-info mb-4">
                                <div class="row text-center">
                                    <div class="col-md-4">
                                        <i class="fas fa-shield-alt text-success mb-2"></i>
                                        <p class="small">Paiement sécurisé</p>
                                    </div>
                                    <div class="col-md-4">
                                        <i class="fas fa-lock text-success mb-2"></i>
                                        <p class="small">Données chiffrées</p>
                                    </div>
                                    <div class="col-md-4">
                                        <i class="fas fa-undo text-success mb-2"></i>
                                        <p class="small">Remboursement 30j</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5" id="paymentBtn">
                                    <i class="fas fa-mobile-alt me-2"></i>Payer par Mobile Money
                                </button>
                            </div>
                        </form>
                    @endif

                    <!-- Course Benefits -->
                    <div class="mt-5">
                        <h5 class="mb-3">Ce que vous obtiendrez :</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Accès à vie au cours</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ $course->modules->count() }} modules de formation</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Exercices pratiques</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Support communautaire</li>
                            @if($course->is_certifying)
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Certificat de fin de formation</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.payment-methods {
    max-width: 600px;
    margin: 0 auto;
}

.payment-option {
    position: relative;
}

.payment-radio {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.payment-label {
    cursor: pointer;
    width: 100%;
    margin: 0;
}

.payment-card {
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    background: white;
    height: 120px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.payment-card:hover {
    border-color: #007bff;
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.15);
}

.payment-radio:checked + .payment-label .payment-card {
    border-color: #007bff;
    background: linear-gradient(135deg, #f8f9ff 0%, #e3f2fd 100%);
}

.payment-card.featured {
    border-color: #28a745;
    background: linear-gradient(135deg, #f8fff9 0%, #e8f5e8 100%);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.15);
}

.payment-card.featured .payment-icon {
    color: #28a745;
}

.payment-radio:checked + .payment-label .payment-card.featured {
    border-color: #28a745;
    background: linear-gradient(135deg, #f0fff4 0%, #d4edda 100%);
}

.payment-icon {
    font-size: 2rem;
    color: #007bff;
    margin-bottom: 8px;
}

.payment-info h5 {
    margin: 0 0 4px 0;
    font-size: 1rem;
    color: #333;
}

.payment-info p {
    margin: 0;
    font-size: 0.85rem;
    color: #666;
}

.payment-check {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #007bff;
    color: white;
    display: none;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
}

.payment-radio:checked + .payment-label .payment-check {
    display: flex;
}

.security-info {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
}

.security-info i {
    font-size: 1.5rem;
    display: block;
}

@media (max-width: 768px) {
    .payment-card {
        height: 100px;
        padding: 15px;
    }
    
    .payment-icon {
        font-size: 1.5rem;
    }
    
    .payment-info h5 {
        font-size: 0.9rem;
    }
    
    .payment-info p {
        font-size: 0.8rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('enrollmentForm');
    const paymentBtn = document.getElementById('paymentBtn');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            // Vérifier que FedaPay est sélectionné (devrait l'être par défaut)
            const selectedPayment = document.querySelector('.payment-radio:checked');
            
            if (!selectedPayment) {
                e.preventDefault();
                alert('Veuillez sélectionner une méthode de paiement');
                return;
            }
            
            // Désactiver le bouton pour éviter les soumissions multiples
            paymentBtn.disabled = true;
            paymentBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Redirection vers FedaPay...';
            
            // Log pour debugging
            console.log('Submitting form with payment method:', selectedPayment.value);
        });
    }
});
</script>
@endsection
