<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Résultats : {{ $attempt->quiz->title }}
            </h2>
            <a href="{{ route('quizzes.show', $attempt->quiz) }}" class="text-blue-500 hover:text-blue-700">
                Retour au quiz
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-8">
                        <div class="flex items-center justify-center mb-4">
                            <div class="text-center">
                                <div class="text-4xl font-bold {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $percentage }}%
                                </div>
                                <div class="text-lg mt-2">
                                    {{ $attempt->passed ? 'Quiz réussi !' : 'Quiz échoué' }}
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm text-gray-600">Score</div>
                                <div class="text-xl font-semibold">{{ $score }}/{{ $totalPoints }}</div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm text-gray-600">Score minimum requis</div>
                                <div class="text-xl font-semibold">{{ $attempt->quiz->passing_score }}%</div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm text-gray-600">Temps pris</div>
                                <div class="text-xl font-semibold">
                                    {{ $attempt->started_at->diffInMinutes($attempt->completed_at) }} minutes
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-8">
                        @foreach($attempt->quiz->questions as $index => $question)
                            @php
                                $answer = $attempt->answers->where('question_id', $question->id)->first();
                                $isCorrect = $answer ? $answer->is_correct : false;
                            @endphp
                            <div class="border-b pb-8 last:border-b-0 last:pb-0">
                                <div class="flex items-start mb-4">
                                    <span class="bg-{{ $isCorrect ? 'green' : 'red' }}-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0">
                                        {{ $index + 1 }}
                                    </span>
                                    <div>
                                        <h3 class="text-lg font-semibold mb-2">{{ $question->question }}</h3>
                                        <div class="text-sm text-gray-600">
                                            Points : {{ $answer ? $answer->points_earned : 0 }}/{{ $question->points }}
                                        </div>
                                    </div>
                                </div>

                                <div class="ml-12">
                                    @switch($question->type)
                                        @case('multiple_choice')
                                            <div class="space-y-2">
                                                @foreach($question->options as $option)
                                                    <div class="flex items-center">
                                                        @if(in_array($option->id, (array)$answer?->answer))
                                                            <span class="text-{{ $option->is_correct ? 'green' : 'red' }}-600">
                                                                ✓ {{ $option->option }}
                                                            </span>
                                                        @else
                                                            <span class="{{ $option->is_correct ? 'text-green-600' : 'text-gray-600' }}">
                                                                {{ $option->option }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                            @break

                                        @case('true_false')
                                            <div class="space-y-2">
                                                @foreach($question->options as $option)
                                                    <div class="flex items-center">
                                                        @if($option->id == $answer?->answer)
                                                            <span class="text-{{ $option->is_correct ? 'green' : 'red' }}-600">
                                                                ✓ {{ $option->option }}
                                                            </span>
                                                        @else
                                                            <span class="{{ $option->is_correct ? 'text-green-600' : 'text-gray-600' }}">
                                                                {{ $option->option }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                            @break

                                        @case('short_answer')
                                            <div class="space-y-2">
                                                <div class="text-gray-600">Votre réponse :</div>
                                                <div class="font-medium">{{ $answer?->answer }}</div>
                                                @if($attempt->quiz->show_correct_answers)
                                                    <div class="text-gray-600 mt-2">Réponses correctes :</div>
                                                    <div class="font-medium">
                                                        {{ $question->options->where('is_correct', true)->pluck('option')->join(', ') }}
                                                    </div>
                                                @endif
                                            </div>
                                            @break

                                        @case('essay')
                                            <div class="space-y-2">
                                                <div class="text-gray-600">Votre réponse :</div>
                                                <div class="font-medium">{{ $answer?->answer }}</div>
                                                @if($attempt->quiz->show_correct_answers)
                                                    <div class="text-gray-600 mt-2">Points attribués : {{ $answer?->points_earned }}/{{ $question->points }}</div>
                                                @endif
                                            </div>
                                            @break
                                    @endswitch
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($attempt->quiz->canBeAttemptedBy(auth()->user()))
                        <div class="mt-8 text-center">
                            <form action="{{ route('quizzes.start', $attempt->quiz) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Réessayer le quiz
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 