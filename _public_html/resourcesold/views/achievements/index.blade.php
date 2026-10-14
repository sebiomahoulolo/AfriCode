@extends('layouts.app')

@section('title', 'Succès et badges - AfriCode')

@section('content')
<div class="container py-5">
    <h1 class="mb-4"><i class="fas fa-trophy text-warning me-2"></i> Succès et badges</h1>

    @if(isset($achievements) && $achievements->isNotEmpty())
        {{-- Skeleton affiché 0.4s pendant que Alpine.js initialise --}}
        <div x-data="{ ready: false }" x-init="setTimeout(() => ready = true, 400)">

            {{-- Skeleton --}}
            <div x-show="!ready" class="row g-3">
                @for($i = 0; $i < 6; $i++)
                <div class="col-md-4">
                    <div class="skeleton-achievement-card">
                        <div class="sk sk-icon"></div>
                        <div class="sk sk-title" style="width:60%; margin:0 auto .5rem;"></div>
                        <div class="sk sk-line" style="width:80%; margin:0 auto .3rem;"></div>
                        <div class="sk sk-line" style="width:55%; margin:0 auto;"></div>
                    </div>
                </div>
                @endfor
            </div>

            {{-- Contenu réel --}}
            <div x-show="ready" x-transition.duration.350ms class="row g-3">
                @foreach($achievements as $achievement)
                    <div class="col-md-4 sk-reveal" style="animation-delay: {{ $loop->index * 60 }}ms">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body text-center py-4">
                                <i class="fas fa-{{ $achievement->icon ?? 'star' }} fa-2x text-warning mb-3"></i>
                                <h5 class="card-title fw-bold">{{ $achievement->name ?? $achievement->title ?? 'Succès' }}</h5>
                                <p class="card-text small text-muted">{{ $achievement->description ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <p class="text-muted">Aucun succès disponible pour le moment.</p>
    @endif

    <p class="mt-4">
        <a href="{{ route('achievements.leaderboard') }}" class="btn btn-outline-primary">
            <i class="fas fa-medal me-1"></i>Voir le classement
        </a>
    </p>
</div>
@endsection
