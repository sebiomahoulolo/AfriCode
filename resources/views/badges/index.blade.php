@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Badges</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($badges as $badge)
            <div class="bg-white rounded-lg shadow-md p-6 {{ $userBadges->contains('id', $badge->id) ? 'border-2 border-green-500' : '' }}">
                <div class="flex items-center justify-center mb-4">
                    <img src="{{ asset('storage/badges/' . $badge->icon) }}" 
                         alt="{{ $badge->name }}" 
                         class="w-24 h-24 object-contain">
                </div>
                
                <h2 class="text-xl font-semibold text-center mb-2">{{ $badge->name }}</h2>
                <p class="text-gray-600 text-center mb-4">{{ $badge->description }}</p>
                
                <div class="flex justify-between items-center text-sm text-gray-500">
                    <span>Type: {{ ucfirst($badge->type) }}</span>
                    <span>{{ $badge->points_reward }} points</span>
                </div>

                @if($userBadges->contains('id', $badge->id))
                    <div class="mt-4 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Obtenu
                        </span>
                    </div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('badges.show', $badge) }}" 
                       class="block text-center bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                        Voir les détails
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection 