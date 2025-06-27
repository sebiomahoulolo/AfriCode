@extends('apprenants.layouts.app')

@section('title', $quiz->title . ' | AfriCode')
@section('page-title', $quiz->title)

@push('styles')
<style>
    .quiz-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .africode-card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 12px;
        background: white;
    }
    
    .quiz-timer {
        font-size: 1.2rem;
    }
    
    .question-card {
        transition: all 0.3s ease;
    }
    
    .question-card.active {
        transform: scale(1.01);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    
    .answer-option .form-check-input {
        display: none;
    }
    
    .answer-content {
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8f9fa;
        border: 2px solid #e9ecef !important;
    }
    
    .answer-content:hover {
        background: #e3f2fd;
        border-color: #1976d2 !important;
        transform: translateY(-2px);
    }
    
    .form-check-input:checked + .form-check-label .answer-content {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border-color: #1976d2 !important;
        color: #1976d2;
        font-weight: 500;
    }
    
    .question-card:not(.current-question) {
        display: none;
    }
    
    .quiz-navigation {
        position: sticky;
        bottom: 20px;
        z-index: 1000;
    }
    
    #timer {
        font-size: 1.5rem;
        font-weight: bold;
    }
    
    .timer-warning {
        animation: pulse 1s infinite;
        background-color: #ff9800 !important;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .quiz-progress {
        background: #f8f9fa;
        height: 6px;
        border-radius: 3px;
        overflow: hidden;
        margin: 1rem 0;
    }
    
    .quiz-progress-bar {
        background: linear-gradient(90deg, #4caf50, #2196f3);
        height: 100%;
        transition: width 0.3s ease;
    }

    .stat-label {
        color: var(--gray-600);
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .attempts-history {
        background: white;
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
        margin-bottom: 2rem;
    }

    .attempt-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border: 1px solid var(--gray-200);
        border-radius: var(--border-radius-sm);
        margin-bottom: 1rem;
        background: var(--gray-50);
    }

    .attempt-item:last-child {
        margin-bottom: 0;
    }

    .attempt-score {
        font-weight: 600;
        font-size: 1.1rem;
    }

    .attempt-score.passed {
        color: var(--success);
    }

    .attempt-score.failed {
        color: var(--danger);
    }

    .quiz-actions {
        text-align: center;
        margin-top: 2rem;
    }

    .quiz-btn {
        background: linear-gradient(135deg, #1EA38B 0%, #27B371 100%);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: var(--border-radius);
        font-size: 1.1rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(30, 163, 139, 0.3);
    }

    .quiz-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 163, 139, 0.4);
        color: white;
        text-decoration: none;
    }

    .quiz-btn:disabled {
        background: var(--gray-400);
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .quiz-completed {
        background: linear-gradient(135deg, #27B371 0%, #34c759 100%);
        color: white;
        padding: 1.5rem;
        border-radius: var(--border-radius);
        text-align: center;
        margin-bottom: 2rem;
    }

    .quiz-completed h4 {
        margin: 0 0 0.5rem 0;
        color: white;
    }

    .breadcrumb-modern {
        background: none;
        padding: 0;
        margin-bottom: 1rem;
    }

    .breadcrumb-modern .breadcrumb-item + .breadcrumb-item::before {
        content: "→";
        color: var(--gray-400);
    }

    @media (max-width: 768px) {
        .quiz-header {
            padding: 1.5rem;
        }

        .quiz-info,
        .attempts-history {
            padding: 1.5rem;
        }

        .quiz-stats {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="breadcrumb-modern mb-3" data-aos="fade-right">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('apprenant.dashboard') }}" class="text-decoration-none">
                    <i class="fas fa-home me-1"></i>Tableau de bord
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('apprenant.course.access', ['courseId' => $course->id]) }}" class="text-decoration-none">{{ $course->title }}</a>
            </li>
            @if($module)
            <li class="breadcrumb-item">
                {{-- Lien non cliquable car on est dans le contexte du quiz --}}
                <a href="#" class="text-decoration-none">{{ $module->title }}</a>
            </li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">{{ $quiz->title }}</li>
        </ol>
    </nav>

    <div class="quiz-container">
        <!-- Quiz Header -->
        <div class="quiz-header" data-aos="fade-up">
            <h1 class="mb-2">{{ $quiz->title }}</h1>
            @if($quiz->description)
                <p class="mb-0 opacity-90">{{ $quiz->description }}</p>
            @endif
        </div>

        @if($passed)
            <div class="quiz-completed" data-aos="fade-up">
                <h4><i class="fas fa-check-circle me-2"></i>Quiz Réussi !</h4>
                <p class="mb-0">Félicitations ! Vous avez réussi ce quiz avec un score de {{ round($latestAttempt->score_percentage) }}%.</p>
            </div>
        @endif

        <!-- Quiz Stats -->
        <div class="quiz-stats" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card">
                <span class="stat-number">{{ $quiz->questions->count() }}</span>
                <div class="stat-label">{{ $quiz->questions->count() > 1 ? 'Questions' : 'Question' }}</div>
            </div>
            <div class="stat-card">
                <span class="stat-number">{{ $quiz->passing_score }}%</span>
                <div class="stat-label">Score minimum requis</div>
            </div>
            @if($quiz->time_limit)
            <div class="stat-card">
                <span class="stat-number">{{ $quiz->time_limit }}</span>
                <div class="stat-label">Minutes allouées</div>
            </div>
            @endif
            <div class="stat-card">
                <span class="stat-number">{{ $attempts->count() }}</span>
                <div class="stat-label">{{ $attempts->count() > 1 ? 'Tentatives' : 'Tentative' }}</div>
            </div>
        </div>

        <!-- Quiz Info -->
        <div class="quiz-info" data-aos="fade-up" data-aos-delay="200">
            <h4 class="mb-3"><i class="fas fa-info-circle me-2 text-primary"></i>Instructions</h4>
            <ul class="list-unstyled">
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Lisez attentivement chaque question</li>
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Sélectionnez la meilleure réponse pour chaque question</li>
                @if($quiz->time_limit)
                    <li class="mb-2"><i class="fas fa-clock text-warning me-2"></i>Vous disposez de {{ $quiz->time_limit }} minutes pour terminer</li>
                @endif
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Vous devez obtenir au moins {{ $quiz->passing_score }}% pour réussir</li>
                @if($quiz->is_required)
                    <li class="mb-0"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Ce quiz est obligatoire pour obtenir votre certification</li>
                @endif
            </ul>
        </div>

        @if($attempts->count() > 0)
            <!-- Attempts History -->
            <div class="attempts-history" data-aos="fade-up" data-aos-delay="300">
                <h4 class="mb-3"><i class="fas fa-history me-2 text-primary"></i>Historique des tentatives</h4>
                @foreach($attempts as $attempt)
                    <div class="attempt-item">
                        <div>
                            <strong>Tentative {{ $loop->iteration }}</strong>
                            <small class="text-muted d-block">{{ $attempt->completed_at->format('d/m/Y à H:i') }}</small>
                        </div>
                        <div class="text-end">
                            <div class="attempt-score {{ $attempt->score_percentage >= $quiz->passing_score ? 'passed' : 'failed' }}">
                                {{ round($attempt->score_percentage) }}%
                            </div>
                            <small class="text-muted">
                                {{ $attempt->correct_answers }}/{{ $attempt->total_questions }} correctes
                            </small>
                        </div>
                        <div>
                            <a href="{{ route('apprenant.quiz.result', ['quizId' => $quiz->id, 'attemptId' => $attempt->id]) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>Voir le résultat
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Quiz Actions -->
        <div class="quiz-actions" data-aos="fade-up" data-aos-delay="400">
            @if($quiz->isCourseQuiz() && $quiz->max_attempts > 0)
                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle me-2"></i>
                    @if($remainingAttempts > 0)
                        Il vous reste {{ $remainingAttempts }} tentative(s) pour ce quiz final.
                    @else
                        Vous avez utilisé toutes vos tentatives pour ce quiz final.
                    @endif
                </div>
            @endif

            @if($canAttempt)
                <a href="{{ route('apprenant.quiz.start', $quiz->id) }}" class="quiz-btn">
                    @if($attempts->count() > 0)
                        <i class="fas fa-redo me-2"></i>Retenter le quiz
                    @else
                        <i class="fas fa-play me-2"></i>Commencer le quiz
                    @endif
                </a>
            @else
                @if($passed && $bestAttempt)
                    <div class="alert alert-success mb-4">
                        <i class="fas fa-check-circle me-2"></i>
                        Vous avez réussi ce quiz ! Votre meilleur score est de {{ round($bestAttempt->score) }}%.
                    </div>
                @else
                    <div class="alert alert-warning mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Vous avez atteint le nombre maximum de tentatives pour ce quiz.
                    </div>
                @endif
            @endif
            
            <div class="mt-3">
                @if($module)
                    <a href="{{ route('apprenant.lesson', ['lessonId' => $module->lessons->first()->id ?? 1]) }}" 
                       class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour au module
                    </a>
                @else
                     <a href="{{ route('apprenant.course.access', ['courseId' => $course->id]) }}" 
                       class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour au cours
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Animate numbers
    document.addEventListener('DOMContentLoaded', function() {
        const statNumbers = document.querySelectorAll('.stat-number');
        
        statNumbers.forEach(stat => {
            const finalValue = parseInt(stat.textContent);
            if (!isNaN(finalValue)) {
                let currentValue = 0;
                const increment = finalValue / 30;
                const timer = setInterval(() => {
                    currentValue += increment;
                    if (currentValue >= finalValue) {
                        stat.textContent = finalValue + (stat.textContent.includes('%') ? '%' : '');
                        clearInterval(timer);
                    } else {
                        stat.textContent = Math.floor(currentValue) + (stat.textContent.includes('%') ? '%' : '');
                    }
                }, 50);
            }
        });
    });
</script>
@endpush
