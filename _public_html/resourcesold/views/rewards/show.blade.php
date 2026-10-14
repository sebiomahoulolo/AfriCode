@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="flex items-center justify-center mb-6">
                <div class="w-32 h-32 flex items-center justify-center bg-yellow-100 rounded-full">
                    <svg class="w-16 h-16 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>

            <h1 class="text-3xl font-bold text-center mb-4">{{ $reward->name }}</h1>
            <p class="text-gray-600 text-center mb-6">{{ $reward->description }}</p>

            <div class="grid grid-cols-2 gap-4 mb-8">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Type</h3>
                    <p class="text-lg font-semibold">{{ ucfirst($reward->type) }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Montant</h3>
                    <p class="text-lg font-semibold text-yellow-600">{{ number_format($reward->amount, 2) }} {{ $reward->currency }}</p>
                </div>
            </div>

            @if($reward->requirements)
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4">Conditions d'obtention</h2>
                    <div class="space-y-2">
                        @foreach($reward->requirements as $requirement)
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

            @if($claimedBy->count() > 0)
                <div>
                    <h2 class="text-xl font-semibold mb-4">Derniers utilisateurs à l'avoir réclamée</h2>
                    <div class="space-y-2">
                        @foreach($claimedBy as $user)
                            <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                                <div class="flex items-center">
                                    <img src="{{ $user->profile_photo_url }}" 
                                         alt="{{ $user->name }}" 
                                         class="w-8 h-8 rounded-full mr-3">
                                    <span class="font-medium">{{ $user->name }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm text-gray-500">
                                        {{ $user->pivot->claimed_at->diffForHumans() }}
                                    </span>
                                    <span class="block text-xs text-gray-400">
                                        Statut: {{ ucfirst($user->pivot->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mt-8 text-center">
                <a href="{{ route('rewards.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour aux récompenses
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 