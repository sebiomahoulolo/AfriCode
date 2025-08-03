@extends('admin.layouts.app')

@section('breadcrumb', 'Paiements')

@push('styles')
<style>
.payments-dashboard {
    display: grid;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-card.revenue {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.stat-card.refunds {
    background: linear-gradient(135deg, #fc466b 0%, #3f5efb 100%);
}

.stat-card.pending {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
    color: #333;
}

.stat-value {
    font-size: 2rem;
    font-weight: bold;
    margin: 0.5rem 0;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.payments-table-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    overflow: hidden;
}

.table-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.filters-section {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    align-items: center;
}

.filter-input {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    backdrop-filter: blur(10px);
}

.filter-input::placeholder {
    color: rgba(255,255,255,0.7);
}

.payments-table {
    width: 100%;
    border-collapse: collapse;
}

.payments-table th,
.payments-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.payments-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #333;
}

.payments-table tbody tr:hover {
    background: #f8f9fa;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-completed {
    background: #d4edda;
    color: #155724;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-failed {
    background: #f8d7da;
    color: #721c24;
}

.status-refunded {
    background: #d1ecf1;
    color: #0c5460;
}

.payment-method {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.method-icon {
    width: 20px;
    height: 20px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    color: white;
}

.method-stripe {
    background: #635bff;
}

.method-paypal {
    background: #0070ba;
}

.method-card {
    background: #333;
}

.actions-menu {
    position: relative;
    display: inline-block;
}

.actions-btn {
    background: none;
    border: none;
    padding: 0.5rem;
    cursor: pointer;
    border-radius: 6px;
    transition: background 0.2s;
}

.actions-btn:hover {
    background: #f0f0f0;
}

.actions-dropdown {
    display: none;
    position: absolute;
    right: 0;
    top: 100%;
    background: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border-radius: 8px;
    padding: 0.5rem 0;
    min-width: 150px;
    z-index: 100;
}

.actions-dropdown.show {
    display: block;
}

.dropdown-item {
    display: block;
    padding: 0.5rem 1rem;
    color: #333;
    text-decoration: none;
    transition: background 0.2s;
}

.dropdown-item:hover {
    background: #f8f9fa;
}

.pagination-container {
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8f9fa;
}

.chart-container {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    margin-bottom: 2rem;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .table-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filters-section {
        justify-content: center;
    }
    
    .payments-table-container {
        overflow-x: auto;
    }
    
    .payments-table {
        min-width: 800px;
    }
}
</style>
@endpush

@section('content')
<div class="payments-dashboard">
    <!-- Statistiques des paiements -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total des paiements</div>
            <div class="stat-value">{{ $totalPayments ?? 0 }}</div>
            <small>Ce mois</small>
        </div>
        <div class="stat-card revenue">
            <div class="stat-label">Revenus générés</div>
            <div class="stat-value">{{ number_format($totalRevenue ?? 0, 0, ',', ' ') }} FCFA</div>
            <small>Ce mois</small>
        </div>
        <div class="stat-card refunds">
            <div class="stat-label">Remboursements</div>
            <div class="stat-value">{{ $totalRefunds ?? 0 }}</div>
            <small>Ce mois</small>
        </div>
        <div class="stat-card pending">
            <div class="stat-label">En attente</div>
            <div class="stat-value">{{ $pendingPayments ?? 0 }}</div>
            <small>Paiements</small>
        </div>
    </div>

    <!-- Graphique des revenus -->
    <div class="chart-container">
        <h3 style="margin-bottom: 1rem; color: #333;">Évolution des revenus</h3>
        <canvas id="revenueChart" width="400" height="120"></canvas>
    </div>

    <!-- Table des paiements -->
    <div class="payments-table-container">
        <div class="table-header">
            <h2 style="margin: 0;">Tous les paiements</h2>
            <div class="filters-section">
                <input type="text" class="filter-input" placeholder="Rechercher par utilisateur..." id="searchInput">
                <select class="filter-input" id="statusFilter">
                    <option value="">Tous les statuts</option>
                    <option value="completed">Complété</option>
                    <option value="pending">En attente</option>
                    <option value="failed">Échoué</option>
                    <option value="refunded">Remboursé</option>
                </select>
                <select class="filter-input" id="methodFilter">
                    <option value="">Toutes les méthodes</option>
                    <option value="stripe">Stripe</option>
                    <option value="paypal">PayPal</option>
                    <option value="card">Carte bancaire</option>
                </select>
            </div>
        </div>
        
        <table class="payments-table">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Utilisateur</th>
                    <th>Cours</th>
                    <th>Montant</th>
                    <th>Méthode</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments ?? collect() as $payment)
                <tr>
                    <td>
                        <code style="background: #f0f0f0; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">
                            {{ $payment->transaction_id ?? 'TXN-' . str_pad($payment->id ?? 1, 6, '0', STR_PAD_LEFT) }}
                        </code>
                    </td>
                    <td>
                        <div>
                            <strong>{{ $payment->user->name ?? 'Utilisateur Test' }}</strong>
                            <br>
                            <small style="color: #666;">{{ $payment->user->email ?? 'test@example.com' }}</small>
                        </div>
                    </td>
                    <td>
                        <div>
                            <strong>{{ $payment->course->title ?? 'Cours de développement Web' }}</strong>
                            <br>
                            <small style="color: #666;">ID: {{ $payment->course_id ?? 1 }}</small>
                        </div>
                    </td>
                    <td>
                        <strong style="color: #11998e;">{{ number_format($payment->amount ?? 25000, 0, ',', ' ') }} FCFA</strong>
                    </td>
                    <td>
                        <div class="payment-method">
                            <div class="method-icon method-{{ strtolower($payment->payment_method ?? 'stripe') }}">
                                @switch($payment->payment_method ?? 'stripe')
                                    @case('stripe')
                                        S
                                        @break
                                    @case('paypal')
                                        P
                                        @break
                                    @default
                                        💳
                                @endswitch
                            </div>
                            {{ ucfirst($payment->payment_method ?? 'Stripe') }}
                        </div>
                    </td>
                    <td>
                        <span class="status-badge status-{{ $payment->status ?? 'completed' }}">
                            @switch($payment->status ?? 'completed')
                                @case('completed')
                                    ✓ Complété
                                    @break
                                @case('pending')
                                    ⏳ En attente
                                    @break
                                @case('failed')
                                    ✗ Échoué
                                    @break
                                @case('refunded')
                                    ↩ Remboursé
                                    @break
                                @default
                                    {{ ucfirst($payment->status ?? 'completed') }}
                            @endswitch
                        </span>
                    </td>
                    <td>
                        <div>
                            {{ \Carbon\Carbon::parse($payment->payment_date ?? now())->format('d/m/Y') }}
                            <br>
                            <small style="color: #666;">{{ \Carbon\Carbon::parse($payment->payment_date ?? now())->format('H:i') }}</small>
                        </div>
                    </td>
                    <td>
                        <div class="actions-menu">
                            <button class="actions-btn" onclick="toggleActions(this)">
                                ⋮
                            </button>
                            <div class="actions-dropdown">
                                <a href="#" class="dropdown-item" onclick="viewPayment({{ $payment->id ?? 1 }})">👁 Voir détails</a>
                                @if(($payment->status ?? 'completed') == 'completed')
                                    <a href="#" class="dropdown-item" onclick="refundPayment({{ $payment->id ?? 1 }})">↩ Rembourser</a>
                                @endif
                                @if(($payment->status ?? 'completed') == 'pending')
                                    <a href="#" class="dropdown-item" onclick="approvePayment({{ $payment->id ?? 1 }})">✓ Approuver</a>
                                @endif
                                <a href="#" class="dropdown-item" onclick="downloadReceipt({{ $payment->id ?? 1 }})">📄 Reçu</a>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <!-- Données de démonstration -->
                <tr>
                    <td><code style="background: #f0f0f0; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">TXN-001234</code></td>
                    <td>
                        <div>
                            <strong>Jean Dupont</strong><br>
                            <small style="color: #666;">jean.dupont@email.com</small>
                        </div>
                    </td>
                    <td>
                        <div>
                            <strong>Développement Web Complet</strong><br>
                            <small style="color: #666;">ID: 12</small>
                        </div>
                    </td>
                    <td><strong style="color: #11998e;">25 000 FCFA</strong></td>
                    <td>
                        <div class="payment-method">
                            <div class="method-icon method-stripe">S</div>
                            Stripe
                        </div>
                    </td>
                    <td><span class="status-badge status-completed">✓ Complété</span></td>
                    <td>
                        <div>
                            {{ now()->format('d/m/Y') }}<br>
                            <small style="color: #666;">{{ now()->format('H:i') }}</small>
                        </div>
                    </td>
                    <td>
                        <div class="actions-menu">
                            <button class="actions-btn" onclick="toggleActions(this)">⋮</button>
                            <div class="actions-dropdown">
                                <a href="#" class="dropdown-item">👁 Voir détails</a>
                                <a href="#" class="dropdown-item">↩ Rembourser</a>
                                <a href="#" class="dropdown-item">📄 Reçu</a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><code style="background: #f0f0f0; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">TXN-001235</code></td>
                    <td>
                        <div>
                            <strong>Marie Martin</strong><br>
                            <small style="color: #666;">marie.martin@email.com</small>
                        </div>
                    </td>
                    <td>
                        <div>
                            <strong>Design UI/UX</strong><br>
                            <small style="color: #666;">ID: 8</small>
                        </div>
                    </td>
                    <td><strong style="color: #11998e;">30 000 FCFA</strong></td>
                    <td>
                        <div class="payment-method">
                            <div class="method-icon method-paypal">P</div>
                            PayPal
                        </div>
                    </td>
                    <td><span class="status-badge status-pending">⏳ En attente</span></td>
                    <td>
                        <div>
                            {{ now()->subDay()->format('d/m/Y') }}<br>
                            <small style="color: #666;">14:30</small>
                        </div>
                    </td>
                    <td>
                        <div class="actions-menu">
                            <button class="actions-btn" onclick="toggleActions(this)">⋮</button>
                            <div class="actions-dropdown">
                                <a href="#" class="dropdown-item">👁 Voir détails</a>
                                <a href="#" class="dropdown-item">✓ Approuver</a>
                                <a href="#" class="dropdown-item">📄 Reçu</a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><code style="background: #f0f0f0; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">TXN-001236</code></td>
                    <td>
                        <div>
                            <strong>Pierre Durand</strong><br>
                            <small style="color: #666;">pierre.durand@email.com</small>
                        </div>
                    </td>
                    <td>
                        <div>
                            <strong>Machine Learning</strong><br>
                            <small style="color: #666;">ID: 15</small>
                        </div>
                    </td>
                    <td><strong style="color: #11998e;">45 000 FCFA</strong></td>
                    <td>
                        <div class="payment-method">
                            <div class="method-icon method-card">💳</div>
                            Carte
                        </div>
                    </td>
                    <td><span class="status-badge status-refunded">↩ Remboursé</span></td>
                    <td>
                        <div>
                            {{ now()->subDays(3)->format('d/m/Y') }}<br>
                            <small style="color: #666;">09:15</small>
                        </div>
                    </td>
                    <td>
                        <div class="actions-menu">
                            <button class="actions-btn" onclick="toggleActions(this)">⋮</button>
                            <div class="actions-dropdown">
                                <a href="#" class="dropdown-item">👁 Voir détails</a>
                                <a href="#" class="dropdown-item">📄 Reçu</a>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="pagination-container">
            <div>
                Affichage de <strong>1-{{ $payments->count() ?? 3 }}</strong> sur <strong>{{ $payments->total() ?? 3 }}</strong> paiements
            </div>
            <div>
                {{ $payments->links() ?? 'Pagination sera ici' }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique des revenus
const ctx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
        datasets: [{
            label: 'Revenus (FCFA)',
            data: [120000, 190000, 300000, 500000, 200000, 300000, 450000, 680000, 590000, 720000, 850000, 920000],
            borderColor: '#11998e',
            backgroundColor: 'rgba(17, 153, 142, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString() + ' FCFA';
                    }
                }
            }
        }
    }
});

