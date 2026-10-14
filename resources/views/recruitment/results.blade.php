@extends('layouts.layout')

@section('title', 'Résultats du test – AfriCode')

@section('content')
<style>
    .results-hero { background: linear-gradient(135deg, #1EA38B 0%, #0d7a63 50%, #27B371 100%); color: #fff; padding: 2.5rem 0 2rem; margin-top: 56px; }
    .results-hero .text-gradient { background: linear-gradient(90deg, #FF8E2A, #E32D31); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .results-hero .score-in-hero { background: rgba(39, 179, 113, 0.25); border: 2px solid rgba(39, 179, 113, 0.6); color: #fff; }
    .results-container { max-width: 720px; margin: 0 auto; padding: 2rem 0 3rem; }
    .btn-thanks { background: linear-gradient(135deg, #FF8E2A 0%, #E32D31 100%); color: #fff; border: none; font-weight: 600; padding: 0.75rem 2rem; border-radius: 8px; }
    .btn-thanks:hover { color: #fff; opacity: 0.95; }
</style>

<section class="results-hero">
    <div class="container text-center">
        <h1 class="h3 fw-bold mb-2">Merci pour votre <span class="text-gradient">candidature</span></h1>
        <p class="mb-4 opacity-90">Nous avons bien reçu votre dossier. Un accusé de réception a été envoyé à votre adresse email.</p>
        <div class="score-in-hero rounded-3 d-inline-block px-4 py-3">
            <div class="score-value mb-0" style="font-size: 2.25rem;">{{ $application->test_score }}/10</div>
            <div class="score-label small opacity-95">Niveau : {{ ucfirst($application->getScoreLevel()) }}</div>
        </div>
        <p class="mt-3 mb-0 small opacity-90">Poste : {{ $position->title }}</p>
    </div>
</section>

<section class="results-container">
    <div class="container">
        <div class="alert" style="background:rgba(30,163,139,.1);border:1px solid #1EA38B;color:#0d5e50;border-radius:8px;">
            <strong> Rappel :</strong> Les candidats présélectionnés après le test QCM seront contactés pour la suite. Un accusé de réception a été envoyé à votre adresse email.
        </div>

        <div class="thanks-section mt-5 pt-5 border-top">
            <div class="thanks-box rounded-3 p-4 mb-4" style="background: #f8fafc; border-left: 4px solid #FF8E2A;">
                <h6 class="fw-bold mb-3" style="color: #1e293b;"> À retenir</h6>
                <ul class="mb-0 ps-3" style="color: #475569;">
                    <li>Les candidats présélectionnés après le test QCM seront contactés pour la suite du processus.</li>
                    <li>Seuls les candidats retenus seront contactés.</li>
                    <li>Résider à Cocotomey, Cotonou, Calavi ou Cococodji est un atout.</li>
                </ul>
            </div>
            <p class="text-center small text-muted mb-3"><i class="fas fa-globe me-1"></i><a href="https://www.africode.tech" style="color: #FF8E2A;">www.africode.tech</a> · <i class="fas fa-envelope me-1"></i><a href="mailto:contact@africode.tech" style="color: #FF8E2A;">contact@africode.tech</a> · <i class="fas fa-map-marker-alt me-1"></i>Cotonou, Bénin</p>
            <div class="text-center">
                <a href="{{ route('recruitment.index') }}" class="btn btn-thanks">
                    <i class="fas fa-arrow-left me-2"></i> Retour aux offres
                </a>
                <a href="{{ url('/') }}" class="btn btn-outline-secondary ms-2">Accueil AfriCode</a>
            </div>
        </div>
    </div>
</section>
@endsection
