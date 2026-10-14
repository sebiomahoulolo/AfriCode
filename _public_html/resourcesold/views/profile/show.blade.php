@extends('layouts.layout')

@section('title', 'Profil de ' . $user->first_name . ' ' . $user->last_name . ' - AfriCode')

@section('content')
<div class="container mt-4 mb-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-user me-2"></i>Profil de {{ $user->first_name }} {{ $user->last_name }}
                        </h4>
                        <a href="{{ url()->previous() }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <div class="profile-avatar mb-3">
                                @if($user->profile_image_path)
                                    <img src="{{ asset($user->profile_image_path) }}" alt="{{ $user->first_name }}" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 150px; height: 150px; font-size: 3rem; font-weight: bold;">
                                        {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <h5 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h5>
                            <p class="text-muted mb-0">{{ $user->email }}</p>
                            @if($user->role)
                                <span class="badge bg-info mt-2">
                                    @if($user->role === 'formateur')
                                        <i class="fas fa-chalkboard-teacher me-1"></i>Formateur
                                    @elseif($user->role === 'apprenant')
                                        <i class="fas fa-user-graduate me-1"></i>Apprenant
                                    @else
                                        {{ ucfirst($user->role) }}
                                    @endif
                                </span>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h6 class="mb-3">Informations</h6>
                            @if($user->bio)
                                <div class="mb-3">
                                    <strong>Biographie :</strong>
                                    <p class="text-muted">{{ $user->bio }}</p>
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                <strong><i class="fas fa-envelope me-2"></i>Email :</strong>
                                <span class="text-muted">{{ $user->email }}</span>
                            </div>
                            
                            @if($user->created_at)
                                <div class="mb-3">
                                    <strong><i class="fas fa-calendar me-2"></i>Membre depuis :</strong>
                                    <span class="text-muted">{{ $user->created_at->format('d/m/Y') }}</span>
                                </div>
                            @endif
                            
                            @if(Auth::check() && Auth::id() == $user->id)
                                <div class="mt-4">
                                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                        <i class="fas fa-edit me-2"></i>Modifier mon profil
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-avatar img {
    border: 4px solid #11c06d;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.card {
    border-radius: 10px;
    border: none;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}
</style>
@endsection

