@extends('layouts.layout')

@section('title', 'AfriCode - Test Technique')

@section('content')
<style>
    :root {
        --primary-color: #1EA38B;
        --secondary-color: #FF8E2A;
        --accent-color: #E32D31;
        --highlight-color: #27B371;
        --background-color: #f4f7f6;
        --light-accent: #ECF0F1;
        --text-color: #333333;
        --card-bg: #ffffff;
    }

    body {
        background-color: var(--background-color);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--text-color);
        padding-bottom: 30px;
    }

    .assessment-header {
        background: linear-gradient(135deg, var(--secondary-color), #ffae63);
        color: white;
        padding: 25px 15px;
        margin-bottom: 25px;
        text-align: center;
        border-radius: 0;
    }

    .test-container {
        background-color: var(--card-bg);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }

    /* Styles pour la grille de cours */
    .course-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
    }

    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .course-card-img {
        height: 160px;
        background-color: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .course-card-body {
        padding: 15px;
    }

    .course-card-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--primary-color);
    }

    .course-card-text {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 15px;
    }

    .course-card-footer {
        padding: 10px 15px;
        background-color: #f9f9f9;
        border-top: 1px solid #eee;
    }

    .badge-tech {
        background-color: var(--light-accent);
        color: var(--text-color);
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        margin-right: 5px;
    }

    /* Notification pour questions obligatoires */
    .required-notification {
        color: var(--accent-color);
        font-size: 0.9rem;
        margin-top: 10px;
        display: none;
    }

    /* Modal de confirmation */
    .confirmation-modal .modal-content {
        border-radius: 10px;
        border: none;
    }

    .confirmation-modal .modal-header {
        background-color: var(--primary-color);
        color: white;
        border-radius: 10px 10px 0 0;
    }

    .confirmation-modal .modal-footer {
        border-top: none;
        justify-content: center;
    }

    /* Autres styles existants */
    #introduction h2, #results-area h2 {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 15px;
    }

    .progress-container {
        margin-bottom: 15px;
        flex-direction: column;
        align-items: flex-start;
    }

    #timer {
        font-weight: bold;
        color: var(--accent-color);
        font-size: 1rem;
    }

    #question-text {
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 20px;
        line-height: 1.4;
    }

    .answer-options label {
        display: block;
        background-color: var(--light-accent);
        padding: 10px 15px;
        margin-bottom: 8px;
        border-radius: 6px;
        border: 1px solid #d8dcdf;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .navigation-buttons {
        margin-top: 20px;
        flex-direction: column-reverse;
        gap: 10px;
    }

    .level-badge {
        padding: 8px 15px;
        font-size: 1rem;
        display: inline-block;
        border-radius: 20px;
        font-weight: bold;
        color: white;
        margin-bottom: 15px;
    }

    .level-debutant { background-color: var(--highlight-color); }
    .level-intermediaire { background-color: var(--secondary-color); }
    .level-avance { background-color: var(--accent-color); }

    @media (min-width: 768px) {
        .assessment-header {
            padding: 30px 20px;
            border-radius: 0 0 15px 15px;
        }
        
        .navigation-buttons {
            flex-direction: row;
            justify-content: space-between;
        }
        
        .course-card-img {
            height: 180px;
        }
    }
</style>

<!-- Modal de confirmation -->
<div class="modal fade confirmation-modal" id="confirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la soumission</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p>Êtes-vous sûr de vouloir soumettre votre test ?</p>
                <p>Vous ne pourrez plus modifier vos réponses après soumission.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary me-3" data-bs-dismiss="modal">Annuler</button>
                <button type="button" id="confirm-submit-btn" class="btn btn-primary">Confirmer</button>
            </div>
        </div>
    </div>
</div>

