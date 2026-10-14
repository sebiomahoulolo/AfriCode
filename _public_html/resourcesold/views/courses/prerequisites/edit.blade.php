@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Modifier le prérequis</h1>
                <p class="mt-2 text-gray-600">Modifier le prérequis pour {{ $course->title }}</p>
            </div>
            <a href="{{ route('courses.prerequisites.index', $course) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('courses.prerequisites.update', [$course, $prerequisite]) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Cours prérequis (non modifiable) -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Cours prérequis
                    </label>
                    <div class="w-full rounded-md border-gray-300 bg-gray-50 px-3 py-2 text-gray-900">
                        {{ $prerequisite->prerequisiteCourse->title }}
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Le cours prérequis ne peut pas être modifié</p>
                </div>

                <!-- Type de prérequis -->
                <div class="mb-6">
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        Type de prérequis
                    </label>
                    <select name="type" id="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="required" {{ old('type', $prerequisite->type) == 'required' ? 'selected' : '' }}>Obligatoire</option>
                        <option value="recommended" {{ old('type', $prerequisite->type) == 'recommended' ? 'selected' : '' }}>Recommandé</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Score minimum -->
                <div class="mb-6">
                    <label for="minimum_score" class="block text-sm font-medium text-gray-700 mb-2">
                        Score minimum (optionnel)
                    </label>
                    <div class="relative rounded-md shadow-sm">
                        <input type="number" name="minimum_score" id="minimum_score" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               min="0" max="100" step="1"
                               value="{{ old('minimum_score', $prerequisite->minimum_score) }}"
                               placeholder="Ex: 70">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">%</span>
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Laissez vide si aucun score minimum n'est requis</p>
                    @error('minimum_score')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description (optionnelle)
                    </label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                              placeholder="Expliquez pourquoi ce prérequis est nécessaire...">{{ old('description', $prerequisite->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Boutons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('courses.prerequisites.index', $course) }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 