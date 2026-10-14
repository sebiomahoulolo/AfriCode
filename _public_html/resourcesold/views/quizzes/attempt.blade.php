<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $attempt->quiz->title }}
            </h2>
            @if($timeRemaining !== null)
                <div class="text-lg font-semibold" id="timer">
                    Temps restant : <span id="time">{{ floor($timeRemaining / 60) }}:{{ str_pad($timeRemaining % 60, 2, '0', STR_PAD_LEFT) }}</span>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('quizzes.submit', $attempt) }}" method="POST" id="quiz-form">
                        @csrf
                        
                        <div class="space-y-8">
                            @foreach($questions as $index => $question)
                                <div class="border-b pb-8 last:border-b-0 last:pb-0">
                                    <div class="flex items-start mb-4">
                                        <span class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0">
                                            {{ $index + 1 }}
                                        </span>
                                        <div>
                                            <h3 class="text-lg font-semibold mb-2">{{ $question->question }}</h3>
                                            @if($question->required)
                                                <span class="text-sm text-red-600">* Requis</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="ml-12">
                                        @switch($question->type)
                                            @case('multiple_choice')
                                                <div class="space-y-2">
                                                    @foreach($question->options as $option)
                                                        <label class="flex items-center">
                                                            <input type="checkbox" 
                                                                   name="answers[{{ $question->id }}][]" 
                                                                   value="{{ $option->id }}"
                                                                   class="form-checkbox h-5 w-5 text-blue-600">
                                                            <span class="ml-2">{{ $option->option }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                                @break

                                            @case('true_false')
                                                <div class="space-y-2">
                                                    @foreach($question->options as $option)
                                                        <label class="flex items-center">
                                                            <input type="radio" 
                                                                   name="answers[{{ $question->id }}]" 
                                                                   value="{{ $option->id }}"
                                                                   class="form-radio h-5 w-5 text-blue-600">
                                                            <span class="ml-2">{{ $option->option }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                                @break

                                            @case('short_answer')
                                                <input type="text" 
                                                       name="answers[{{ $question->id }}]" 
                                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                @break

                                            @case('essay')
                                                <textarea name="answers[{{ $question->id }}]" 
                                                          rows="4" 
                                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                                @break
                                        @endswitch
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Terminer le quiz
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($timeRemaining !== null)
        @push('scripts')
        <script>
            let timeLeft = {{ $timeRemaining }};
            const timerElement = document.getElementById('time');
            const form = document.getElementById('quiz-form');

            const timer = setInterval(() => {
                timeLeft--;
                
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

                if (timeLeft <= 0) {
                    clearInterval(timer);
                    form.submit();
                }
            }, 1000);

            // Empêcher la soumission accidentelle du formulaire
            window.addEventListener('beforeunload', (e) => {
                if (!form.submitted) {
                    e.preventDefault();
                    e.returnValue = '';
                }
            });
        </script>
        @endpush
    @endif
</x-app-layout> 