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
                                    @error('site_name')
                                        <span class="admin-form-error">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="admin-form-group">
                                    <label for="currency" class="admin-form-label">Devise *</label>
                                    <select class="admin-form-select @error('currency') error @enderror" id="currency" name="currency" required>
                                        <option value="XOF" {{ old('currency', $settings['currency']) == 'XOF' ? 'selected' : '' }}>Franc CFA BCEAO (XOF)</option>
                                        <option value="USD" {{ old('currency', $settings['currency']) == 'USD' ? 'selected' : '' }}>Dollar américain (USD)</option>
                                        <option value="EUR" {{ old('currency', $settings['currency']) == 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                                    </select>
                                    @error('currency')
                                        <span class="admin-form-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="admin-form-group">
                                <label for="site_description" class="admin-form-label">Description du site</label>
                                <textarea class="admin-form-textarea @error('site_description') error @enderror" id="site_description" name="site_description" rows="3">{{ old('site_description', $settings['site_description']) }}</textarea>
                                @error('site_description')
                                    <span class="admin-form-error">{{ $message }}</span>
                                @enderror
                                <div class="admin-form-hint">Cette description sera utilisée pour le SEO et les métadonnées.</div>
                            </div>
                            
                            <div class="admin-form-grid">
                                <div class="admin-form-group form-switch">
                                    <input class="admin-form-switch" type="checkbox" id="registration_enabled" name="registration_enabled" value="1" {{ old('registration_enabled', $settings['registration_enabled']) ? 'checked' : '' }}>
                                    <label class="admin-form-label" for="registration_enabled">Activer les inscriptions</label>
                                </div>
                                
                                <div class="admin-form-group form-switch">
                                    <input class="admin-form-switch" type="checkbox" id="allow_instructor_signup" name="allow_instructor_signup" value="1" {{ old('allow_instructor_signup', $settings['allow_instructor_signup']) ? 'checked' : '' }}>
                                    <label class="admin-form-label" for="allow_instructor_signup">Permettre aux utilisateurs de s'inscrire en tant que formateurs</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Settings -->
                    <div class="admin-tab-pane" id="contact">
                        <div class="admin-form-section">
                            <h3><i class="fas fa-envelope"></i> Informations de contact</h3>
                            <div class="admin-form-grid">
                                <div class="admin-form-group">
                                    <label for="contact_email" class="admin-form-label">Email de contact *</label>
                                    <input type="email" class="admin-form-input @error('contact_email') error @enderror" id="contact_email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" required>
                                    @error('contact_email')
                                        <span class="admin-form-error">{{ $message }}</span>
                                    @enderror
                                    <div class="admin-form-hint">Cet email sera utilisé pour le formulaire de contact.</div>
                                </div>
                                
                                <div class="admin-form-group">
                                    <label for="support_email" class="admin-form-label">Email de support *</label>
                                    <input type="email" class="admin-form-input @error('support_email') error @enderror" id="support_email" name="support_email" value="{{ old('support_email', $settings['support_email']) }}" required>
                                    @error('support_email')
                                        <span class="admin-form-error">{{ $message }}</span>
                                    @enderror
                                    <div class="admin-form-hint">Cet email sera utilisé pour les demandes de support et notifications système.</div>
                                </div>
                            </div>
                            
                            <div class="admin-form-group">
                                <label for="address" class="admin-form-label">Adresse</label>
                                <textarea class="admin-form-textarea" id="address" name="address" rows="2">{{ old('address', $settings['address'] ?? '') }}</textarea>
                            </div>
                            
                            <div class="admin-form-grid">
                                <div class="admin-form-group">
                                    <label for="phone" class="admin-form-label">Téléphone</label>
                                    <input type="text" class="admin-form-input" id="phone" name="phone" value="{{ old('phone', $settings['phone'] ?? '') }}">
                                </div>
                                
                                <div class="admin-form-group">
                                    <label for="business_hours" class="admin-form-label">Heures d'ouverture</label>
                                    <input type="text" class="admin-form-input" id="business_hours" name="business_hours" value="{{ old('business_hours', $settings['business_hours'] ?? '') }}">
                                    <div class="admin-form-hint">Exemple: Lun-Ven: 9h-18h</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Social Media Settings -->
                    <div class="admin-tab-pane" id="social">
                        <div class="admin-form-section">
                            <h3><i class="fas fa-share-alt"></i> Réseaux sociaux</h3>
                            
                            <div class="admin-form-group">
                                <label for="facebook_url" class="admin-form-label">
                                    <i class="fab fa-facebook me-2 text-primary"></i>Facebook
                                </label>
                                <input type="url" class="admin-form-input @error('facebook_url') error @enderror" id="facebook_url" name="facebook_url" value="{{ old('facebook_url', $settings['facebook_url']) }}">
                                @error('facebook_url')
                                    <span class="admin-form-error">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="admin-form-group">
                                <label for="twitter_url" class="admin-form-label">
                                    <i class="fab fa-twitter me-2 text-info"></i>Twitter
                                </label>
                                <input type="url" class="admin-form-input @error('twitter_url') error @enderror" id="twitter_url" name="twitter_url" value="{{ old('twitter_url', $settings['twitter_url']) }}">
                                @error('twitter_url')
                                    <span class="admin-form-error">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="admin-form-group">
                                <label for="instagram_url" class="admin-form-label">
                                    <i class="fab fa-instagram me-2 text-danger"></i>Instagram
                                </label>
                                <input type="url" class="admin-form-input @error('instagram_url') error @enderror" id="instagram_url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url']) }}">
                                @error('instagram_url')
                                    <span class="admin-form-error">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="admin-form-group">
                                <label for="linkedin_url" class="admin-form-label">
                                    <i class="fab fa-linkedin me-2 text-primary"></i>LinkedIn
                                </label>
                                <input type="url" class="admin-form-input @error('linkedin_url') error @enderror" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $settings['linkedin_url']) }}">
                                @error('linkedin_url')
                                    <span class="admin-form-error">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="admin-form-group">
                                <label for="youtube_url" class="admin-form-label">
                                    <i class="fab fa-youtube me-2 text-danger"></i>YouTube
                                </label>
                                <input type="url" class="admin-form-input @error('youtube_url') error @enderror" id="youtube_url" name="youtube_url" value="{{ old('youtube_url', $settings['youtube_url']) }}">
                                @error('youtube_url')
                                    <span class="admin-form-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Settings -->
                    <div class="admin-tab-pane" id="payment">
                        <div class="admin-form-section">
                            <h3><i class="fas fa-money-bill"></i> Paramètres de paiement</h3>
                            
                            <div class="admin-form-group">
                                <label for="payment_gateway" class="admin-form-label">Passerelle de paiement principale *</label>
                                <select class="admin-form-select @error('payment_gateway') error @enderror" id="payment_gateway" name="payment_gateway" required>
                                    <option value="stripe" {{ old('payment_gateway', $settings['payment_gateway']) == 'stripe' ? 'selected' : '' }}>Stripe</option>
                                    <option value="paypal" {{ old('payment_gateway', $settings['payment_gateway']) == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                    <option value="orange_money" {{ old('payment_gateway', $settings['payment_gateway']) == 'orange_money' ? 'selected' : '' }}>Orange Money</option>
                                    <option value="wave" {{ old('payment_gateway', $settings['payment_gateway']) == 'wave' ? 'selected' : '' }}>Wave</option>
                                    <option value="free" {{ old('payment_gateway', $settings['payment_gateway']) == 'free' ? 'selected' : '' }}>Gratuit uniquement</option>
                                </select>
                                @error('payment_gateway')
                                    <span class="admin-form-error">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <!-- Stripe Settings -->
                            <div class="admin-form-subsection stripe-settings">
                                <h4><i class="fab fa-stripe me-2"></i>Configuration Stripe</h4>
                                <div class="admin-form-grid">
                                    <div class="admin-form-group">
                                        <label for="stripe_key" class="admin-form-label">Clé publique Stripe</label>
                                        <input type="text" class="admin-form-input" id="stripe_key" name="stripe_key" value="{{ old('stripe_key', $settings['stripe_key'] ?? '') }}">
                                    </div>
                                    
                                    <div class="admin-form-group">
                                        <label for="stripe_secret" class="admin-form-label">Clé secrète Stripe</label>
                                        <input type="password" class="admin-form-input" id="stripe_secret" name="stripe_secret" value="{{ old('stripe_secret', $settings['stripe_secret'] ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Orange Money Settings -->
                            <div class="admin-form-subsection orange-money-settings">
                                <h4><i class="fas fa-mobile-alt me-2"></i>Configuration Orange Money</h4>
                                <div class="admin-form-grid">
                                    <div class="admin-form-group">
                                        <label for="orange_money_merchant_id" class="admin-form-label">Identifiant marchand</label>
                                        <input type="text" class="admin-form-input" id="orange_money_merchant_id" name="orange_money_merchant_id" value="{{ old('orange_money_merchant_id', $settings['orange_money_merchant_id'] ?? '') }}">
                                    </div>
                                    
                                    <div class="admin-form-group">
                                        <label for="orange_money_api_key" class="admin-form-label">Clé API</label>
                                        <input type="password" class="admin-form-input" id="orange_money_api_key" name="orange_money_api_key" value="{{ old('orange_money_api_key', $settings['orange_money_api_key'] ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="admin-form-group">
                                <label for="platform_commission" class="admin-form-label">Commission de la plateforme (%)</label>
                                <input type="number" class="admin-form-input" id="platform_commission" name="platform_commission" value="{{ old('platform_commission', $settings['platform_commission'] ?? 20) }}" min="0" max="100">
                                <div class="admin-form-hint">Pourcentage prélevé sur les ventes de cours.</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Advanced Settings -->
                    <div class="admin-tab-pane" id="advanced">
                        <div class="admin-form-section">
                            <h3><i class="fas fa-sliders-h"></i> Paramètres avancés</h3>
                            
                            <div class="admin-form-group form-switch">
                                <input class="admin-form-switch" type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1" {{ old('maintenance_mode', $settings['maintenance_mode']) ? 'checked' : '' }}>
                                <label class="admin-form-label" for="maintenance_mode">Mode maintenance</label>
                                <div class="admin-form-hint">Si activé, seuls les administrateurs pourront accéder au site.</div>
                            </div>
                            
                            <div class="admin-form-group">
                                <label for="max_file_upload_size" class="admin-form-label">Taille maximale de fichier (MB) *</label>
                                <input type="number" class="admin-form-input @error('max_file_upload_size') error @enderror" id="max_file_upload_size" name="max_file_upload_size" value="{{ old('max_file_upload_size', $settings['max_file_upload_size']) }}" min="1" max="100" required>
                                @error('max_file_upload_size')
                                    <span class="admin-form-error">{{ $message }}</span>
                                @enderror
                                <div class="admin-form-hint">Taille maximale autorisée pour les téléchargements (en mégaoctets).</div>
                            </div>
                            
                            <div class="admin-form-group">
                                <label class="admin-form-label">Cache</label>
                                <div class="admin-button-group">
                                    <button type="button" class="admin-button admin-button-secondary" onclick="event.preventDefault(); if(confirm('Êtes-vous sûr de vouloir vider le cache ?')) document.getElementById('clear-cache-form').submit();">
                                        <i class="fas fa-trash me-1"></i> Vider le cache
                                    </button>
                                    <button type="button" class="admin-button admin-button-secondary" onclick="event.preventDefault(); if(confirm('Êtes-vous sûr de vouloir régénérer le cache ?')) document.getElementById('optimize-cache-form').submit();">
                                        <i class="fas fa-sync me-1"></i> Optimiser
                                    </button>
                                </div>
                                <form id="clear-cache-form" action="#" method="POST" style="display: none;">@csrf</form>
                                <form id="optimize-cache-form" action="#" method="POST" style="display: none;">@csrf</form>
                            </div>
                        </div>
                        
                        <div class="admin-form-section">
                            <h3><i class="fas fa-info-circle"></i> Informations système</h3>
                            <div class="admin-table-responsive">
                                <table class="admin-table">
                                    <tbody>
                                        <tr>
                                            <th>Version de l'application</th>
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
                </div>
            </div>
            
            <div class="admin-form-actions">
                <button type="reset" class="admin-button admin-button-light">
                    <i class="fas fa-undo me-1"></i> Réinitialiser
                </button>
                <button type="submit" class="admin-button admin-button-primary">
                    <i class="fas fa-save me-1"></i> Enregistrer les paramètres
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.admin-tab-btn');
    const tabPanes = document.querySelectorAll('.admin-tab-pane');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetTab = button.getAttribute('data-tab');
            
            // Update active tab button
            tabButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            
            // Show corresponding tab pane
            tabPanes.forEach(pane => pane.classList.remove('active'));
            const targetPane = document.getElementById(targetTab);
            if (targetPane) {
                targetPane.classList.add('active');
            }
        });
    });
    
    // Show/hide payment gateway specific settings
    const paymentGatewaySelect = document.getElementById('payment_gateway');
    if (paymentGatewaySelect) {
        paymentGatewaySelect.addEventListener('change', function() {
            document.querySelectorAll('.admin-form-subsection').forEach(section => {
                section.style.display = 'none';
            });
            
            const selectedGateway = this.value;
            if (selectedGateway === 'stripe') {
                document.querySelector('.stripe-settings').style.display = 'block';
            } else if (selectedGateway === 'orange_money') {
                document.querySelector('.orange-money-settings').style.display = 'block';
            }
        });
        
        // Trigger change event on page load
        paymentGatewaySelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush