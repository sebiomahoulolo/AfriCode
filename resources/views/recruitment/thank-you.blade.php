@extends('layouts.layout')

@section('title', 'Merci pour votre candidature – AfriCode')

@section('content')
<style>
    .thanks-hero { background: linear-gradient(135deg, #1EA38B 0%, #0d7a63 50%, #27B371 100%); color: #fff; padding: 3rem 0 3rem; margin-top: 56px; }
    .thanks-hero .text-gradient { background: linear-gradient(90deg, #FF8E2A, #E32D31); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .thanks-content { max-width: 960px; margin: 0 auto; text-align: center; }
    .thanks-icon { font-size: 3rem; margin-bottom: 0.75rem; }
    .thanks-title-desc { margin-bottom: 2rem; }
    .thanks-cards-row { display: flex; flex-wrap: wrap; gap: 1.25rem; justify-content: center; margin-bottom: 2rem; }
    .thanks-card { flex: 1 1 320px; max-width: 460px; min-height: 280px; border-radius: 16px; padding: 1.5rem 1.75rem; text-align: left; box-shadow: 0 4px 20px rgba(0,0,0,0.12); }
    .thanks-card-retenir { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #fff; }
    .thanks-card-retenir h6 { color: #fff; font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; }
    .thanks-card-retenir ul { margin: 0; padding-left: 1.25rem; font-size: 0.95rem; line-height: 1.7; color: rgba(255,255,255,0.95); }
    .thanks-card-retenir li { margin-bottom: 0.5rem; }
    .thanks-card-cours { background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border: 1px solid #a7f3d0; color: #1e293b; }
    .thanks-card-cours h6 { color: #1e293b; font-size: 1.1rem; font-weight: 700; margin-bottom: 0.75rem; }
    .thanks-card-cours p { font-size: 0.95rem; line-height: 1.65; color: #475569; margin-bottom: 1.25rem; }
    .btn-back-recruit { background: #FF8E2A; color: #fff; border: none; font-weight: 600; padding: 0.65rem 1.5rem; border-radius: 8px; }
    .btn-back-recruit:hover { background: #e67e22; color: #fff; }
    .btn-cours-notif { background: linear-gradient(135deg, #27B371 0%, #1EA38B 100%); color: #fff; border: none; font-weight: 600; padding: 0.65rem 1.5rem; border-radius: 8px; }
    .btn-cours-notif:hover { background: linear-gradient(135deg, #1EA38B 0%, #158f7a 100%); color: #fff; }
    .thanks-result-box { background: rgba(39, 179, 113, 0.25); border: 2px solid rgba(39, 179, 113, 0.6); border-radius: 16px; color: #fff; padding: 1.25rem 1.75rem; margin-bottom: 2rem; display: inline-block; }
    .thanks-result-box .result-value { font-size: 2rem; font-weight: 800; }
    .thanks-result-box .result-label { font-size: 0.95rem; opacity: 0.95; }
    @media (min-width: 992px) {
        .thanks-cards-row { flex-wrap: nowrap; justify-content: center; }
        .thanks-card { flex: 0 1 50%; max-width: none; }
    }
</style>

<section class="thanks-hero">
    <div class="container">
        <div class="thanks-content">
            @if(session('test_score') !== null)
            <div class="thanks-result-box">
                <div class="result-value">{{ session('test_score') }}/10</div>
                <div class="result-label">Résultat du test QCM · {{ session('test_position_title') }}</div>
                <div class="result-label mt-1">Niveau : {{ ucfirst(session('test_score_level', '—')) }}</div>
            </div>
            @endif
            <h1 class="h2 fw-bold mb-2 thanks-title-desc">Merci pour votre <span class="text-gradient">candidature</span></h1>
            <p class="lead mb-0 opacity-90" style="font-size: 1.05rem;">Nous avons bien reçu votre dossier. Un accusé de réception a été envoyé à votre adresse email.</p>
            <br>
            <div class="thanks-cards-row">
                <div class="thanks-card thanks-card-retenir">
                    <h6> À retenir</h6>
                    <ul>
                        <li>Les candidats présélectionnés après le test QCM seront contactés pour la suite du processus.</li>
                        <li>Seuls les candidats retenus seront contactés.</li>
                        <li>Résider à Cocotomey, Cotonou, Calavi ou Cococodji est un atout.</li>
                    </ul>
                        <a href="{{ route('recruitment.index') }}" class="btn " style="background-color: white ; color: black;">
                            <i class="fas fa-arrow-left me-2"  ></i> Retour aux offres
                        </a>
                </div>
                <div class="thanks-card thanks-card-cours">
                    <h6> Les cours sont disponibles sur AfriCode</h6>
                    <p class="mb-0">Renforcez votre niveau dans tous les domaines du numérique : développement web, cybersécurité, intelligence artificielle, data, et bien plus.</p>
                    <div class="mt-3">
                        <a href="{{ route('courses.index') }}" class="btn btn-cours-notif" style="color: white">
                            <i class="fas fa-book me-2"></i> Voir les cours
                        </a>
                        <a href="{{ url('/') }}" class="btn btn-outline-light" style="background-color: #158f7a; color: white">Accueil AfriCode</a>

                    </div>

                </div>
            </div>


        </div>
    </div>
</section>
@endsection
