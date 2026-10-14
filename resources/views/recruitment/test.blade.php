@extends('layouts.layout')

@section('title', 'Test QCM – ' . $position->title . ' – AfriCode')

@section('content')
<style>
    .test-hero { background: linear-gradient(135deg, #1EA38B 0%, #0d7a63 50%, #27B371 100%); color: #fff; padding: 2.5rem 0 2rem; margin-top: 56px; }
    .test-hero .text-gradient { background: linear-gradient(90deg, #FF8E2A, #E32D31); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .test-container { max-width: 720px; margin: 0 auto; padding: 2rem 0 3rem; }
    .question-block { background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); padding: 1.5rem; margin-bottom: 1.25rem; border: 1px solid #e2e8f0; }
    .question-block h6 { color: #1e293b; font-weight: 600; margin-bottom: 1rem; }
    .form-check-input:checked { background-color: #27B371; border-color: #27B371; }
    .form-check-label { cursor: pointer; }
    .question-block.locked { pointer-events: none; opacity: 0.95; }
    .question-block.locked .form-check { cursor: default; }
    .option-feedback { display: none; font-size: 0.9rem; font-weight: 600; margin-left: 0.5rem; }
    .question-block.locked .option-feedback.visible { display: inline; }
    .option-feedback.correct { color: #27B371; }
    .option-feedback.incorrect { color: #E32D31; }
    .btn-submit-test { background: linear-gradient(135deg, #27B371 0%, #1EA38B 100%); color: #fff; border: none; font-weight: 600; padding: 0.75rem 2rem; border-radius: 8px; }
    .btn-submit-test:hover { background: linear-gradient(135deg, #1EA38B 0%, #158f7a 100%); color: #fff; }
</style>

<section class="test-hero">
    <div class="container text-center">
        <h1 class="h4 fw-bold mb-2">Test QCM – <span class="text-gradient">{{ $position->title }}</span></h1>
        <p class="mb-0 opacity-90">Répondez aux {{ $questions->count() }} questions. Une seule réponse correcte par question.</p>
    </div>
</section>

<section class="test-container">
    <div class="container">
        <form action="{{ route('recruitment.submit-test', [$position->slug, $application->token]) }}" method="POST" id="testForm">
            @csrf
            @foreach($questions as $index => $question)
            <div class="question-block" data-correct-option="{{ $question->correct_option }}">
                <h6 class="mb-3">{{ $index + 1 }}. {{ $question->question_text }}</h6>
                <div class="options-wrap d-flex flex-column gap-2">
                    @foreach($question->shuffled_option_letters as $letter)
                        @php $text = $question->getOption($letter); @endphp
                        @if($text)
                        <div class="form-check border rounded p-2 option-row" data-option="{{ $letter }}">
                            <input class="form-check-input" type="radio" name="q{{ $question->id }}" id="q{{ $question->id }}_{{ $letter }}" value="{{ $letter }}" {{ old('q'.$question->id) === $letter ? 'checked' : '' }}>
                            <label class="form-check-label w-100 d-flex align-items-center flex-wrap" for="q{{ $question->id }}_{{ $letter }}">
                                <span><strong>{{ $letter }})</strong> {{ $text }}</span>
                                <span class="option-feedback correct" aria-hidden="true"><i class="fas fa-check-circle me-1"></i>Bonne réponse</span>
                                <span class="option-feedback incorrect" aria-hidden="true"><i class="fas fa-times-circle me-1"></i>Faux</span>
                            </label>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endforeach

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4">
                <a href="{{ route('recruitment.form', $position->slug) }}" class="btn btn-outline-secondary">Retour au formulaire</a>
                <button type="submit" class="btn btn-submit-test">
                    <i class="fas fa-paper-plane me-2"></i> Envoyer mes réponses
                </button>
            </div>
        </form>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.question-block').forEach(function(block) {
        var radios = block.querySelectorAll('input[type="radio"]');
        var correctOption = block.getAttribute('data-correct-option');
        if (!correctOption) return;

        function lockAndReveal() {
            if (block.classList.contains('locked')) return;
            var selected = block.querySelector('input[type="radio"]:checked');
            var selectedVal = selected ? selected.value : null;
            block.classList.add('locked');
            block.querySelectorAll('.option-row').forEach(function(row) {
                var letter = row.getAttribute('data-option');
                var correctSpan = row.querySelector('.option-feedback.correct');
                var incorrectSpan = row.querySelector('.option-feedback.incorrect');
                if (!letter || !correctSpan || !incorrectSpan) return;
                correctSpan.classList.remove('visible');
                incorrectSpan.classList.remove('visible');
                if (letter === correctOption) {
                    correctSpan.classList.add('visible');
                }
                if (selectedVal && letter === selectedVal && letter !== correctOption) {
                    incorrectSpan.classList.add('visible');
                }
            });
        }

        radios.forEach(function(radio) {
            radio.addEventListener('change', lockAndReveal);
        });
    });
});
</script>
@endpush
@endsection
