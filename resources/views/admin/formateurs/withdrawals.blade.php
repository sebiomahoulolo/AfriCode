@extends('admin.layouts.app')

@section('breadcrumb', 'Gestion des retraits')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="admin-card" data-aos="fade-up">
        <div class="admin-card-header">
            <div>
                <h1 class="admin-card-title">
                    <i class="fas fa-money-bill-wave"></i> Gestion des retraits
                </h1>
                <p class="admin-card-subtitle">Traiter les demandes de retrait des formateurs</p>
            </div>
            <a href="{{ route('admin.formateurs.index') }}" class="admin-btn admin-btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
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

    <!-- Filters -->
    <div class="admin-card" data-aos="fade-up" data-aos-delay="100" style="margin-bottom: 2rem;">
        <div class="admin-card-body">
            <form action="{{ route('admin.formateurs.withdrawals') }}" method="GET" class="row g-3">
                <div class="col-md-8">
                    <label for="status" class="form-label">Statut</label>
                    <select name="status" id="status" class="form-control" style="padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px;">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>En traitement</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Complété</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Échoué</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="admin-btn admin-btn-primary" style="width: 100%;">
                        <i class="fas fa-search me-2"></i> Filtrer
                    </button>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="{{ route('admin.formateurs.withdrawals') }}" class="admin-btn admin-btn-secondary" style="width: 100%;">
                        <i class="fas fa-redo me-2"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Retraits Table -->
    <div class="admin-card" data-aos="fade-up" data-aos-delay="200">
        <div class="admin-card-header">
            <h3 class="admin-card-title">Liste des retraits ({{ $withdrawals->total() }})</h3>
        </div>
        <div class="admin-card-body">
            @if($withdrawals->count() > 0)
                <div class="table-responsive">
                    <table class="admin-table">
                <thead>
                    <tr>
                        <th>Formateur</th>
                        <th>Montant</th>
                        <th>Méthode</th>
                        <th>Compte</th>
                        <th>Date demande</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($withdrawals as $withdrawal)
                        <tr>
                            <td>
                                <strong>{{ $withdrawal->formateur->first_name }} {{ $withdrawal->formateur->last_name }}</strong><br>
                                <small class="text-muted">{{ $withdrawal->formateur->email }}</small>
                            </td>
                            <td>
                                <strong>{{ number_format($withdrawal->amount, 0, ',', ' ') }} {{ $withdrawal->currency }}</strong>
                            </td>
                            <td>{{ ucfirst(str_replace('_', ' ', $withdrawal->payment_method)) }}</td>
                            <td>{{ $withdrawal->payment_account }}</td>
                            <td>{{ $withdrawal->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @php
                                    $statusConfig = [
                                        'pending' => ['bg' => 'rgba(255, 142, 42, 0.1)', 'color' => '#FF8E2A', 'icon' => 'clock'],
                                        'processing' => ['bg' => 'rgba(30, 163, 139, 0.1)', 'color' => '#1EA38B', 'icon' => 'spinner'],
                                        'completed' => ['bg' => 'rgba(39, 179, 113, 0.1)', 'color' => '#27B371', 'icon' => 'check-circle'],
                                        'failed' => ['bg' => 'rgba(227, 45, 49, 0.1)', 'color' => '#E32D31', 'icon' => 'times-circle'],
                                        'cancelled' => ['bg' => 'rgba(108, 117, 125, 0.1)', 'color' => '#6C757D', 'icon' => 'ban']
                                    ];
                                    $status = $statusConfig[$withdrawal->status] ?? ['bg' => 'rgba(108, 117, 125, 0.1)', 'color' => '#6C757D', 'icon' => 'question-circle'];
                                @endphp
                                <span style="background: {{ $status['bg'] }}; color: {{ $status['color'] }}; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.85rem; font-weight: 500;">
                                    <i class="fas fa-{{ $status['icon'] }} me-1"></i>
                                    {{ ucfirst($withdrawal->status) }}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    @if($withdrawal->status == 'pending')
                                        <button type="button" 
                                                class="admin-btn admin-btn-primary admin-btn-sm" 
                                                onclick="openProcessModal({{ $withdrawal->id }}, '{{ $withdrawal->formateur->first_name }} {{ $withdrawal->formateur->last_name }}', {{ $withdrawal->amount }}, '{{ $withdrawal->currency }}')"
                                                title="Traiter le retrait">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    @endif
                                    @if($withdrawal->status == 'processing')
                                        <button type="button" 
                                                class="admin-btn admin-btn-success admin-btn-sm" 
                                                onclick="openCompleteModal({{ $withdrawal->id }}, '{{ $withdrawal->formateur->first_name }} {{ $withdrawal->formateur->last_name }}', {{ $withdrawal->amount }}, '{{ $withdrawal->currency }}')"
                                                title="Compléter le retrait">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div style="font-size: 4rem; color: #E0E0E0; margin-bottom: 1rem;">
                                    <i class="fas fa-inbox"></i>
                                </div>
                                <h3 style="color: #6C757D; margin-bottom: 0.5rem;">Aucun retrait trouvé</h3>
                                <p style="color: #95A5A6;">Aucun retrait ne correspond à vos critères de recherche.</p>
                                @if(request()->has('status'))
                                <a href="{{ route('admin.formateurs.withdrawals') }}" class="admin-btn admin-btn-primary mt-3">
                                    <i class="fas fa-redo me-2"></i>
                                    Réinitialiser les filtres
                                </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
                </div>

                <!-- Pagination -->
                @if($withdrawals->hasPages() || $withdrawals->total() > 0)
                <div class="mt-4" data-aos="fade-up" data-aos-delay="400">
                    @if($withdrawals->total() > 0)
                        <div style="text-align: center; margin-bottom: 1rem; color: #6C757D; font-size: 0.9rem;">
                            Affichage de <strong>{{ $withdrawals->firstItem() }}</strong> à <strong>{{ $withdrawals->lastItem() }}</strong> 
                            sur <strong>{{ $withdrawals->total() }}</strong> retrait{{ $withdrawals->total() > 1 ? 's' : '' }}
                        </div>
                    @endif
                    @if($withdrawals->hasPages())
                        <div style="display: flex; justify-content: center;">
                            <nav aria-label="Navigation des pages">
                                <ul class="pagination" style="justify-content: center; margin: 0; padding: 0; list-style: none; display: flex; gap: 0.5rem;">
                                    <!-- Bouton Précédent -->
                                    @if($withdrawals->onFirstPage())
                                        <li class="page-item disabled" style="margin: 0;">
                                            <span class="page-link" style="color: #6c757d; background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 0.5rem 0.75rem; border-radius: 0.375rem; text-decoration: none; cursor: not-allowed; opacity: 0.65; display: flex; align-items: center; justify-content: center; min-width: 38px;">
                                                <i class="fas fa-chevron-left"></i>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item" style="margin: 0;">
                                            <a class="page-link" href="{{ $withdrawals->appends(request()->except('page'))->previousPageUrl() }}" aria-label="Précédent" style="color: #1EA38B; background-color: white; border: 1px solid #dee2e6; padding: 0.5rem 0.75rem; border-radius: 0.375rem; text-decoration: none; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; min-width: 38px;">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                    @endif

                                    <!-- Numéros de pages -->
                                    @foreach($withdrawals->getUrlRange(max(1, $withdrawals->currentPage() - 2), min($withdrawals->lastPage(), $withdrawals->currentPage() + 2)) as $page => $url)
                                        @if($page == $withdrawals->currentPage())
                                            <li class="page-item active" style="margin: 0;">
                                                <span class="page-link" style="color: white; background-color: #1EA38B; border: 1px solid #1EA38B; padding: 0.5rem 0.75rem; border-radius: 0.375rem; font-weight: 600; display: flex; align-items: center; justify-content: center; min-width: 38px;">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item" style="margin: 0;">
                                                <a class="page-link" href="{{ $withdrawals->appends(request()->except('page'))->url($page) }}" style="color: #1EA38B; background-color: white; border: 1px solid #dee2e6; padding: 0.5rem 0.75rem; border-radius: 0.375rem; text-decoration: none; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; min-width: 38px;">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    <!-- Bouton Suivant -->
                                    @if($withdrawals->hasMorePages())
                                        <li class="page-item" style="margin: 0;">
                                            <a class="page-link" href="{{ $withdrawals->appends(request()->except('page'))->nextPageUrl() }}" aria-label="Suivant" style="color: #1EA38B; background-color: white; border: 1px solid #dee2e6; padding: 0.5rem 0.75rem; border-radius: 0.375rem; text-decoration: none; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; min-width: 38px;">
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
            @endif
        </div>
    </div>
</div>

<!-- Modal Traiter Retrait -->
<div id="processModal" class="admin-confirm-modal" style="display: none;">
    <div class="admin-confirm-modal-content">
        <div class="admin-confirm-modal-header">
            <h3>Traiter le retrait</h3>
            <button type="button" onclick="closeProcessModal()" style="background: none; border: none; font-size: 1.5rem; color: #6C757D; cursor: pointer;">&times;</button>
        </div>
        <form id="processForm" method="POST">
            @csrf
            <div class="admin-confirm-modal-body">
                <div style="background: #E8F5E9; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #27B371;">
                    <div style="font-size: 0.9rem; font-weight: 600; color: #333; margin-bottom: 0.25rem;">Action : Validation et Complétion</div>
                    <div style="font-size: 0.85rem; color: #6C757D;">
                        En validant, le retrait passera directement au statut "Complété" et les revenus du formateur seront mis à jour.
                    </div>
                </div>
                <p><strong>Formateur:</strong> <span id="processFormateurName"></span></p>
                <p><strong>Montant:</strong> <span id="processAmount"></span></p>
                <div style="margin-bottom: 1rem;">
                    <label for="process_payment_reference" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Référence de paiement (optionnel)</label>
                    <input type="text" id="process_payment_reference" name="payment_reference"
                           placeholder="Ex: TRX123456789 ou REF20241124-001"
                           style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; font-family: monospace;">
                    <div style="font-size: 0.8rem; color: #6C757D; margin-top: 0.25rem;">
                        Entrez la référence de transaction fournie par votre système de paiement. Une référence sera générée automatiquement si non fournie.
                    </div>
                </div>
            </div>
            <div class="admin-confirm-modal-footer">
                <button type="button" onclick="closeProcessModal()" class="admin-btn admin-btn-secondary">Annuler</button>
                <button type="submit" class="admin-btn admin-btn-primary">Valider et compléter le retrait</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Compléter Retrait -->
<div id="completeModal" class="admin-confirm-modal" style="display: none;">
    <div class="admin-confirm-modal-content">
        <div class="admin-confirm-modal-header">
            <h3>Compléter le retrait</h3>
            <button type="button" onclick="closeCompleteModal()" style="background: none; border: none; font-size: 1.5rem; color: #6C757D; cursor: pointer;">&times;</button>
        </div>
        <form id="completeForm" method="POST">
            @csrf
            <div class="admin-confirm-modal-body">
                <p><strong>Formateur:</strong> <span id="completeFormateurName"></span></p>
                <p><strong>Montant:</strong> <span id="completeAmount"></span></p>
                <div style="margin-bottom: 1rem;">
                    <label for="complete_payment_reference" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Référence de paiement *</label>
                    <input type="text" id="complete_payment_reference" name="payment_reference" required
                           placeholder="Ex: MTN-123456789"
                           style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; font-family: monospace;">
                </div>
            </div>
            <div class="admin-confirm-modal-footer">
                <button type="button" onclick="closeCompleteModal()" class="admin-btn admin-btn-secondary">Annuler</button>
                <button type="submit" class="admin-btn admin-btn-success">Compléter</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openProcessModal(id, formateurName, amount, currency) {
        document.getElementById('processForm').action = '{{ route("admin.formateurs.withdrawals.process", ":id") }}'.replace(':id', id);
        document.getElementById('processFormateurName').textContent = formateurName;
        document.getElementById('processAmount').textContent = new Intl.NumberFormat('fr-FR').format(amount) + ' ' + currency;
        document.getElementById('processModal').style.display = 'flex';
    }

    function closeProcessModal() {
        document.getElementById('processModal').style.display = 'none';
        document.getElementById('process_payment_reference').value = '';
    }

    function openCompleteModal(id, formateurName, amount, currency) {
        document.getElementById('completeForm').action = '{{ route("admin.formateurs.withdrawals.complete", ":id") }}'.replace(':id', id);
        document.getElementById('completeFormateurName').textContent = formateurName;
        document.getElementById('completeAmount').textContent = new Intl.NumberFormat('fr-FR').format(amount) + ' ' + currency;
        document.getElementById('completeModal').style.display = 'flex';
    }

    function closeCompleteModal() {
        document.getElementById('completeModal').style.display = 'none';
        document.getElementById('complete_payment_reference').value = '';
    }

    // Fermer les modals en cliquant à l'extérieur
    window.onclick = function(event) {
        const processModal = document.getElementById('processModal');
        const completeModal = document.getElementById('completeModal');
        if (event.target == processModal) {
            closeProcessModal();
        }
        if (event.target == completeModal) {
            closeCompleteModal();
        }
    }
</script>
@endpush
@endsection

