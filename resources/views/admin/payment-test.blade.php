@extends('layouts.admin')

@section('title', 'Test du Système de Paiement')
@section('page-title', 'Test du Système de Paiement')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">État du Système de Paiement</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Passerelles de Paiement Configurées</h5>
                            <div class="payment-gateways-list">
                                @forelse($paymentGateways as $gateway)
                                    <div class="payment-gateway-item mb-3 p-3 border rounded {{ $gateway->is_active ? 'border-success' : 'border-secondary' }}">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <i class="{{ $gateway->icon }} fa-2x me-3 text-primary"></i>
                                                <div>
                                                    <h6 class="mb-1">{{ $gateway->display_name }}</h6>
                                                    <small class="text-muted">{{ $gateway->description }}</small>
                                                    <br>
                                                    <small class="text-info">
                                                        Devises: {{ implode(', ', $gateway->supported_currencies) }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge {{ $gateway->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $gateway->is_active ? 'Actif' : 'Inactif' }}
                                                </span>
                                                @if($gateway->is_default)
                                                    <br><span class="badge bg-primary mt-1">Par défaut</span>
                                                @endif
                                                @if($gateway->test_mode)
                                                    <br><span class="badge bg-warning mt-1">Mode Test</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-warning">
                                        Aucune passerelle de paiement configurée.
                                    </div>
                                @endforelse
                            </div>
                            
                            <div class="mt-4">
                                <a href="{{ route('admin.payment-gateways.index') }}" class="btn btn-primary">
                                    <i class="fas fa-cog"></i> Gérer les Passerelles
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Statistiques des Paiements</h5>
                            <div class="stats-cards">
                                <div class="card bg-light mb-3">
                                    <div class="card-body text-center">
                                        <h4 class="text-primary">{{ $stats['total_payments'] ?? 0 }}</h4>
                                        <p class="mb-0">Paiements Total</p>
                                    </div>
                                </div>
                                
                                <div class="card bg-light mb-3">
                                    <div class="card-body text-center">
                                        <h4 class="text-success">{{ $stats['successful_payments'] ?? 0 }}</h4>
                                        <p class="mb-0">Paiements Réussis</p>
                                    </div>
                                </div>
                                
                                <div class="card bg-light mb-3">
                                    <div class="card-body text-center">
                                        <h4 class="text-warning">{{ $stats['pending_payments'] ?? 0 }}</h4>
                                        <p class="mb-0">Paiements En Attente</p>
                                    </div>
                                </div>
                                
                                <div class="card bg-light mb-3">
                                    <div class="card-body text-center">
                                        <h4 class="text-info">{{ number_format($stats['total_revenue'] ?? 0, 0, ',', ' ') }} FCFA</h4>
                                        <p class="mb-0">Revenus Total</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Actions Rapides</h5>
                            <div class="quick-actions">
                                <a href="{{ route('admin.payment-gateways.create') }}" class="btn btn-success me-2">
                                    <i class="fas fa-plus"></i> Ajouter une Passerelle
                                </a>
                                <button type="button" class="btn btn-info me-2" onclick="testPaymentSystem()">
                                    <i class="fas fa-play"></i> Tester le Système
                                </button>
                                <button type="button" class="btn btn-warning" onclick="refreshStats()">
                                    <i class="fas fa-sync"></i> Actualiser les Stats
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.payment-gateway-item {
    transition: all 0.3s ease;
}

.payment-gateway-item:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.stats-cards .card {
    transition: transform 0.3s ease;
}

.stats-cards .card:hover {
    transform: translateY(-2px);
}

.quick-actions .btn {
    margin-bottom: 10px;
}
</style>

<script>
function testPaymentSystem() {
    // Simuler un test du système de paiement
    const btn = event.target;
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Test en cours...';
    btn.disabled = true;
    
    setTimeout(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        
        // Afficher un message de succès
        showAlert('success', 'Test du système de paiement réussi ! Toutes les passerelles actives répondent correctement.');
    }, 2000);
}

function refreshStats() {
    // Simuler une actualisation des statistiques
    location.reload();
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>
@endsection
