@extends('formateurs.layouts.app')

@section('title', 'AfriCode - Mes revenus')
@section('page-title', 'Mes revenus')
@section('page-subtitle', 'Gérez vos revenus et demandez des retraits')

@section('content')
<div class="fade-in">
    <!-- Informations du compte -->
    {{-- <div class="card-modern mb-4" data-aos="fade-up">
        <div class="card-body-modern">
            <h5 class="mb-3">
                <i class="fas fa-user-circle"></i> Informations du compte
            </h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div style="background: #F8F9FA; padding: 1rem; border-radius: 8px; border-left: 4px solid #1EA38B;">
                        <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.5rem; font-weight: 500;">Identité</div>
                        <div style="font-size: 1rem; color: #333; font-weight: 600; margin-bottom: 0.25rem;">
                            <i class="fas fa-user me-2"></i>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                        </div>
                        <div style="font-size: 0.9rem; color: #6C757D;">
                            <i class="fas fa-envelope me-2"></i>{{ Auth::user()->email }}
                        </div>
                        @if(Auth::user()->phone)
                            <div style="font-size: 0.9rem; color: #6C757D; margin-top: 0.25rem;">
                                <i class="fas fa-phone me-2"></i>{{ Auth::user()->phone }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div style="background: #F8F9FA; padding: 1rem; border-radius: 8px; border-left: 4px solid #27B371;">
                        <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.5rem; font-weight: 500;">Informations de paiement</div>
                        @if(Auth::user()->payment_method)
                            <div style="font-size: 0.95rem; color: #333; margin-bottom: 0.5rem;">
                                <strong>Méthode :</strong> 
                                @if(Auth::user()->payment_method == 'mobile_money')
                                    <i class="fas fa-mobile-alt me-1"></i>Mobile Money
                                @elseif(Auth::user()->payment_method == 'bank_transfer')
                                    <i class="fas fa-university me-1"></i>Virement bancaire
                                @else
                                    {{ ucfirst(str_replace('_', ' ', Auth::user()->payment_method)) }}
                                @endif
                            </div>
                        @else
                            <div style="font-size: 0.9rem; color: #FF8E2A; margin-bottom: 0.5rem;">
                                <i class="fas fa-exclamation-triangle me-1"></i>Méthode de paiement non configurée
                            </div>
                        @endif
                        @if(Auth::user()->payment_account)
                            <div style="font-size: 0.95rem; color: #333; font-family: monospace; font-weight: 600;">
                                <strong>Compte :</strong> {{ Auth::user()->payment_account }}
                            </div>
                        @else
                            <div style="font-size: 0.9rem; color: #FF8E2A;">
                                <i class="fas fa-exclamation-triangle me-1"></i>Numéro de compte non configuré
                            </div>
                        @endif
                        @if(!Auth::user()->payment_method || !Auth::user()->payment_account)
                            <div style="margin-top: 0.75rem;">
                                <a href="{{ route('profile.edit') }}" class="btn btn-sm text-white" style="background:linear-gradient(135deg,#1EA38B,#27B371);border:none;">
                                    <i class="fas fa-edit me-1"></i>Compléter les informations
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @if(Auth::user()->city || Auth::user()->country)
                <div class="row mt-2">
                    <div class="col-md-12">
                        <div style="background: #F8F9FA; padding: 1rem; border-radius: 8px; border-left: 4px solid #1EA38B;">
                            <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.5rem; font-weight: 500;">Localisation</div>
                            <div style="font-size: 0.9rem; color: #333;">
                                @if(Auth::user()->city)
                                    <i class="fas fa-map-marker-alt me-2"></i>{{ Auth::user()->city }}
                                @endif
                                @if(Auth::user()->city && Auth::user()->country), @endif
                                @if(Auth::user()->country)
                                    {{ Auth::user()->country }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div> --}}

    <!-- Résumé financier -->
    <div class="row mb-4" data-aos="fade-up">
        <div class="col-md-4">
            <div class="card-modern">
                <div class="card-body-modern text-center">
                    <h3 class="mb-2" style="color:#1EA38B;">{{ number_format($totalEarnings, 0, ',', ' ') }} FCFA</h3>
                    <p class="text-muted mb-0">Revenus totaux</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-modern">
                <div class="card-body-modern text-center">
                    <h3 class="text-warning mb-2">{{ number_format(max(0, $pendingEarnings), 0, ',', ' ') }} FCFA</h3>
                    <p class="text-muted mb-0">Revenus en attente</p>
                    <small class="text-muted d-block mt-2">
                        <i class="fas fa-info-circle"></i> Montant total disponible
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-modern">
                <div class="card-body-modern text-center">
                    <h3 class="text-success mb-2">
                        {{ $availableForWithdrawal > 0 ? number_format($availableForWithdrawal, 0, ',', ' ') : '0' }} FCFA
                    </h3>
                    <p class="text-muted mb-0">Disponible pour retrait</p>
                    <small class="text-muted d-block mt-2">
                        <i class="fas fa-info-circle"></i> Seuil minimum: 25 000 FCFA
                    </small>
                    @if($pendingEarnings > 0 && $pendingEarnings < $formateur->withdrawal_threshold)
                        <div class="mt-2">
                            <small class="text-warning d-block">
                                <i class="fas fa-exclamation-triangle"></i> 
                                Il manque {{ number_format($formateur->withdrawal_threshold - $pendingEarnings, 0, ',', ' ') }} FCFA pour retirer
                            </small>
                        </div>
                    @endif
                    @if($availableForWithdrawal >= 25000)
                        <div class="mt-3">
                            <a href="#withdrawal-form" class="btn btn-sm text-white" style="background:linear-gradient(135deg,#27B371,#1EA38B);border:none;">
                                <i class="fas fa-money-bill-wave me-1"></i>Demander un retrait
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Demande de retrait -->
    @if($availableForWithdrawal >= 25000)
    <div class="card-modern mb-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card-body-modern">
            <h5 class="mb-3"><i class="fas fa-money-bill-wave"></i> Demander un retrait</h5>
            <div class="alert alert-info mb-3">
                <i class="fas fa-info-circle"></i> 
                <strong>Important :</strong> Les retraits nécessitent une approbation. 
                Le traitement se fait sous 2-24h après validation.
            </div>
            <form action="{{ route('formateur.earnings.withdraw') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <label for="amount" class="form-label">Montant (FCFA) *</label>
                        <input type="number" class="form-control" id="amount" name="amount" 
                               min="25000" max="{{ $availableForWithdrawal }}" 
                               value="{{ min($availableForWithdrawal, 25000) }}" required>
                        <small class="text-muted">Minimum: 25 000 FCFA</small>
                    </div>
                    <div class="col-md-4">
                        <label for="payment_method" class="form-label">Méthode de paiement *</label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="">Sélectionner une méthode</option>
                            <option value="mobile_money" {{ Auth::user()->payment_method == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                            {{-- <option value="card" {{ Auth::user()->payment_method == 'card' ? 'selected' : '' }}>Carte bancaire</option> --}}
                            <option value="bank_transfer" {{ Auth::user()->payment_method == 'bank_transfer' ? 'selected' : '' }}>Virement bancaire</option>
                        </select>
                        @if(Auth::user()->payment_method)
                            <small class="text-success">
                                <i class="fas fa-check-circle"></i> Méthode enregistrée dans votre profil
                            </small>
                        @else
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> 
                                <a href="{{ route('profile.edit') }}">Enregistrez votre méthode de paiement dans votre profil</a>
                            </small>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <label for="payment_account" class="form-label">Numéro de compte *</label>
                        <input type="text" class="form-control" id="payment_account" name="payment_account" 
                               value="{{ Auth::user()->payment_account }}" required
                               placeholder="Ex: +225 07 12 34 56 78">
                        @if(Auth::user()->payment_account)
                            <small class="text-success">
                                <i class="fas fa-check-circle"></i> Compte enregistré dans votre profil
                            </small>
                        @else
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> 
                                <a href="{{ route('profile.edit') }}">Enregistrez votre numéro de compte dans votre profil</a>
                            </small>
                        @endif
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn text-white" style="background:linear-gradient(135deg,#1EA38B,#27B371);border:none;">
                        <i class="fas fa-paper-plane"></i> Demander le retrait
                    </button>
                    <small class="d-block text-muted mt-2">
                        <i class="fas fa-clock"></i> Votre demande sera examinée
                    </small>
                </div>
            </form>
        </div>
    </div>
    @else
    <div class="card-modern mb-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card-body-modern">
            <div class="alert alert-info mb-3">
                <i class="fas fa-info-circle"></i> 
                <strong>Information :</strong> Vous devez avoir au moins 25 000 FCFA en revenus en attente pour demander un retrait.
                <br>
                <strong>Actuellement :</strong> {{ number_format(max(0, $pendingEarnings), 0, ',', ' ') }} FCFA
                @if($pendingEarnings < $formateur->withdrawal_threshold && $pendingEarnings > 0)
                    <br>
                    <strong>Il manque :</strong> {{ number_format($formateur->withdrawal_threshold - $pendingEarnings, 0, ',', ' ') }} FCFA pour atteindre le seuil minimum.
                @endif
            </div>
            @if($pendingEarnings > 0)
                <div class="text-center">
                    <a href="{{ route('formateur.earnings.index') }}#withdrawal-form" class="btn" style="color:#1EA38B;border:1px solid #1EA38B;">
                        <i class="fas fa-eye me-2"></i>Voir le formulaire de retrait
                    </a>
                </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Historique des revenus -->
    <div class="card-modern" data-aos="fade-up" data-aos-delay="200">
        <div class="card-body-modern">
            <h5 class="mb-3"><i class="fas fa-history"></i> Historique des revenus</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Cours</th>
                            <th>Montant</th>
                            <th>Part (77%)</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($earnings as $earning)
                            <tr>
                                <td>{{ $earning->created_at->format('d/m/Y') }}</td>
                                <td>{{ $earning->course->title ?? 'N/A' }}</td>
                                <td>{{ number_format($earning->course_price, 0, ',', ' ') }} FCFA</td>
                                <td>{{ number_format($earning->formateur_share, 0, ',', ' ') }} FCFA</td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => '#FF8E2A',
                                            'paid' => '#27B371',
                                            'processing' => '#1EA38B',
                                            'failed' => '#E32D31'
                                        ];
                                        $statusBg = $statusColors[$earning->payment_status] ?? '#6c757d';
                                    @endphp
                                    <span class="badge" style="background:{{ $statusBg }};">
                                        {{ ucfirst($earning->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('formateur.earnings.show', $earning->id) }}"
                                       class="btn btn-sm text-white" style="background:linear-gradient(135deg,#1EA38B,#0d7a63);border:none;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucun revenu enregistré</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $earnings->links() }}
            </div>
        </div>
    </div>

    <!-- Retraits récents -->
    @if($withdrawals->count() > 0)
    <div class="card-modern mt-4" data-aos="fade-up" data-aos-delay="300">
        <div class="card-body-modern">
            <h5 class="mb-3"><i class="fas fa-credit-card"></i> Retraits récents</h5>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Montant</th>
                            <th>Méthode</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($withdrawals as $withdrawal)
                            <tr>
                                <td>{{ $withdrawal->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ number_format($withdrawal->amount, 0, ',', ' ') }} FCFA</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $withdrawal->payment_method)) }}</td>
                                <td>
                                    @php
                                        $wStatusColors = [
                                            'pending' => '#FF8E2A',
                                            'processing' => '#1EA38B',
                                            'completed' => '#27B371',
                                            'failed' => '#E32D31',
                                            'cancelled' => '#6c757d'
                                        ];
                                        $statusLabels = [
                                            'pending' => 'En attente d\'approbation',
                                            'processing' => 'En traitement',
                                            'completed' => 'Complété',
                                            'failed' => 'Échoué',
                                            'cancelled' => 'Annulé'
                                        ];
                                        $wBg = $wStatusColors[$withdrawal->status] ?? '#6c757d';
                                        $label = $statusLabels[$withdrawal->status] ?? ucfirst($withdrawal->status);
                                    @endphp
                                    <span class="badge" style="background:{{ $wBg }};" title="{{ $withdrawal->status === 'pending' ? 'En attente de validation' : '' }}">
                                        {{ $label }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

