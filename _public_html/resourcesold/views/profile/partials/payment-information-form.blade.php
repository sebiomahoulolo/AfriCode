{{-- Partiel pour le formulaire d'informations de paiement (formateurs uniquement, pas pour les admins) --}}
@php
    if (!isset($user)) {
        $user = Auth::user();
    }
    $isAdmin = $user->role === 'administrateur' || $user->role === 'admin';
    $isFormateur = !$isAdmin && $user->role === 'formateur';
@endphp

@if($isFormateur && !$isAdmin)
    <hr class="my-4">
    <h6 class="mb-3">
        <i class="fas fa-credit-card me-2 text-primary"></i>
        Informations de paiement
    </h6>
    <div class="alert alert-info mb-3">
        <i class="fas fa-info-circle"></i> 
        Ces informations seront utilisées pour vos retraits de revenus. 
        Assurez-vous qu'elles sont correctes et à jour.
    </div>
    
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="payment_method" class="form-label">Méthode de paiement</label>
            <select class="form-select @error('payment_method') is-invalid @enderror" 
                    id="payment_method" name="payment_method">
                <option value="">Sélectionner une méthode</option>
                <option value="mobile_money" {{ old('payment_method', $user->payment_method) == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                {{-- <option value="card" {{ old('payment_method', $user->payment_method) == 'card' ? 'selected' : '' }}>Carte bancaire</option> --}}
                 <option value="bank_transfer" {{ old('payment_method', $user->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Virement bancaire</option> 
            </select>
            @error('payment_method')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="payment_account" class="form-label">Numéro de compte</label>
            <input type="text" class="form-control @error('payment_account') is-invalid @enderror" 
                   id="payment_account" name="payment_account" 
                   value="{{ old('payment_account', $user->payment_account) }}"
                   placeholder="Ex: +225 07 12 34 56 78">
            @error('payment_account')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
@endif

