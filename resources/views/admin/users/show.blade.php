@extends('admin.layouts.app')

@section('breadcrumb', 'Détail utilisateur')

@section('content')
    <div class="admin-card">
        <h1>Détail de l'utilisateur</h1>
        <ul class="list-unstyled">
            <li><strong>Nom :</strong> {{ $user->first_name }} {{ $user->last_name }}</li>
            <li><strong>Email :</strong> {{ $user->email }}</li>
            <li><strong>Rôle :</strong> {{ ucfirst($user->role) }}</li>
            <li><strong>Date d'inscription :</strong> {{ $user->created_at->format('d/m/Y') }}</li>
            <li><strong>Statut :</strong> {{ $user->is_active ? 'Actif' : 'Inactif' }}</li>
        </ul>
        <a href="{{ route('admin.users.index') }}" class="admin-btn mt-3">Retour à la liste</a>
    </div>
@endsection 