@extends('admin.layouts.app')

@section('content')
<div class="admin-content-header">
    <h1><i class="fas fa-cog"></i> Paramètres de la plateforme</h1>
    <nav class="admin-breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>/</span>
        <span>Paramètres</span>
    </nav>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h2><i class="fas fa-sliders-h"></i> Configuration de la plateforme</h2>
    </div>
    <div class="admin-card-body">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="admin-form">
            @csrf
            @method('PUT')
            
            <div class="admin-tabs">
                <div class="admin-tab-nav">
                    <button type="button" class="admin-tab-btn active" data-tab="general">
                        <i class="fas fa-cog"></i> Général
                    </button>
                    <button type="button" class="admin-tab-btn" data-tab="contact">
                        <i class="fas fa-envelope"></i> Contact
                    </button>
                    <button type="button" class="admin-tab-btn" data-tab="social">
                        <i class="fas fa-share-alt"></i> Réseaux sociaux
                    </button>
                    <button type="button" class="admin-tab-btn" data-tab="payment">
                        <i class="fas fa-money-bill"></i> Paiement
                    </button>
                    <button type="button" class="admin-tab-btn" data-tab="advanced">
                        <i class="fas fa-sliders-h"></i> Avancé
                    </button>
                </div>
                
                <div class="admin-tab-content">
                    <!-- General Settings -->
                    <div class="admin-tab-pane active" id="general">
                        <div class="admin-form-section">
                            <h3><i class="fas fa-info-circle"></i> Informations générales</h3>
                            <div class="admin-form-grid">
                                <div class="admin-form-group">
                                    <label for="site_name" class="admin-form-label">Nom du site *</label>
                                    <input type="text" class="admin-form-input @error('site_name') error @enderror" id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" required>
                                    @error('site_name') <span class="admin-form-error">{{ $message }}</span> @enderror
                                </div>
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="currency" class="form-label">Devise</label>
                                <select class="form-select @error('currency') is-invalid @enderror" id="currency" name="currency" required>
                                    <option value="XOF" {{ old('currency', $settings['currency']) == 'XOF' ? 'selected' : '' }}>Franc CFA BCEAO (XOF)</option>
                                    <option value="USD" {{ old('currency', $settings['currency']) == 'USD' ? 'selected' : '' }}>Dollar américain (USD)</option>
                                    <option value="EUR" {{ old('currency', $settings['currency']) == 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                                </select>
                                @error('currency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="site_description" class="form-label">Description du site</label>
                        <textarea class="form-control @error('site_description') is-invalid @enderror" id="site_description" name="site_description" rows="3">{{ old('site_description', $settings['site_description']) }}</textarea>
                        @error('site_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Cette description sera utilisée pour le SEO et les métadonnées.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="registration_enabled" name="registration_enabled" value="1" {{ old('registration_enabled', $settings['registration_enabled']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="registration_enabled">Activer les inscriptions</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="allow_instructor_signup" name="allow_instructor_signup" value="1" {{ old('allow_instructor_signup', $settings['allow_instructor_signup']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="allow_instructor_signup">Permettre aux utilisateurs de s'inscrire en tant que formateurs</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Settings -->
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contact_email" class="form-label">Email de contact</label>
                                <input type="email" class="form-control @error('contact_email') is-invalid @enderror" id="contact_email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" required>
                                @error('contact_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Cet email sera utilisé pour le formulaire de contact.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="support_email" class="form-label">Email de support</label>
                                <input type="email" class="form-control @error('support_email') is-invalid @enderror" id="support_email" name="support_email" value="{{ old('support_email', $settings['support_email']) }}" required>
                                @error('support_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Cet email sera utilisé pour les demandes de support et notifications système.</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Adresse</label>
                        <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $settings['address'] ?? '') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Téléphone</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $settings['phone'] ?? '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="business_hours" class="form-label">Heures d'ouverture</label>
                                <input type="text" class="form-control" id="business_hours" name="business_hours" value="{{ old('business_hours', $settings['business_hours'] ?? '') }}">
                                <div class="form-text">Exemple: Lun-Ven: 9h-18h</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Social Media Settings -->
                <div class="tab-pane fade" id="social" role="tabpanel" aria-labelledby="social-tab">
                    <div class="mb-3">
                        <label for="facebook_url" class="form-label">
                            <i class="fab fa-facebook me-2 text-primary"></i>Facebook
                        </label>
                        <input type="url" class="form-control @error('facebook_url') is-invalid @enderror" id="facebook_url" name="facebook_url" value="{{ old('facebook_url', $settings['facebook_url']) }}">
                        @error('facebook_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="twitter_url" class="form-label">
                            <i class="fab fa-twitter me-2 text-info"></i>Twitter
                        </label>
                        <input type="url" class="form-control @error('twitter_url') is-invalid @enderror" id="twitter_url" name="twitter_url" value="{{ old('twitter_url', $settings['twitter_url']) }}">
                        @error('twitter_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="instagram_url" class="form-label">
                            <i class="fab fa-instagram me-2 text-danger"></i>Instagram
                        </label>
                        <input type="url" class="form-control @error('instagram_url') is-invalid @enderror" id="instagram_url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url']) }}">
                        @error('instagram_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="linkedin_url" class="form-label">
                            <i class="fab fa-linkedin me-2 text-primary"></i>LinkedIn
                        </label>
                        <input type="url" class="form-control @error('linkedin_url') is-invalid @enderror" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $settings['linkedin_url']) }}">
                        @error('linkedin_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="youtube_url" class="form-label">
                            <i class="fab fa-youtube me-2 text-danger"></i>YouTube
                        </label>
                        <input type="url" class="form-control @error('youtube_url') is-invalid @enderror" id="youtube_url" name="youtube_url" value="{{ old('youtube_url', $settings['youtube_url']) }}">
                        @error('youtube_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Payment Settings -->
                <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                    <div class="mb-3">
                        <label for="payment_gateway" class="form-label">Passerelle de paiement principale</label>
                        <select class="form-select @error('payment_gateway') is-invalid @enderror" id="payment_gateway" name="payment_gateway" required>
                            <option value="stripe" {{ old('payment_gateway', $settings['payment_gateway']) == 'stripe' ? 'selected' : '' }}>Stripe</option>
                            <option value="paypal" {{ old('payment_gateway', $settings['payment_gateway']) == 'paypal' ? 'selected' : '' }}>PayPal</option>
                            <option value="orange_money" {{ old('payment_gateway', $settings['payment_gateway']) == 'orange_money' ? 'selected' : '' }}>Orange Money</option>
                            <option value="wave" {{ old('payment_gateway', $settings['payment_gateway']) == 'wave' ? 'selected' : '' }}>Wave</option>
                            <option value="free" {{ old('payment_gateway', $settings['payment_gateway']) == 'free' ? 'selected' : '' }}>Gratuit uniquement</option>
                        </select>
                        @error('payment_gateway')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Stripe Settings -->
                    <div class="payment-settings stripe-settings mb-3 p-3 border rounded">
                        <h6>Configuration Stripe</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stripe_key" class="form-label">Clé publique Stripe</label>
                                    <input type="text" class="form-control" id="stripe_key" name="stripe_key" value="{{ old('stripe_key', $settings['stripe_key'] ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stripe_secret" class="form-label">Clé secrète Stripe</label>
                                    <input type="password" class="form-control" id="stripe_secret" name="stripe_secret" value="{{ old('stripe_secret', $settings['stripe_secret'] ?? '') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Orange Money Settings -->
                    <div class="payment-settings orange-money-settings mb-3 p-3 border rounded">
                        <h6>Configuration Orange Money</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="orange_money_merchant_id" class="form-label">Identifiant marchand</label>
                                    <input type="text" class="form-control" id="orange_money_merchant_id" name="orange_money_merchant_id" value="{{ old('orange_money_merchant_id', $settings['orange_money_merchant_id'] ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="orange_money_api_key" class="form-label">Clé API</label>
                                    <input type="password" class="form-control" id="orange_money_api_key" name="orange_money_api_key" value="{{ old('orange_money_api_key', $settings['orange_money_api_key'] ?? '') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Commission Settings -->
                    <div class="mb-3">
                        <label for="platform_commission" class="form-label">Commission de la plateforme (%)</label>
                        <input type="number" class="form-control" id="platform_commission" name="platform_commission" value="{{ old('platform_commission', $settings['platform_commission'] ?? 20) }}" min="0" max="100">
                        <div class="form-text">Pourcentage prélevé sur les ventes de cours.</div>
                    </div>
                </div>
                
                <!-- Advanced Settings -->
                <div class="tab-pane fade" id="advanced" role="tabpanel" aria-labelledby="advanced-tab">
                    <div class="mb-3 form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1" {{ old('maintenance_mode', $settings['maintenance_mode']) ? 'checked' : '' }}>
                        <label class="form-check-label" for="maintenance_mode">Mode maintenance</label>
                        <div class="form-text">Si activé, seuls les administrateurs pourront accéder au site.</div>
                    </div>

                    <div class="mb-3">
                        <label for="max_file_upload_size" class="form-label">Taille maximale de fichier (MB)</label>
                        <input type="number" class="form-control @error('max_file_upload_size') is-invalid @enderror" id="max_file_upload_size" name="max_file_upload_size" value="{{ old('max_file_upload_size', $settings['max_file_upload_size']) }}" min="1" max="100">
                        @error('max_file_upload_size')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Taille maximale autorisée pour les téléchargements (en mégaoctets).</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Cache</label>
                        <div class="d-flex">
                            <button type="button" class="btn btn-outline-secondary me-2" onclick="event.preventDefault(); if(confirm('Êtes-vous sûr de vouloir vider le cache ?')) document.getElementById('clear-cache-form').submit();">
                                <i class="fas fa-trash me-1"></i> Vider le cache
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="event.preventDefault(); if(confirm('Êtes-vous sûr de vouloir régénérer le cache ?')) document.getElementById('optimize-cache-form').submit();">
                                <i class="fas fa-sync me-1"></i> Optimiser
                            </button>
                        </div>
                        <form id="clear-cache-form" action="#" method="POST" style="display: none;">@csrf</form>
                        <form id="optimize-cache-form" action="#" method="POST" style="display: none;">@csrf</form>
                    </div>

                    <div class="mt-4">
                        <h6 class="text-muted mb-3">Informations système</h6>
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <th style="width: 30%">Version de l'application</th>
                                    <td>1.0.0</td>
                                </tr>
                                <tr>
                                    <th>Version de PHP</th>
                                    <td>{{ phpversion() }}</td>
                                </tr>
                                <tr>
                                    <th>Version de Laravel</th>
                                    <td>{{ app()->version() }}</td>
                                </tr>
                                <tr>
                                    <th>Environnement</th>
                                    <td>{{ app()->environment() }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end mt-4">
                <button type="reset" class="btn btn-light me-2">
                    <i class="fas fa-undo me-1"></i> Réinitialiser
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Enregistrer les paramètres
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
