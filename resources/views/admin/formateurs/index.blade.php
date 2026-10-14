@extends('admin.layouts.app')

@section('breadcrumb', 'Gestion des formateurs')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="admin-card" data-aos="fade-up">
        <div class="admin-card-header">
            <div>
                <h1 class="admin-card-title" style="font-size: 1.5rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-chalkboard-teacher"></i> Gestion des formateurs
                </h1>
                <p class="admin-card-subtitle">Gérer tous les formateurs de la plateforme</p>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="admin-grid admin-grid-4" data-aos="fade-up" data-aos-delay="100" style="margin-bottom: 2rem;">
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="100">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #1EA38B, #27B371);">
                <i class="fas fa-users"></i>
            </div>
            <div class="admin-stats-number">{{ $stats['total'] }}</div>
            <div class="admin-stats-label">Total formateurs</div>
        </div>
        
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="200">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #27B371, #1EA38B);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="admin-stats-number">{{ $stats['actifs'] }}</div>
            <div class="admin-stats-label">Actifs</div>
        </div>
        
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="300">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #FF8E2A, #FFB366);">
                <i class="fas fa-user-clock"></i>
            </div>
            <div class="admin-stats-number">{{ $stats['candidats'] }}</div>
            <div class="admin-stats-label">Candidats</div>
        </div>
        
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="400">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #E32D31, #FF6B6B);">
                <i class="fas fa-ban"></i>
            </div>
            <div class="admin-stats-number">{{ $stats['suspendus'] }}</div>
            <div class="admin-stats-label">Suspendus</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="admin-card" data-aos="fade-up" data-aos-delay="200" style="margin-bottom: 2rem;">
        <div class="admin-card-header">
            <h3 class="admin-card-title">Filtres de recherche</h3>
        </div>
        <div class="admin-card-body">
            <form action="{{ route('admin.formateurs.index') }}" method="GET">
                <div class="admin-grid admin-grid-3" style="gap: 1rem;">
                    <div>
                        <label for="search" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Recherche</label>
                        <input type="text" name="search" id="search" placeholder="Nom, email..." value="{{ request('search') }}" style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; transition: all 0.3s;">
                    </div>
                    <div>
                        <label for="status" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Statut :</label>
                        <select name="status" id="status" style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; transition: all 0.3s;">
                            <option value="">Tous les statuts</option>
                            <option value="candidat" {{ request('status') == 'candidat' ? 'selected' : '' }}>Candidat</option>
                            <option value="actif" {{ request('status') == 'actif' ? 'selected' : '' }}>Actif</option>
                            <option value="suspendu" {{ request('status') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                            <option value="inactif" {{ request('status') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                            <option value="banni" {{ request('status') == 'banni' ? 'selected' : '' }}>Banni</option>
                        </select> 
                    </div>
                    <div style="display: flex; align-items: flex-end;">
                        <button type="submit" style="background: linear-gradient(135deg, #1EA38B, #27B371); color: white; padding: 0.75rem 2rem; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.3s; width: 100%;">
                            <i class="fas fa-search"></i> Rechercher
                        </button>
                    </div>
                </div>
                <div style="margin-top: 1rem;">
                    <a href="{{ route('admin.formateurs.index') }}" style="background: #6C757D; color: white; padding: 0.75rem 2rem; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.3s; display: inline-block;">
                        <i class="fas fa-times"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Formateurs Table -->
    <div class="admin-table-card" data-aos="fade-up" data-aos-delay="300">
        <div class="admin-table-header">
            <h3 class="admin-table-title">Liste des formateurs ({{ $formateurs->total() }})</h3>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <div style="color: #6C757D; font-size: 0.9rem;">
                    @if($formateurs->total() > 0)
                        Affichage de {{ $formateurs->firstItem() }} à {{ $formateurs->lastItem() }} sur {{ $formateurs->total() }} formateurs
                    @endif
                </div>
            </div>
        </div>
        <div class="admin-table-body">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Formateur</th>
                        <th>Statut</th>
                        <th>Cours</th>
                        <th>Étudiants</th>
                        <th>Cours à validé</th>
                        <th>Revenus totaux</th>
                        <th>Retrait en attente</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($formateurs as $formateur)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    @if($formateur->profile_image_path)
                                        <img src="{{ asset($formateur->profile_image_path) }}" 
                                             alt="{{ $formateur->first_name }} {{ $formateur->last_name }}" 
                                             style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                    @else
                                        <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #1EA38B, #27B371); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.9rem;">
                                            {{ strtoupper(substr($formateur->first_name, 0, 1)) }}{{ strtoupper(substr($formateur->last_name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div style="font-weight: 600; color: #333;">{{ $formateur->first_name }} {{ $formateur->last_name }}</div>
                                        <div style="font-size: 0.8rem; color: #6C757D;">{{ $formateur->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $status = $formateur->formateur_status ?? 'candidat';
                                    $statusStyles = [
                                        'candidat' => ['bg' => 'linear-gradient(135deg, rgba(255, 142, 42, 0.1), rgba(255, 179, 102, 0.1))', 'color' => '#FF8E2A', 'icon' => 'fa-user-clock'],
                                        'actif' => ['bg' => 'linear-gradient(135deg, rgba(39, 179, 113, 0.1), rgba(30, 163, 139, 0.1))', 'color' => '#27B371', 'icon' => 'fa-check-circle'],
                                        'suspendu' => ['bg' => 'linear-gradient(135deg, rgba(227, 45, 49, 0.1), rgba(255, 107, 107, 0.1))', 'color' => '#E32D31', 'icon' => 'fa-ban'],
                                        'inactif' => ['bg' => 'rgba(108, 117, 125, 0.1)', 'color' => '#6C757D', 'icon' => 'fa-pause-circle'],
                                        'banni' => ['bg' => 'rgba(0, 0, 0, 0.1)', 'color' => '#000', 'icon' => 'fa-times-circle']
                                    ];
                                    $style = $statusStyles[$status] ?? $statusStyles['candidat'];
                                @endphp
                                <span style="background: {{ $style['bg'] }}; color: {{ $style['color'] }}; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600;">
                                    <i class="fas {{ $style['icon'] }}" style="font-size: 0.7rem;"></i> {{ ucfirst($status) }}
                                </span>
                            </td>
                            {{-- <td>
                                @if($formateur->badge_level)
                                    @php
                                        $badgeStyles = [
                                            'bronze' => ['bg' => 'linear-gradient(135deg, rgba(255, 142, 42, 0.1), rgba(255, 179, 102, 0.1))', 'color' => '#FF8E2A'],
                                            'argent' => ['bg' => 'rgba(192, 192, 192, 0.1)', 'color' => '#C0C0C0'],
                                            'or' => ['bg' => 'linear-gradient(135deg, rgba(255, 215, 0, 0.1), rgba(255, 223, 0, 0.1))', 'color' => '#FFD700'],
                                            'platine' => ['bg' => 'linear-gradient(135deg, rgba(30, 163, 139, 0.1), rgba(39, 179, 113, 0.1))', 'color' => '#1EA38B']
                                        ];
                                        $badgeStyle = $badgeStyles[$formateur->badge_level] ?? $badgeStyles['bronze'];
                                    @endphp
                                    <span style="background: {{ $badgeStyle['bg'] }}; color: {{ $badgeStyle['color'] }}; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600;">
                                        <i class="fas fa-medal"></i> {{ ucfirst($formateur->badge_level) }}
                                    </span>
                                @else
                                    <span style="color: #6C757D; font-size: 0.9rem;">-</span>
                                @endif
                            </td> --}}
                            <td style="color: #333; font-weight: 500;">{{ $formateur->courses_instructed_count ?? 0 }}</td>
                            <td style="color: #333; font-weight: 500;">{{ number_format($formateur->total_students ?? 0) }}</td>
                            <td style="color: #333; font-weight: 500;">
                                @if(isset($formateur->pending_validation_courses) && $formateur->pending_validation_courses > 0)
                                    <span style="background: #FFF3E0; color: #FF8E2A; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.85rem; font-weight: 600;">
                                        <i class="fas fa-clock me-1"></i>{{ $formateur->pending_validation_courses }}
                                    </span>
                                @else
                                    <span style="color: #6C757D;">0</span>
                                @endif
                            </td>
                            <td style="color: #333; font-weight: 500;">
                                <span style="color: #27B371; font-weight: 600;">
                                    <i class="fas fa-money-bill-wave me-1"></i>{{ number_format($formateur->total_revenue ?? 0, 0, ',', ' ') }} FCFA
                                </span>
                            </td>
                            <td style="color: #333; font-weight: 500;">
                                @if(isset($formateur->pending_withdrawal_amount) && $formateur->pending_withdrawal_amount > 0)
                                    <span style="background: #E3F2FD; color: #1EA38B; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.85rem; font-weight: 600;">
                                        <i class="fas fa-hourglass-half me-1"></i>{{ number_format($formateur->pending_withdrawal_amount, 0, ',', ' ') }} FCFA
                                    </span>
                                @else
                                    <span style="color: #6C757D;">0 FCFA</span>
                                @endif
                            </td>
                            
                            <td>
                                <div class="admin-table-actions">
                                    <a href="{{ route('admin.formateurs.show', $formateur->id) }}" 
                                       style="color: #1EA38B; padding: 0.5rem; border-radius: 6px; text-decoration: none; transition: all 0.2s;" 
                                       title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($formateur->formateur_status == 'candidat')
                                        <form action="{{ route('admin.formateurs.approve', $formateur->id) }}" 
                                              method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" 
                                                    style="color: #27B371; padding: 0.5rem; border: none; background: none; border-radius: 6px; cursor: pointer; transition: all 0.2s;" 
                                                    title="Valider">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 3rem; color: #6C757D;">
                                <i class="fas fa-chalkboard-teacher" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                                <div style="font-size: 1.1rem; font-weight: 500; margin-bottom: 0.5rem;">Aucun formateur trouvé</div>
                                <div style="font-size: 0.9rem;">Essayez de modifier vos critères de recherche.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($formateurs->hasPages())
            <div style="padding:1.5rem;border-top:1px solid #f0f0f0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                <span style="font-size:.8rem;color:#9ca3af;">
                    Affichage <strong style="color:#374151;">{{ $formateurs->firstItem() }}–{{ $formateurs->lastItem() }}</strong>
                    sur <strong style="color:#374151;">{{ $formateurs->total() }}</strong> formateurs
                </span>
                <nav class="africode-pag">{{ $formateurs->links() }}</nav>
            </div>
        @endif
    </div>
</div>

<style>
.africode-pag .pagination { margin:0;gap:3px;display:flex;flex-wrap:wrap; }
.africode-pag .page-link {
    color:#1EA38B; border:1.5px solid #e5e7eb; border-radius:8px !important;
    margin:0; font-weight:600; font-size:.8rem; padding:6px 12px; line-height:1.4;
    transition:all .15s;
}
.africode-pag .page-item.active .page-link {
    background:linear-gradient(135deg,#1EA38B,#27B371);
    border-color:transparent; color:#fff;
    box-shadow:0 3px 10px rgba(30,163,139,.3);
}
.africode-pag .page-item.disabled .page-link { color:#d1d5db; background:#f9fafb; pointer-events:none; }
.africode-pag .page-link:hover:not([aria-current]) {
    background:rgba(30,163,139,.08); color:#1EA38B; border-color:rgba(30,163,139,.3);
}
</style>

@endsection

@push('scripts')
<script>
    // Styles pour les effets hover
    document.addEventListener('DOMContentLoaded', function() {
        // Hover effects pour les boutons d'action
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
        
        // Hover effects pour les boutons principaux
        document.querySelectorAll('a[style*="background: linear-gradient"], button[style*="background: linear-gradient"]').forEach(element => {
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

