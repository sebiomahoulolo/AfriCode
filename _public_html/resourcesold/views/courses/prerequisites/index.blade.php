@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Prérequis pour {{ $course->title }}</h1>
                <p class="mt-2 text-gray-600">Gérez les cours prérequis pour ce cours</p>
            </div>
            <a href="{{ route('courses.show', $course) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour au cours
            </a>
        </div>

        <!-- Liste des prérequis -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Prérequis actuels</h2>
                <a href="{{ route('courses.prerequisites.create', $course) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Ajouter un prérequis
                </a>
            </div>

            @if($prerequisites->count() > 0)
                <div class="space-y-4">
                    @foreach($prerequisites as $prerequisite)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 rounded-lg bg-{{ $prerequisite->type === 'required' ? 'red' : 'yellow' }}-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-{{ $prerequisite->type === 'required' ? 'red' : 'yellow' }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">
                                        {{ $prerequisite->prerequisiteCourse->title }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        {{ $prerequisite->type === 'required' ? 'Prérequis obligatoire' : 'Prérequis recommandé' }}
                                        @if($prerequisite->minimum_score)
                                            - Score minimum : {{ $prerequisite->minimum_score }}%
                                        @endif
                                    </p>
                                    @if($prerequisite->description)
                                        <p class="text-sm text-gray-500 mt-1">{{ $prerequisite->description }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('courses.prerequisites.edit', [$course, $prerequisite]) }}" class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('courses.prerequisites.destroy', [$course, $prerequisite]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce prérequis ?')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-600">Aucun prérequis n'a été défini pour ce cours.</p>
                    <p class="text-sm text-gray-500 mt-2">Cliquez sur "Ajouter un prérequis" pour en définir un.</p>
                </div>
            @endif
        </div>

        <!-- Cours disponibles -->
        @if($availableCourses->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Cours disponibles comme prérequis</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($availableCourses as $availableCourse)
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900">{{ $availableCourse->title }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($availableCourse->description, 100) }}</p>
                            <a href="{{ route('courses.prerequisites.create', $course) }}" class="text-sm text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                Ajouter comme prérequis
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection 