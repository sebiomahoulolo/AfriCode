@extends('layouts.app')

@section('title', 'Classement - AfriCode')

@section('content')
<div class="container py-5">
    <h1 class="mb-4"><i class="fas fa-medal text-warning me-2"></i> Classement</h1>

    @if(isset($users) && $users->isNotEmpty())
        {{-- Skeleton affiché pendant l'initialisation Alpine --}}
        <div x-data="{ ready: false }" x-init="setTimeout(() => ready = true, 400)">

            {{-- Skeleton rows --}}
            <div x-show="!ready">
                @for($i = 0; $i < 8; $i++)
                <div class="skeleton-rank-row">
                    <div class="sk sk-rank-num"></div>
                    <div class="sk sk-avatar" style="width:40px; height:40px;"></div>
                    <div style="flex:1; display:flex; flex-direction:column; gap:.4rem;">
                        <div class="sk sk-line" style="width:40%"></div>
                        <div class="sk sk-line-sm" style="width:25%"></div>
                    </div>
                    <div class="sk sk-badge" style="width:80px;"></div>
                </div>
                @endfor
            </div>

            {{-- Contenu réel --}}
            <div x-show="ready" x-transition.duration.350ms>
                @foreach($users as $index => $user)
                    <div class="skeleton-rank-row sk-reveal" style="animation-delay: {{ $index * 50 }}ms">
                        <div style="width:36px; height:36px; border-radius:50%; background:{{ $index < 3 ? 'linear-gradient(135deg,#11c06d,#239e79)' : '#e9ecef' }}; display:flex; align-items:center; justify-content:center; font-weight:700; color:{{ $index < 3 ? '#fff' : '#495057' }}; font-size:.9rem; flex-shrink:0;">
                            #{{ $index + 1 }}
                        </div>
                        <div style="width:40px; height:40px; border-radius:50%; background:#11c06d; display:flex; align-items:center; justify-content:center; color:#fea837; font-weight:700; font-size:.85rem; flex-shrink:0;">
                            {{ strtoupper(substr($user->first_name ?? $user->name ?? $user->email, 0, 1)) }}
                        </div>
                        <div style="flex:1;">
                            <div style="font-weight:600; font-size:.95rem; color:#1f2937;">
                                {{ $user->first_name ?? $user->name ?? $user->email }}
                            </div>
                            <div style="font-size:.8rem; color:#6b7280;">Membre AfriCode</div>
                        </div>
                        <span class="badge" style="background:linear-gradient(135deg,#11c06d,#239e79); color:#fff; font-size:.85rem; padding:.4rem .85rem; border-radius:20px;">
                            {{ $user->achievements_count ?? $user->score ?? 0 }} succès
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-medal fa-3x mb-3 opacity-50"></i>
            <p>Aucun classement pour le moment.</p>
        </div>
    @endif

    <p class="mt-4">
        <a href="{{ route('achievements.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Retour aux succès
        </a>
    </p>
</div>
@endsection
