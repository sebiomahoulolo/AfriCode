@extends('layouts.app')

@section('title', 'Test FedaPay Integration')

@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-vial me-2"></i>Test d'Intégration FedaPay
                    </h4>
                </div>
                <div class="card-body p-4">
                    <!-- Configuration Status -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-cog me-2"></i>Configuration
                                    </h6>
                                    <div class="status-item">
                                        <span class="badge {{ config('services.fedapay.api_key') ? 'bg-success' : 'bg-danger' }}">
                                            API Key: {{ config('services.fedapay.api_key') ? 'Configurée' : 'Manquante' }}
                                        </span>
                                    </div>
                                    <div class="status-item mt-2">
                                        <span class="badge {{ config('services.fedapay.environment') ? 'bg-success' : 'bg-warning' }}">
                                            Environnement: {{ config('services.fedapay.environment', 'sandbox') }}
                                        </span>
                                    </div>
                                    <div class="status-item mt-2">
                                        <span class="badge bg-info">
                                            Webhook URL: {{ route('enrollment.fedapay.webhook') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-database me-2"></i>Base de Données
                                    </h6>
                                    <div class="status-item">
                                        <span class="badge bg-success">
                                            Cours: {{ \App\Models\Course::count() }} disponibles
                                        </span>
                                    </div>
                                    <div class="status-item mt-2">
                                        <span class="badge bg-info">
                                            Paiements: {{ \App\Models\Payment::count() }} enregistrés
                                        </span>
                                    </div>
                                    <div class="status-item mt-2">
                                        <span class="badge bg-warning">
                                            Inscriptions: {{ \App\Models\Enrollment::count() }} actives
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Test Actions -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">Test de Connexion API</h6>
                                </div>
                                <div class="card-body">
                                    <p class="small text-muted">Tester la connectivité avec l'API FedaPay</p>
                                    <button class="btn btn-primary btn-sm w-100" onclick="testApiConnection()">
                                        <i class="fas fa-plug me-2"></i>Tester la Connexion
                                    </button>
                                    <div id="api-test-result" class="mt-3"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">Test de Transaction</h6>
                                </div>
                                <div class="card-body">
                                    <p class="small text-muted">Créer une transaction de test</p>
                                    <select class="form-select form-select-sm mb-2" id="test-course">
                                        @foreach(\App\Models\Course::take(5)->get() as $course)
                                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-success btn-sm w-100" onclick="testTransaction()">
                                        <i class="fas fa-credit-card me-2"></i>Créer Transaction Test
                                    </button>
                                    <div id="transaction-test-result" class="mt-3"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0">Test Webhook</h6>
                                </div>
                                <div class="card-body">
                                    <p class="small text-muted">Simuler un webhook FedaPay</p>
                                    <select class="form-select form-select-sm mb-2" id="webhook-status">
                                        <option value="approved">Paiement Approuvé</option>
                                        <option value="declined">Paiement Refusé</option>
                                        <option value="canceled">Paiement Annulé</option>
                                    </select>
                                    <button class="btn btn-warning btn-sm w-100" onclick="testWebhook()">
                                        <i class="fas fa-webhook me-2"></i>Simuler Webhook
                                    </button>
                                    <div id="webhook-test-result" class="mt-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Payments -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-history me-2"></i>Paiements Récents
                                        <button class="btn btn-sm btn-outline-primary float-end" onclick="refreshPayments()">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover" id="payments-table">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Utilisateur</th>
                                                    <th>Cours</th>
                                                    <th>Montant</th>
                                                    <th>Gateway</th>
                                                    <th>Statut</th>
                                                    <th>Date</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach(\App\Models\Payment::with(['user', 'course'])->latest()->take(10)->get() as $payment)
                                                    <tr>
                                                        <td>{{ $payment->id }}</td>
                                                        <td>{{ $payment->user->name ?? 'N/A' }}</td>
                                                        <td>{{ Str::limit($payment->course->title ?? 'N/A', 30) }}</td>
                                                        <td>{{ number_format($payment->amount) }} {{ $payment->currency }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $payment->payment_gateway === 'fedapay' ? 'success' : 'info' }}">
                                                                {{ $payment->payment_gateway }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'failed' ? 'danger' : 'warning') }}">
                                                                {{ $payment->status }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-primary" onclick="viewPaymentDetails({{ $payment->id }})">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Console Log -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-terminal me-2"></i>Console de Test
                                        <button class="btn btn-sm btn-outline-danger float-end" onclick="clearConsole()">
                                            <i class="fas fa-trash"></i> Effacer
                                        </button>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div id="test-console" style="background: #1e1e1e; color: #00ff00; padding: 15px; border-radius: 5px; font-family: monospace; height: 200px; overflow-y: auto;">
                                        <div>FedaPay Test Console - Prêt</div>
                                        <div>================================</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour les détails de paiement -->
<div class="modal fade" id="paymentDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails du Paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="paymentDetailsContent">
                <!-- Contenu chargé dynamiquement -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function logToConsole(message, type = 'info') {
    const console = document.getElementById('test-console');
    const timestamp = new Date().toLocaleTimeString();
    const colorClass = type === 'error' ? 'text-danger' : (type === 'success' ? 'text-success' : 'text-info');
    
    console.innerHTML += `<div class="${colorClass}">[${timestamp}] ${message}</div>`;
    console.scrollTop = console.scrollHeight;
}

function clearConsole() {
    document.getElementById('test-console').innerHTML = 
        '<div>FedaPay Test Console - Prêt</div><div>================================</div>';
}

async function testApiConnection() {
    logToConsole('Test de connexion à l\'API FedaPay...', 'info');
    
    try {
        const response = await fetch('/test-fedapay/api-connection', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            logToConsole('✅ Connexion API réussie', 'success');
            document.getElementById('api-test-result').innerHTML = 
                '<div class="alert alert-success alert-sm">Connexion réussie</div>';
        } else {
            logToConsole('❌ Erreur de connexion: ' + result.message, 'error');
            document.getElementById('api-test-result').innerHTML = 
                '<div class="alert alert-danger alert-sm">' + result.message + '</div>';
        }
    } catch (error) {
        logToConsole('❌ Erreur réseau: ' + error.message, 'error');
        document.getElementById('api-test-result').innerHTML = 
            '<div class="alert alert-danger alert-sm">Erreur réseau</div>';
    }
}

async function testTransaction() {
    const courseId = document.getElementById('test-course').value;
    logToConsole(`Création d'une transaction test pour le cours ${courseId}...`, 'info');
    
    try {
        const response = await fetch('/test-fedapay/transaction', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ course_id: courseId })
        });
        
        const result = await response.json();
        
        if (result.success) {
            logToConsole('✅ Transaction créée: ' + result.transaction_id, 'success');
            document.getElementById('transaction-test-result').innerHTML = 
                `<div class="alert alert-success alert-sm">Transaction: ${result.transaction_id}</div>`;
        } else {
            logToConsole('❌ Erreur de transaction: ' + result.message, 'error');
            document.getElementById('transaction-test-result').innerHTML = 
                '<div class="alert alert-danger alert-sm">' + result.message + '</div>';
        }
    } catch (error) {
        logToConsole('❌ Erreur: ' + error.message, 'error');
    }
}

