@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-users"></i> Liste des Utilisateurs
            </div>
            <div class="card-actions">
                 <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter Utilisateur
                </a>
                <a href="{{ route('admin.users.export') }}" class="btn btn-secondary"> {{-- Adaptez le style --}}
                    <i class="fas fa-download"></i> Exporter
                </a>
            </div>
        </div>

        {{-- Afficher les messages de succès/erreur --}}
        @if (session('success'))
            <div class="alert alert-success m-3"> {{-- Stylez cette alerte --}}
                {{ session('success') }}
            </div>
        @endif

        <div class="table-container p-3"> {{-- Ajoutez padding si besoin --}}
             {{-- Ajoutez ici la barre de recherche si nécessaire --}}
            <table>
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Date d'inscription</th>
                        <th>Statut</th> {{-- Vous devrez définir ce statut --}}
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user) {{-- Variable $users passée par le contrôleur --}}
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center;">
                                 {{-- Afficher l'avatar --}}
                                <div class="user-avatar" style="background-color: #ddd; margin-right: 10px;">
                                    {{-- <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : asset('images/default-avatar.png') }}" alt="Avatar" style="width:100%; height:100%; border-radius:50%;"> --}}
                                    {{ strtoupper(substr($user->name, 0, 1) . substr($user->surname, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight: bold;">{{ $user->name }} {{ $user->surname }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->is_admin ? 'Admin' : 'Utilisateur' }}</td> {{-- Ou $user->role --}}
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <span class="status-badge {{ $user->email_verified_at ? 'status-active' : 'status-pending' }}">
                                {{ $user->email_verified_at ? 'Vérifié' : 'Non Vérifié' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.users.show', $user) }}" class="action-btn view" title="Voir"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Aucun utilisateur trouvé.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination p-3"> {{-- Ajoutez padding --}}
            {{ $users->links('pagination::bootstrap-5') }} {{-- Utilisez le style de pagination que vous préférez --}}
        </div>
    </div>
@endsection