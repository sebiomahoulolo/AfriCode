@extends('layouts.layout')

@section('title', 'Participer à la compétition')

@section('content')
<div class="container mt-5">
    <div class="competition-header">
        <h1><i class="fas fa-trophy me-2"></i>Compétition : {{ $competition->title }}</h1>
        <p>{{ $competition->description }}</p>
    </div>
    <div class="alert alert-info mt-4">
        <i class="fas fa-info-circle"></i> <b>Zone de participation à la compétition</b> (à personnaliser selon le type d’épreuve, quiz, soumission, etc.)
    </div>
    <div class="card mt-4">
        <div class="card-header">Quiz d'exemple</div>
        <div class="card-body">
            <form method="POST" action="#">
                @csrf
                <div class="mb-3">
                    <label>1. Quelle est la capitale du Cameroun ?</label><br>
                    <input type="radio" name="q1" value="Yaoundé"> Yaoundé<br>
                    <input type="radio" name="q1" value="Douala"> Douala<br>
                    <input type="radio" name="q1" value="Libreville"> Libreville<br>
                </div>
                <div class="mb-3">
                    <label>2. Combien font 12 x 12 ?</label><br>
                    <input type="radio" name="q2" value="124"> 124<br>
                    <input type="radio" name="q2" value="144"> 144<br>
                    <input type="radio" name="q2" value="132"> 132<br>
                </div>
                <button type="submit" class="btn btn-primary">Soumettre le quiz</button>
            </form>
        </div>
    </div>
    @if(isset($confirmation) && $confirmation)
        <div class="alert alert-success mt-4">
            <i class="fas fa-check-circle"></i> Projet soumis avec succès !
        </div>
        @php
            $registration = \App\Models\CompetitionRegistration::where('competition_id', $competition->id)->where('user_id', auth()->id())->first();
            $submission = $registration ? \App\Models\CompetitionSubmission::where('registration_id', $registration->id)->first() : null;
        @endphp
        @if($submission && ($submission->score !== null || $submission->feedback_from_judges))
            <div class="alert alert-info mt-4">
                <i class="fas fa-star"></i> <b>Score :</b> {{ $submission->score ?? 'Non noté' }}<br>
                <i class="fas fa-comments"></i> <b>Feedback du jury :</b> {{ $submission->feedback_from_judges ?? 'Aucun feedback pour le moment.' }}
            </div>
        @endif
    @endif
    <div class="card mt-4">
        <div class="card-header">Soumission de projet</div>
        <div class="card-body">
            <form method="POST" action="">
                @csrf
                <div class="mb-3">
                    <label for="project_title">Titre du projet</label>
                    <input type="text" name="project_title" id="project_title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="project_description">Description</label>
                    <textarea name="project_description" id="project_description" class="form-control" rows="4" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="project_link_repository">Lien du dépôt (GitHub, GitLab...)</label>
                    <input type="url" name="project_link_repository" id="project_link_repository" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="project_link_live">Lien du projet en ligne (optionnel)</label>
                    <input type="url" name="project_link_live" id="project_link_live" class="form-control">
                </div>
                <button type="submit" class="btn btn-success">Soumettre le projet</button>
            </form>
        </div>
    </div>
    <a href="{{ route('competitions.show', $competition->slug) }}" class="btn btn-link mt-3"><i class="fas fa-arrow-left"></i> Retour à la compétition</a>
</div>
@endsection 