@extends('admin.layouts.app')

@section('breadcrumb', 'Détails formateur')

@section('content')
<div class="fade-in">
    <!-- Header -->
    <div class="admin-card" data-aos="fade-up">
        <div class="admin-card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 class="admin-card-title" style="font-size: 1.5rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-user-tie"></i> {{ $formateur->first_name }} {{ $formateur->last_name }}
                </h1>
                <p class="admin-card-subtitle">{{ $formateur->email }}</p>
            </div>
            <div>
                <a href="{{ route('admin.formateurs.index') }}" style="background: #6C757D; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.3s; display: inline-block;">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="admin-grid admin-grid-4" data-aos="fade-up" data-aos-delay="100" style="margin-bottom: 2rem;">
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="100">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #11c06d, #239e79);">
                <i class="fas fa-book"></i>
            </div>
            <div class="admin-stats-number">{{ $stats['total_courses'] }}</div>
            <div class="admin-stats-label">Cours créés</div>
        </div>
        
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="200">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #239e79, #11c06d);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="admin-stats-number">{{ $stats['published_courses'] }}</div>
            <div class="admin-stats-label">Cours publiés</div>
        </div>
        
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="300">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #fea837, #FFB366);">
                <i class="fas fa-clock"></i>
            </div>
            <div class="admin-stats-number">{{ $stats['pending_validation'] }}</div>
            <div class="admin-stats-label">En attente de validation</div>
        </div>
        
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="400">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #fa1c39, #FF6B6B);">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="admin-stats-number">{{ number_format($stats['total_earnings'], 0, ',', ' ') }}</div>
            <div class="admin-stats-label">Revenus totaux (FCFA)</div>
        </div>
    </div>

    <div class="admin-grid admin-grid-2" style="gap: 2rem; margin-bottom: 2rem;">
        <!-- Colonne gauche -->
        <div>
        <!-- Informations -->
            <div class="admin-card" data-aos="fade-up" data-aos-delay="200" style="margin-bottom: 1.5rem;">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Informations</h3>
                </div>
                <div class="admin-card-body">
                    <div style="display: grid; gap: 1rem;">
                        <div>
                            <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.25rem; font-weight: 500;">Statut :
                            @php
                                $status = $formateur->formateur_status ?? 'candidat';
                                $statusStyles = [
                                    'candidat' => ['bg' => 'linear-gradient(135deg, rgba(254, 168, 55, 0.1), rgba(255, 179, 102, 0.1))', 'color' => '#fea837', 'icon' => 'fa-user-clock'],
                                    'actif' => ['bg' => 'linear-gradient(135deg, rgba(35, 158, 121, 0.1), rgba(17, 192, 109, 0.1))', 'color' => '#239e79', 'icon' => 'fa-check-circle'],
                                    'suspendu' => ['bg' => 'linear-gradient(135deg, rgba(250, 28, 57, 0.1), rgba(255, 107, 107, 0.1))', 'color' => '#fa1c39', 'icon' => 'fa-ban'],
                                    'inactif' => ['bg' => 'rgba(108, 117, 125, 0.1)', 'color' => '#6C757D', 'icon' => 'fa-pause-circle'],
                                    'banni' => ['bg' => 'rgba(0, 0, 0, 0.1)', 'color' => '#000', 'icon' => 'fa-times-circle']
                                ];
                                $style = $statusStyles[$status] ?? $statusStyles['candidat'];
                            @endphp
                            <span style="background: {{ $style['bg'] }}; color: {{ $style['color'] }}; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600; display: inline-block;">
                                <i class="fas {{ $style['icon'] }}" style="font-size: 0.7rem;"></i> {{ ucfirst($status) }}
                            </span></div>
                        </div>
                        
                        {{-- <div>
                            <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.25rem; font-weight: 500;">Badge :
                            @if($formateur->badge_level)
                                @php
                                    $badgeStyles = [
                                        'bronze' => ['bg' => 'linear-gradient(135deg, rgba(254, 168, 55, 0.1), rgba(255, 179, 102, 0.1))', 'color' => '#fea837'],
                                        'argent' => ['bg' => 'rgba(192, 192, 192, 0.1)', 'color' => '#C0C0C0'],
                                        'or' => ['bg' => 'linear-gradient(135deg, rgba(255, 215, 0, 0.1), rgba(255, 223, 0, 0.1))', 'color' => '#FFD700'],
                                        'platine' => ['bg' => 'linear-gradient(135deg, rgba(17, 192, 109, 0.1), rgba(35, 158, 121, 0.1))', 'color' => '#11c06d']
                                    ];
                                    $badgeStyle = $badgeStyles[$formateur->badge_level] ?? $badgeStyles['bronze'];
                                @endphp
                                <span style="background: {{ $badgeStyle['bg'] }}; color: {{ $badgeStyle['color'] }}; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600; display: inline-block;">
                                    <i class="fas fa-medal"></i> {{ ucfirst($formateur->badge_level) }}
                                </span>
                            @else
                                <span style="color: #6C757D; font-size: 0.9rem;">Aucun</span>
                            @endif
                        </div></div> --}}
                        </div>
                        <div>
                            <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.25rem; font-weight: 500;">Onboarding :
                            @if($formateur->onboarding_completed)
                                <span style="background: rgba(35, 158, 121, 0.1); color: #239e79; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600; display: inline-block;">
                                    <i class="fas fa-check-circle"></i> Complété
                                </span>
                            @else
                                <span style="background: rgba(250, 28, 57, 0.1); color: #fa1c39; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600; display: inline-block;">
                                    <i class="fas fa-times-circle"></i> Non complété
                                </span>
                            @endif
                        </div></div>
                        
                        <div>
                            <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.25rem; font-weight: 500;">Revenus en attente : {{ number_format($stats['pending_earnings'], 0, ',', ' ') }} FCFA</div>
                            {{-- <div style="font-size: 1rem; font-weight: 600; color: #333;"></div> --}}
                        </div>
                        
                        <div>
                            <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.25rem; font-weight: 500;">Note moyenne :
                            <span style="background: linear-gradient(135deg, rgba(17, 192, 109, 0.1), rgba(35, 158, 121, 0.1)); color: #11c06d; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.9rem; font-weight: 600; display: inline-block;">
                                <i class="fas fa-star"></i> {{ number_format($stats['average_rating'], 1) }}/5
                        </span>
                        </div></div>
                        
                        <div>
                            <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.25rem; font-weight: 500;">Inscrit le : {{ $formateur->created_at->format('d/m/Y') }}
                            {{-- <div style="font-size: 0.9rem; color: #333;"></div> --}}
                        </div>
                        
                        @if($formateur->formateur_status == 'candidat' || $formateur->formateur_status == null)
                            <div style="background: #FFF3E0; padding: 1rem; border-radius: 8px; border-left: 4px solid #fea837; margin-top: 1rem;">
                                <div style="font-size: 0.9rem; font-weight: 600; color: #E65100; margin-bottom: 0.5rem;">
                                    <i class="fas fa-info-circle"></i> Vérification du compte candidat
                                </div>
                                <div style="display: grid; gap: 0.5rem; font-size: 0.85rem;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        @if($formateur->onboarding_completed)
                                            <i class="fas fa-check-circle" style="color: #239e79;"></i>
                                        @else
                                            <i class="fas fa-times-circle" style="color: #fa1c39;"></i>
                                        @endif
                                        <span>Onboarding: {{ $formateur->onboarding_completed ? 'Complété' : 'Non complété' }}</span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        @if($formateur->contract_signed_at)
                                            <i class="fas fa-check-circle" style="color: #239e79;"></i>
                                        @else
                                            <i class="fas fa-times-circle" style="color: #fa1c39;"></i>
                                        @endif
                                        <span>Contrat: {{ $formateur->contract_signed_at ? 'Signé le ' . $formateur->contract_signed_at->format('d/m/Y') : 'Non signé' }}</span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        @if($formateur->payment_method && $formateur->payment_account)
                                            <i class="fas fa-check-circle" style="color: #239e79;"></i>
                                        @else
                                            <i class="fas fa-times-circle" style="color: #fa1c39;"></i>
                                        @endif
                                        <span>Infos bancaires: {{ $formateur->payment_method && $formateur->payment_account ? 'Configurées' : 'Non configurées' }}</span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        @if($formateur->email_verified_at)
                                            <i class="fas fa-check-circle" style="color: #239e79;"></i>
                                        @else
                                            <i class="fas fa-times-circle" style="color: #fa1c39;"></i>
                                        @endif
                                        <span>Email: {{ $formateur->email_verified_at ? 'Vérifié' : 'Non vérifié' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="admin-card" data-aos="fade-up" data-aos-delay="300">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Actions</h3>
                </div>
                <div class="admin-card-body">
                    <div style="display: grid; gap: 0.75rem;">
                    @if(($formateur->formateur_status == 'candidat' || $formateur->formateur_status == null) && $formateur->role == 'formateur')
                            <form action="{{ route('admin.formateurs.approve', $formateur->id) }}" method="POST">
                            @csrf
                                <button type="submit" style="background: linear-gradient(135deg, #239e79, #11c06d); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.3s; width: 100%;">
                                <i class="fas fa-check"></i> Valider formateur (Candidat → Actif)
                            </button>
                        </form>
                        <div style="background: #E3F2FD; padding: 0.75rem; border-radius: 8px; border-left: 4px solid #11c06d; font-size: 0.85rem; color: #333;">
                            <i class="fas fa-info-circle" style="color: #11c06d;"></i> 
                            <strong>Action :</strong> Valider ce formateur pour le passer du statut "Candidat" au statut "Actif". L'onboarding sera automatiquement marqué comme complété. Un email de confirmation sera envoyé au formateur.
                        </div>
                    @endif
                    
                    @if($formateur->formateur_status == 'suspendu')
                            <form action="{{ route('admin.formateurs.unsuspend', $formateur->id) }}" method="POST">
                            @csrf
                                <button type="submit" style="background: linear-gradient(135deg, #239e79, #11c06d); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.3s; width: 100%;">
                                <i class="fas fa-unlock"></i> Lever suspension
                            </button>
                        </form>
                    @else
                            <button type="button" onclick="openModal('warnModal')" style="background: linear-gradient(135deg, #fea837, #FFB366); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.3s; width: 100%;">
                            <i class="fas fa-exclamation-triangle"></i> Ajouter avertissement
                        </button>
                            <button type="button" onclick="openModal('suspendModal')" style="background: linear-gradient(135deg, #fa1c39, #FF6B6B); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.3s; width: 100%;">
                            <i class="fas fa-ban"></i> Suspendre
                        </button>
                    @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne droite -->
        <div>
        <!-- Cours en attente de validation -->
            <div class="admin-card" data-aos="fade-up" data-aos-delay="400" style="margin-bottom: 1.5rem;">
                <div class="admin-card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 class="admin-card-title">
                        <i class="fas fa-clock"></i> Cours en attente de validation
                    </h3>
                    @if($coursesPendingValidation->count() > 0)
                        <span style="background: linear-gradient(135deg, rgba(254, 168, 55, 0.1), rgba(255, 179, 102, 0.1)); color: #fea837; padding: 0.5rem 1rem; border-radius: 15px; font-size: 0.85rem; font-weight: 600;">
                            {{ $coursesPendingValidation->count() }} {{ Str::plural('cours', $coursesPendingValidation->count()) }}
                        </span>
                    @endif
                </div>
                <div class="admin-card-body">
                    @if($coursesPendingValidation->count() > 0)
                        <div class="admin-table-body">
                            <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Statut</th>
                                    <th>Soumis le</th>
                                    
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                    @foreach($coursesPendingValidation as $course)
                                        <tr>
                                            <td>
                                                <div style="font-weight: 500; color: #333;">{{ $course->title }}</div>
                                            </td>
                                            <td>
                                                @if($course->status == 'published')
                                                    <span style="background: rgba(35, 158, 121, 0.1); color: #239e79; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600;">
                                                        <i class="fas fa-check-circle"></i> Publié
                                                    </span>
                                                @else
                                                    <span style="background: rgba(108, 117, 125, 0.1); color: #6C757D; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600;">
                                                        <i class="fas fa-file"></i> Brouillon
                                            </span>
                                                @endif
                                        </td>
                                        <td>
                                            @if($course->submitted_for_validation_at)
                                                @php
                                                    $submittedDate = $course->submitted_for_validation_at;
                                                    if (is_string($submittedDate)) {
                                                        $submittedDate = \Carbon\Carbon::parse($submittedDate);
                                                    }
                                            @endphp
                                                <div style="font-size: 0.85rem; color: #6C757D;">
                                                    {{ $submittedDate->format('d/m/Y') }}
                                                </div>
                                                <div style="font-size: 0.75rem; color: #6C757D;">
                                                    {{ $submittedDate->format('H:i') }}
                                                </div>
                                            @else
                                                <span style="color: #6C757D; font-size: 0.9rem;">-</span>
                                            @endif
                                        </td>
                                            
                                            <td>
                                                <div class="admin-table-actions">
                                                    <button type="button" onclick="openModal('validateCourseModal{{ $course->id }}')" 
                                                            style="color: #239e79; padding: 0.5rem; border: none; background: none; border-radius: 6px; cursor: pointer; transition: all 0.2s;" 
                                                            title="Valider">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                   
                                                    <button type="button" onclick="openModal('rejectCourseModal{{ $course->id }}')" 
                                                            style="color: #fa1c39; padding: 0.5rem; border: none; background: none; border-radius: 6px; cursor: pointer; transition: all 0.2s;" 
                                                            title="Rejeter">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                    <a href="{{ route('admin.courses.show', $course->id) }}" style="color: #11c06d; padding: 0.5rem; border-radius: 6px; text-decoration: none; transition: all 0.2s;" title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                                </div>
                                        </td>
                                    </tr>
                                    @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <div style="text-align: center; padding: 2rem; color: #6C757D;">
                            <i class="fas fa-check-circle" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3; color: #239e79;"></i>
                            <div style="font-size: 1.1rem; font-weight: 500; margin-bottom: 0.5rem;">Aucun cours en attente</div>
                            <div style="font-size: 0.9rem;">Tous les cours de ce formateur ont été traités ou aucun cours n'est en attente de validation.</div>
                        </div>
                    @endif
            </div>
        </div>
    </div>
    </div>

    {{-- <!-- Actions disciplinaires -->
    @if($disciplinaryActions->count() > 0)
        <div class="admin-card" data-aos="fade-up" data-aos-delay="500" style="margin-bottom: 1.5rem;">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Historique des actions disciplinaires</h3>
            </div>
            <div class="admin-card-body">
                <div style="display: grid; gap: 1rem;">
                    @foreach($disciplinaryActions as $action)
                        <div style="padding: 1rem; background: #F8F9FA; border-radius: 8px; border-left: 4px solid #fa1c39;">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                                <div>
                                    <div style="font-weight: 600; color: #333; margin-bottom: 0.25rem;">
                                        {{ ucfirst(str_replace('_', ' ', $action->action_type)) }}
                                    </div>
                                    <div style="font-size: 0.85rem; color: #6C757D;">
                                        {{ $action->action_date->format('d/m/Y à H:i') }}
                                    </div>
                                </div>
                                @if($action->is_active)
                                    <span style="background: rgba(250, 28, 57, 0.1); color: #fa1c39; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                                        Actif
                                    </span>
                                @endif
                            </div>
                            <div style="font-size: 0.9rem; color: #333; margin-bottom: 0.25rem;">
                                <strong>Raison:</strong> {{ $action->reason }}
                            </div>
                            @if($action->details)
                                <div style="font-size: 0.85rem; color: #6C757D;">
                                    {{ $action->details }}
                                </div>
                            @endif
                            @if($action->expires_at)
                                <div style="font-size: 0.8rem; color: #6C757D; margin-top: 0.5rem;">
                                    Expire le: {{ $action->expires_at->format('d/m/Y') }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif --}}

    <!-- Informations bancaires -->
    <div class="admin-card" data-aos="fade-up" data-aos-delay="500" style="margin-bottom: 1.5rem;">
        <div class="admin-card-header">
            <h3 class="admin-card-title">
                <i class="fas fa-credit-card"></i> Informations bancaires
            </h3>
        </div>
        <div class="admin-card-body">
            @if($formateur->payment_method && $formateur->payment_account)
                <div style="display: grid; gap: 1rem;">
                    <div>
                        <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.25rem; font-weight: 500;">Méthode de paiement</div>
                        <div style="font-size: 0.9rem; color: #333; font-weight: 500;">
                            @if($formateur->payment_method == 'mobile_money')
                                <i class="fas fa-mobile-alt"></i> Mobile Money
                            @elseif($formateur->payment_method == 'bank_transfer')
                                <i class="fas fa-university"></i> Virement bancaire
                            @else
                                <i class="fas fa-credit-card"></i> {{ ucfirst(str_replace('_', ' ', $formateur->payment_method)) }}
                            @endif
                        </div>
                    </div>
                    <div>
                        <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.25rem; font-weight: 500;">Numéro de compte</div>
                        <div style="font-size: 1rem; color: #333; font-weight: 600; font-family: monospace; background: #F8F9FA; padding: 0.5rem; border-radius: 6px;">
                            {{ $formateur->payment_account }}
                        </div>
                    </div>
                </div>
            @else
                <div style="text-align: center; padding: 1.5rem; color: #6C757D; background: #F8F9FA; border-radius: 8px;">
                    <i class="fas fa-exclamation-circle" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                    <div style="font-weight: 500;">Aucune information bancaire enregistrée</div>
                    <div style="font-size: 0.85rem; margin-top: 0.25rem;">Le formateur doit compléter ses informations de paiement dans son profil.</div>
                </div>
            @endif
        </div>
    

    <!-- Retraits récents -->
    @if($withdrawals->count() > 0)
        <div class="admin-card" data-aos="fade-up" data-aos-delay="600">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Retraits récents</h3>
            </div>
            <div class="admin-card-body">
                <div class="admin-table-body">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($withdrawals as $withdrawal)
                                <tr>
                                    <td style="font-weight: 600; color: #333;">{{ number_format($withdrawal->amount, 0, ',', ' ') }} FCFA</td>
                                    <td>
                                        @php
                                            $withdrawalStatusStyles = [
                                                'pending' => ['bg' => 'linear-gradient(135deg, rgba(254, 168, 55, 0.1), rgba(255, 179, 102, 0.1))', 'color' => '#fea837'],
                                                'processing' => ['bg' => 'linear-gradient(135deg, rgba(17, 192, 109, 0.1), rgba(35, 158, 121, 0.1))', 'color' => '#11c06d'],
                                                'completed' => ['bg' => 'rgba(35, 158, 121, 0.1)', 'color' => '#239e79'],
                                                'failed' => ['bg' => 'rgba(250, 28, 57, 0.1)', 'color' => '#fa1c39']
                                            ];
                                            $wStyle = $withdrawalStatusStyles[$withdrawal->status] ?? $withdrawalStatusStyles['pending'];
                                        @endphp
                                        <span style="background: {{ $wStyle['bg'] }}; color: {{ $wStyle['color'] }}; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600;">
                                            {{ ucfirst($withdrawal->status) }}
                                        </span>
                                    </td>
                                    <td style="color: #6C757D;">{{ $withdrawal->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="admin-table-actions">
                                            @if($withdrawal->status == 'pending')
                                                <button type="button" onclick="openModal('processWithdrawalModal{{ $withdrawal->id }}')" 
                                                        style="color: #11c06d; padding: 0.5rem; border: none; background: none; border-radius: 6px; cursor: pointer; transition: all 0.2s;" 
                                                        title="Traiter le retrait">
                                                    <i class="fas fa-play-circle"></i>
                                                </button>
                                            @elseif($withdrawal->status == 'processing')
                                                <button type="button" onclick="openModal('completeWithdrawalModal{{ $withdrawal->id }}')" 
                                                        style="color: #239e79; padding: 0.5rem; border: none; background: none; border-radius: 6px; cursor: pointer; transition: all 0.2s;" 
                                                        title="Compléter le retrait">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                            @endif
                                        </div>
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
</div>

<!-- Modal Avertissement -->
<div id="warnModal" class="admin-confirm-modal" style="display: none;">
    <div class="admin-confirm-backdrop" onclick="closeModal('warnModal')"></div>
    <div class="admin-confirm-dialog">
            <form action="{{ route('admin.formateurs.warn', $formateur->id) }}" method="POST">
                @csrf
            <div class="admin-confirm-header">
                <h6>Ajouter un avertissement</h6>
                </div>
            <div class="admin-confirm-body">
                <div style="margin-bottom: 1rem;">
                    <label for="reason" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Raison *</label>
                    <input type="text" id="reason" name="reason" required style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem;">
                    </div>
                <div>
                    <label for="details" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Détails</label>
                    <textarea id="details" name="details" rows="3" style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; resize: vertical;"></textarea>
                    </div>
                </div>
            <div class="admin-confirm-footer">
                <button type="button" onclick="closeModal('warnModal')" class="admin-btn admin-btn-secondary">Annuler</button>
                <button type="submit" class="admin-btn admin-btn-primary" style="background: linear-gradient(135deg, #fea837, #FFB366);">Ajouter</button>
                </div>
            </form>
    </div>
</div>

<!-- Modal Suspension -->
<div id="suspendModal" class="admin-confirm-modal" style="display: none;">
    <div class="admin-confirm-backdrop" onclick="closeModal('suspendModal')"></div>
    <div class="admin-confirm-dialog">
            <form action="{{ route('admin.formateurs.suspend', $formateur->id) }}" method="POST">
                @csrf
            <div class="admin-confirm-header">
                <h6>Suspendre le formateur</h6>
                </div>
            <div class="admin-confirm-body">
                <div style="margin-bottom: 1rem;">
                    <label for="days" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Durée</label>
                    <select id="days" name="days" required style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem;">
                            <option value="7">7 jours</option>
                            <option value="30">30 jours</option>
                        </select>
                    </div>
                <div style="margin-bottom: 1rem;">
                    <label for="suspend_reason" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Raison *</label>
                    <input type="text" id="suspend_reason" name="reason" required style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem;">
                    </div>
                <div>
                    <label for="suspend_details" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Détails</label>
                    <textarea id="suspend_details" name="details" rows="3" style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; resize: vertical;"></textarea>
                    </div>
                </div>
            <div class="admin-confirm-footer">
                <button type="button" onclick="closeModal('suspendModal')" class="admin-btn admin-btn-secondary">Annuler</button>
                <button type="submit" class="admin-btn admin-btn-primary" style="background: linear-gradient(135deg, #fa1c39, #FF6B6B);">Suspendre</button>
                </div>
            </form>
    </div>
</div>

@foreach($withdrawals as $withdrawal)
    @if($withdrawal->status == 'pending')
        <!-- Modal Traiter Retrait {{ $withdrawal->id }} -->
        <div id="processWithdrawalModal{{ $withdrawal->id }}" class="admin-confirm-modal" style="display: none;">
            <div class="admin-confirm-backdrop" onclick="closeModal('processWithdrawalModal{{ $withdrawal->id }}')"></div>
            <div class="admin-confirm-dialog" style="max-width: 500px;">
                <form action="{{ route('admin.formateurs.withdrawals.process', $withdrawal->id) }}" method="POST">
                    @csrf
                    <div class="admin-confirm-header">
                        <h6>Traiter le retrait</h6>
                    </div>
                    <div class="admin-confirm-body">
                        <div style="background: #F8F9FA; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                            <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.25rem;">Montant à transférer</div>
                            <div style="font-size: 1.5rem; font-weight: 700; color: #11c06d;">{{ number_format($withdrawal->amount, 0, ',', ' ') }} FCFA</div>
                        </div>
                        
                        @if($formateur->payment_method && $formateur->payment_account)
                            <div style="background: #E8F5E9; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #239e79;">
                                <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.5rem; font-weight: 500;">Compte de destination</div>
                                <div style="font-size: 0.9rem; color: #333; margin-bottom: 0.25rem;">
                                    <strong>Méthode:</strong> 
                                    @if($formateur->payment_method == 'mobile_money')
                                        <i class="fas fa-mobile-alt"></i> Mobile Money
                                    @elseif($formateur->payment_method == 'bank_transfer')
                                        <i class="fas fa-university"></i> Virement bancaire
                                    @else
                                        {{ ucfirst(str_replace('_', ' ', $formateur->payment_method)) }}
                                    @endif
                                </div>
                                <div style="font-size: 0.9rem; color: #333; font-family: monospace; font-weight: 600;">
                                    <strong>Compte:</strong> {{ $formateur->payment_account }}
                                </div>
                            </div>
                        @else
                            <div style="background: #FFF3E0; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #fea837;">
                                <div style="font-size: 0.9rem; color: #E65100;">
                                    <i class="fas fa-exclamation-triangle"></i> <strong>Attention:</strong> Le formateur n'a pas configuré ses informations bancaires.
                                </div>
                    </div>
                        @endif
                        
                        <div style="margin-bottom: 1rem;">
                            <label for="payment_reference{{ $withdrawal->id }}" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Référence de paiement (optionnel)</label>
                            <input type="text" id="payment_reference{{ $withdrawal->id }}" name="payment_reference" 
                                   placeholder="Ex: TRX123456789 ou REF20241124-001" 
                                   style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; font-family: monospace;">
                            <div style="font-size: 0.8rem; color: #6C757D; margin-top: 0.25rem;">
                                La référence sera générée automatiquement si non fournie
                            </div>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label for="process_notes{{ $withdrawal->id }}" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Notes (optionnel)</label>
                            <textarea id="process_notes{{ $withdrawal->id }}" name="notes" rows="3" placeholder="Ajoutez des notes sur le traitement du retrait..." style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; resize: vertical;"></textarea>
                        </div>
                        <div style="background: #E8F5E9; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #239e79;">
                            <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.5rem; font-weight: 500;"><i class="fas fa-exclamation-triangle text-warning me-1"></i> Action immédiate</div>
                            <div style="font-size: 0.9rem; color: #333;">
                                En validant ce retrait, il sera directement marqué comme <strong>complété (succès)</strong> et les revenus du formateur seront mis à jour automatiquement.
                            </div>
                        </div>
                    </div>
                    <div class="admin-confirm-footer">
                        <button type="button" onclick="closeModal('processWithdrawalModal{{ $withdrawal->id }}')" class="admin-btn admin-btn-secondary">Annuler</button>
                        <button type="submit" class="admin-btn admin-btn-primary" style="background: linear-gradient(135deg, #239e79, #11c06d);">Valider et compléter le retrait</button>
                    </div>
            </form>
        </div>
        </div>
    @endif
    
    @if($withdrawal->status == 'processing')
        <!-- Modal Compléter Retrait {{ $withdrawal->id }} -->
        <div id="completeWithdrawalModal{{ $withdrawal->id }}" class="admin-confirm-modal" style="display: none;">
            <div class="admin-confirm-backdrop" onclick="closeModal('completeWithdrawalModal{{ $withdrawal->id }}')"></div>
            <div class="admin-confirm-dialog" style="max-width: 500px;">
                <form action="{{ route('admin.formateurs.withdrawals.complete', $withdrawal->id) }}" method="POST">
                    @csrf
                    <div class="admin-confirm-header">
                        <h6>Compléter le retrait</h6>
                    </div>
                    <div class="admin-confirm-body">
                        <div style="background: #F8F9FA; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                            <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.25rem;">Montant transféré</div>
                            <div style="font-size: 1.5rem; font-weight: 700; color: #239e79;">{{ number_format($withdrawal->amount, 0, ',', ' ') }} FCFA</div>
                        </div>
                        
                        @if($formateur->payment_method && $formateur->payment_account)
                            <div style="background: #E8F5E9; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #239e79;">
                                <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.5rem; font-weight: 500;">Compte de destination</div>
                                <div style="font-size: 0.9rem; color: #333; margin-bottom: 0.25rem;">
                                    <strong>Méthode:</strong> 
                                    @if($formateur->payment_method == 'mobile_money')
                                        <i class="fas fa-mobile-alt"></i> Mobile Money
                                    @elseif($formateur->payment_method == 'bank_transfer')
                                        <i class="fas fa-university"></i> Virement bancaire
                                    @else
                                        {{ ucfirst(str_replace('_', ' ', $formateur->payment_method)) }}
                                    @endif
                                </div>
                                <div style="font-size: 0.9rem; color: #333; font-family: monospace; font-weight: 600;">
                                    <strong>Compte:</strong> {{ $formateur->payment_account }}
                                </div>
                            </div>
                        @endif
                        
                        <div style="background: #E3F2FD; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #11c06d;">
                            <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.5rem; font-weight: 500;">Vérification du transfert</div>
                            <div style="font-size: 0.9rem; color: #333;">
                                Assurez-vous que le transfert a bien été effectué vers le compte du formateur avant de compléter cette action.
                            </div>
                        </div>
                        
                        <div style="margin-bottom: 1rem;">
                            <label for="payment_reference{{ $withdrawal->id }}" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Référence de paiement *</label>
                            <input type="text" id="payment_reference{{ $withdrawal->id }}" name="payment_reference" required 
                                   placeholder="Ex: TRX123456789 ou REF20241124-001" 
                                   style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; font-family: monospace;">
                            <div style="font-size: 0.8rem; color: #6C757D; margin-top: 0.25rem;">
                                Entrez la référence de transaction fournie par votre système de paiement
                            </div>
                        </div>
                    </div>
                    <div class="admin-confirm-footer">
                        <button type="button" onclick="closeModal('completeWithdrawalModal{{ $withdrawal->id }}')" class="admin-btn admin-btn-secondary">Annuler</button>
                        <button type="submit" class="admin-btn admin-btn-primary" style="background: linear-gradient(135deg, #239e79, #11c06d);">Compléter le retrait</button>
                    </div>
                </form>
    </div>
</div>
    @endif
@endforeach

<!-- Modals pour validation des cours -->
@foreach($formateur->coursesInstructed as $course)
    @if($course->validation_status == 'pending_validation')
        <!-- Modal Valider Cours {{ $course->id }} -->
        <div id="validateCourseModal{{ $course->id }}" class="admin-confirm-modal" style="display: none;">
            <div class="admin-confirm-backdrop" onclick="closeModal('validateCourseModal{{ $course->id }}')"></div>
            <div class="admin-confirm-dialog" style="max-width: 500px;">
                <form action="{{ route('admin.formateurs.courses.validate', $course->id) }}" method="POST">
                    @csrf
                    <div class="admin-confirm-header">
                        <h6>Valider le cours</h6>
                    </div>
                    <div class="admin-confirm-body">
                        <div style="background: #E8F5E9; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #239e79;">
                            <div style="font-size: 0.9rem; font-weight: 600; color: #333; margin-bottom: 0.25rem;">{{ $course->title }}</div>
                            <div style="font-size: 0.85rem; color: #6C757D;">Ce cours sera validé et publié automatiquement.</div>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label for="quality_score{{ $course->id }}" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Score de qualité (optionnel)</label>
                            <input type="number" id="quality_score{{ $course->id }}" name="quality_score" min="0" max="100" 
                                   placeholder="Ex: 85" 
                                   style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem;">
                            <div style="font-size: 0.8rem; color: #6C757D; margin-top: 0.25rem;">
                                Note sur 100 pour évaluer la qualité du cours
                            </div>
                        </div>
                    </div>
                    <div class="admin-confirm-footer">
                        <button type="button" onclick="closeModal('validateCourseModal{{ $course->id }}')" class="admin-btn admin-btn-secondary">Annuler</button>
                        <button type="submit" class="admin-btn admin-btn-primary" style="background: linear-gradient(135deg, #239e79, #11c06d);">Valider et publier</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Demander Corrections {{ $course->id }} -->
        <div id="revisionCourseModal{{ $course->id }}" class="admin-confirm-modal" style="display: none;">
            <div class="admin-confirm-backdrop" onclick="closeModal('revisionCourseModal{{ $course->id }}')"></div>
            <div class="admin-confirm-dialog" style="max-width: 500px;">
                <form action="{{ route('admin.formateurs.courses.revision', $course->id) }}" method="POST">
                    @csrf
                    <div class="admin-confirm-header">
                        <h6>Demander des corrections</h6>
                    </div>
                    <div class="admin-confirm-body">
                        <div style="background: #FFF3E0; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #fea837;">
                            <div style="font-size: 0.9rem; font-weight: 600; color: #333; margin-bottom: 0.25rem;">{{ $course->title }}</div>
                            <div style="font-size: 0.85rem; color: #6C757D;">Le formateur devra apporter des corrections avant de pouvoir soumettre à nouveau.</div>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label for="revision_notes{{ $course->id }}" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Notes de correction *</label>
                            <textarea id="revision_notes{{ $course->id }}" name="revision_notes" required rows="4" 
                                      placeholder="Indiquez les corrections à apporter au formateur..." 
                                      style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; resize: vertical;"></textarea>
                            <div style="font-size: 0.8rem; color: #6C757D; margin-top: 0.25rem;">
                                Ces notes seront visibles par le formateur
                    </div>
                    </div>
                </div>
                    <div class="admin-confirm-footer">
                        <button type="button" onclick="closeModal('revisionCourseModal{{ $course->id }}')" class="admin-btn admin-btn-secondary">Annuler</button>
                        <button type="submit" class="admin-btn admin-btn-primary" style="background: linear-gradient(135deg, #fea837, #FFB366);">Demander corrections</button>
                </div>
            </form>
        </div>
        </div>

        <!-- Modal Rejeter Cours {{ $course->id }} -->
        <div id="rejectCourseModal{{ $course->id }}" class="admin-confirm-modal" style="display: none;">
            <div class="admin-confirm-backdrop" onclick="closeModal('rejectCourseModal{{ $course->id }}')"></div>
            <div class="admin-confirm-dialog" style="max-width: 500px;">
                <form action="{{ route('admin.formateurs.courses.reject', $course->id) }}" method="POST">
                    @csrf
                    <div class="admin-confirm-header">
                        <h6>Rejeter le cours</h6>
                    </div>
                    <div class="admin-confirm-body">
                        <div style="background: #FFEBEE; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #fa1c39;">
                            <div style="font-size: 0.9rem; font-weight: 600; color: #333; margin-bottom: 0.25rem;">{{ $course->title }}</div>
                            <div style="font-size: 0.85rem; color: #6C757D;">Cette action rejettera le cours. Le formateur pourra modifier et resoumettre ce cours après avoir apporté les corrections demandées.</div>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label for="admin_notes{{ $course->id }}" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Raison du rejet *</label>
                            <textarea id="admin_notes{{ $course->id }}" name="admin_notes" required rows="4" 
                                      placeholder="Expliquez pourquoi ce cours est rejeté..." 
                                      style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; resize: vertical;"></textarea>
                            <div style="font-size: 0.8rem; color: #6C757D; margin-top: 0.25rem;">
                                Ces notes seront visibles par le formateur
                            </div>
                        </div>
                    </div>
                    <div class="admin-confirm-footer">
                        <button type="button" onclick="closeModal('rejectCourseModal{{ $course->id }}')" class="admin-btn admin-btn-secondary">Annuler</button>
                        <button type="submit" class="admin-btn admin-btn-primary" style="background: linear-gradient(135deg, #fa1c39, #FF6B6B);">Rejeter le cours</button>
                    </div>
                </form>
    </div>
</div>
    @endif
@endforeach
@endsection

@push('scripts')
<script>
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    // Fermer avec Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.admin-confirm-modal').forEach(modal => {
                if (modal.style.display === 'flex') {
                    closeModal(modal.id);
                }
            });
        }
    });
    
    // Hover effects pour les boutons
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.admin-table-actions a, .admin-table-actions button').forEach(element => {
            element.addEventListener('mouseenter', function() {
                this.style.background = 'rgba(0, 0, 0, 0.1)';
                this.style.transform = 'scale(1.1)';
            });
            
            element.addEventListener('mouseleave', function() {
                this.style.background = 'none';
                this.style.transform = 'scale(1)';
            });
        });
        
        document.querySelectorAll('button[style*="background: linear-gradient"], a[style*="background: linear-gradient"]').forEach(element => {
            element.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
            });
            
            element.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });
    });
</script>
@endpush
