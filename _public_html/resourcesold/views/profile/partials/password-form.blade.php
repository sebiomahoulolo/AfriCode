{{-- Partiel pour le formulaire de modification du mot de passe --}}
<div class="card-modern mt-4" data-aos="fade-up" data-aos-delay="100">
    <div class="card-header-modern">
        <h5 class="mb-0">
            <i class="fas fa-lock me-2 text-primary"></i>
            Modifier le mot de passe
        </h5>
    </div>
    <div class="card-body-modern">
        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="mb-3">
                <label for="current_password" class="form-label">Mot de passe actuel <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                           id="current_password" name="current_password" required autocomplete="current-password">
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('current_password', 'eye_current_password')">
                        <i class="fas fa-eye" id="eye_current_password"></i>
                    </button>
                    @error('current_password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Nouveau mot de passe <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                           id="password" name="password" required autocomplete="new-password">
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('password', 'eye_password')">
                        <i class="fas fa-eye" id="eye_password"></i>
                    </button>
                    @error('password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="password" class="form-control" 
                           id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('password_confirmation', 'eye_password_confirmation')">
                        <i class="fas fa-eye" id="eye_password_confirmation"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function togglePasswordVisibility(inputId, eyeId) {
    const input = document.getElementById(inputId);
    const eye = document.getElementById(eyeId);
    
    if (input.type === 'password') {
        input.type = 'text';
        eye.classList.remove('fa-eye');
        eye.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        eye.classList.remove('fa-eye-slash');
        eye.classList.add('fa-eye');
    }
}
</script>

