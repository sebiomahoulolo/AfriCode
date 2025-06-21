@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Récompenses</h1>
        <div class="flex space-x-4">
            <a href="{{ route('badges.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Badges
            </a>
            <a href="{{ route('badges.profile') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Mon Progression
            </a>
        </div>
    </div>

    <!-- Récompenses Obtenues -->
    <div class="mb-12">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Mes Récompenses</h2>
        @if($userRewards->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($userRewards as $reward)
                    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 rounded-full bg-{{ $reward->type === 'discount' ? 'green' : ($reward->type === 'premium' ? 'purple' : 'blue') }}-100 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-{{ $reward->type === 'discount' ? 'green' : ($reward->type === 'premium' ? 'purple' : 'blue') }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($reward->type === 'discount')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        @elseif($reward->type === 'premium')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        @endif
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $reward->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $reward->description }}</p>
                                @if($reward->pivot->is_claimed)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-2">
                                        Réclamée le {{ $reward->pivot->claimed_at->format('d/m/Y') }}
                                    </span>
                                @else
                                    <form action="{{ route('badges.claim-reward', $reward) }}" method="POST" class="mt-2">
                                        @csrf
                                        <button type="submit" class="text-sm text-blue-600 hover:text-blue-800">
                                            Réclamer la récompense
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-600">Vous n'avez pas encore obtenu de récompenses.</p>
                <p class="text-sm text-gray-500 mt-2">Continuez à progresser pour débloquer des récompenses !</p>
            </div>
        @endif
    </div>

    <!-- Récompenses Disponibles -->
    <div>
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Récompenses Disponibles</h2>
        @if($availableRewards->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($availableRewards as $reward)
                    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200 opacity-75">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 rounded-full bg-{{ $reward->type === 'discount' ? 'green' : ($reward->type === 'premium' ? 'purple' : 'blue') }}-100 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-{{ $reward->type === 'discount' ? 'green' : ($reward->type === 'premium' ? 'purple' : 'blue') }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($reward->type === 'discount')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        @elseif($reward->type === 'premium')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        @endif
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $reward->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $reward->description }}</p>
                                <p class="text-sm text-gray-500 mt-2">
                                    Points requis : {{ $reward->required_points }}
                                </p>
                                <a href="{{ route('badges.show-reward', $reward) }}" class="text-sm text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                    Voir les détails
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-600">Aucune récompense disponible pour le moment.</p>
            </div>
        @endif
    </div>
</div>
@endsection 