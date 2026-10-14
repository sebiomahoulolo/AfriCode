<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $quiz->title }}
            </h2>
            <div class="flex space-x-4">
                @can('update', $quiz)
                    <a href="{{ route('quizzes.edit', $quiz) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        Modifier
                    </a>
                @endcan
                @can('delete', $quiz)
                    <form action="{{ route('quizzes.destroy', $quiz) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce quiz ?')">
                            Supprimer
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-2">Description</h3>
                        <p class="text-gray-600">{{ $quiz->description }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold mb-2">Informations</h4>
                            <ul class="space-y-2">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Temps limite : {{ $quiz->time_limit ? $quiz->time_limit . ' minutes' : 'Pas de limite' }}</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <span>Nombre de questions : {{ $quiz->questions->count() }}</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Score minimum : {{ $quiz->passing_score }}%</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>Réessais autorisés : {{ $quiz->allow_retake ? 'Oui' : 'Non' }}</span>
                                </li>
                                @if($quiz->max_attempts)
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Nombre maximum de tentatives : {{ $quiz->max_attempts }}</span>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold mb-2">Vos tentatives</h4>
                            @if($userAttempts->isEmpty())
                                <p class="text-gray-600">Vous n'avez pas encore tenté ce quiz.</p>
                            @else
                                <div class="space-y-4">
                                    @foreach($userAttempts as $attempt)
                                        <div class="border-b pb-4 last:border-b-0 last:pb-0">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-600">
                                                    {{ $attempt->created_at->format('d/m/Y H:i') }}
                                                </span>
                                                <span class="text-sm font-semibold {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $attempt->passed ? 'Réussi' : 'Échoué' }}
                                                </span>
                                            </div>
                                            <div class="mt-2">
                                                <span class="text-sm">Score : {{ $attempt->score }}/{{ $quiz->total_points }}</span>
                                            </div>
                                            <div class="mt-2">
                                                <a href="{{ route('quizzes.results', $attempt) }}" class="text-blue-500 hover:text-blue-700 text-sm">
                                                    Voir les résultats
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($quiz->canBeAttemptedBy(auth()->user()))
                        <div class="mt-8">
                            <form action="{{ route('quizzes.start', $quiz) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Commencer le quiz
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="mt-8">
                            <p class="text-red-600">
                                Vous ne pouvez plus tenter ce quiz.
                                @if($quiz->max_attempts)
                                    Vous avez atteint le nombre maximum de tentatives.
                                @else
                                    Les réessais ne sont pas autorisés.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 