// Fonctions de gestion des actions
function toggleActions(button) {
    const dropdown = button.nextElementSibling;
    const allDropdowns = document.querySelectorAll('.actions-dropdown');
    
    // Fermer tous les autres dropdowns
    allDropdowns.forEach(d => {
        if (d !== dropdown) {
            d.classList.remove('show');
        }
    });
    
    dropdown.classList.toggle('show');
}

// Fermer les dropdowns quand on clique ailleurs
document.addEventListener('click', function(e) {
    if (!e.target.closest('.actions-menu')) {
        document.querySelectorAll('.actions-dropdown').forEach(d => {
            d.classList.remove('show');
        });
    }
});

// Fonctions d'actions
function viewPayment(id) {
    alert('Voir les détails du paiement #' + id);
    // Ici vous pouvez ouvrir une modal ou rediriger vers une page de détails
}

function refundPayment(id) {
    if (confirm('Êtes-vous sûr de vouloir rembourser ce paiement ?')) {
        alert('Remboursement initié pour le paiement #' + id);
        // Ici vous pouvez faire l'appel API pour le remboursement
    }
}

function approvePayment(id) {
    if (confirm('Approuver ce paiement ?')) {
        alert('Paiement #' + id + ' approuvé');
        // Ici vous pouvez faire l'appel API pour approuver
    }
}

function downloadReceipt(id) {
    alert('Téléchargement du reçu pour le paiement #' + id);
    // Ici vous pouvez générer et télécharger le PDF du reçu
}

// Filtres
document.getElementById('searchInput').addEventListener('input', function() {
    // Logique de filtrage par nom d'utilisateur
    console.log('Recherche:', this.value);
});

document.getElementById('statusFilter').addEventListener('change', function() {
    // Logique de filtrage par statut
    console.log('Filtre statut:', this.value);
});

document.getElementById('methodFilter').addEventListener('change', function() {
    // Logique de filtrage par méthode de paiement
    console.log('Filtre méthode:', this.value);
});
</script>
@endpush
@endsection 