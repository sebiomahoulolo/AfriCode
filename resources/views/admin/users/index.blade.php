@extends('admin.layouts.app')

@section('breadcrumb', 'Gestion des utilisateurs')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="admin-card" data-aos="fade-up">
        <div class="admin-card-header">
            <div>
                <h1 class="admin-card-title" style="font-size: 1.5rem; margin-bottom: 0.5rem;">Gestion des utilisateurs</h1>
                <p class="admin-card-subtitle">Gérer tous les utilisateurs de la plateforme</p>
            </div>
            <div class="admin-card-actions">
                <a href="{{ route('admin.users.create') }}" style="background: linear-gradient(135deg, #1EA38B, #27B371); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.3s;">
                    <i class="fas fa-plus-circle"></i> Nouvel utilisateur
                </a>
                <a href="{{ route('admin.users.export') }}" style="background: linear-gradient(135deg, #FF8E2A, #FFB366); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.3s; margin-left: 0.5rem;">
                    <i class="fas fa-file-export"></i> Exporter
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="admin-card" data-aos="fade-up" data-aos-delay="100" style="margin-bottom: 2rem;">
        <div class="admin-card-header">
            <h3 class="admin-card-title">Filtres de recherche</h3>
        </div>
        <div class="admin-card-body">
            <form action="{{ route('admin.users.index') }}" method="GET">
                <div class="admin-grid admin-grid-4" style="gap: 1rem;">
                    <div>
                        <label for="search" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Recherche</label>
                        <input type="text" name="search" id="search" placeholder="Nom, email..." value="{{ request('search') }}" style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; transition: all 0.3s;">
                    </div>
                    <div>
                        <label for="role" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Rôle</label>
                        <select name="role" id="role" style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; transition: all 0.3s;">
                            <option value="">Tous</option>
                            <option value="administrateur" {{ request('role') == 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                            <option value="formateur" {{ request('role') == 'formateur' ? 'selected' : '' }}>Formateur</option>
                            <option value="apprenant" {{ request('role') == 'apprenant' ? 'selected' : '' }}>Apprenant</option>
                        </select>
                    </div>
                    <div>
                        <label for="status" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Statut</label>
                        <select name="status" id="status" style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; transition: all 0.3s;">
                            <option value="">Tous</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>
                    <div>
                        <label for="sort" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Trier par</label>
                        <select name="sort" id="sort" style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; transition: all 0.3s;">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récent</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Plus ancien</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nom (A-Z)</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nom (Z-A)</option>
                        </select>
                    </div>
                </div>
                <div style="margin-top: 1.5rem; display: flex; gap: 1rem;">
                    <button type="submit" style="background: linear-gradient(135deg, #1EA38B, #27B371); color: white; padding: 0.75rem 2rem; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.3s;">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                    <a href="{{ route('admin.users.index') }}" style="background: #6C757D; color: white; padding: 0.75rem 2rem; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.3s;">
                        <i class="fas fa-times"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="admin-table-card" data-aos="fade-up" data-aos-delay="200">
        <div class="admin-table-header">
            <h3 class="admin-table-title">Liste des utilisateurs ({{ $users->total() }})</h3>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <div style="color: #6C757D; font-size: 0.9rem;">
                    Affichage de {{ $users->firstItem() }} à {{ $users->lastItem() }} sur {{ $users->total() }} utilisateurs
                </div>
            </div>
        </div>
        <div class="admin-table-body">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Inscrit le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #1EA38B, #27B371); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.9rem;">
                                        {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: #333;">{{ $user->first_name }} {{ $user->last_name }}</div>
                                        <div style="font-size: 0.8rem; color: #6C757D;">ID: {{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="color: #333;">{{ $user->email }}</div>
                                @if($user->email_verified_at)
                                    <div style="font-size: 0.8rem; color: #27B371;">
                                        <i class="fas fa-check-circle"></i> Vérifié
                                    </div>
                                @else
                                    <div style="font-size: 0.8rem; color: #E32D31;">
                                        <i class="fas fa-exclamation-circle"></i> Non vérifié
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if ($user->role === 'administrateur')
                                    <span style="background: linear-gradient(135deg, rgba(30, 163, 139, 0.1), rgba(39, 179, 113, 0.1)); color: #1EA38B; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600;">
                                        <i class="fas fa-crown"></i> Admin
                                    </span>
                                @elseif ($user->role === 'formateur')
                                    <span style="background: linear-gradient(135deg, rgba(255, 142, 42, 0.1), rgba(255, 179, 102, 0.1)); color: #FF8E2A; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600;">
                                        <i class="fas fa-chalkboard-teacher"></i> Formateur
                                    </span>
                                @elseif ($user->role === 'apprenant')
                                    <span style="background: linear-gradient(135deg, rgba(227, 45, 49, 0.1), rgba(255, 107, 107, 0.1)); color: #E32D31; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600;">
                                        <i class="fas fa-user-graduate"></i> Apprenant
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if ($user->status === 'active')
                                    <span style="background: rgba(39, 179, 113, 0.1); color: #27B371; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600;">
                                        <i class="fas fa-circle" style="font-size: 0.5rem;"></i> Actif
                                    </span>
                                @else
                                    <span style="background: rgba(108, 117, 125, 0.1); color: #6C757D; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600;">
                                        <i class="fas fa-circle" style="font-size: 0.5rem;"></i> Inactif
                                    </span>
                                @endif
                            </td>
                            <td style="color: #6C757D;">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="admin-table-actions">
                                    <a href="{{ route('admin.users.show', $user) }}" style="color: #1EA38B; padding: 0.5rem; border-radius: 6px; text-decoration: none; transition: all 0.2s;" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" style="color: #FF8E2A; padding: 0.5rem; border-radius: 6px; text-decoration: none; transition: all 0.2s;" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="confirmAdminAction('Êtes-vous sûr de vouloir supprimer cet utilisateur ?', () => document.getElementById('delete-user-{{ $user->id }}').submit())" style="color: #E32D31; padding: 0.5rem; border: none; background: none; border-radius: 6px; cursor: pointer; transition: all 0.2s;" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="delete-user-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 3rem; color: #6C757D;">
                                <i class="fas fa-users" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                                <div style="font-size: 1.1rem; font-weight: 500; margin-bottom: 0.5rem;">Aucun utilisateur trouvé</div>
                                <div style="font-size: 0.9rem;">Essayez de modifier vos critères de recherche.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
            <div style="padding: 1.5rem; border-top: 1px solid #E9ECEF; display: flex; justify-content: center;">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
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

