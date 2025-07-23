@extends('apprenants.layouts.app')

@section('title', 'AfriCode')
@section('page-title', 'Résultats: ' . $quiz->title)

@push('styles')
{{-- J'ajoute quelques styles pour améliorer l'apparence et la cohérence --}}
<style>
    .result-summary-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 2rem;
        text-align: center;
    }
    .score-circle {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }
    .score-circle.passed { background-color: rgba(30, 163, 139, 0.1); }
    .score-circle.failed { background-color: rgba(220, 53, 69, 0.1); }
    .score-circle i { font-size: 3rem; }
    .score-circle.passed i { color: #1EA38B; }
    .score-circle.failed i { color: #dc3545; }

    .score-percent { font-size: 2.5rem; font-weight: bold; }
    .score-percent.passed { color: #1EA38B; }
    .score-percent.failed { color: #dc3545; }

    .result-stat {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
    }
    .result-stat-number { font-size: 1.5rem; font-weight: bold; color: #343a40;}
    .result-stat-label { font-size: 0.9rem; color: #6c757d; }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <!-- Score Summary -->
            <div class="result-summary-card mb-4" data-aos="fade-up">
                @php $passed = $attempt->score >= $quiz->passing_score; @endphp

                <div class="score-circle {{ $passed ? 'passed' : 'failed' }}">
                    <i class="fas {{ $passed ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                </div>
                
                <h3 class="score-percent {{ $passed ? 'passed' : 'failed' }}">
                    {{ round($attempt->score) }}%
                </h3>
                
                <p class="h5 mb-4">
                    @if($passed)
                        🎉 Félicitations ! Quiz réussi
                    @else
                        Quiz échoué
                    @endif
                </p>
                
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="result-stat">
                            <div class="result-stat-number text-success">{{ $correctAnswers }}</div>
                            <div class="result-stat-label">Réponses correctes</div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="result-stat">
                            <div class="result-stat-number">{{ $totalQuestions }}</div>
                            <div class="result-stat-label">Total questions</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="result-stat">
                            <div class="result-stat-number">{{ gmdate('i:s', $attempt->time_taken ?? 0) }}</div>
                            <div class="result-stat-label">Temps utilisé</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Results -->
            <div class="card africode-card mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card-body">
                    <h3 class="card-title mb-4">Détail des réponses</h3>
                    @foreach($questionResults as $index => $result)
                        @php
                            $question = $result['question'];
                            $userAnswersForQuestion = $result['user_answers'];
                            $correctAnswersForQuestion = $result['correct_answers'];
                            $isCorrect = $result['is_correct'];
                            $selectedAnswerIds = $userAnswersForQuestion->pluck('answer_id');
                        @endphp
                        
                        <div class="border-left-4 p-3 mb-3 rounded {{ $isCorrect ? 'border-success bg-success-light' : 'border-danger bg-danger-light' }}">
                            <h6 class="font-weight-bold">Question {{ $index + 1 }}: {{ $question->text }}</h6>
                            <div class="space-y-2 mt-3">
                                @foreach($question->answers as $answer)
                                    @php
                                        $isSelected = $selectedAnswerIds->contains($answer->id);
                                        $isCorrectAnswer = $correctAnswersForQuestion->contains('id', $answer->id);
                                    @endphp
                                    <div class="d-flex align-items-center text-sm p-2 rounded
                                        @if($isSelected && $isCorrectAnswer) 
                                            bg-success-light border border-success
                                        @elseif($isSelected && !$isCorrectAnswer) 
                                            bg-danger-light border border-danger
                                        @elseif($isCorrectAnswer) 
                                            bg-success-light
                                        @else 
                                            bg-light
                                        @endif">
                                        
                                        @if($isSelected)
                                            <i class="fas fa-dot-circle mr-2 {{ $isCorrectAnswer ? 'text-success' : 'text-danger' }}"></i>
                                        @else
                                            <i class="far fa-circle mr-2 text-muted"></i>
                                        @endif

                                        <span class="flex-grow-1">{{ $answer->text }}</span>

                                        @if($isCorrectAnswer)
                                            <span class="ml-2 text-xs font-weight-bold text-success">(Bonne réponse)</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Actions -->
            <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                @if(!$passed)
                    @if($quiz->canBeAttemptedByUser($user->id))
                        <a href="{{ route('apprenant.quiz.show', $quiz->id) }}" 
                           class="btn btn-primary btn-lg mx-2">
                           <i class="fas fa-redo-alt me-2"></i>Retenter le quiz
                        </a>
                        @if($quiz->isCourseQuiz() && $quiz->max_attempts > 0)
                            <div class="text-muted mt-2">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    Il vous reste {{ $quiz->getRemainingAttempts($user->id) }} tentative(s)
                                </small>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Vous avez atteint le nombre maximum de tentatives pour ce quiz.
                        </div>
                    @endif
                @endif

                @if($passed)
                    @if($quiz->isModuleQuiz())
                        <a href="{{ route($backRoute, $backId) }}" 
                           class="btn btn-success btn-lg mx-2">
                            <i class="fas fa-arrow-right me-2"></i>
                            Continuer vers le module suivant
                        </a>
                    @elseif($quiz->isCourseQuiz() && $certification)
                        <a href="{{ route($backRoute, $backId) }}" 
                           class="btn btn-success btn-lg mx-2">
                            <i class="fas fa-certificate me-2"></i>
                            Voir mon certificat
                        </a>
                    @else
                        <a href="{{ route($backRoute, $backId) }}" 
                           class="btn btn-success btn-lg mx-2">
                            <i class="fas fa-check-circle me-2"></i>
                            Terminer le cours
                        </a>
                    @endif
                @else
                    <a href="{{ route($backRoute, $backId) }}" 
                       class="btn btn-outline-secondary btn-lg mx-2">
                        <i class="fas fa-book-reader me-2"></i>
                        Retourner au cours
                    </a>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
