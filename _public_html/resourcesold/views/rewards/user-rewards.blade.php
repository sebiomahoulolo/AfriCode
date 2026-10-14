@extends('layouts.app')

@section('title', 'Récompenses de l\'utilisateur')

@section('content')
<div class="container py-5">
    <h1 class="mb-4"><i class="fas fa-gift"></i> Récompenses</h1>
    @if(isset($user))
        <p class="text-muted">Récompenses pour {{ $user->first_name ?? $user->email }}.</p>
    @endif
    @if(isset($rewards) && $rewards->isNotEmpty())
        <div class="row g-3">
            @foreach($rewards as $reward)
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $reward->name ?? $reward->title ?? 'Récompense' }}</h5>
                            <p class="card-text small text-muted">{{ $reward->description ?? '' }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-muted">Aucune récompense pour le moment.</p>
    @endif
    <p class="mt-4"><a href="{{ route('rewards.index') }}" class="btn btn-outline-secondary">Voir toutes les récompenses</a></p>
</div>
@endsection
