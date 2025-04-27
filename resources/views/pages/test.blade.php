@extends('layouts.layout')

@section('title', 'AfriCode')

@section('content')


<style>
        :root {
            --primary-color: #1EA38B; /* Vert émeraude */
            --secondary-color: #FF8E2A; /* Orange */
            --accent-color: #E32D31; /* Rouge */
            --highlight-color: #27B371; /* Vert clair */
            --background-color: #f4f7f6;
            --light-accent: #ECF0F1;
            --text-color: #333333;
            --card-bg: #ffffff;
            --correct-answer: #d1e7dd; /* Vert léger Bootstrap */
            --incorrect-answer: #f8d7da; /* Rouge léger Bootstrap */
            --selected-answer: #cfe2ff; /* Bleu léger Bootstrap */
        }

        body {
            background-color: var(--background-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
        }

        .assessment-header {
            background: linear-gradient(135deg, var(--secondary-color), #ffae63); /* Dégradé Orange */
            color: white;
            padding: 30px 20px;
            margin-bottom: 30px;
            text-align: center;
            border-radius: 0 0 15px 15px;
        }
        .assessment-header h1 { font-weight: 700; }

        .test-container {
            background-color: var(--card-bg);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        #introduction, #test-area, #results-area {
            /* display: none; */ /* Géré par JS */
        }

        #introduction h2, #results-area h2 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 20px;
        }

        .progress-container {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        #timer {
            font-weight: bold;
            color: var(--accent-color);
            font-size: 1.1em;
        }

        #question-number {
            font-weight: 500;
            color: #555;
        }

        #question-text {
            font-size: 1.3em;
            font-weight: 500;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .answer-options label {
            display: block;
            background-color: var(--light-accent);
            padding: 12px 18px;
            margin-bottom: 10px;
            border-radius: 8px;
            border: 1px solid #d8dcdf;
            cursor: pointer;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }
        .answer-options label:hover {
            background-color: #e2e6e7;
            border-color: #c1c7c9;
        }
        .answer-options input[type="radio"] {
            margin-right: 12px;
            vertical-align: middle;
        }
        .answer-options input[type="radio"]:checked + span {
             font-weight: 600; /* Optionnel: mettre en gras si sélectionné */
        }

         /* Style pour l'option sélectionnée */
         .answer-options input[type="radio"]:checked + span {
            /* On ne change pas le style ici pour un test d'évaluation, */
            /* on pourrait le faire si on voulait un retour immédiat */
         }

        /* Styles pour après la soumission (si on montrait les bonnes/mauvaises réponses) */
         .answer-options label.correct {
             background-color: var(--correct-answer);
             border-color: green;
             font-weight: bold;
         }
         .answer-options label.incorrect {
             background-color: var(--incorrect-answer);
             border-color: red;
         }
         .answer-options label.selected {
             outline: 2px solid var(--secondary-color); /* Montre ce que l'user a choisi */
         }


        .navigation-buttons {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        #results-area .level-badge {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 1.2em;
            font-weight: bold;
            color: white;
            margin-bottom: 15px;
        }
        .level-debutant { background-color: var(--highlight-color); }
        .level-intermediaire { background-color: var(--secondary-color); }
        .level-avance { background-color: var(--accent-color); }

        #recommendations ul {
            list-style: none;
            padding-left: 0;
        }
        #recommendations li {
            background-color: var(--light-accent);
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 5px;
            border-left: 4px solid var(--primary-color);
        }
         #recommendations li a {
             text-decoration: none;
             color: var(--primary-color);
             font-weight: 500;
         }
         #recommendations li a:hover {
             text-decoration: underline;
         }

        /* Cacher les zones par défaut */
        #test-area, #results-area {
            display: none;
        }

        .africode-footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            color: #777;
            font-style: italic;
        }

    </style>


    <div class="assessment-header">
        <h1><i class="fas fa-graduation-cap me-2"></i>Test de Niveau AfriCode</h1>
        <p>Découvrez le parcours de formation idéal pour vous en quelques minutes !</p>
    </div>

    <div class="container mt-4">
        <div class="test-container">

            <!-- Section Introduction -->
            <div id="introduction">
                <h2>Bienvenue !</h2>
                <p>Ce test rapide nous aidera à évaluer vos connaissances actuelles en développement web.</p>
                <p>Il comporte <strong id="total-questions-intro">X</strong> questions et devrait prendre environ <strong id="test-duration-intro">Y</strong> minutes.</p>
                <p>Vos réponses nous permettront de vous recommander les formations AfriCode les plus adaptées à votre niveau.</p>
                <button id="start-test-btn" class="btn btn-lg text-white" style="background-color: var(--primary-color); border-color: var(--primary-color);">
                    <i class="fas fa-play-circle me-2"></i>Commencer le Test
                </button>
            </div>

            <!-- Section Test en cours -->
            <div id="test-area">
                <div class="progress-container">
                    <div>
                        <span id="question-number">Question X / Y</span>
                        <div class="progress" style="height: 10px; width: 200px; display: inline-block; margin-left: 10px;">
                            <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%; background-color: var(--secondary-color);" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div id="timer-container">
                        <i class="fas fa-stopwatch me-1"></i> Temps restant: <span id="timer">--:--</span>
                    </div>
                </div>

                <div id="question-content">
                    <p id="question-text">Chargement de la question...</p>
                    <div id="answer-options" class="answer-options">
                        <!-- Les options de réponse seront chargées ici -->
                    </div>
                </div>

                <div class="navigation-buttons">
                    <button id="prev-btn" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Précédent</button>
                    <button id="next-btn" class="btn text-white" style="background-color: var(--primary-color); border-color: var(--primary-color);">Suivant <i class="fas fa-arrow-right ms-1"></i></button>
                    <button id="submit-btn" class="btn btn-success" style="display: none;"><i class="fas fa-check-circle me-1"></i> Terminer le Test</button>
                </div>
            </div>

            <!-- Section Résultats -->
            <div id="results-area">
                <h2>Résultats du Test</h2>
                <p>Votre score est de <strong id="score-percentage">--%</strong>.</p>
                <p>Votre niveau estimé est :</p>
                <div id="level-badge" class="level-badge mb-3">NIVEAU ESTIMÉ</div>
                <p id="level-description">Description du niveau...</p>

                <div id="recommendations" class="mt-4">
                    <h4>Formations recommandées pour vous :</h4>
                    <ul id="recommended-courses-list">
                        <!-- Recommandations chargées ici -->
                    </ul>
                    <p class="mt-3">
                        <a href="/parcours" class="btn btn-outline-primary">Voir tous les parcours</a>
                        <a href="#" id="retake-test-btn" class="btn btn-outline-secondary ms-2">Refaire le test</a>
                    </p>
                </div>
            </div>

        </div> <!-- fin .test-container -->
    </div> <!-- fin .container -->


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- Configuration du Test ---
            const TOTAL_TIME_SECONDS = 10 * 60; // 10 minutes
            // TODO: Remplacer par un vrai fetch API vers /api/assessment/questions
            const questions = [
                { id: 1, question: "Quelle balise HTML est utilisée pour créer un titre principal ?", options: ["<h1>", "<p>", "<div>", "<header>"], correctAnswerIndex: 0 },
                { id: 2, question: "Comment ciblez-vous un élément avec l'ID 'logo' en CSS ?", options: [".logo", "logo", "#logo", "*logo"], correctAnswerIndex: 2 },
                { id: 3, question: "Quel attribut HTML spécifie une URL pour une image ?", options: ["href", "src", "link", "url"], correctAnswerIndex: 1 },
                { id: 4, question: "En JavaScript, comment déclarez-vous une variable qui ne peut pas être réassignée ?", options: ["let", "var", "const", "static"], correctAnswerIndex: 2 },
                { id: 5, question: "Quelle méthode JavaScript est utilisée pour ajouter un élément à la fin d'un tableau ?", options: [".add()", ".push()", ".append()", ".join()"], correctAnswerIndex: 1 },
                { id: 6, question: "Quel sélecteur CSS applique un style à TOUS les éléments <p> ?", options: ["p", ".p", "#p", "*p"], correctAnswerIndex: 0 },
                { id: 7, question: "Quelle boucle est idéale pour parcourir les propriétés d'un objet en JavaScript ?", options: ["for", "while", "for...in", "for...of"], correctAnswerIndex: 2 },
                { id: 8, question: "Que signifie 'DOM' en développement web ?", options: ["Data Object Model", "Document Object Model", "Document Oriented Module", "Distribution Object Manager"], correctAnswerIndex: 1 },
                { id: 9, question: "Comment inclure un fichier CSS externe dans une page HTML ?", options: ["<style src='...'>", "<css link='...'>", "<script src='...'>", "<link rel='stylesheet' href='...'>"], correctAnswerIndex: 3 },
                { id: 10, question: "Quelle fonction JavaScript permet d'afficher une boîte de dialogue avec un message ?", options: ["prompt()", "confirm()", "log()", "alert()"], correctAnswerIndex: 3 },
                 // Ajouter d'autres questions pour mieux évaluer (potentiellement plus difficiles)
                { id: 11, question: "Quelle est la différence principale entre `==` et `===` en JavaScript ?", options: ["Aucune", "`===` compare aussi le type", "`==` est plus rapide", "`===` est obsolète"], correctAnswerIndex: 1 },
                { id: 12, question: "Quel framework CSS populaire utilise un système de grille basé sur 12 colonnes ?", options: ["Tailwind", "Materialize", "Bootstrap", "Bulma"], correctAnswerIndex: 2 },
            ];

            // --- Variables d'état ---
            let currentQuestionIndex = 0;
            let userAnswers = new Array(questions.length).fill(null);
            let timerInterval = null;
            let timeLeft = TOTAL_TIME_SECONDS;
            let testStarted = false;
            let testSubmitted = false;

            // --- Éléments du DOM ---
            const introductionDiv = document.getElementById('introduction');
            const testAreaDiv = document.getElementById('test-area');
            const resultsAreaDiv = document.getElementById('results-area');
            const startBtn = document.getElementById('start-test-btn');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const submitBtn = document.getElementById('submit-btn');
            const questionNumberSpan = document.getElementById('question-number');
            const progressBar = document.getElementById('progress-bar');
            const timerSpan = document.getElementById('timer');
            const questionTextP = document.getElementById('question-text');
            const answerOptionsDiv = document.getElementById('answer-options');
            const scorePercentageSpan = document.getElementById('score-percentage');
            const levelBadgeDiv = document.getElementById('level-badge');
            const levelDescriptionP = document.getElementById('level-description');
            const recommendationsListUl = document.getElementById('recommended-courses-list');
            const retakeBtn = document.getElementById('retake-test-btn');
            const totalQuestionsIntro = document.getElementById('total-questions-intro');
            const testDurationIntro = document.getElementById('test-duration-intro');

            // --- Fonctions ---
            function initializeTest() {
                totalQuestionsIntro.textContent = questions.length;
                testDurationIntro.textContent = Math.ceil(TOTAL_TIME_SECONDS / 60);
                introductionDiv.style.display = 'block';
                testAreaDiv.style.display = 'none';
                resultsAreaDiv.style.display = 'none';
                testStarted = false;
                testSubmitted = false;
                currentQuestionIndex = 0;
                userAnswers.fill(null);
                timeLeft = TOTAL_TIME_SECONDS;
                clearInterval(timerInterval);
                timerSpan.textContent = formatTime(timeLeft);
            }

            function startTest() {
                testStarted = true;
                introductionDiv.style.display = 'none';
                testAreaDiv.style.display = 'block';
                resultsAreaDiv.style.display = 'none';
                displayQuestion(currentQuestionIndex);
                startTimer();
            }

            function displayQuestion(index) {
                if (index < 0 || index >= questions.length) return;

                const question = questions[index];
                questionTextP.textContent = question.question;
                answerOptionsDiv.innerHTML = ''; // Vider les anciennes options

                question.options.forEach((option, optionIndex) => {
                    const isChecked = userAnswers[index] === optionIndex;
                    const label = document.createElement('label');
                    label.innerHTML = `
                        <input type="radio" name="question-${question.id}" value="${optionIndex}" ${isChecked ? 'checked' : ''}>
                        <span>${escapeHtml(option)}</span>
                    `;
                    label.querySelector('input').addEventListener('change', () => {
                        userAnswers[index] = optionIndex;
                        // Optionnel: aller à la question suivante automatiquement après sélection
                        // setTimeout(nextQuestion, 300);
                    });
                    answerOptionsDiv.appendChild(label);
                });

                questionNumberSpan.textContent = `Question ${index + 1} / ${questions.length}`;
                const progress = ((index + 1) / questions.length) * 100;
                progressBar.style.width = `${progress}%`;
                progressBar.setAttribute('aria-valuenow', progress);

                // Gérer l'affichage des boutons de navigation
                prevBtn.disabled = index === 0;
                nextBtn.style.display = index === questions.length - 1 ? 'none' : 'inline-block';
                submitBtn.style.display = index === questions.length - 1 ? 'inline-block' : 'none';
            }

            function nextQuestion() {
                if (currentQuestionIndex < questions.length - 1) {
                    currentQuestionIndex++;
                    displayQuestion(currentQuestionIndex);
                }
            }

            function previousQuestion() {
                if (currentQuestionIndex > 0) {
                    currentQuestionIndex--;
                    displayQuestion(currentQuestionIndex);
                }
            }

            function startTimer() {
                timerSpan.textContent = formatTime(timeLeft);
                timerInterval = setInterval(() => {
                    timeLeft--;
                    timerSpan.textContent = formatTime(timeLeft);
                    if (timeLeft <= 0) {
                        clearInterval(timerInterval);
                        alert("Temps écoulé ! Le test va être soumis.");
                        submitTest();
                    }
                }, 1000);
            }

            function formatTime(seconds) {
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = seconds % 60;
                return `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
            }

            function submitTest() {
                if (testSubmitted) return;
                testSubmitted = true;
                clearInterval(timerInterval);

                const score = calculateScore();
                const percentage = Math.round((score / questions.length) * 100);
                const level = determineLevel(percentage);

                displayResults(percentage, level);
                testAreaDiv.style.display = 'none';
                resultsAreaDiv.style.display = 'block';

                // TODO: Envoyer les résultats à l'API Backend
                // fetch('/api/assessment/submit', { method: 'POST', body: JSON.stringify({ answers: userAnswers, score: percentage, level: level }) });
                console.log("Test soumis", { answers: userAnswers, score: percentage, level: level });
            }

            function calculateScore() {
                let score = 0;
                userAnswers.forEach((answer, index) => {
                    if (answer !== null && answer === questions[index].correctAnswerIndex) {
                        score++;
                    }
                });
                return score;
            }

            function determineLevel(percentage) {
                if (percentage < 40) return "Débutant";
                if (percentage < 75) return "Intermédiaire";
                return "Avancé"; // Ou ajuster les seuils
            }

            function displayResults(percentage, level) {
                scorePercentageSpan.textContent = `${percentage}%`;
                levelBadgeDiv.textContent = level.toUpperCase();
                levelBadgeDiv.className = 'level-badge mb-3'; // Reset classes

                let description = "";
                let recommendationsHTML = "";
                recommendationsListUl.innerHTML = ''; // Vider

                switch (level) {
                    case "Débutant":
                        levelBadgeDiv.classList.add('level-debutant');
                        description = "Vous semblez débuter en développement web. C'est le moment idéal pour construire des bases solides !";
                         recommendationsHTML = `
                            <li><a href="/cours/intro-dev-web">Introduction au développement web</a></li>
                            <li><a href="/cours/html-css">HTML & CSS : Les fondations</a></li>
                            <li><a href="/parcours/debutant">Parcours Débutant Complet</a></li>
                         `;
                        break;
                    case "Intermédiaire":
                        levelBadgeDiv.classList.add('level-intermediaire');
                        description = "Vous avez déjà de bonnes bases ! Approfondissez vos compétences avec des sujets plus avancés.";
                         recommendationsHTML = `
                            <li><a href="/cours/javascript">JavaScript : Programmation côté client</a></li>
                            <li><a href="/cours/php-mysql">PHP et MySQL : Programmation côté serveur</a></li>
                            <li><a href="/cours/reactjs">ReactJS : Développement front-end moderne</a></li>
                            <li><a href="/parcours/intermediaire">Parcours Intermédiaire</a></li>
                         `;
                        break;
                    case "Avancé":
                        levelBadgeDiv.classList.add('level-avance');
                        description = "Excellent ! Vous maîtrisez bien les fondamentaux. Explorez nos formations avancées et spécialisées.";
                         recommendationsHTML = `
                            <li><a href="/cours/laravel">Laravel : Framework PHP pour le backend</a></li>
                            <li><a href="/cours/full-stack">Full-Stack Development</a></li>
                            <li><a href="/cours/flutter">Flutter : Développement multiplateforme</a></li>
                             <li><a href="/cours/projets-pratiques">Formation avancée avec projets pratiques</a></li>
                         `;
                        break;
                }
                levelDescriptionP.textContent = description;
                recommendationsListUl.innerHTML = recommendationsHTML;
            }

             function escapeHtml(unsafe) {
                return unsafe
                     .replace(/&/g, "&")
                     .replace(/</g, "<")
                     .replace(/>/g, ">")
                     .replace(/"/g, """)
                     .replace(/'/g, "'");
             }

            // --- Écouteurs d'événements ---
            startBtn.addEventListener('click', startTest);
            prevBtn.addEventListener('click', previousQuestion);
            nextBtn.addEventListener('click', nextQuestion);
            submitBtn.addEventListener('click', () => {
                 // Confirmation avant de soumettre
                 if (confirm("Êtes-vous sûr de vouloir terminer le test ?")) {
                     submitTest();
                 }
            });
            retakeBtn.addEventListener('click', initializeTest);

            // --- Initialisation ---
            initializeTest();

        });
    </script>
@endsection