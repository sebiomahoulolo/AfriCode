<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Questions du quiz') }} : {{ $quiz->title }}
            </h2>
            <a href="{{ route('quizzes.show', $quiz) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Retour au quiz
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Formulaire d'ajout de question -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Ajouter une nouvelle question</h3>
                        <form action="{{ route('quizzes.questions.store', $quiz) }}" method="POST" class="space-y-4">
                            @csrf
                            
                            <div>
                                <label for="question_text" class="block text-sm font-medium text-gray-700">Question</label>
                                <textarea name="question_text" id="question_text" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                            </div>

                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Type de question</label>
                                <select name="type" id="type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="multiple_choice">Choix multiple</option>
                                    <option value="true_false">Vrai/Faux</option>
                                    <option value="short_answer">Réponse courte</option>
                                    <option value="essay">Dissertation</option>
                                </select>
                            </div>

                            <div>
                                <label for="points" class="block text-sm font-medium text-gray-700">Points</label>
                                <input type="number" name="points" id="points" min="1" value="1" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Options pour les questions à choix multiple -->
                            <div id="multiple_choice_options" class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-medium text-gray-700">Options</h4>
                                    <button type="button" onclick="addOption()" class="text-sm text-blue-600 hover:text-blue-800">+ Ajouter une option</button>
                                </div>
                                <div id="options_container">
                                    <div class="option-item flex items-center space-x-4">
                                        <input type="text" name="options[]" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Option">
                                        <input type="radio" name="correct_option" value="0" class="rounded-full border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <span class="text-sm text-gray-600">Correct</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Option pour Vrai/Faux -->
                            <div id="true_false_option" class="hidden">
                                <div class="space-y-4">
                                    <label class="flex items-center">
                                        <input type="radio" name="correct_option" value="true" class="rounded-full border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <span class="ml-2">Vrai</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="correct_option" value="false" class="rounded-full border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <span class="ml-2">Faux</span>
                                    </label>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Ajouter la question
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Liste des questions existantes -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Questions existantes</h3>
                        <div class="space-y-6">
                            @forelse($quiz->questions as $question)
                                <div class="border rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="font-medium">{{ $question->question_text }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">
                                                Type: {{ ucfirst(str_replace('_', ' ', $question->type)) }} | 
                                                Points: {{ $question->points }}
                                            </p>
                                            
                                            @if($question->type === 'multiple_choice')
                                                <div class="mt-2 space-y-2">
                                                    @foreach($question->options as $option)
                                                        <div class="flex items-center space-x-2">
                                                            <span class="text-sm {{ $option->is_correct ? 'text-green-600 font-medium' : 'text-gray-600' }}">
                                                                {{ $option->option_text }}
                                                                @if($option->is_correct)
                                                                    (Correct)
                                                                @endif
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @elseif($question->type === 'true_false')
                                                <p class="text-sm text-gray-600 mt-1">
                                                    Réponse correcte: {{ $question->correct_answer ? 'Vrai' : 'Faux' }}
                                                </p>
                                            @endif
                                        </div>
                                        
                                        <div class="flex space-x-2">
                                            <form action="{{ route('quizzes.questions.destroy', [$quiz, $question]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette question ?')">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500">Aucune question n'a été ajoutée à ce quiz.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let optionCount = 1;

        function addOption() {
            const container = document.getElementById('options_container');
            const newOption = document.createElement('div');
            newOption.className = 'option-item flex items-center space-x-4 mt-4';
            newOption.innerHTML = `
                <input type="text" name="options[]" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Option">
                <input type="radio" name="correct_option" value="${optionCount}" class="rounded-full border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <span class="text-sm text-gray-600">Correct</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                    Supprimer
                </button>
            `;
            container.appendChild(newOption);
            optionCount++;
        }

        // Gestion de l'affichage des options selon le type de question
        document.getElementById('type').addEventListener('change', function() {
            const multipleChoiceOptions = document.getElementById('multiple_choice_options');
            const trueFalseOption = document.getElementById('true_false_option');
            
            if (this.value === 'multiple_choice') {
                multipleChoiceOptions.classList.remove('hidden');
                trueFalseOption.classList.add('hidden');
            } else if (this.value === 'true_false') {
                multipleChoiceOptions.classList.add('hidden');
                trueFalseOption.classList.remove('hidden');
            } else {
                multipleChoiceOptions.classList.add('hidden');
                trueFalseOption.classList.add('hidden');
            }
        });
    </script>
    @endpush
</x-app-layout> 