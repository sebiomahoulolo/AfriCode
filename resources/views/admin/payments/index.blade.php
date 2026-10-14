@extends('admin.layouts.app')

@section('breadcrumb', 'Paiements')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="admin-card" data-aos="fade-up">
        <div class="admin-card-header">
            <div>
                <h1 class="admin-card-title" style="font-size: 1.5rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-credit-card me-2" style="color: #1EA38B;"></i>
                    Gestion des Paiements
                </h1>
                <p class="admin-card-subtitle">Liste de tous les paiements effectués par les apprenants</p>
            </div>
            <div>
                <span style="background: linear-gradient(135deg, #1EA38B, #27B371); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; font-weight: 500;">
                    <i class="fas fa-money-bill-wave"></i> {{ number_format($stats['total_amount'], 0, ',', ' ') }} {{ $payments->first()?->currency ?? 'FCFA' }}
                </span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-up" data-aos-delay="100">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" data-aos="fade-up" data-aos-delay="100">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="admin-grid admin-grid-3" style="margin-bottom: 2rem;" data-aos="fade-up" data-aos-delay="100">
        <div class="admin-stats-card">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #1EA38B, #27B371);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="admin-stats-number">{{ $stats['succeeded'] }}</div>
            <div class="admin-stats-label">Paiements réussis</div>
            <div class="admin-stats-change positive">
                <i class="fas fa-info-circle"></i> Cours payants uniquement
            </div>
        </div>
        
        <div class="admin-stats-card">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #1EA38B, #27B371);">
                <i class="fas fa-coins"></i>
            </div>
            <div class="admin-stats-number">{{ number_format($stats['total_amount'], 0, ',', ' ') }} <span style="font-size: 0.75em; font-weight: 500; color: #6C757D;">{{ $payments->first()?->currency ?? 'FCFA' }}</span></div>
            <div class="admin-stats-label">Montant total</div>
            <div class="admin-stats-change positive">
                <small>Tous les montants en FCFA</small>
            </div>
        </div>
        
        <div class="admin-stats-card">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #FF8E2A, #FFB366);">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="admin-stats-number">{{ number_format($stats['this_month_amount'], 0, ',', ' ') }} <span style="font-size: 0.75em; font-weight: 500; color: #6C757D;">{{ $payments->first()?->currency ?? 'FCFA' }}</span></div>
            <div class="admin-stats-label">Ce mois</div>
            <div class="admin-stats-change positive">
                <small>FCFA</small>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="admin-card" data-aos="fade-up" data-aos-delay="200">
        <div class="admin-card-body">
            <form method="GET" action="{{ route('admin.payments.index') }}" class="row g-3">
                <div class="col-md-8">
                    <label for="search" class="form-label">Rechercher</label>
                    <input type="text" 
                           class="form-control" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Nom, email, cours, transaction...">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="admin-btn admin-btn-primary" style="width: 100%;">
                        <i class="fas fa-search me-2"></i>
                        Rechercher
                    </button>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="{{ route('admin.payments.index') }}" class="admin-btn admin-btn-secondary" style="width: 100%;">
                        <i class="fas fa-redo me-2"></i>
                        Réinitialiser
                    </a>
                </div>
            </form>
            <div class="mt-3">
                <div class="alert alert-info mb-0" style="background: rgba(30, 163, 139, 0.1); border-color: #1EA38B; color: #1EA38B;">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Filtre actif :</strong> Affichage des paiements <strong>réussis</strong> (<em>Succeeded</em> ou <em>Completed</em>) pour les <strong>cours payants</strong> uniquement.
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="admin-card" data-aos="fade-up" data-aos-delay="300">
        <div class="admin-card-body">
            @if($payments->count() > 0)
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">
                                    <i class="fas fa-hashtag"></i>
                                </th>
                                <th>Apprenant</th>
                                <th>Cours</th>
                                <th>Montant</th>
                                <th>Méthode</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                            <tr>
                                <td>
                                    @if($payment->user)
                                        <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #1EA38B, #27B371); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.9rem;">
                                            {{ strtoupper(substr($payment->user->first_name ?? 'N', 0, 1)) }}{{ strtoupper(substr($payment->user->last_name ?? 'A', 0, 1)) }}
                                        </div>
                                    @else
                                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #6C757D; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.9rem;">
                                            ?
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($payment->user)
                                        <div>
                                            <div style="font-weight: 600; color: #2C3E50;">
                                                {{ $payment->user->first_name ?? 'N/A' }} {{ $payment->user->last_name ?? 'N/A' }}
                                            </div>
                                            <div style="font-size: 0.85rem; color: #6C757D;">
                                                <i class="fas fa-envelope me-1"></i>
                                                {{ $payment->user->email ?? 'N/A' }}
                                            </div>
                                        </div>
                                    @else
                                        <span style="color: #6C757D;">Utilisateur supprimé</span>
                                    @endif
                                </td>
                                <td>
                                    @if($payment->payable && $payment->payable instanceof \App\Models\Course)
                                        <div>
                                            <div style="font-weight: 500; color: #2C3E50;">
                                                {{ $payment->payable->title ?? 'N/A' }}
                                            </div>
                                            @if($payment->payable->formateur)
                                                <div style="font-size: 0.85rem; color: #6C757D;">
                                                    <i class="fas fa-user-tie me-1"></i>
                                                    {{ $payment->payable->formateur->first_name ?? 'N/A' }} {{ $payment->payable->formateur->last_name ?? 'N/A' }}
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span style="color: #6C757D;">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #1EA38B; font-size: 1.1rem;">
                                        {{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency ?? 'FCFA' }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span style="background: rgba(30, 163, 139, 0.1); color: #1EA38B; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.85rem; font-weight: 500;">
                                            <i class="fas fa-{{ $payment->payment_method === 'card' ? 'credit-card' : ($payment->payment_method === 'mobile_money' ? 'mobile-alt' : 'wallet') }} me-1"></i>
                                            {{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'N/A')) }}
                                        </span>
                                    </div>
                                    @if($payment->payment_gateway)
                                    <div style="font-size: 0.75rem; color: #95A5A6; margin-top: 0.25rem;">
                                        {{ ucfirst($payment->payment_gateway) }}
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'succeeded' => ['bg' => 'rgba(39, 179, 113, 0.1)', 'color' => '#27B371', 'icon' => 'check-circle'],
                                            'completed' => ['bg' => 'rgba(39, 179, 113, 0.1)', 'color' => '#27B371', 'icon' => 'check-circle'],
                                            'pending' => ['bg' => 'rgba(255, 142, 42, 0.1)', 'color' => '#FF8E2A', 'icon' => 'clock'],
                                            'failed' => ['bg' => 'rgba(227, 45, 49, 0.1)', 'color' => '#E32D31', 'icon' => 'times-circle'],
                                        ];
                                        $status = $statusColors[$payment->status] ?? ['bg' => 'rgba(108, 117, 125, 0.1)', 'color' => '#6C757D', 'icon' => 'question-circle'];
                                    @endphp
                                    <span style="background: {{ $status['bg'] }}; color: {{ $status['color'] }}; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.85rem; font-weight: 500;">
                                        <i class="fas fa-{{ $status['icon'] }} me-1"></i>
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $paymentDate = $payment->paid_at ?? $payment->payment_date ?? $payment->created_at;
                                        if ($paymentDate && !($paymentDate instanceof \Carbon\Carbon)) {
                                            $paymentDate = \Carbon\Carbon::parse($paymentDate);
                                        }
                                    @endphp
                                    <div style="color: #6C757D;">
                                        <i class="fas fa-calendar-alt me-1" style="color: #1EA38B;"></i>
                                        {{ $paymentDate ? $paymentDate->format('d/m/Y') : 'N/A' }}
                                    </div>
                                    <div style="font-size: 0.8rem; color: #95A5A6;">
                                        {{ $paymentDate ? $paymentDate->format('H:i') : '' }}
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="{{ route('admin.payments.show', $payment->id) }}" 
                                           class="admin-btn admin-btn-primary admin-btn-sm"
                                           title="Voir les détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($payment->transaction_id)
                                        <button class="admin-btn admin-btn-secondary admin-btn-sm" 
                                                onclick="copyToClipboard('{{ $payment->transaction_id }}')"
                                                title="Copier l'ID de transaction">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($payments->hasPages() || $payments->total() > 0)
                <div class="mt-4" data-aos="fade-up" data-aos-delay="400">
                    @if($payments->total() > 0)
                        <div style="text-align: center; margin-bottom: 1rem; color: #6C757D; font-size: 0.9rem;">
                            Affichage de <strong>{{ $payments->firstItem() }}</strong> à <strong>{{ $payments->lastItem() }}</strong> 
                            sur <strong>{{ $payments->total() }}</strong> paiement{{ $payments->total() > 1 ? 's' : '' }}
                        </div>
                    @endif
                    @if($payments->hasPages())
                        <div style="display: flex; justify-content: center;">
                            <nav aria-label="Navigation des pages">
                                <ul class="pagination" style="justify-content: center; margin: 0; padding: 0; list-style: none; display: flex; gap: 0.5rem;">
                                    <!-- Bouton Précédent -->
                                    @if($payments->onFirstPage())
                                        <li class="page-item disabled" style="margin: 0;">
                                            <span class="page-link" style="color: #6c757d; background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 0.5rem 0.75rem; border-radius: 0.375rem; text-decoration: none; cursor: not-allowed; opacity: 0.65; display: flex; align-items: center; justify-content: center; min-width: 38px;">
                                                <i class="fas fa-chevron-left"></i>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item" style="margin: 0;">
                                            <a class="page-link" href="{{ $payments->appends(request()->except('page'))->previousPageUrl() }}" aria-label="Précédent" style="color: #1EA38B; background-color: white; border: 1px solid #dee2e6; padding: 0.5rem 0.75rem; border-radius: 0.375rem; text-decoration: none; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; min-width: 38px;">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                    @endif

                                    <!-- Numéros de pages -->
                                    @foreach($payments->getUrlRange(max(1, $payments->currentPage() - 2), min($payments->lastPage(), $payments->currentPage() + 2)) as $page => $url)
                                        @if($page == $payments->currentPage())
                                            <li class="page-item active" style="margin: 0;">
                                                <span class="page-link" style="color: white; background-color: #1EA38B; border: 1px solid #1EA38B; padding: 0.5rem 0.75rem; border-radius: 0.375rem; font-weight: 600; display: flex; align-items: center; justify-content: center; min-width: 38px;">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item" style="margin: 0;">
                                                <a class="page-link" href="{{ $payments->appends(request()->except('page'))->url($page) }}" style="color: #1EA38B; background-color: white; border: 1px solid #dee2e6; padding: 0.5rem 0.75rem; border-radius: 0.375rem; text-decoration: none; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; min-width: 38px;">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    <!-- Bouton Suivant -->
                                    @if($payments->hasMorePages())
                                        <li class="page-item" style="margin: 0;">
                                            <a class="page-link" href="{{ $payments->appends(request()->except('page'))->nextPageUrl() }}" aria-label="Suivant" style="color: #1EA38B; background-color: white; border: 1px solid #dee2e6; padding: 0.5rem 0.75rem; border-radius: 0.375rem; text-decoration: none; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; min-width: 38px;">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled" style="margin: 0;">
                                            <span class="page-link" style="color: #6c757d; background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 0.5rem 0.75rem; border-radius: 0.375rem; text-decoration: none; cursor: not-allowed; opacity: 0.65; display: flex; align-items: center; justify-content: center; min-width: 38px;">
                                                <i class="fas fa-chevron-right"></i>
                                            </span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    @endif
                </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-5" data-aos="fade-up">
                    <div style="font-size: 4rem; color: #E0E0E0; margin-bottom: 1rem;">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3 style="color: #6C757D; margin-bottom: 0.5rem;">Aucun paiement trouvé</h3>
                    <p style="color: #95A5A6;">Aucun paiement ne correspond à vos critères de recherche.</p>
                    @if(request()->has('search') || request()->has('status'))
                    <a href="{{ route('admin.payments.index') }}" class="admin-btn admin-btn-primary mt-3">
                        <i class="fas fa-redo me-2"></i>
                        Réinitialiser les filtres
                    </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            showToast('ID de transaction copié dans le presse-papiers !', 'success');
        }).catch(function(err) {
            console.error('Erreur lors de la copie:', err);
            showToast('Erreur lors de la copie', 'error');
        });
    }

    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'}`;
        toast.style.position = 'fixed';
        toast.style.top = '20px';
        toast.style.right = '20px';
        toast.style.zIndex = '9999';
        toast.style.minWidth = '300px';
        toast.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
        toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}`;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>
@endpush
@endsection