<div class="assessment-header">
    <h1><i class="fas fa-graduation-cap me-2"></i>Test Technique AfriCode</h1>
    <p class="mb-0">Évaluez vos compétences en développement et découvrez les formations adaptées</p>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="test-container">
                <!-- Section Introduction -->
                <div id="introduction">
                    <h2>Test Technique Multilangage</h2>
                    <p>Ce test évalue vos connaissances en PHP, CSS, SQL, Python, HTML, Laravel, C, C++, React et technologies numériques.</p>
                    <p>Il comporte <strong id="total-questions-intro">35</strong> questions avec un temps limité de <strong id="test-duration-intro">15</strong> minutes.</p>
                    <p>Les questions sont sélectionnées aléatoirement pour couvrir différents aspects du développement.</p>
                    <div class="text-center mt-4">
                        <button id="start-test-btn" class="btn btn-lg text-white" style="background-color: var(--primary-color);">
                            <i class="fas fa-play-circle me-2"></i>Commencer le Test
                        </button>
                    </div>
                </div>

                <!-- Section Test en cours -->
                <div id="test-area">
                    <div class="progress-container">
                        <div class="d-flex justify-content-between w-100 mb-2">
                            <span id="question-number">Question X/35</span>
                            <span id="timer-container">
                                <i class="fas fa-stopwatch me-1"></i><span id="timer">15:00</span>
                            </span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%; background-color: var(--secondary-color);"></div>
                        </div>
                    </div>

                    <div id="question-content">
                        <p id="question-text">Chargement de la question...</p>
                        <div id="answer-options" class="answer-options"></div>
                    </div>

                    <div class="navigation-buttons">
                        <button id="prev-btn" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Précédent
                        </button>
                        <div>
                            <button id="next-btn" class="btn text-white me-2" style="background-color: var(--primary-color);">
                                Suivant <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                            <button id="submit-btn" class="btn btn-success" style="display: none;">
                                <i class="fas fa-check-circle me-1"></i>Terminer
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Section Résultats -->
                <div id="results-area">
                    <h2>Résultats du Test</h2>
                    <p>Votre score est de <strong id="score-percentage">--%</strong>.</p>
                    <p>Votre niveau estimé est :</p>
                    <div id="level-badge" class="level-badge mb-3">NIVEAU ESTIMÉ</div>
                    <p id="level-description">Description du niveau...</p>

                    <div class="mt-4">
                        <h4>Formations recommandées :</h4>
                        <div id="courses-grid" class="row g-4 mt-3">
                            <!-- Les cours seront chargés ici dynamiquement -->
                        </div>
                        
                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <a href="/cours" class="btn btn-outline-primary">Voir tous les parcours</a>
                            <a href="#" id="retake-test-btn" class="btn btn-outline-secondary">Refaire le test</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration du Test
    const TOTAL_QUESTIONS = 35;
    const TEST_DURATION = 15 * 60; // 15 minutes en secondes
    
    // Base de données de questions (150 questions au total)
   const questionBank = [
    // PHP (5 questions)
    { id: 1, category: 'PHP', question: "Quelle fonction PHP permet de vérifier si une variable est définie et non nulle ?", options: ["isset()", "empty()", "is_null()", "defined()"], correctAnswerIndex: 0 },
    { id: 2, category: 'PHP', question: "Comment démarre-t-on une session en PHP ?", options: ["session_start()", "start_session()", "init_session()", "create_session()"], correctAnswerIndex: 0 },
    { id: 3, category: 'PHP', question: "Quelle est la différence entre == et === en PHP ?", options: ["Aucune différence", "=== compare aussi le type", "== est plus rapide", "=== est obsolète"], correctAnswerIndex: 1 },
    { id: 4, category: 'PHP', question: "Comment inclut-on un fichier en PHP ?", options: ["include()", "import()", "require()", "Les réponses 1 et 3"], correctAnswerIndex: 3 },
    { id: 5, category: 'PHP', question: "Quelle superglobale contient les données POST ?", options: ["$_GET", "$_REQUEST", "$_POST", "$_SESSION"], correctAnswerIndex: 2 },

    // CSS (5 questions)
    { id: 6, category: 'CSS', question: "Quelle propriété CSS permet de créer des animations ?", options: ["@animation", "@keyframes", "@transition", "@transform"], correctAnswerIndex: 1 },
    { id: 7, category: 'CSS', question: "Que fait la propriété 'flex: 1' en CSS Flexbox ?", options: ["Définit la taille initiale", "Permet à l'élément de grandir et rétrécir", "Désactive Flexbox", "Centre l'élément"], correctAnswerIndex: 1 },
    { id: 8, category: 'CSS', question: "Comment centrer horizontalement un élément block ?", options: ["text-align: center", "margin: auto", "align: center", "position: center"], correctAnswerIndex: 1 },
    { id: 9, category: 'CSS', question: "Quelle unité est relative à la taille de police de l'élément parent ?", options: ["px", "em", "rem", "vw"], correctAnswerIndex: 1 },
    { id: 10, category: 'CSS', question: "Comment appliquer un style quand la souris passe sur un élément ?", options: [":active", ":focus", ":hover", ":over"], correctAnswerIndex: 2 },

    // SQL (5 questions)
    { id: 11, category: 'SQL', question: "Quelle commande SQL permet de modifier des données existantes ?", options: ["UPDATE", "MODIFY", "ALTER", "CHANGE"], correctAnswerIndex: 0 },
    { id: 12, category: 'SQL', question: "Quel type de jointure retourne toutes les lignes des deux tables ?", options: ["INNER JOIN", "LEFT JOIN", "FULL OUTER JOIN", "CROSS JOIN"], correctAnswerIndex: 2 },
    { id: 13, category: 'SQL', question: "Quelle clause permet de filtrer les résultats ?", options: ["FILTER", "WHERE", "HAVING", "Les réponses 2 et 3"], correctAnswerIndex: 3 },
    { id: 14, category: 'SQL', question: "Comment trier les résultats par ordre décroissant ?", options: ["ORDER BY DESC", "SORT DESC", "ORDER DESC", "SORT BY DESC"], correctAnswerIndex: 0 },
    { id: 15, category: 'SQL', question: "Quelle fonction agrège compte le nombre de lignes ?", options: ["SUM()", "AVG()", "COUNT()", "TOTAL()"], correctAnswerIndex: 2 },

    // Python (5 questions)
    { id: 16, category: 'Python', question: "Comment crée-t-on un dictionnaire en Python ?", options: ["{}", "dict()", "[]", "Les deux premières réponses"], correctAnswerIndex: 3 },
    { id: 17, category: 'Python', question: "Comment itérer sur un dictionnaire ?", options: ["for key in dict", "for key, value in dict.items()", "for item in dict", "Les réponses 1 et 2"], correctAnswerIndex: 3 },
    { id: 18, category: 'Python', question: "Quelle méthode ajoute un élément à une liste ?", options: ["append()", "add()", "insert()", "push()"], correctAnswerIndex: 0 },
    { id: 19, category: 'Python', question: "Comment gère-t-on les exceptions ?", options: ["try/except", "catch/throw", "error/handle", "exception/rescue"], correctAnswerIndex: 0 },
    { id: 20, category: 'Python', question: "Quel mot-clé définit une fonction ?", options: ["def", "function", "func", "define"], correctAnswerIndex: 0 },

    // HTML (5 questions)
    { id: 21, category: 'HTML', question: "Quel attribut HTML5 permet de valider un champ avant soumission ?", options: ["required", "validate", "mandatory", "necessary"], correctAnswerIndex: 0 },
    { id: 22, category: 'HTML', question: "Quelle balise crée un lien ?", options: ["<link>", "<a>", "<href>", "<url>"], correctAnswerIndex: 1 },
    { id: 23, category: 'HTML', question: "Comment intégrer du JavaScript ?", options: ["<script>", "<javascript>", "<js>", "<scripting>"], correctAnswerIndex: 0 },
    { id: 24, category: 'HTML', question: "Quelle balise est pour une liste non-ordonnée ?", options: ["<ol>", "<ul>", "<li>", "<list>"], correctAnswerIndex: 1 },
    { id: 25, category: 'HTML', question: "Quel élément sémantique représente un en-tête ?", options: ["<head>", "<header>", "<heading>", "<hgroup>"], correctAnswerIndex: 1 },

    // Laravel (5 questions)
    { id: 26, category: 'Laravel', question: "Quelle commande Artisan crée un nouveau contrôleur ?", options: ["make:controller", "create:controller", "new:controller", "generate:controller"], correctAnswerIndex: 0 },
    { id: 27, category: 'Laravel', question: "Où sont définies les routes ?", options: ["app/Http/routes.php", "routes/web.php", "config/routes.php", "public/routes.php"], correctAnswerIndex: 1 },
    { id: 28, category: 'Laravel', question: "Quel est le système de template ?", options: ["Twig", "Blade", "Smarty", "Plates"], correctAnswerIndex: 1 },
    { id: 29, category: 'Laravel', question: "Comment accéder à la requête HTTP ?", options: ["$request", "Request::input()", "request()", "Toutes ces réponses"], correctAnswerIndex: 3 },
    { id: 30, category: 'Laravel', question: "Quel est l'ORM de Laravel ?", options: ["Doctrine", "Eloquent", "RedBean", "ActiveRecord"], correctAnswerIndex: 1 },

    // Numérique (3 questions)
    { id: 31, category: 'Numérique', question: "Qu'est-ce qu'une API REST ?", options: ["Un langage de programmation", "Un style d'architecture pour les services web", "Un framework frontend", "Un protocole de base de données"], correctAnswerIndex: 1 },
    { id: 32, category: 'Numérique', question: "Quel protocole est utilisé pour les requêtes HTTP/2 ?", options: ["TCP", "UDP", "QUIC", "TLS"], correctAnswerIndex: 0 },
    { id: 33, category: 'Numérique', question: "Qu'est-ce que le SEO ?", options: ["Un langage de programmation", "Une technique de référencement naturel", "Un framework JavaScript", "Un protocole réseau"], correctAnswerIndex: 1 },

    // IA (2 questions)
    { id: 34, category: 'IA', question: "Qu'est-ce que le Machine Learning ?", options: ["Une méthode de cryptage", "Un langage de programmation", "Un système qui apprend à partir de données", "Un protocole réseau"], correctAnswerIndex: 2 },
    { id: 35, category: 'IA', question: "Quel algorithme est souvent utilisé pour la classification ?", options: ["K-means", "Random Forest", "Apriori", "PageRank"], correctAnswerIndex: 1 }
];
    // Sélection aléatoire de 35 questions
    const questions = [];
    const shuffled = [...questionBank].sort(() => 0.5 - Math.random());
    for (let i = 0; i < TOTAL_QUESTIONS && i < shuffled.length; i++) {
        questions.push(shuffled[i]);
    }

    // Variables d'état
    let currentQuestionIndex = 0;
    let userAnswers = new Array(TOTAL_QUESTIONS).fill(null);
    let timerInterval = null;
    let timeLeft = TEST_DURATION;
    let testStarted = false;
    let testSubmitted = false;
    let answeredQuestions = new Array(TOTAL_QUESTIONS).fill(false);

    // Éléments du DOM
    const elements = {
        introduction: document.getElementById('introduction'),
        testArea: document.getElementById('test-area'),
        resultsArea: document.getElementById('results-area'),
        startBtn: document.getElementById('start-test-btn'),
        prevBtn: document.getElementById('prev-btn'),
        nextBtn: document.getElementById('next-btn'),
        submitBtn: document.getElementById('submit-btn'),
        questionNumber: document.getElementById('question-number'),
        progressBar: document.getElementById('progress-bar'),
        timer: document.getElementById('timer'),
        questionText: document.getElementById('question-text'),
        answerOptions: document.getElementById('answer-options'),
        scorePercentage: document.getElementById('score-percentage'),
        levelBadge: document.getElementById('level-badge'),
        levelDescription: document.getElementById('level-description'),
        coursesGrid: document.getElementById('courses-grid'),
        retakeBtn: document.getElementById('retake-test-btn'),
        totalQuestionsIntro: document.getElementById('total-questions-intro'),
        testDurationIntro: document.getElementById('test-duration-intro'),
        confirmationModal: new bootstrap.Modal(document.getElementById('confirmationModal')),
        confirmSubmitBtn: document.getElementById('confirm-submit-btn')
    };

    // Fonctions
    function initializeTest() {
        elements.totalQuestionsIntro.textContent = TOTAL_QUESTIONS;
        elements.testDurationIntro.textContent = TEST_DURATION / 60;
        elements.introduction.style.display = 'block';
        elements.testArea.style.display = 'none';
        elements.resultsArea.style.display = 'none';
        testStarted = false;
        testSubmitted = false;
        currentQuestionIndex = 0;
        userAnswers.fill(null);
        answeredQuestions.fill(false);
        timeLeft = TEST_DURATION;
        clearInterval(timerInterval);
        elements.timer.textContent = formatTime(timeLeft);
    }

    function startTest() {
        testStarted = true;
        elements.introduction.style.display = 'none';
        elements.testArea.style.display = 'block';
        elements.resultsArea.style.display = 'none';
        displayQuestion(currentQuestionIndex);
        startTimer();
    }

    function displayQuestion(index) {
        if (index < 0 || index >= questions.length) return;

        const question = questions[index];
        elements.questionText.textContent = question.question;
        elements.answerOptions.innerHTML = '';

        // Ajouter la notification pour les questions obligatoires
        elements.answerOptions.innerHTML += `
            <div class="required-notification" id="required-notification-${index}">
                <i class="fas fa-exclamation-circle me-1"></i> Vous devez répondre à cette question avant de continuer.
            </div>
        `;

        question.options.forEach((option, optionIndex) => {
            const label = document.createElement('label');
            label.innerHTML = `
                <input type="radio" name="question-${question.id}" value="${optionIndex}" 
                    ${userAnswers[index] === optionIndex ? 'checked' : ''}>
                <span>${escapeHtml(option)}</span>
            `;
            label.querySelector('input').addEventListener('change', () => {
                userAnswers[index] = optionIndex;
                answeredQuestions[index] = true;
                // Masquer la notification si elle était visible
                document.getElementById(`required-notification-${index}`).style.display = 'none';
            });
            elements.answerOptions.appendChild(label);
        });

        elements.questionNumber.textContent = `Question ${index + 1}/${TOTAL_QUESTIONS}`;
        const progress = ((index + 1) / TOTAL_QUESTIONS) * 100;
        elements.progressBar.style.width = `${progress}%`;

        elements.prevBtn.disabled = index === 0;
        elements.nextBtn.style.display = index === TOTAL_QUESTIONS - 1 ? 'none' : 'inline-block';
        elements.submitBtn.style.display = index === TOTAL_QUESTIONS - 1 ? 'inline-block' : 'none';
    }

    function nextQuestion() {
        if (!answeredQuestions[currentQuestionIndex]) {
            // Afficher la notification si l'utilisateur essaie de passer sans répondre
            document.getElementById(`required-notification-${currentQuestionIndex}`).style.display = 'block';
            return;
        }
        
        if (currentQuestionIndex < TOTAL_QUESTIONS - 1) {
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
        elements.timer.textContent = formatTime(timeLeft);
        timerInterval = setInterval(() => {
            timeLeft--;
            elements.timer.textContent = formatTime(timeLeft);
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                submitTest();
            }
        }, 1000);
    }

    function formatTime(seconds) {
        const mins = Math.floor(seconds / 60).toString().padStart(2, '0');
        const secs = (seconds % 60).toString().padStart(2, '0');
        return `${mins}:${secs}`;
    }

    function submitTest() {
        if (testSubmitted) return;
        
        // Vérifier si toutes les questions ont été répondues
        const unanswered = answeredQuestions.some(answered => !answered);
        if (unanswered) {
            // Trouver la première question sans réponse
            const firstUnanswered = answeredQuestions.findIndex(answered => !answered);
            currentQuestionIndex = firstUnanswered;
            displayQuestion(currentQuestionIndex);
            
            // Afficher la notification
            document.getElementById(`required-notification-${currentQuestionIndex}`).style.display = 'block';
            
            // Faire défiler jusqu'à la question non répondue
            elements.questionContent.scrollIntoView({ behavior: 'smooth' });
            return;
        }
        
        // Si tout est répondu, afficher la modal de confirmation
        elements.confirmationModal.show();
    }

    function calculateScore() {
        return userAnswers.reduce((score, answer, index) => {
            return score + (answer !== null && answer === questions[index].correctAnswerIndex ? 1 : 0);
        }, 0);
    }

    function determineLevel(percentage) {
        if (percentage < 40) return "Débutant";
        if (percentage < 75) return "Intermédiaire";
        return "Avancé";
    }

    function displayResults(percentage, level) {
        elements.scorePercentage.textContent = `${percentage}%`;
        elements.levelBadge.textContent = level.toUpperCase();
        elements.levelBadge.className = 'level-badge mb-3';

        let description = "";
        elements.coursesGrid.innerHTML = '';

        // Définir les cours recommandés en fonction du niveau
        const recommendedCourses = getRecommendedCourses(level);

        // Afficher les cours dans des cartes
        recommendedCourses.forEach(course => {
            const courseCard = document.createElement('div');
            courseCard.className = 'col-md-6 col-lg-4';
            courseCard.innerHTML = `
                <div class="course-card h-100">
                    <div class="course-card-img">
                        <i class="${course.icon} fa-3x" style="color: ${course.color};"></i>
                    </div>
                    <div class="course-card-body">
                        <h5 class="course-card-title">${course.title}</h5>
                        <p class="course-card-text">${course.description}</p>
                        <div class="mb-2">
                            ${course.technologies.map(tech => `<span class="badge-tech">${tech}</span>`).join('')}
                        </div>
                    </div>
                    <div class="course-card-footer text-end">
                        <a href="${course.link}" class="btn btn-sm btn-primary">Voir le cours</a>
                    </div>
                </div>
            `;
            elements.coursesGrid.appendChild(courseCard);
        });

        switch (level) {
            case "Débutant":
                elements.levelBadge.classList.add('level-debutant');
                description = "Vous commencez votre parcours en développement. Nos formations débutants vous aideront à bâtir des bases solides.";
                break;
            case "Intermédiaire":
                elements.levelBadge.classList.add('level-intermediaire');
                description = "Vous avez de bonnes bases. Approfondissez vos connaissances avec nos formations avancées.";
                break;
            case "Avancé":
                elements.levelBadge.classList.add('level-avance');
                description = "Vous maîtrisez les concepts fondamentaux. Perfectionnez-vous avec nos formations expertes.";
                break;
        }
        elements.levelDescription.textContent = description;
    }

    function getRecommendedCourses(level) {
        const allCourses = {
            beginner: [
                {
                    title: "HTML/CSS Fondamentaux",
                    description: "Apprenez à créer des sites web avec HTML5 et CSS3",
                    technologies: ["HTML", "CSS"],
                    icon: "fas fa-code",
                    color: "#E44D26",
                    link: "/cours/html-css"
                },
                {
                    title: "JavaScript pour Débutants",
                    description: "Découvrez les bases de la programmation avec JavaScript",
                    technologies: ["JavaScript"],
                    icon: "fab fa-js",
                    color: "#F0DB4F",
                    link: "/cours/javascript-debutant"
                },
                {
                    title: "Introduction à Python",
                    description: "Premiers pas avec le langage Python",
                    technologies: ["Python"],
                    icon: "fab fa-python",
                    color: "#3776AB",
                    link: "/cours/python-intro"
                }
            ],
            intermediate: [
                {
                    title: "PHP et MySQL",
                    description: "Créez des applications web dynamiques avec PHP et MySQL",
                    technologies: ["PHP", "MySQL"],
                    icon: "fab fa-php",
                    color: "#777BB4",
                    link: "/cours/php-mysql"
                },
                {
                    title: "React JS Fondamentaux",
                    description: "Développez des interfaces utilisateur modernes avec React",
                    technologies: ["React", "JavaScript"],
                    icon: "fab fa-react",
                    color: "#61DAFB",
                    link: "/cours/react-js"
                },
                {
                    title: "Laravel pour Débutants",
                    description: "Initiation au framework PHP Laravel",
                    technologies: ["Laravel", "PHP"],
                    icon: "fab fa-laravel",
                    color: "#FF2D20",
                    link: "/cours/laravel-debutant"
                }
            ],
            advanced: [
                {
                    title: "Architecture Logicielle en C++",
                    description: "Concevez des applications performantes en C++",
                    technologies: ["C++", "OOP"],
                    icon: "fas fa-microchip",
                    color: "#00599C",
                    link: "/cours/cpp-avance"
                },
                {
                    title: "Optimisation SQL Avancée",
                    description: "Techniques avancées pour des bases de données performantes",
                    technologies: ["SQL", "Performance"],
                    icon: "fas fa-database",
                    color: "#4479A1",
                    link: "/cours/sql-avance"
                },
                {
                    title: "Microservices avec Python",
                    description: "Développez des architectures microservices avec Python",
                    technologies: ["Python", "Microservices"],
                    icon: "fas fa-server",
                    color: "#3776AB",
                    link: "/cours/python-microservices"
                }
            ]
        };

        return level === "Débutant" ? allCourses.beginner : 
               level === "Intermédiaire" ? allCourses.intermediate : 
               allCourses.advanced;
    }

    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Écouteurs d'événements
    elements.startBtn.addEventListener('click', startTest);
    elements.prevBtn.addEventListener('click', previousQuestion);
    elements.nextBtn.addEventListener('click', nextQuestion);
    elements.submitBtn.addEventListener('click', submitTest);
    elements.confirmSubmitBtn.addEventListener('click', () => {
        elements.confirmationModal.hide();
        testSubmitted = true;
        clearInterval(timerInterval);

        const score = calculateScore();
        const percentage = Math.round((score / TOTAL_QUESTIONS) * 100);
        const level = determineLevel(percentage);

        displayResults(percentage, level);
        elements.testArea.style.display = 'none';
        elements.resultsArea.style.display = 'block';
    });
    elements.retakeBtn.addEventListener('click', initializeTest);

    // Initialisation
    initializeTest();
});
</script>
@endsection