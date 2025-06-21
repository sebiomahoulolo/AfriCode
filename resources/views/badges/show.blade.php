@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="flex items-center justify-center mb-6">
                <img src="{{ asset('storage/badges/' . $badge->icon) }}" 
                     alt="{{ $badge->name }}" 
                     class="w-32 h-32 object-contain">
            </div>

            <h1 class="text-3xl font-bold text-center mb-4">{{ $badge->name }}</h1>
            <p class="text-gray-600 text-center mb-6">{{ $badge->description }}</p>

            <div class="grid grid-cols-2 gap-4 mb-8">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Type</h3>
                    <p class="text-lg font-semibold">{{ ucfirst($badge->type) }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Points de récompense</h3>
                    <p class="text-lg font-semibold">{{ $badge->points_reward }} points</p>
                </div>
            </div>

            @if($badge->requirements)
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4">Conditions d'obtention</h2>
                    <div class="space-y-2">
                        @foreach($badge->requirements as $requirement)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                <span class="text-gray-600">
                                    @switch($requirement['type'])
                                        @case('level')
                                            Atteindre le niveau {{ $requirement['value'] }}
                                            @break
                                        @case('courses_completed')
                                            Compléter {{ $requirement['value'] }} cours
                                            @break
                                        @case('perfect_quizzes')
                                            Obtenir 100% à {{ $requirement['value'] }} quiz
                                            @break
                                        @case('streak_days')
                                            Se connecter pendant {{ $requirement['value'] }} jours consécutifs
                                            @break
                                        @default
                                            {{ ucfirst($requirement['type']) }}: {{ $requirement['value'] }}
                                    @endswitch
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($earnedBy->count() > 0)
                <div>
                    <h2 class="text-xl font-semibold mb-4">Derniers utilisateurs à l'avoir obtenu</h2>
                    <div class="space-y-2">
                        @foreach($earnedBy as $user)
                            <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                                <div class="flex items-center">
                                    <img src="{{ $user->profile_photo_url }}" 
                                         alt="{{ $user->name }}" 
                                         class="w-8 h-8 rounded-full mr-3">
                                    <span class="font-medium">{{ $user->name }}</span>
                                </div>
                                <span class="text-sm text-gray-500">
                                    {{ $user->pivot->earned_at->diffForHumans() }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mt-8 text-center">
                <a href="{{ route('badges.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour aux badges
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 