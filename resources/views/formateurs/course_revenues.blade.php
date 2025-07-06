@extends('formateurs.layouts.app')

@section('title', __('messages.course_revenues_title'))

@section('page-heading', $course->title)
@section('page-subheading', __('messages.revenues_and_transactions'))

@section('styles')
<style>
    .card {
        overflow: hidden;
    }
    
    .revenue-stats {
        background-color: #f8f9fa;
        border-radius: var(--border-radius);
        padding: 1.5rem;
    }
    
    .avatar-sm {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .transaction-card:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    .revenue-chart {
        height: 250px;
        width: 100%;
    }
    
    .stat-value {
        font-size: 2.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 0;
    }
    
    .filters {
        margin-bottom: 1.5rem;
    }
</style>
@endsection

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('formateur.dashboard') }}">Tableau de bord</a></li>
            <li class="breadcrumb-item"><a href="{{ route('formateur.manage.course', ['courseId' => $course->id]) }}">{{ $course->title }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Revenus</li>
        </ol>
    </nav>
    
    <div class="row mb-4">
        <!-- Revenue Summary -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body revenue-stats text-center">
                    <h5 class="card-title mb-4">Revenus totaux</h5>
                    <div class="stat-value">{{ number_format($totalRevenue, 2) }} €</div>
                    <p class="stat-label mb-4">Depuis la création du cours</p>
                    
                    <div class="divider my-4"></div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="stat-value">{{ $payments->total() }}</div>
                            <p class="stat-label">Ventes</p>
                        </div>
                        <div class="col-6">
                            <div class="stat-value">{{ number_format($totalRevenue > 0 && $payments->total() > 0 ? $totalRevenue / $payments->total() : 0, 2) }} €</div>
                            <p class="stat-label">Prix moyen</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-white">
                    <a href="{{ route('formateur.manage.course', ['courseId' => $course->id]) }}" class="btn btn-outline-primary btn-block w-100">
                        <i class="fas fa-arrow-left me-1"></i> Retour au cours
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Revenue Chart -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Revenus mensuels</h5>
                    
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-secondary active" data-period="month">6 mois</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-period="year">1 année</button>
                    </div>
                </div>
                
                <div class="card-body">
                    <canvas id="revenueChart" class="revenue-chart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Transactions -->
    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Historique des transactions</h5>
                
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Filtrer par période
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                        <li><a class="dropdown-item {{ request('period') == '' ? 'active' : '' }}" href="{{ route('formateur.courses.revenues', ['courseId' => $course->id]) }}">Toutes les transactions</a></li>
                        <li><a class="dropdown-item {{ request('period') == 'month' ? 'active' : '' }}" href="{{ route('formateur.courses.revenues', ['courseId' => $course->id, 'period' => 'month']) }}">Ce mois-ci</a></li>
                        <li><a class="dropdown-item {{ request('period') == 'year' ? 'active' : '' }}" href="{{ route('formateur.courses.revenues', ['courseId' => $course->id, 'period' => 'year']) }}">Cette année</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Étudiant</th>
                            <th scope="col">Date</th>
                            <th scope="col">Montant</th>
                            <th scope="col">Type</th>
                            <th scope="col">Statut</th>
                            <th scope="col">Référence</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $index => $payment)
                            <tr class="transaction-card">
                                <th scope="row">{{ $payments->firstItem() + $index }}</th>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $payment->user->profile_image_path ? asset($payment->user->profile_image_path) : 'https://via.placeholder.com/40' }}" 
                                             alt="{{ $payment->user->first_name }}" 
                                             class="avatar-sm me-3">
                                        <div>
                                            <h6 class="mb-0">{{ $payment->user->first_name }} {{ $payment->user->last_name }}</h6>
                                            <small class="text-muted">{{ $payment->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $payment->paid_at->format('d/m/Y H:i') }}</td>
                                <td class="fw-bold">{{ number_format($payment->amount, 2) }} €</td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $payment->payment_method }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        Complété
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $payment->transaction_id }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-coins fa-3x text-muted mb-3"></i>
                                    <p class="mb-1">Aucune transaction pour ce cours.</p>
                                    <p class="text-muted">Les transactions apparaîtront ici lorsque des étudiants achèteront votre cours.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-center">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
    
    <!-- Export Button -->
    <div class="text-end mt-4">
        <a href="{{ route('formateur.courses.revenues.export', ['courseId' => $course->id]) }}" class="btn btn-outline-primary">
            <i class="fas fa-file-export me-1"></i> Exporter les transactions
        </a>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Données pour le graphique
        const monthlyData = @json($monthlyRevenues);
        const labels = Object.keys(monthlyData);
        const values = Object.values(monthlyData);
        
        // Formatter les labels pour afficher mois/année
        const formattedLabels = labels.map(label => {
            const parts = label.split('-');
            const year = parts[0];
            const month = parts[1];
            return month + '/' + year.substring(2);
        });
        
        // Créer le graphique
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: formattedLabels,
                datasets: [{
                    label: 'Revenus (€)',
                    data: values,
                    backgroundColor: 'rgba(44, 115, 210, 0.2)',
                    borderColor: '#2C73D2',
                    borderWidth: 2,
                    pointBackgroundColor: '#2C73D2',
                    pointRadius: 4,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' €';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' €';
                            }
                        }
                    }
                }
            }
        });
        
        // Filtrer par période
        document.querySelectorAll('[data-period]').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('[data-period]').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Implémenter la logique de filtrage ici
                // Cette partie serait normalement implémentée avec des requêtes AJAX pour mettre à jour les données
            });
        });
    });
</script>
@endsection
