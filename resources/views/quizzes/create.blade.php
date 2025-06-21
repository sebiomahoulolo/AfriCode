<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Créer un nouveau quiz') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('quizzes.store') }}" method="POST" id="quiz-form">
                        @csrf

                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4">Informations générales</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="course_id" class="block text-sm font-medium text-gray-700">Cours</label>
                                    <select name="course_id" id="course_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Sélectionner un cours</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700">Titre</label>
                                    <input type="text" name="title" id="title" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div class="md:col-span-2">
                                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                </div>

                                <div>
                                    <label for="time_limit" class="block text-sm font-medium text-gray-700">Temps limite (minutes)</label>
                                    <input type="number" name="time_limit" id="time_limit" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="passing_score" class="block text-sm font-medium text-gray-700">Score minimum requis (%)</label>
                                    <input type="number" name="passing_score" id="passing_score" required min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="max_attempts" class="block text-sm font-medium text-gray-700">Nombre maximum de tentatives</label>
                                    <input type="number" name="max_attempts" id="max_attempts" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div class="flex items-center space-x-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="randomize_questions" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-600">Mélanger les questions</span>
                                    </label>

                                    <label class="flex items-center">
                                        <input type="checkbox" name="show_correct_answers" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-600">Afficher les réponses correctes</span>
                                    </label>

                                    <label class="flex items-center">
                                        <input type="checkbox" name="allow_retake" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-600">Autoriser les réessais</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold">Questions</h3>
                                <button type="button" onclick="addQuestion()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Ajouter une question
                                </button>
                            </div>

                            <div id="questions-container" class="space-y-8">
                                <!-- Les questions seront ajoutées ici dynamiquement -->
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Créer le quiz
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let questionCount = 0;

        function addQuestion() {
            const container = document.getElementById('questions-container');
            const questionDiv = document.createElement('div');
            questionDiv.className = 'border rounded-lg p-4';
            questionDiv.innerHTML = `
                <div class="flex justify-between items-start mb-4">
                    <h4 class="text-lg font-semibold">Question ${questionCount + 1}</h4>
                    <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-red-500 hover:text-red-700">
                        Supprimer
                    </button>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Question</label>
                        <input type="text" name="questions[${questionCount}][question]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Type de question</label>
                        <select name="questions[${questionCount}][type]" onchange="updateOptions(this)" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Sélectionner un type</option>
                            <option value="multiple_choice">Choix multiples</option>
                            <option value="true_false">Vrai/Faux</option>
                            <option value="short_answer">Réponse courte</option>
                            <option value="essay">Dissertation</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Points</label>
                        <input type="number" name="questions[${questionCount}][points]" required min="1" value="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="questions[${questionCount}][required]" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-600">Question requise</span>
                        </label>
                    </div>

                    <div id="options-container-${questionCount}" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Options</label>
                        <div class="space-y-2" id="options-list-${questionCount}">
                            <!-- Les options seront ajoutées ici dynamiquement -->
                        </div>
                        <button type="button" onclick="addOption(${questionCount})" class="mt-2 text-blue-500 hover:text-blue-700">
                            + Ajouter une option
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(questionDiv);
            questionCount++;
        }

        function updateOptions(select) {
            const questionDiv = select.closest('.border');
            const optionsContainer = questionDiv.querySelector('[id^="options-container-"]');
            const optionsList = questionDiv.querySelector('[id^="options-list-"]');
            optionsList.innerHTML = '';

            if (select.value === 'multiple_choice' || select.value === 'true_false') {
                optionsContainer.classList.remove('hidden');
                addOption(questionCount - 1);
                if (select.value === 'true_false') {
                    addOption(questionCount - 1);
                }
            } else {
                optionsContainer.classList.add('hidden');
            }
        }

        function addOption(questionIndex) {
            const optionsList = document.getElementById(`options-list-${questionIndex}`);
            const optionDiv = document.createElement('div');
            optionDiv.className = 'flex items-center space-x-2';
            optionDiv.innerHTML = `
                <input type="text" name="questions[${questionIndex}][options][][option]" required class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <label class="flex items-center">
                    <input type="checkbox" name="questions[${questionIndex}][options][][is_correct]" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-600">Correct</span>
                </label>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                    Supprimer
                </button>
            `;
            optionsList.appendChild(optionDiv);
        }

        // Ajouter une première question au chargement
        document.addEventListener('DOMContentLoaded', addQuestion);
    </script>
    @endpush
</x-app-layout> 