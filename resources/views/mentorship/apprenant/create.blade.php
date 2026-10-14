@extends('apprenants.layouts.app')

@section('title', 'Demander un Mentorat — AfriCode')
@section('page-title', 'Demander un Mentorat')

@section('content')
<div class="container-fluid py-4">

    <!-- Action Bar -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <p class="text-muted mb-0">Trouvez le mentor parfait pour vous accompagner dans votre apprentissage</p>
        <a href="{{ route('mentorship.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <div class="row">
        <!-- Form -->
        <div class="col-lg-8">
            <div class="card border-0" style="box-shadow:0 2px 10px rgba(0,0,0,.06);border-radius:.5rem;">
                <div class="card-header" style="background:#f8f9fa;border-bottom:1px solid #dee2e6;border-radius:.5rem .5rem 0 0;">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>Formulaire de Demande
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('mentorship.store') }}">
                        @csrf

                        <!-- Mentor Selection -->
                        <div class="mb-4">
                            <label for="mentor_id" class="form-label">
                                <i class="fas fa-user-tie me-2"></i>Choisir un Mentor
                            </label>
                            <select class="form-select @error('mentor_id') is-invalid @enderror" id="mentor_id" name="mentor_id" required>
                                <option value="">Sélectionnez un mentor...</option>
                                @foreach($mentors as $mentor)
                                    <option value="{{ $mentor->id }}" {{ old('mentor_id') == $mentor->id ? 'selected' : '' }}>
                                        {{ $mentor->first_name }} {{ $mentor->last_name }}
                                        ({{ $mentor->coursesInstructed->count() }} cours)
                                    </option>
                                @endforeach
                            </select>
                            @error('mentor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Specialization Area -->
                        <div class="mb-4">
                            <label for="specialization_area" class="form-label">
                                <i class="fas fa-code me-2"></i>Domaine de Spécialisation
                            </label>
                            <select class="form-select @error('specialization_area') is-invalid @enderror" id="specialization_area" name="specialization_area" required>
                                <option value="">Sélectionnez un domaine...</option>
                                <option value="Développement Web Frontend" {{ old('specialization_area') == 'Développement Web Frontend' ? 'selected' : '' }}>Développement Web Frontend</option>
                                <option value="Développement Web Backend" {{ old('specialization_area') == 'Développement Web Backend' ? 'selected' : '' }}>Développement Web Backend</option>
                                <option value="Développement Mobile" {{ old('specialization_area') == 'Développement Mobile' ? 'selected' : '' }}>Développement Mobile</option>
                                <option value="Data Science" {{ old('specialization_area') == 'Data Science' ? 'selected' : '' }}>Data Science</option>
                                <option value="Machine Learning" {{ old('specialization_area') == 'Machine Learning' ? 'selected' : '' }}>Machine Learning</option>
                                <option value="DevOps" {{ old('specialization_area') == 'DevOps' ? 'selected' : '' }}>DevOps</option>
                                <option value="Cybersécurité" {{ old('specialization_area') == 'Cybersécurité' ? 'selected' : '' }}>Cybersécurité</option>
                                <option value="UI/UX Design" {{ old('specialization_area') == 'UI/UX Design' ? 'selected' : '' }}>UI/UX Design</option>
                                <option value="Gestion de Projet" {{ old('specialization_area') == 'Gestion de Projet' ? 'selected' : '' }}>Gestion de Projet</option>
                                <option value="Autre" {{ old('specialization_area') == 'Autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('specialization_area')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Custom Specialization -->
                        <div class="mb-4" id="custom-specialization" style="display:none;">
                            <label for="custom_specialization" class="form-label">
                                <i class="fas fa-edit me-2"></i>Précisez votre domaine
                            </label>
                            <input type="text" class="form-control @error('custom_specialization') is-invalid @enderror"
                                   id="custom_specialization" name="custom_specialization"
                                   placeholder="Ex: Intelligence Artificielle, Blockchain, etc.">
                            @error('custom_specialization')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Message -->
                        <div class="mb-4">
                            <label for="message" class="form-label">
                                <i class="fas fa-comment me-2"></i>Message (Optionnel)
                            </label>
                            <textarea class="form-control @error('message') is-invalid @enderror"
                                      id="message" name="message" rows="4"
                                      placeholder="Décrivez vos objectifs d'apprentissage et ce que vous attendez de ce mentorat...">{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 1000 caractères</div>
                        </div>

                        <!-- Submit -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('mentorship.index') }}" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn text-white" style="background:linear-gradient(135deg,#1EA38B,#27B371);border:none;">
                                <i class="fas fa-paper-plane me-2"></i>Envoyer la Demande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Panel -->
        <div class="col-lg-4">
            <div class="card border-0" style="box-shadow:0 2px 10px rgba(0,0,0,.06);border-radius:.5rem;">
                <div class="card-header" style="background:#f8f9fa;border-bottom:1px solid #dee2e6;border-radius:.5rem .5rem 0 0;">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Comment ça marche ?
                    </h5>
                </div>
                <div class="card-body">
                    @foreach([
                        ['Choisissez votre mentor', 'Sélectionnez un formateur expert dans votre domaine d\'intérêt.'],
                        ['Envoyez votre demande', 'Décrivez vos objectifs et attentes pour le mentorat.'],
                        ['Attendez l\'approbation', 'Le mentor examinera votre demande et vous répondra.'],
                        ['Commencez votre mentorat', 'Une fois approuvé, vous pourrez planifier des sessions.'],
                    ] as $i => [$title, $desc])
                    <div class="{{ $i < 3 ? 'mb-3' : 'mb-0' }}">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <div class="text-white rounded-circle d-flex align-items-center justify-content-center" style="width:30px;height:30px;background:linear-gradient(135deg,#1EA38B,#27B371);">
                                    <span class="small fw-bold">{{ $i + 1 }}</span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ $title }}</h6>
                                <p class="text-muted small mb-0">{{ $desc }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Tips Card -->
            <div class="card border-0 mt-3" style="box-shadow:0 2px 10px rgba(0,0,0,.06);border-radius:.5rem;">
                <div class="card-header" style="background:#f8f9fa;border-bottom:1px solid #dee2e6;border-radius:.5rem .5rem 0 0;">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lightbulb me-2"></i>Conseils
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-check me-2" style="color:#27B371;"></i><small>Soyez précis dans vos objectifs</small></li>
                        <li class="mb-2"><i class="fas fa-check me-2" style="color:#27B371;"></i><small>Choisissez un domaine qui vous passionne</small></li>
                        <li class="mb-2"><i class="fas fa-check me-2" style="color:#27B371;"></i><small>Respectez le temps du mentor</small></li>
                        <li class="mb-0"><i class="fas fa-check me-2" style="color:#27B371;"></i><small>Soyez patient pour la réponse</small></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-label { font-weight:600; color:#495057; }
.form-control:focus, .form-select:focus {
    border-color:#1EA38B;
    box-shadow:0 0 0 .2rem rgba(30,163,139,.2);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sel = document.getElementById('specialization_area');
    const wrap = document.getElementById('custom-specialization');
    const input = document.getElementById('custom_specialization');
    sel.addEventListener('change', function() {
        const isAutre = this.value === 'Autre';
        wrap.style.display = isAutre ? 'block' : 'none';
        input.required = isAutre;
    });
    if (sel.value === 'Autre') sel.dispatchEvent(new Event('change'));
});
</script>
@endsection