async function testWebhook() {
    const status = document.getElementById('webhook-status').value;
    logToConsole(`Simulation d'un webhook avec statut: ${status}...`, 'info');
    
    try {
        const response = await fetch('/test-fedapay/webhook', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ status: status })
        });
        
        const result = await response.json();
        
        if (result.success) {
            logToConsole('✅ Webhook simulé avec succès', 'success');
            document.getElementById('webhook-test-result').innerHTML = 
                '<div class="alert alert-success alert-sm">Webhook traité</div>';
        } else {
            logToConsole('❌ Erreur webhook: ' + result.message, 'error');
            document.getElementById('webhook-test-result').innerHTML = 
                '<div class="alert alert-danger alert-sm">' + result.message + '</div>';
        }
    } catch (error) {
        logToConsole('❌ Erreur: ' + error.message, 'error');
    }
}

function refreshPayments() {
    location.reload();
}

async function viewPaymentDetails(paymentId) {
    try {
        const response = await fetch(`/test-fedapay/payment/${paymentId}`);
        const result = await response.json();
        
        document.getElementById('paymentDetailsContent').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Informations de Base</h6>
                    <ul class="list-unstyled">
                        <li><strong>ID:</strong> ${result.id}</li>
                        <li><strong>Montant:</strong> ${result.amount} ${result.currency}</li>
                        <li><strong>Gateway:</strong> ${result.payment_gateway}</li>
                        <li><strong>Statut:</strong> ${result.status}</li>
                        <li><strong>Transaction ID:</strong> ${result.transaction_id || 'N/A'}</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6>Réponse Gateway</h6>
                    <pre class="bg-light p-3 rounded" style="font-size: 12px; max-height: 200px; overflow-y: auto;">
${JSON.stringify(result.gateway_response || {}, null, 2)}
                    </pre>
                </div>
            </div>
        `;
        
        new bootstrap.Modal(document.getElementById('paymentDetailsModal')).show();
        
    } catch (error) {
        console.error('Erreur lors du chargement des détails:', error);
    }
}

// Auto-refresh des paiements toutes les 30 secondes
setInterval(() => {
    // Vous pouvez implémenter un refresh automatique ici si nécessaire
}, 30000);
</script>
@endpush
