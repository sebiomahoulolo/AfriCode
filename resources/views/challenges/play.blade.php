@extends('layouts.layout')

@section('title', 'Participer au défi')

@section('content')
<div class="container mt-5">
    <div class="competition-header">
        <h1><i class="fas fa-code me-2"></i>Défi : {{ $challenge->name }}</h1>
        <p>{{ $challenge->description }}</p>
    </div>
    <div class="alert alert-info mt-4">
        <i class="fas fa-info-circle"></i> <b>Zone de participation au défi</b> (à personnaliser selon le type de défi, quiz, soumission, etc.)
    </div>
    @if(isset($submitted) && $submitted)
        <div class="alert alert-success mt-4">
            <i class="fas fa-check-circle"></i> Quiz soumis !<br>
            Votre score : <b>{{ $score }}/{{ $questions->count() }}</b>
        </div>
    @endif
    @if(isset($questions) && $questions->count())
        <div class="card mt-4">
            <div class="card-header">Quiz du défi</div>
            <div class="card-body">
                <form method="POST" action="" @if($submitted) style="pointer-events:none;opacity:0.7;" @endif>
                    @csrf
                    @foreach($questions as $q)
                        <div class="mb-3">
                            <label><b>{{ $loop->iteration }}. {{ $q->question_text }}</b></label><br>
                            @foreach($q->options as $opt)
                                @php
                                    $checked = isset($userAnswers[$q->id]) && in_array($opt->id, (array)$userAnswers[$q->id]);
                                    $isCorrect = $opt->is_correct;
                                @endphp
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="answers[{{ $q->id }}][]" value="{{ $opt->id }}" id="q{{ $q->id }}_opt{{ $opt->id }}" @if($checked) checked @endif @if($submitted) disabled @endif>
                                    <label class="form-check-label @if($submitted && $isCorrect) text-success fw-bold @elseif($submitted && $checked && !$isCorrect) text-danger fw-bold @endif" for="q{{ $q->id }}_opt{{ $opt->id }}">
                                        {{ $opt->option_text }}
                                        @if($submitted && $isCorrect)
                                            <i class="fas fa-check-circle"></i>
                                        @elseif($submitted && $checked && !$isCorrect)
                                            <i class="fas fa-times-circle"></i>
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                    @if(!$submitted)
                        <button type="submit" class="btn btn-primary">Soumettre le quiz</button>
                    @endif
                </form>
            </div>
        </div>
    @endif
    <br>
    {{-- <div class="card mt-4">
        <div class="card-header">Soumission de code ou texte</div>
        <div class="card-body">
            <form method="POST" action="#">
                @csrf
                <div class="mb-3">
                    <label for="code">Votre code ou réponse :</label>
                    <textarea name="code" id="code" class="form-control" rows="5" placeholder="Collez votre code ou réponse ici..."></textarea>
                </div>
                <button type="submit" class="btn btn-success">Soumettre</button>
            </form>
        </div>
    </div>
    <a href="{{ route('challenges.show', $challenge->id) }}" class="btn btn-link mt-3"><i class="fas fa-arrow-left"></i> Retour au défi</a>
</div> --}}
@endsection 