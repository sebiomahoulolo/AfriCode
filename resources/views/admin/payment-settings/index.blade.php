@extends('admin.layouts.app')

@section('title', 'Paramètres de Paiement')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Paramètres de Paiement</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item active">Paramètres de Paiement</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques de paiement -->
    <div class="row mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <p class="text-muted fw-normal mb-0">Total Paiements</p>
                            <h2 class="text-dark mt-1 fw-bold" id="total-payments">-</h2>
                        </div>
                        <div class="col-4">
                            <div class="text-end">
                                <i class="fas fa-credit-card text-primary" style="font-size: 24px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <p class="text-muted fw-normal mb-0">Paiements Réussis</p>
                            <h2 class="text-success mt-1 fw-bold" id="completed-payments">-</h2>
                        </div>
                        <div class="col-4">
                            <div class="text-end">
                                <i class="fas fa-check-circle text-success" style="font-size: 24px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <p class="text-muted fw-normal mb-0">FedaPay</p>
                            <h2 class="text-info mt-1 fw-bold" id="fedapay-payments">-</h2>
                        </div>
                        <div class="col-4">
                            <div class="text-end">
                                <i class="fas fa-mobile-alt text-info" style="font-size: 24px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <p class="text-muted fw-normal mb-0">Revenus Total</p>
                            <h2 class="text-warning mt-1 fw-bold" id="total-revenue">-</h2>
                        </div>
                        <div class="col-4">
                            <div class="text-end">
                                <i class="fas fa-coins text-warning" style="font-size: 24px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Configuration FedaPay -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-mobile-alt me-2"></i>Configuration FedaPay
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.payment-settings.fedapay.update') }}" method="POST" id="fedapay-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="fedapay_api_key" class="form-label">Clé API (Privée)</label>
                            <input type="password" class="form-control" id="fedapay_api_key" name="api_key" 
                                   placeholder="sk_live_..." value="{{ old('api_key') }}" required>
                            <small class="form-text text-muted">
                                Commence par sk_live_ pour la production ou sk_sandbox_ pour les tests
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="fedapay_public_key" class="form-label">Clé Publique</label>
                            <input type="text" class="form-control" id="fedapay_public_key" name="public_key" 
                                   placeholder="pk_live_..." value="{{ old('public_key') }}" required>
                            <small class="form-text text-muted">
                                Commence par pk_live_ pour la production ou pk_sandbox_ pour les tests
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="fedapay_secret" class="form-label">Secret Webhook (Optionnel)</label>
                            <input type="password" class="form-control" id="fedapay_secret" name="secret" 
                                   placeholder="Secret pour la validation des webhooks" value="{{ old('secret') }}">
                        </div>

                        <div class="mb-3">
                            <label for="fedapay_environment" class="form-label">Environnement</label>
                            <select class="form-select" id="fedapay_environment" name="environment" required>
                                <option value="live" {{ config('services.fadapay.environment') === 'live' ? 'selected' : '' }}>
                                    Production (Live)
                                </option>
                                <option value="sandbox" {{ config('services.fadapay.environment') === 'sandbox' ? 'selected' : '' }}>
                                    Test (Sandbox)
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="fedapay_currency" class="form-label">Devise par Défaut</label>
                            <select class="form-select" id="fedapay_currency" name="currency" required>
                                <option value="XOF" {{ config('services.fadapay.currency') === 'XOF' ? 'selected' : '' }}>
                                    Franc CFA (XOF)
                                </option>
                                <option value="USD" {{ config('services.fadapay.currency') === 'USD' ? 'selected' : '' }}>
                                    Dollar US (USD)
                                </option>
                                <option value="EUR" {{ config('services.fadapay.currency') === 'EUR' ? 'selected' : '' }}>
                                    Euro (EUR)
                                </option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-save me-2"></i>Sauvegarder
                                </button>
                            </div>
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-outline-info w-100" onclick="testFedapayConnection()">
                                    <i class="fas fa-wifi me-2"></i>Tester la Connexion
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- État actuel -->
                    <hr class="mt-4">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="p-2">
                                <h6 class="mb-1">État</h6>
                                <span class="badge {{ $settings['fedapay']['api_key'] ? 'bg-success' : 'bg-danger' }}">
                                    {{ $settings['fedapay']['api_key'] ? 'Configuré' : 'Non configuré' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2">
                                <h6 class="mb-1">Environnement</h6>
                                <span class="badge {{ $settings['fedapay']['environment'] === 'live' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($settings['fedapay']['environment']) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuration Stripe -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fab fa-stripe me-2"></i>Configuration Stripe
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.payment-settings.stripe.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="stripe_key" class="form-label">Clé Publique</label>
                            <input type="text" class="form-control" id="stripe_key" name="key" 
                                   placeholder="pk_live_..." value="{{ old('key') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="stripe_secret" class="form-label">Clé Secrète</label>
                            <input type="password" class="form-control" id="stripe_secret" name="secret" 
                                   placeholder="sk_live_..." value="{{ old('secret') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="stripe_webhook_secret" class="form-label">Secret Webhook</label>
                            <input type="password" class="form-control" id="stripe_webhook_secret" name="webhook_secret" 
                                   placeholder="whsec_..." value="{{ old('webhook_secret') }}">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Sauvegarder
                        </button>
                    </form>

                    <!-- État actuel -->
                    <hr class="mt-4">
                    <div class="text-center">
                        <h6 class="mb-1">État</h6>
                        <span class="badge {{ $settings['stripe']['secret'] ? 'bg-success' : 'bg-danger' }}">
                            {{ $settings['stripe']['secret'] ? 'Configuré' : 'Non configuré' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations complémentaires -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informations Importantes
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>FedaPay</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Supporte Orange, MTN, Moov, Wave</li>
                                <li><i class="fas fa-check text-success me-2"></i>Paiements en Franc CFA (XOF)</li>
                                <li><i class="fas fa-check text-success me-2"></i>Fees compétitives</li>
                                <li><i class="fas fa-globe text-info me-2"></i>
                                    URL Webhook: <code>{{ route('payment.fedapay.webhook') }}</code>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Stripe</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Cartes bancaires internationales</li>
                                <li><i class="fas fa-check text-success me-2"></i>Support multi-devises</li>
                                <li><i class="fas fa-check text-success me-2"></i>Sécurité renforcée</li>
                                <li><i class="fas fa-globe text-info me-2"></i>
                                    URL Webhook: <code>{{ route('webhooks.stripe') }}</code>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Charger les statistiques
    loadPaymentStats();
});

function loadPaymentStats() {
    fetch('{{ route("admin.payment-settings.stats") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-payments').textContent = data.total_payments;
            document.getElementById('completed-payments').textContent = data.completed_payments;
            document.getElementById('fedapay-payments').textContent = data.fedapay_payments;
            document.getElementById('total-revenue').textContent = new Intl.NumberFormat('fr-FR').format(data.total_revenue) + ' XOF';
        })
        .catch(error => {
            console.error('Erreur lors du chargement des statistiques:', error);
        });
}

function testFedapayConnection() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Test en cours...';
    
    fetch('{{ route("admin.payment-settings.fedapay.test") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Connexion réussie !',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 3000
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur de connexion',
                    text: data.message
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Erreur lors du test de connexion'
            });
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = originalText;
        });
}
</script>
@endpush
@endsection
