@extends('apprenants.layouts.app')

@section('title', 'AfriCode')
@section('page-title', $quiz->title)

@push('styles')
<style>
    .quiz-form-container { max-width: 900px; margin: auto; }
    .question-card { display: none; }
    .question-card.active { display: block; }
    .answer-option .form-check-input { display: none; }
    .answer-label {
        display: block;
        padding: 1rem 1.5rem;
        border: 2px solid var(--gray-200);
        border-radius: var(--border-radius-sm);
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }
    .answer-label:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
        border-color: var(--primary-color-light);
    }
    .form-check-input:checked + .answer-label {
        background: var(--primary-color-lightest);
        border-color: var(--primary-color);
        font-weight: 600;
        color: var(--primary-color-dark);
    }
    .quiz-progress-bar-container {
        height: 8px;
        background: var(--gray-200);
        border-radius: 4px;
        overflow: hidden;
    }
    .quiz-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #1EA38B, #27B371);
        transition: width 0.3s ease;
    }
    .timer-card {
        position: sticky;
        top: 80px;
    }
    .timer-warning { animation: pulse-warning 1s infinite; }
    @keyframes pulse-warning {
        0%, 100% { background: #ffc107; }
        50% { background: #e0a800; }
    }
</style>
@endpush

@section('content')
<div class="quiz-form-container px-2 py-2" data-aos="fade-up">
    <form id="quiz-form" action="{{ route('apprenant.quiz.take', ['quizId' => $quiz->id]) }}" method="POST">
            @csrf
        <div class="card africode-card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="mb-0">Question <span id="question-number">1</span>/{{ $quiz->questions->count() }}</h3>
                    @if($quiz->time_limit_minutes)
                        <div id="timer-display" class="badge badge-lg badge-primary">
                            <i class="fas fa-clock me-2"></i>
                            <span id="timer"></span>
                    </div>
                    @endif
                </div>
                <div class="quiz-progress-bar-container" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="{{ $quiz->questions->count() }}">
                    <div id="progress-bar" class="quiz-progress-bar" style="width: 0%;"></div>
                </div>
            </div>
                        </div>
                        
        @foreach($quiz->questions as $index => $question)
        <div id="question-{{ $index + 1 }}" class="question-card card africode-card mb-4 @if($loop->first) active @endif">
            <div class="card-header">
                <h5 class="mb-0">{{ $question->text }}</h5>
                <small class="text-muted">{{ $question->points }} point(s)</small>
                        </div>
            <div class="card-body">
                <div class="list-group">
                    @foreach($question->answers as $answer)
                        <div class="answer-option form-check">
                            <input class="form-check-input" 
                                   type="{{ $question->type === 'multiple_choice' ? 'checkbox' : 'radio' }}" 
                                   name="question_{{ $question->id }}[]" 
                                   value="{{ $answer->id }}" 
                                   id="answer-{{ $answer->id }}">
                            <label class="answer-label form-check-label" for="answer-{{ $answer->id }}">
                                        {{ $answer->text }}
                                    </label>
                            </div>
                        @endforeach
                </div>
            </div>
        </div>
        @endforeach

        <div class="card africode-card">
             <div class="card-body d-flex justify-content-between align-items-center">
                <button type="button" id="prev-btn" class="btn btn-outline-modern focus:outline-none focus:ring-2 focus:ring-primary" aria-label="Question précédente" disabled>
                    <i class="fas fa-arrow-left me-2"></i>Précédent
                </button>
                <button type="button" id="next-btn" class="btn btn-modern focus:outline-none focus:ring-2 focus:ring-primary" aria-label="Question suivante">
                    Suivant<i class="fas fa-arrow-right ms-2"></i>
                </button>
                <button type="submit" id="submit-btn" class="btn btn-success w-full focus:outline-none focus:ring-2 focus:ring-primary" style="display: none;" aria-label="Soumettre mes réponses">
                    <i class="fas fa-check-circle me-2"></i>Soumettre mes réponses
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const questions = document.querySelectorAll('.question-card');
    const nextBtn = document.getElementById('next-btn');
    const prevBtn = document.getElementById('prev-btn');
    const submitBtn = document.getElementById('submit-btn');
    const progressBar = document.getElementById('progress-bar');
    const questionNumber = document.getElementById('question-number');
    let currentQuestionIndex = 0;

    function showQuestion(index) {
        questions.forEach((q, i) => {
            q.classList.toggle('active', i === index);
        });
        currentQuestionIndex = index;
        questionNumber.textContent = index + 1;
        
        const progress = ((index + 1) / questions.length) * 100;
        progressBar.style.width = `${progress}%`;

        prevBtn.disabled = index === 0;
        nextBtn.style.display = index === questions.length - 1 ? 'none' : 'block';
        submitBtn.style.display = index === questions.length - 1 ? 'block' : 'none';
    }

    nextBtn.addEventListener('click', () => {
        if (currentQuestionIndex < questions.length - 1) {
            showQuestion(currentQuestionIndex + 1);
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentQuestionIndex > 0) {
            showQuestion(currentQuestionIndex - 1);
        }
    });

    // Timer
    @if($quiz->time_limit_minutes)
        const timerDisplay = document.getElementById('timer');
        const timerContainer = document.getElementById('timer-display');
        let timeLeft = {{ $quiz->time_limit_minutes * 60 }};

        const timerInterval = setInterval(() => {
            const minutes = Math.floor(timeLeft / 60);
            let seconds = timeLeft % 60;
            seconds = seconds < 10 ? '0' + seconds : seconds;

            timerDisplay.textContent = `${minutes}:${seconds}`;
            
            if (timeLeft <= 60 && !timerContainer.classList.contains('timer-warning')) {
                 timerContainer.classList.remove('badge-primary');
                 timerContainer.classList.add('badge-warning', 'timer-warning');
            }

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                document.getElementById('quiz-form').submit();
            }
            timeLeft--;
        }, 1000);
    @endif

    showQuestion(0);
    });
</script>
@endpush
