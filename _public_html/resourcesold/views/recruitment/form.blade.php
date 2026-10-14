@extends('layouts.layout')

@section('title', 'Candidater – ' . $position->title . ' – AfriCode')

@section('content')
<style>
    .recruit-form-hero { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #fff; padding: 3rem 0 2rem; margin-top: 56px; }
    .recruit-form-hero .text-gradient { background: linear-gradient(90deg, #fea837, #fa1c39); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .form-recruit-container { max-width: 720px; margin: 0 auto; padding: 2rem 0 3rem; }
    .form-recruit-container .form-control, .form-recruit-container .form-select { border-radius: 8px; border: 1px solid #e2e8f0; }
    .form-recruit-container .form-control:focus { border-color: #fea837; box-shadow: 0 0 0 3px rgba(254, 168, 55, 0.15); }
    .btn-submit-recruit { background: linear-gradient(135deg, #239e79 0%, #11c06d 100%); color: #fff; border: none; font-weight: 600; padding: 0.75rem 2rem; border-radius: 8px; }
    .btn-submit-recruit:hover { background: linear-gradient(135deg, #11c06d 0%, #158f7a 100%); color: #fff; }
    .offer-source-other { display: none; }
    .offer-source-other.show { display: block; }
    .projet-description-wrap { display: none; }
    .projet-description-wrap.show { display: block; }
</style>

<section class="recruit-form-hero">
    <div class="container text-center">
        <h1 class="h3 fw-bold mb-2">Candidature – <span class="text-gradient">{{ $position->title }}</span></h1>
        <p class="mb-0 opacity-90">Remplissez le formulaire et joignez votre dossier. Vous passerez ensuite au test QCM.</p>
    </div>
</section>

<section class="form-recruit-container">
    <div class="container">
        <form action="{{ route('recruitment.store', $position->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold" style="color: #1e293b;">Informations personnelles</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="nom_prenoms" class="form-label">Nom & Prénoms <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nom_prenoms" name="nom_prenoms" value="{{ old('nom_prenoms') }}" required>
                            @error('nom_prenoms')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Sexe <span class="text-danger">*</span></label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sexe" id="sexe_feminin" value="feminin" {{ old('sexe') === 'feminin' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="sexe_feminin">Féminin</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sexe" id="sexe_masculin" value="masculin" {{ old('sexe') === 'masculin' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sexe_masculin">Masculin</label>
                                </div>
                            </div>
                            @error('sexe')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="telephone" name="telephone" value="{{ old('telephone') }}" required>
                            @error('telephone')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="date_naissance" class="form-label">Date de naissance <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="date_naissance" name="date_naissance" value="{{ old('date_naissance') }}" required>
                            @error('date_naissance')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="adresse" class="form-label">Adresse <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="adresse" name="adresse" value="{{ old('adresse') }}" placeholder="Ville, quartier" required>
                            @error('adresse')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="niveau_etudes" class="form-label">Niveau d'études <span class="text-danger">*</span></label>
                            <select class="form-select" id="niveau_etudes" name="niveau_etudes" required>
                                <option value="">Choisir...</option>
                                <option value="Bac" {{ old('niveau_etudes') === 'Bac' ? 'selected' : '' }}>Baccalauréat</option>
                                <option value="Bac+2" {{ old('niveau_etudes') === 'Bac+2' ? 'selected' : '' }}>Bac+2 (DUT, BTS, etc.)</option>
                                <option value="Bac+3" {{ old('niveau_etudes') === 'Bac+3' ? 'selected' : '' }}>Bac+3 (Licence)</option>
                                <option value="Bac+5" {{ old('niveau_etudes') === 'Bac+5' ? 'selected' : '' }}>Bac+5 (Master)</option>
                                <option value="Autre" {{ old('niveau_etudes') === 'Autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('niveau_etudes')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold" style="color: #1e293b;">Où avez-vous vu l'offre AfriCode ? <span class="text-danger">*</span></h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        @foreach(['facebook' => 'Facebook', 'instagram' => 'Instagram', 'tiktok' => 'TikTok', 'whatsapp' => 'WhatsApp', 'site_web' => 'Site web (www.africode.tech)', 'recommandation' => "Recommandation d'un ami", 'autre' => 'Autre'] as $value => $label)
                        <div class="col-12 col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="offer_source" id="offer_{{ $value }}" value="{{ $value }}" {{ old('offer_source') === $value ? 'checked' : '' }}>
                                <label class="form-check-label" for="offer_{{ $value }}">{{ $label }}</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-3 offer-source-other {{ old('offer_source') === 'autre' ? 'show' : '' }}" id="offer_source_other_wrap">
                        <label for="offer_source_other" class="form-label">Précisez</label>
                        <input type="text" class="form-control" id="offer_source_other" name="offer_source_other" value="{{ old('offer_source_other') }}" placeholder="Ex: LinkedIn, affiche...">
                        @error('offer_source_other')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            @if($position->slug === 'developpeur-fullstack')
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold" style="color: #1e293b;">Compétences techniques</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Technologie principale maîtrisée</label>
                        <div class="d-flex flex-wrap gap-3">
                            @foreach(['laravel' => 'Laravel', 'react' => 'React', 'python' => 'Python', 'angular' => 'Angular', 'wordpress' => 'WordPress'] as $val => $lbl)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="form_technologie_principale" id="tech_{{ $val }}" value="{{ $val }}" {{ old('form_technologie_principale') === $val ? 'checked' : '' }}>
                                <label class="form-check-label" for="tech_{{ $val }}">{{ $lbl }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="form_autres_technologies" class="form-label">Autres technologies connues</label>
                        <input type="text" class="form-control" id="form_autres_technologies" name="form_autres_technologies" value="{{ old('form_autres_technologies') }}" placeholder="HTML, CSS, MySQL...">
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="form_lien_github" class="form-label">Lien GitHub</label>
                            <input type="url" class="form-control" id="form_lien_github" name="form_lien_github" value="{{ old('form_lien_github') }}" placeholder="https://github.com/...">
                        </div>
                        <div class="col-md-6">
                            <label for="form_lien_portfolio" class="form-label">Lien Portfolio</label>
                            <input type="url" class="form-control" id="form_lien_portfolio" name="form_lien_portfolio" value="{{ old('form_lien_portfolio') }}" placeholder="https://...">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="form_annees_experience" class="form-label">Nombre d'années d'expérience</label>
                        <input type="text" class="form-control" id="form_annees_experience" name="form_annees_experience" value="{{ old('form_annees_experience') }}" placeholder="Ex: 2 ans">
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Avez-vous déjà travaillé sur un projet réel ?</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="form_projet_reel" id="projet_oui" value="oui" {{ old('form_projet_reel') === 'oui' ? 'checked' : '' }}>
                                <label class="form-check-label" for="projet_oui">Oui</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="form_projet_reel" id="projet_non" value="non" {{ old('form_projet_reel') === 'non' ? 'checked' : '' }}>
                                <label class="form-check-label" for="projet_non">Non</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 projet-description-wrap {{ old('form_projet_reel') === 'oui' ? 'show' : '' }}" id="projet_description_wrap">
                        <label for="form_description_projet" class="form-label">Décrivez brièvement un projet réalisé</label>
                        <textarea class="form-control" id="form_description_projet" name="form_description_projet" rows="3" placeholder="Description du projet...">{{ old('form_description_projet') }}</textarea>
                    </div>
                </div>
            </div>
            @endif

            @if($position->slug === 'community-manager')
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold" style="color: #1e293b;">Portfolio / Pages gérées</h5>
                </div>
                <div class="card-body">
                    <label for="form_portfolio_liens" class="form-label">Portfolio ou liens des pages déjà gérées</label>
                    <textarea class="form-control" id="form_portfolio_liens" name="form_portfolio_liens" rows="3" placeholder="URLs des pages ou portfolio...">{{ old('form_portfolio_liens') }}</textarea>
                </div>
            </div>
            @endif

            @if($position->slug === 'monteur-video')
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold" style="color: #1e293b;">Portfolio / Réalisations</h5>
                </div>
                <div class="card-body">
                    <label for="form_portfolio_realisations" class="form-label">Portfolio ou réalisations (liens)</label>
                    <textarea class="form-control" id="form_portfolio_realisations" name="form_portfolio_realisations" rows="3" placeholder="Liens vers vos montages...">{{ old('form_portfolio_realisations') }}</textarea>
                </div>
            </div>
            @endif

            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold" style="color: #1e293b;">Documents à fournir</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3"> Tous les documents sont obligatoires. Max. 5 Mo par fichier.</p>
                    <div class="mb-3">
                        <label for="cv" class="form-label">CV actualisé (PDF) <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="cv" name="cv" accept=".pdf,application/pdf" required>
                        @error('cv')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="lettre_motivation" class="form-label">Lettre de motivation adressée au Directeur Général de AFRICODE (PDF) <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="lettre_motivation" name="lettre_motivation" accept=".pdf,application/pdf" required>
                        @error('lettre_motivation')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="diplome" class="form-label">Copie du diplôme ou attestation (PDF) <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="diplome" name="diplome" accept=".pdf,application/pdf" required>
                        @error('diplome')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <a href="{{ route('recruitment.index') }}" class="btn btn-outline-secondary">Retour aux offres</a>
                <button type="submit" class="btn btn-submit-recruit">
                    <i class="fas fa-arrow-right me-2"></i> Enregistrer et passer au test QCM
                </button>
            </div>
        </form>
    </div>
</section>

@push('scripts')
<script>
document.querySelectorAll('input[name="offer_source"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.getElementById('offer_source_other_wrap').classList.toggle('show', this.value === 'autre');
    });
});
var projetOui = document.getElementById('projet_oui');
var projetNon = document.getElementById('projet_non');
var projetDescWrap = document.getElementById('projet_description_wrap');
if (projetOui && projetNon && projetDescWrap) {
    function toggleProjetDescription() {
        var textarea = projetDescWrap.querySelector('#form_description_projet');
        projetDescWrap.classList.toggle('show', projetOui.checked);
        if (projetOui.checked) {
            textarea.setAttribute('required', 'required');
        } else {
            textarea.removeAttribute('required');
            textarea.value = '';
        }
    }
    projetOui.addEventListener('change', toggleProjetDescription);
    projetNon.addEventListener('change', toggleProjetDescription);
    toggleProjetDescription();
}
</script>
@endpush
@endsection
