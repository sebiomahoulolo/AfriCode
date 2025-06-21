@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Mon Progression</h1>
        <div class="flex space-x-4">
            <a href="{{ route('badges.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Badges
            </a>
            <a href="{{ route('badges.rewards') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                </svg>
                Récompenses
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Progression générale -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Progression générale</h2>
                
                <!-- Niveau et points -->
                <div class="grid grid-cols-2 gap-6 mb-8">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-blue-800">Niveau actuel</h3>
                        <p class="text-3xl font-bold text-blue-900 mt-2">{{ $stats['progress']['level'] }}</p>
                        <div class="mt-2">
                            <div class="w-full h-2 bg-blue-200 rounded-full">
                                <div class="h-full bg-blue-600 rounded-full" style="width: {{ $stats['progress']['next_level'] }}%"></div>
                            </div>
                            <p class="text-xs text-blue-600 mt-1">
                                {{ $stats['progress']['experience'] }} / {{ $stats['progress']['next_level'] * 100 }} XP
                            </p>
                        </div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-green-800">Points totaux</h3>
                        <p class="text-3xl font-bold text-green-900 mt-2">{{ $stats['progress']['points'] }}</p>
                        <p class="text-xs text-green-600 mt-1">
                            Points accumulés depuis le début
                        </p>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-800">Badges</h3>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['badges']['total'] }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-800">Récompenses</h3>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['rewards']['total'] }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-800">Récompenses réclamées</h3>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['rewards']['claimed'] }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-800">Activités</h3>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['activities']['total'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activités récentes -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Activités récentes</h2>
                
                @if($recentActivities->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentActivities as $activity)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-{{ $activity->type === 'course_completion' ? 'blue' : ($activity->type === 'quiz_completion' ? 'green' : 'purple') }}-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-{{ $activity->type === 'course_completion' ? 'blue' : ($activity->type === 'quiz_completion' ? 'green' : 'purple') }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($activity->type === 'course_completion')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            @elseif($activity->type === 'quiz_completion')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            @endif
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-900">{{ $activity->description }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $activity->created_at->diffForHumans() }}
                                        @if($activity->points_earned > 0)
                                            <span class="text-green-600">+{{ $activity->points_earned }} points</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-600">Aucune activité récente.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 