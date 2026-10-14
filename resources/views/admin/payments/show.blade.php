@extends('admin.layouts.app')

@section('breadcrumb', 'Détails du Paiement')

@section('content')
<div class="fade-in">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" style="margin-bottom: 1.5rem;" data-aos="fade-up">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.payments.index') }}">Paiements</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Détails</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="admin-card" data-aos="fade-up" data-aos-delay="100">
        <div class="admin-card-header">
            <div>
                <h1 class="admin-card-title" style="font-size: 1.5rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-credit-card me-2" style="color: #1EA38B;"></i>
                    Détails du Paiement
                </h1>
                <p class="admin-card-subtitle">Informations complètes sur le paiement</p>
            </div>
            <div>
                <a href="{{ route('admin.payments.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <!-- Payment Information -->
    <div class="admin-grid admin-grid-2" style="margin-bottom: 2rem;">
        <!-- Student Information -->
        <div class="admin-card" data-aos="fade-up" data-aos-delay="200">
            <div class="admin-card-header">
                <h3 class="admin-card-title">
                    <i class="fas fa-user-graduate me-2" style="color: #1EA38B;"></i>
                    Informations de l'Apprenant
                </h3>
            </div>
            <div class="admin-card-body">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                    <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #1EA38B, #27B371); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 1.5rem;">
                        {{ strtoupper(substr($payment->user->first_name ?? 'N', 0, 1)) }}{{ strtoupper(substr($payment->user->last_name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <h4 style="margin: 0; color: #2C3E50; font-weight: 600;">
                            {{ $payment->user->first_name ?? 'N/A' }} {{ $payment->user->last_name ?? 'N/A' }}
                        </h4>
                        <p style="margin: 0.25rem 0 0 0; color: #6C757D; font-size: 0.9rem;">
                            <i class="fas fa-envelope me-1"></i>
                            {{ $payment->user->email ?? 'N/A' }}
                        </p>
                    </div>
                </div>
                
                <div style="display: grid; gap: 1rem;">
                    <div>
                        <label style="font-size: 0.85rem; color: #6C757D; font-weight: 500;">Rôle</label>
                        <div style="margin-top: 0.25rem;">
                            <span style="background: rgba(30, 163, 139, 0.1); color: #1EA38B; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.85rem; font-weight: 500;">
                                {{ ucfirst($payment->user->role ?? 'N/A') }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <label style="font-size: 0.85rem; color: #6C757D; font-weight: 500;">Date d'inscription</label>
                        <div style="margin-top: 0.25rem; color: #2C3E50;">
                            {{ $payment->user->created_at ? $payment->user->created_at->format('d/m/Y') : 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Information -->
        <div class="admin-card" data-aos="fade-up" data-aos-delay="300">
            <div class="admin-card-header">
                <h3 class="admin-card-title">
                    <i class="fas fa-book me-2" style="color: #FF8E2A;"></i>
                    Informations du Cours
                </h3>
            </div>
            <div class="admin-card-body">
                <div style="margin-bottom: 1.5rem;">
                    <h4 style="margin: 0 0 0.5rem 0; color: #2C3E50; font-weight: 600;">
                        {{ $payment->payable->title ?? 'N/A' }}
                    </h4>
                    <p style="margin: 0; color: #6C757D; font-size: 0.9rem;">
                        {{ \Illuminate\Support\Str::limit($payment->payable->short_description ?? 'N/A', 100) }}
                    </p>
                </div>
                
                <div style="display: grid; gap: 1rem;">
                    <div>
                        <label style="font-size: 0.85rem; color: #6C757D; font-weight: 500;">Formateur</label>
                        <div style="margin-top: 0.25rem; color: #2C3E50;">
                            <i class="fas fa-user-tie me-1" style="color: #1EA38B;"></i>
                            {{ $payment->payable->formateur->first_name ?? 'N/A' }} {{ $payment->payable->formateur->last_name ?? 'N/A' }}
                        </div>
                    </div>
                    <div>
                        <label style="font-size: 0.85rem; color: #6C757D; font-weight: 500;">Niveau</label>
                        <div style="margin-top: 0.25rem;">
                            <span style="background: rgba(255, 142, 42, 0.1); color: #FF8E2A; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.85rem; font-weight: 500;">
                                {{ ucfirst($payment->payable->level ?? 'N/A') }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <label style="font-size: 0.85rem; color: #6C757D; font-weight: 500;">Prix du cours</label>
                        <div style="margin-top: 0.25rem; color: #2C3E50; font-weight: 600;">
                            {{ number_format($payment->payable->price ?? 0, 0, ',', ' ') }} {{ $payment->payable->currency ?? 'FCFA' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Details -->
    <div class="admin-card" data-aos="fade-up" data-aos-delay="400">
        <div class="admin-card-header">
            <h3 class="admin-card-title">
                <i class="fas fa-receipt me-2" style="color: #E32D31;"></i>
                Détails du Paiement
            </h3>
        </div>
        <div class="admin-card-body">
            <div class="admin-grid admin-grid-3" style="margin-bottom: 2rem;">
                <div>
                    <label style="font-size: 0.85rem; color: #6C757D; font-weight: 500;">Montant payé</label>
                    <div style="margin-top: 0.5rem; color: #1EA38B; font-size: 1.5rem; font-weight: 700;">
                        {{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency ?? 'FCFA' }}
                    </div>
                </div>
                <div>
                    <label style="font-size: 0.85rem; color: #6C757D; font-weight: 500;">Statut</label>
                    <div style="margin-top: 0.5rem;">
                        @php
                            $statusColors = [
                                'succeeded' => ['bg' => 'rgba(39, 179, 113, 0.1)', 'color' => '#27B371', 'icon' => 'check-circle'],
                                'completed' => ['bg' => 'rgba(39, 179, 113, 0.1)', 'color' => '#27B371', 'icon' => 'check-circle'],
                                'pending' => ['bg' => 'rgba(255, 142, 42, 0.1)', 'color' => '#FF8E2A', 'icon' => 'clock'],
                                'failed' => ['bg' => 'rgba(227, 45, 49, 0.1)', 'color' => '#E32D31', 'icon' => 'times-circle'],
                            ];
                            $status = $statusColors[$payment->status] ?? ['bg' => 'rgba(108, 117, 125, 0.1)', 'color' => '#6C757D', 'icon' => 'question-circle'];
                        @endphp
                        <span style="background: {{ $status['bg'] }}; color: {{ $status['color'] }}; padding: 0.5rem 1rem; border-radius: 12px; font-size: 1rem; font-weight: 600;">
                            <i class="fas fa-{{ $status['icon'] }} me-2"></i>
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>
                </div>
                <div>
                    <label style="font-size: 0.85rem; color: #6C757D; font-weight: 500;">Méthode de paiement</label>
                    <div style="margin-top: 0.5rem;">
                        <span style="background: rgba(30, 163, 139, 0.1); color: #1EA38B; padding: 0.5rem 1rem; border-radius: 12px; font-size: 1rem; font-weight: 500;">
                            <i class="fas fa-{{ $payment->payment_method === 'card' ? 'credit-card' : ($payment->payment_method === 'mobile_money' ? 'mobile-alt' : 'wallet') }} me-2"></i>
                            {{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'N/A')) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="admin-grid admin-grid-2" style="margin-bottom: 2rem;">
                <div>
                    <label style="font-size: 0.85rem; color: #6C757D; font-weight: 500;">ID de transaction</label>
                    <div style="margin-top: 0.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <code style="background: rgba(30, 163, 139, 0.1); color: #1EA38B; padding: 0.5rem 1rem; border-radius: 8px; font-size: 1rem; font-weight: 600; flex: 1;">
                                {{ $payment->transaction_id ?? 'N/A' }}
                            </code>
                            <button class="admin-btn admin-btn-secondary admin-btn-sm" 
                                    onclick="copyToClipboard('{{ $payment->transaction_id }}')"
                                    title="Copier l'ID">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div>
                    <label style="font-size: 0.85rem; color: #6C757D; font-weight: 500;">Passerelle de paiement</label>
                    <div style="margin-top: 0.5rem; color: #2C3E50; font-size: 1rem; font-weight: 500;">
                        {{ $payment->payment_gateway ? ucfirst($payment->payment_gateway) : 'N/A' }}
                    </div>
                </div>
            </div>

            <div class="admin-grid admin-grid-3">
                <div>
                    <label style="font-size: 0.85rem; color: #6C757D; font-weight: 500;">Date de paiement</label>
                    <div style="margin-top: 0.5rem; color: #2C3E50; font-size: 1rem; font-weight: 500;">
                        <i class="fas fa-calendar-alt me-2" style="color: #1EA38B;"></i>
                        {{ $payment->paid_at ? $payment->paid_at->format('d/m/Y à H:i') : ($payment->payment_date ? $payment->payment_date->format('d/m/Y à H:i') : 'N/A') }}
                    </div>
                </div>
                <div>
                    <label style="font-size: 0.85rem; color: #6C757D; font-weight: 500;">Date de création</label>
                    <div style="margin-top: 0.5rem; color: #2C3E50; font-size: 1rem; font-weight: 500;">
                        <i class="fas fa-clock me-2" style="color: #6C757D;"></i>
                        {{ $payment->created_at->format('d/m/Y à H:i') }}
                    </div>
                </div>
                <div>
                    <label style="font-size: 0.85rem; color: #6C757D; font-weight: 500;">Dernière mise à jour</label>
                    <div style="margin-top: 0.5rem; color: #2C3E50; font-size: 1rem; font-weight: 500;">
                        <i class="fas fa-sync-alt me-2" style="color: #6C757D;"></i>
                        {{ $payment->updated_at->format('d/m/Y à H:i') }}
                    </div>
                </div>
            </div>

            @if($payment->enrollment)
            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 2px solid #E0E0E0;">
                <h4 style="color: #2C3E50; margin-bottom: 1rem;">
                    <i class="fas fa-link me-2" style="color: #1EA38B;"></i>
                    Inscription associée
                </h4>
                <div style="background: rgba(30, 163, 139, 0.05); border-radius: 12px; padding: 1.5rem;">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                        <div>
                            <label style="font-size: 0.85rem; color: #6C757D; font-weight: 500;">Date d'inscription</label>
                            <div style="margin-top: 0.25rem; color: #2C3E50;">
                                {{ $payment->enrollment->enrolled_at ? $payment->enrollment->enrolled_at->format('d/m/Y à H:i') : 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label style="font-size: 0.85rem; color: #6C757D; font-weight: 500;">Progression</label>
                            <div style="margin-top: 0.25rem; color: #2C3E50; font-weight: 600;">
                                {{ $payment->enrollment->progress_percentage ?? 0 }}%
                            </div>
                        </div>
                        <div>
                            <label style="font-size: 0.85rem; color: #6C757D; font-weight: 500;">Statut</label>
                            <div style="margin-top: 0.25rem;">
                                @if($payment->enrollment->completed_at)
                                <span style="background: rgba(39, 179, 113, 0.1); color: #27B371; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.85rem; font-weight: 500;">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Complété
                                </span>
                                @else
                                <span style="background: rgba(255, 142, 42, 0.1); color: #FF8E2A; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.85rem; font-weight: 500;">
                                    <i class="fas fa-clock me-1"></i>
                                    En cours
                                </span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label style="font-size: 0.85rem; color: #6C757D; font-weight: 500;">Date de complétion</label>
                            <div style="margin-top: 0.25rem; color: #2C3E50;">
                                {{ $payment->enrollment->completed_at ? $payment->enrollment->completed_at->format('d/m/Y à H:i') : 'Non complété' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($payment->payment_details && is_array($payment->payment_details))
            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 2px solid #E0E0E0;">
                <h4 style="color: #2C3E50; margin-bottom: 1rem;">
                    <i class="fas fa-info-circle me-2" style="color: #1EA38B;"></i>
                    Détails techniques
                </h4>
                <div style="background: #F8F9FA; border-radius: 12px; padding: 1.5rem;">
                    <pre style="margin: 0; color: #2C3E50; font-size: 0.85rem; white-space: pre-wrap; word-wrap: break-word;">{{ json_encode($payment->payment_details, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
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

