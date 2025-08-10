@extends('admin.layouts.app')

@section('breadcrumb', 'Passerelles de paiement')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="admin-card gradient-header">
                <div class="admin-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="admin-card-title mb-2">
                                <i class="fas fa-credit-card me-3 text-primary"></i>Gestion des passerelles de paiement
                            </h1>
                            <p class="admin-card-subtitle mb-0">Configurez et gérez les moyens de paiement disponibles sur votre plateforme</p>
                        </div>
                        <div class="action-buttons d-none d-md-flex">
                            <a href="{{ route('admin.payment-gateways.create') }}" class="btn btn-primary me-2">
                                <i class="fas fa-plus me-2"></i>Ajouter une passerelle
                            </a>
                            <button class="btn btn-outline-primary" onclick="installDefaults()">
                                <i class="fas fa-download me-2"></i>Installer par défaut
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row mb-4 g-3">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-card stat-card-primary">
                <div class="stat-card-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-label">Passerelles actives</div>
                    <div class="stat-number">{{ $gateways->where('is_active', true)->count() }}</div>
                    <div class="stat-trend">
                        <span>sur {{ $gateways->count() }} total</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-card stat-card-success">
                <div class="stat-card-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-label">Paiements réussis</div>
                    <div class="stat-number">{{ number_format($stats['successful_payments'] ?? 0) }}</div>
                    <div class="stat-trend positive">
                        <span>{{ number_format(($stats['successful_payments'] ?? 0) / max($stats['total_payments'] ?? 1, 1) * 100, 1) }}% de succès</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-card stat-card-info">
                <div class="stat-card-icon">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-label">Revenus totaux</div>
                    <div class="stat-number">{{ number_format($stats['total_amount'] ?? 0, 0, ',', ' ') }}</div>
                    <div class="stat-trend">
                        <span>XOF</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-card stat-card-warning">
                <div class="stat-card-icon">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-label">Frais collectés</div>
                    <div class="stat-number">{{ number_format($stats['total_fees'] ?? 0, 0, ',', ' ') }}</div>
                    <div class="stat-trend">
                        <span>XOF</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des passerelles -->
    <div class="row">
        <div class="col-12">
            <div class="admin-card">
                <div class="admin-card-body">
                    @if($gateways->isEmpty())
                        <div class="empty-state text-center py-5">
                            <i class="fas fa-credit-card fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">Aucune passerelle configurée</h4>
                            <p class="text-muted mb-4">Commencez par ajouter vos premières passerelles de paiement</p>
                            <a href="{{ route('admin.payment-gateways.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Ajouter une passerelle
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Passerelle</th>
                                        <th>Statut</th>
                                        <th>Devises</th>
                                        <th>Frais</th>
                                        <th>Paiements</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($gateways as $gateway)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="gateway-icon me-3">
                                                    <i class="{{ $gateway->icon }} fa-2x"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">{{ $gateway->display_name }}</h6>
                                                    <small class="text-muted">{{ $gateway->description }}</small>
                                                    @if($gateway->is_default)
                                                        <span class="badge bg-success ms-2">Par défaut</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge {{ $gateway->is_active ? 'bg-success' : 'bg-secondary' }} me-2">
                                                    {{ $gateway->is_active ? 'Actif' : 'Inactif' }}
                                                </span>
                                                @if($gateway->test_mode)
                                                    <span class="badge bg-warning">Test</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($gateway->supported_currencies)
                                                @foreach($gateway->supported_currencies as $currency)
                                                    <span class="badge bg-light text-dark me-1">{{ $currency }}</span>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @if($gateway->fees_percentage > 0 || $gateway->fees_fixed > 0)
                                                <small>
                                                    @if($gateway->fees_percentage > 0)
                                                        {{ $gateway->fees_percentage }}%
                                                    @endif
                                                    @if($gateway->fees_fixed > 0)
                                                        + {{ number_format($gateway->fees_fixed, 0, ',', ' ') }} XOF
                                                    @endif
                                                </small>
                                            @else
                                                <span class="text-muted">Aucun</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="payment-stats">
                                                <div class="fw-bold">{{ $gateway->payments->count() }}</div>
                                                <small class="text-success">
                                                    {{ $gateway->payments->where('status', 'completed')->count() }} réussis
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.payment-gateways.show', $gateway) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.payment-gateways.edit', $gateway) }}" 
                                                   class="btn btn-sm btn-outline-secondary" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-sm btn-outline-{{ $gateway->is_active ? 'warning' : 'success' }}" 
                                                        onclick="toggleGateway({{ $gateway->id }})" 
                                                        title="{{ $gateway->is_active ? 'Désactiver' : 'Activer' }}">
                                                    <i class="fas fa-{{ $gateway->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                                @if($gateway->is_active && !$gateway->is_default)
                                                    <button class="btn btn-sm btn-outline-info" 
                                                            onclick="setDefault({{ $gateway->id }})" 
                                                            title="Définir par défaut">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                @endif
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        onclick="testGateway({{ $gateway->id }})" 
                                                        title="Tester la configuration">
                                                    <i class="fas fa-vial"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.gateway-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--gray-100);
    border-radius: 8px;
}

.payment-stats {
    text-align: center;
}

.empty-state {
    padding: 3rem 1rem;
}
</style>
@endsection

@push('scripts')
<script>
function toggleGateway(gatewayId) {
    fetch(`/admin/payment-gateways/${gatewayId}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Une erreur est survenue');
    });
}

function setDefault(gatewayId) {
    fetch(`/admin/payment-gateways/${gatewayId}/set-default`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Une erreur est survenue');
    });
}

function testGateway(gatewayId) {
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;

    fetch(`/admin/payment-gateways/${gatewayId}/test`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Une erreur est survenue lors du test');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function installDefaults() {
    if (confirm('Installer les passerelles par défaut? Cela ajoutera toutes les passerelles supportées (inactives par défaut).')) {
        window.location.href = '{{ route("admin.payment-gateways.install-defaults") }}';
    }
}

function showAlert(type, message) {
    // Fonction utilitaire pour afficher des alertes
    const alert = document.createElement('div');
    alert.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container-fluid');
    container.insertBefore(alert, container.firstChild);
    
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}
</script>
@endpush
