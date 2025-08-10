@extends('admin.layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.payment-gateways.index') }}">Passerelles de paiement</a></li>
        <li class="breadcrumb-item active">Ajouter une passerelle</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="admin-card">
                <div class="admin-card-body">
                    <h2 class="admin-card-title mb-4">
                        <i class="fas fa-plus-circle me-2 text-primary"></i>Ajouter une passerelle de paiement
                    </h2>

                    @if(empty($newGateways))
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Toutes les passerelles disponibles ont déjà été ajoutées. 
                            <a href="{{ route('admin.payment-gateways.index') }}">Retour à la liste</a>
                        </div>
                    @else
                        <form action="{{ route('admin.payment-gateways.store') }}" method="POST" id="gatewayForm">
                            @csrf
                            
                            <!-- Sélection du type de passerelle -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Choisir le type de passerelle *</label>
                                <div class="row g-3">
                                    @foreach($newGateways as $key => $gateway)
                                        <div class="col-md-6 col-lg-4">
                                            <div class="gateway-option">
                                                <input type="radio" name="gateway_type" value="{{ $key }}" 
                                                       id="gateway_{{ $key }}" class="gateway-radio d-none" 
                                                       onchange="updateConfigFields()">
                                                <label for="gateway_{{ $key }}" class="gateway-card">
                                                    <div class="gateway-icon">
                                                        <i class="{{ $gateway['icon'] }} fa-2x"></i>
                                                    </div>
                                                    <h5>{{ $gateway['name'] }}</h5>
                                                    <p class="text-muted small">{{ $gateway['description'] }}</p>
                                                    <div class="currencies">
                                                        @foreach($gateway['currencies'] as $currency)
                                                            <span class="badge bg-light text-dark">{{ $currency }}</span>
                                                        @endforeach
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('gateway_type')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Configuration de base -->
                            <div class="gateway-config" style="display: none;">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="display_name" class="form-label">Nom d'affichage *</label>
                                        <input type="text" class="form-control @error('display_name') is-invalid @enderror" 
                                               id="display_name" name="display_name" value="{{ old('display_name') }}" required>
                                        @error('display_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="order_priority" class="form-label">Ordre d'affichage</label>
                                        <input type="number" class="form-control @error('order_priority') is-invalid @enderror" 
                                               id="order_priority" name="order_priority" value="{{ old('order_priority', 0) }}" min="0">
                                        @error('order_priority')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="2">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Limites et frais -->
                                <div class="row g-3 mb-3">
                                    <div class="col-md-3">
                                        <label for="min_amount" class="form-label">Montant minimum</label>
                                        <input type="number" class="form-control @error('min_amount') is-invalid @enderror" 
                                               id="min_amount" name="min_amount" value="{{ old('min_amount') }}" step="0.01">
                                        @error('min_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label for="max_amount" class="form-label">Montant maximum</label>
                                        <input type="number" class="form-control @error('max_amount') is-invalid @enderror" 
                                               id="max_amount" name="max_amount" value="{{ old('max_amount') }}" step="0.01">
                                        @error('max_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label for="fees_percentage" class="form-label">Frais (%)</label>
                                        <input type="number" class="form-control @error('fees_percentage') is-invalid @enderror" 
                                               id="fees_percentage" name="fees_percentage" value="{{ old('fees_percentage', 0) }}" 
                                               step="0.01" min="0" max="100">
                                        @error('fees_percentage')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label for="fees_fixed" class="form-label">Frais fixes</label>
                                        <input type="number" class="form-control @error('fees_fixed') is-invalid @enderror" 
                                               id="fees_fixed" name="fees_fixed" value="{{ old('fees_fixed', 0) }}" 
                                               step="0.01" min="0">
                                        @error('fees_fixed')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Devises supportées -->
                                <div class="mb-3">
                                    <label class="form-label">Devises supportées</label>
                                    <div class="currencies-checkboxes">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="supported_currencies[]" 
                                                   value="XOF" id="currency_xof" checked>
                                            <label class="form-check-label" for="currency_xof">XOF (Franc CFA)</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="supported_currencies[]" 
                                                   value="USD" id="currency_usd">
                                            <label class="form-check-label" for="currency_usd">USD</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="supported_currencies[]" 
                                                   value="EUR" id="currency_eur">
                                            <label class="form-check-label" for="currency_eur">EUR</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Options -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" 
                                                   id="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">Activer immédiatement</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="test_mode" 
                                                   id="test_mode" value="1" checked>
                                            <label class="form-check-label" for="test_mode">Mode test</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Configuration spécifique -->
                                <div id="specific-config" class="border-top pt-4">
                                    <h5 class="mb-3">Configuration spécifique</h5>
                                    <div id="config-fields"></div>
                                </div>

                                <!-- Boutons -->
                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('admin.payment-gateways.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Annuler
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Créer la passerelle
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.gateway-option {
    height: 100%;
}

.gateway-card {
    display: block;
    padding: 1.5rem;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    height: 100%;
    text-decoration: none;
    color: inherit;
}

.gateway-card:hover {
    border-color: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.gateway-radio:checked + .gateway-card {
    border-color: var(--primary);
    background: linear-gradient(135deg, rgba(30, 163, 139, 0.1), rgba(30, 163, 139, 0.05));
    box-shadow: 0 4px 12px rgba(30, 163, 139, 0.2);
}

.gateway-icon {
    width: 64px;
    height: 64px;
    background: var(--gray-100);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: var(--primary);
}

.gateway-card h5 {
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.currencies {
    margin-top: 1rem;
}

.currencies .badge {
    margin-right: 0.25rem;
}
</style>
@endsection

@push('scripts')
<script>
const gatewayConfigs = {!! json_encode($newGateways) !!};

function updateConfigFields() {
    const selectedGateway = document.querySelector('input[name="gateway_type"]:checked');
    const configContainer = document.querySelector('.gateway-config');
    const configFields = document.getElementById('config-fields');
    const displayNameInput = document.getElementById('display_name');
    
    if (selectedGateway) {
        const gatewayType = selectedGateway.value;
        const gatewayData = gatewayConfigs[gatewayType];
        
        // Afficher la section de configuration
        configContainer.style.display = 'block';
        
        // Mettre à jour le nom d'affichage par défaut
        if (!displayNameInput.value) {
            displayNameInput.value = gatewayData.name;
        }
        
        // Mettre à jour les devises supportées
        updateSupportedCurrencies(gatewayData.currencies);
        
        // Générer les champs de configuration spécifiques
        generateConfigFields(gatewayType, gatewayData.config_fields);
    } else {
        configContainer.style.display = 'none';
    }
}

function updateSupportedCurrencies(supportedCurrencies) {
    const checkboxes = document.querySelectorAll('input[name="supported_currencies[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = supportedCurrencies.includes(checkbox.value);
    });
}

function generateConfigFields(gatewayType, configFields) {
    const container = document.getElementById('config-fields');
    container.innerHTML = '';
    
    if (!configFields || Object.keys(configFields).length === 0) {
        container.innerHTML = '<p class="text-muted">Aucune configuration spécifique requise.</p>';
        return;
    }
    
    const row = document.createElement('div');
    row.className = 'row g-3';
    
    Object.entries(configFields).forEach(([field, label]) => {
        const col = document.createElement('div');
        col.className = 'col-md-6';
        
        const isSecret = field.includes('secret') || field.includes('password') || field.includes('key');
        const inputType = isSecret ? 'password' : 'text';
        
        col.innerHTML = `
            <label for="config_${field}" class="form-label">${label}</label>
            <input type="${inputType}" 
                   class="form-control" 
                   id="config_${field}" 
                   name="config[${field}]" 
                   placeholder="Entrez votre ${label.toLowerCase()}">
            <div class="form-text">
                ${getFieldHelp(field)}
            </div>
        `;
        
        row.appendChild(col);
    });
    
    container.appendChild(row);
}

function getFieldHelp(field) {
    const helpTexts = {
        'publishable_key': 'Votre clé publique commençant par pk_',
        'secret_key': 'Votre clé secrète commençant par sk_',
        'webhook_secret': 'Secret pour vérifier les webhooks',
        'client_id': 'Identifiant client de votre application',
        'client_secret': 'Secret client de votre application',
        'merchant_key': 'Clé marchand fournie par votre fournisseur',
        'api_key': 'Clé API pour l\'authentification',
        'api_url': 'URL de base de l\'API'
    };
    
    return helpTexts[field] || 'Consultez la documentation de votre fournisseur';
}

// Initialiser si une option est déjà sélectionnée
document.addEventListener('DOMContentLoaded', function() {
    const selectedGateway = document.querySelector('input[name="gateway_type"]:checked');
    if (selectedGateway) {
        updateConfigFields();
    }
});
</script>
@endpush
