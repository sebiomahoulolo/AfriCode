@extends('admin.layouts.app')

@section('breadcrumb', 'Tableau de bord')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="admin-card" data-aos="fade-up">
        <div class="admin-card-header">
            <div>
                <h1 class="admin-card-title" style="font-size: 1.5rem; margin-bottom: 0.5rem;">Tableau de bord administrateur</h1>
                <p class="admin-card-subtitle">Bienvenue dans votre espace d'administration AfriCode</p>
            </div>
            <div>
                <span style="background: linear-gradient(135deg, #1EA38B, #27B371); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; font-weight: 500;">
                    <i class="fas fa-calendar-alt"></i> {{ now()->format('d/m/Y') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="admin-grid admin-grid-4" style="margin-bottom: 2rem;">
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="100">
            <div class="admin-stats-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="admin-stats-number">{{ $userCounts['total'] }}</div>
            <div class="admin-stats-label">Utilisateurs</div>
            <div class="admin-stats-change positive">
                <i class="fas fa-arrow-up"></i> +{{ $userCounts['newThisMonth'] }} ce mois
            </div>
        </div>
        
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="200">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #FF8E2A, #FFB366);">
                <i class="fas fa-book"></i>
            </div>
            <div class="admin-stats-number">{{ $courseCounts['total'] }}</div>
            <div class="admin-stats-label">Cours</div>
            <div class="admin-stats-change positive">
                <i class="fas fa-check-circle"></i> {{ $courseCounts['published'] }} publiés
            </div>
        </div>
        
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="300">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #E32D31, #FF6B6B);">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="admin-stats-number">{{ $enrollmentCounts['total'] }}</div>
            <div class="admin-stats-label">Inscriptions</div>
            <div class="admin-stats-change positive">
                <i class="fas fa-arrow-up"></i> +{{ $enrollmentCounts['thisMonth'] }} ce mois
            </div>
        </div>
        
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="400">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #6C757D, #95A5A6);">
                <i class="fas fa-euro-sign"></i>
            </div>
            <div class="admin-stats-number">{{ number_format($paymentStats['total'], 0, ',', ' ') }}€</div>
            <div class="admin-stats-label">Revenus</div>
            <div class="admin-stats-change positive">
                <i class="fas fa-arrow-up"></i> +{{ number_format($paymentStats['thisMonth'], 0, ',', ' ') }}€ ce mois
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="admin-grid admin-grid-2" style="margin-bottom: 2rem;">
        <div class="admin-chart-card" data-aos="fade-up" data-aos-delay="100">
            <div class="admin-chart-header">
                <h3 class="admin-chart-title">Évolution des utilisateurs</h3>
                <div class="admin-chart-controls">
                    <button class="admin-chart-control active">6 mois</button>
                    <button class="admin-chart-control">1 an</button>
                </div>
            </div>
            <div class="admin-chart-body">
                <canvas id="usersChart"></canvas>
            </div>
        </div>
        
        <div class="admin-chart-card" data-aos="fade-up" data-aos-delay="200">
            <div class="admin-chart-header">
                <h3 class="admin-chart-title">Répartition des cours</h3>
                <div class="admin-chart-controls">
                    <button class="admin-chart-control active">Statut</button>
                    <button class="admin-chart-control">Catégorie</button>
                </div>
            </div>
            <div class="admin-chart-body">
                <canvas id="coursesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="admin-chart-card" data-aos="fade-up" data-aos-delay="300" style="margin-bottom: 2rem;">
        <div class="admin-chart-header">
            <h3 class="admin-chart-title">Revenus mensuels</h3>
            <div class="admin-chart-controls">
                <button class="admin-chart-control active">6 mois</button>
                <button class="admin-chart-control">1 an</button>
                <button class="admin-chart-control">2 ans</button>
            </div>
        </div>
        <div class="admin-chart-body">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Recent Activity and Quick Actions -->
    <div class="admin-grid admin-grid-2">
        <!-- Recent Users -->
        <div class="admin-table-card" data-aos="fade-up" data-aos-delay="100">
            <div class="admin-table-header">
                <h3 class="admin-table-title">Utilisateurs récents</h3>
                <a href="{{ route('admin.users.index') }}" style="color: #1EA38B; text-decoration: none; font-weight: 500;">
                    Voir tous <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="admin-table-body">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Rôle</th>
                            <th>Inscrit le</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentUsers as $user)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #1EA38B, #27B371); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.8rem;">
                                        {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 500;">{{ $user->first_name }} {{ $user->last_name }}</div>
                                        <div style="font-size: 0.8rem; color: #6C757D;">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span style="background: rgba(30, 163, 139, 0.1); color: #1EA38B; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.8rem; font-weight: 500;">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td style="color: #6C757D; font-size: 0.9rem;">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <span style="background: rgba(39, 179, 113, 0.1); color: #27B371; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.8rem; font-weight: 500;">
                                    <i class="fas fa-circle" style="font-size: 0.5rem;"></i> Actif
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="admin-card" data-aos="fade-up" data-aos-delay="200">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Actions rapides</h3>
                <p class="admin-card-subtitle">Raccourcis vers les actions fréquentes</p>
            </div>
            <div class="admin-card-body">
                <div style="display: grid; gap: 1rem;">
                    <a href="{{ route('admin.users.create') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: linear-gradient(135deg, rgba(30, 163, 139, 0.1), rgba(39, 179, 113, 0.1)); border-radius: 8px; text-decoration: none; color: inherit; transition: all 0.3s;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #1EA38B, #27B371); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600;">Créer un utilisateur</div>
                            <div style="font-size: 0.8rem; color: #6C757D;">Ajouter un nouvel utilisateur</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.courses.create') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: linear-gradient(135deg, rgba(255, 142, 42, 0.1), rgba(255, 179, 102, 0.1)); border-radius: 8px; text-decoration: none; color: inherit; transition: all 0.3s;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #FF8E2A, #FFB366); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600;">Créer un cours</div>
                            <div style="font-size: 0.8rem; color: #6C757D;">Ajouter un nouveau cours</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.platform-data.index') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: linear-gradient(135deg, rgba(227, 45, 49, 0.1), rgba(255, 107, 107, 0.1)); border-radius: 8px; text-decoration: none; color: inherit; transition: all 0.3s;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #E32D31, #FF6B6B); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600;">Voir les statistiques</div>
                            <div style="font-size: 0.8rem; color: #6C757D;">Analyser les performances</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.settings.edit') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: linear-gradient(135deg, rgba(108, 117, 125, 0.1), rgba(149, 165, 166, 0.1)); border-radius: 8px; text-decoration: none; color: inherit; transition: all 0.3s;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #6C757D, #95A5A6); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600;">Paramètres</div>
                            <div style="font-size: 0.8rem; color: #6C757D;">Configurer la plateforme</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Les graphiques seront initialisés automatiquement par admin.js
    document.addEventListener('DOMContentLoaded', function() {
        // Effet hover sur les actions rapides
        document.querySelectorAll('.admin-card-body a').forEach(link => {
            link.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
            });
            
            link.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });
    });
</script>
@endpush
@endsection

