{{-- Partiel pour le formulaire d'informations personnelles --}}
@php
    if (!isset($user)) {
        $user = Auth::user();
    }
@endphp

<div class="card-modern" data-aos="fade-up">
    <div class="card-header-modern">
        <h5 class="mb-0">
            <i class="fas fa-user me-2" style="color:#1EA38B;"></i>
            Informations personnelles
        </h5>
    </div>
    <div class="card-body-modern">
        <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="first_name" class="form-label">Prénom <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                           id="first_name" name="first_name" 
                           value="{{ old('first_name', $user->first_name) }}" required>
                    @error('first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="last_name" class="form-label">Nom <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                           id="last_name" name="last_name" 
                           value="{{ old('last_name', $user->last_name) }}" required>
                    @error('last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" 
                       value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                
                {{-- Vérification d'email si l'utilisateur doit vérifier son email --}}
                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                            @csrf
                        </form>
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Votre adresse email n'est pas vérifiée.</strong>
                            <button form="send-verification" type="submit" class="btn btn-link p-0 ms-2 text-decoration-underline">
                                Cliquez ici pour renvoyer le lien de vérification.
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Téléphone</label>
                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                       id="phone" name="phone" 
                       value="{{ old('phone', $user->phone) }}"
                       placeholder="Ex: +225 07 12 34 56 78">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="bio" class="form-label">Biographie</label>
                <textarea class="form-control @error('bio') is-invalid @enderror" 
                          id="bio" name="bio" rows="4" 
                          placeholder="Parlez-nous de vous...">{{ old('bio', $user->bio) }}</textarea>
                @error('bio')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="city" class="form-label">Ville</label>
                    <input type="text" class="form-control @error('city') is-invalid @enderror" 
                           id="city" name="city" 
                           value="{{ old('city', $user->city) }}"
                           placeholder="Ex: Abidjan">
                    @error('city')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="country" class="form-label">Pays</label>
                    <input type="text" class="form-control @error('country') is-invalid @enderror" 
                           id="country" name="country" 
                           value="{{ old('country', $user->country) }}"
                           placeholder="Ex: Côte d'Ivoire">
                    @error('country')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Informations de paiement uniquement pour les formateurs (pas pour les admins) --}}
            @if(isset($isFormateur) && $isFormateur && (!isset($isAdmin) || !$isAdmin))
                @include('profile.partials.payment-information-form', ['user' => $user])
            @endif

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn text-white" style="background:linear-gradient(135deg,#1EA38B,#27B371);border:none;">
                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

