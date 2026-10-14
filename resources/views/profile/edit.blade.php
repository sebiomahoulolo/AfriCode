@php
    $user = Auth::user();
    // Prioriser l'admin sur le formateur pour éviter les conflits
    $isAdmin = $user->role === 'administrateur' || $user->role === 'admin';
    $isFormateur = !$isAdmin && $user->role === 'formateur';
@endphp

@if($isAdmin)
    @extends('admin.layouts.app')
    @section('title', 'AfriCode - Mon profil')
    @section('page-title', 'Mon profil')
    @section('page-subtitle', 'Gérez vos informations personnelles')
@elseif($isFormateur)
    @extends('formateurs.layouts.app')
    @section('title', 'AfriCode - Mon profil')
    @section('page-title', 'Mon profil')
    @section('page-subtitle', 'Gérez vos informations personnelles et vos paramètres de paiement')
@else
    @extends('layouts.app')
    @section('title', 'AfriCode - Mon profil')
@endif

@section('content')
{{-- Conteneur unique pour éviter toute duplication --}}
<div class="profile-edit-container fade-in">
    {{-- Gestion centralisée de toutes les alertes --}}
    @include('profile.partials.alerts')

    <div class="row">
        <!-- Colonne principale -->
        <div class="col-lg-8 mb-4">
            {{-- Formulaire d'informations personnelles (inclut les informations de paiement pour formateurs uniquement) --}}
            @include('profile.partials.personal-information-form', ['user' => $user, 'isFormateur' => $isFormateur, 'isAdmin' => $isAdmin])

            {{-- Formulaire de modification du mot de passe --}}
            @include('profile.partials.password-form')
        </div>

        <!-- Sidebar - Informations formateur (uniquement pour les formateurs, pas pour les admins) --}}
        @if($isFormateur && !$isAdmin)
            @include('profile.partials.formateur-sidebar', ['user' => $user])
        @endif
    </div>

    {{-- ═══ PRÉFÉRENCES DE NOTIFICATIONS ═══════════════════════════════ --}}
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0" style="border-radius:16px;overflow:hidden;">
                <div class="card-header border-0 pb-0 pt-4 px-4" style="background:#fff;">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#1EA38B,#27B371);display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-bell" style="color:#fff;font-size:.9rem;"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-700">Préférences de notifications</h6>
                            <small class="text-muted">Choisissez ce que vous souhaitez recevoir</small>
                        </div>
                    </div>
                </div>
                <div class="card-body px-4 py-3">
                    <form action="{{ route('profile.notifications.update') }}" method="POST" id="notifPrefsForm">
                        @csrf @method('PATCH')

                        <p class="text-muted small mb-3">
                            <i class="fas fa-info-circle me-1" style="color:#1EA38B;"></i>
                            Les notifications push nécessitent que vous ayez activé les notifications dans votre navigateur.
                        </p>

                        <div style="background:#f8f9fa;border-radius:12px;padding:4px 0;margin-bottom:16px;">
                            <div class="px-3 py-2 border-bottom" style="border-color:#e9ecef !important;">
                                <div class="d-flex align-items-center justify-content-between py-1">
                                    <div>
                                        <div class="fw-600 small"><i class="fas fa-graduation-cap me-2" style="color:#1EA38B;width:16px;"></i>Nouveaux cours</div>
                                        <div class="text-muted" style="font-size:.75rem;padding-left:24px;">Soyez notifié à chaque nouveau cours publié</div>
                                    </div>
                                    <label class="notif-toggle">
                                        <input type="hidden" name="push_new_courses" value="0">
                                        <input type="checkbox" name="push_new_courses" value="1"
                                            {{ ($user->notification_preferences['push_new_courses'] ?? true) ? 'checked' : '' }}>
                                        <span class="notif-slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="px-3 py-2 border-bottom" style="border-color:#e9ecef !important;">
                                <div class="d-flex align-items-center justify-content-between py-1">
                                    <div>
                                        <div class="fw-600 small"><i class="fas fa-clock me-2" style="color:#FF8E2A;width:16px;"></i>Rappels de cours</div>
                                        <div class="text-muted" style="font-size:.75rem;padding-left:24px;">Rappels si vous n'êtes pas connecté depuis plusieurs jours</div>
                                    </div>
                                    <label class="notif-toggle">
                                        <input type="hidden" name="push_reminders" value="0">
                                        <input type="checkbox" name="push_reminders" value="1"
                                            {{ ($user->notification_preferences['push_reminders'] ?? true) ? 'checked' : '' }}>
                                        <span class="notif-slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="px-3 py-2 border-bottom" style="border-color:#e9ecef !important;">
                                <div class="d-flex align-items-center justify-content-between py-1">
                                    <div>
                                        <div class="fw-600 small"><i class="fas fa-bullhorn me-2" style="color:#3b82f6;width:16px;"></i>Offres &amp; actualités</div>
                                        <div class="text-muted" style="font-size:.75rem;padding-left:24px;">Promotions, événements et nouvelles AfriCode</div>
                                    </div>
                                    <label class="notif-toggle">
                                        <input type="hidden" name="push_updates" value="0">
                                        <input type="checkbox" name="push_updates" value="1"
                                            {{ ($user->notification_preferences['push_updates'] ?? true) ? 'checked' : '' }}>
                                        <span class="notif-slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="px-3 py-2 border-bottom" style="border-color:#e9ecef !important;">
                                <div class="d-flex align-items-center justify-content-between py-1">
                                    <div>
                                        <div class="fw-600 small"><i class="fas fa-envelope me-2" style="color:#1EA38B;width:16px;"></i>Emails — Nouveaux cours</div>
                                        <div class="text-muted" style="font-size:.75rem;padding-left:24px;">Recevoir un email à chaque nouveau cours</div>
                                    </div>
                                    <label class="notif-toggle">
                                        <input type="hidden" name="email_new_courses" value="0">
                                        <input type="checkbox" name="email_new_courses" value="1"
                                            {{ ($user->notification_preferences['email_new_courses'] ?? true) ? 'checked' : '' }}>
                                        <span class="notif-slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="px-3 py-2">
                                <div class="d-flex align-items-center justify-content-between py-1">
                                    <div>
                                        <div class="fw-600 small"><i class="fas fa-newspaper me-2" style="color:#FF8E2A;width:16px;"></i>Newsletter</div>
                                        <div class="text-muted" style="font-size:.75rem;padding-left:24px;">Résumé hebdomadaire des contenus et actualités</div>
                                    </div>
                                    <label class="notif-toggle">
                                        <input type="hidden" name="email_newsletter" value="0">
                                        <input type="checkbox" name="email_newsletter" value="1"
                                            {{ ($user->notification_preferences['email_newsletter'] ?? true) ? 'checked' : '' }}>
                                        <span class="notif-slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Désabonnement push --}}
                        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:12px 14px;margin-bottom:16px;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="fw-600 small text-danger"><i class="fas fa-bell-slash me-2"></i>Désactiver les notifications push</div>
                                    <div style="font-size:.75rem;color:#dc2626;margin-top:2px;">Supprime votre abonnement aux notifications sur cet appareil</div>
                                </div>
                                <button type="button" id="unsubscribePushBtn"
                                    class="btn btn-sm btn-outline-danger"
                                    style="white-space:nowrap;font-size:.78rem;">
                                    Désactiver
                                </button>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <button type="submit" class="btn text-white px-4" style="background:linear-gradient(135deg,#27B371,#1EA38B);border:none;">
                                <i class="fas fa-save me-1"></i> Enregistrer les préférences
                            </button>
                            @if(session('notif_success'))
                                <span class="text-success small"><i class="fas fa-check-circle me-1"></i>{{ session('notif_success') }}</span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.fw-600 { font-weight:600; }
.fw-700 { font-weight:700; }

.notif-toggle {
    position:relative; display:inline-block; width:46px; height:26px; flex-shrink:0; cursor:pointer;
}
.notif-toggle input[type="checkbox"] { display:none; }
.notif-toggle input[type="hidden"]   { display:none; }
.notif-slider {
    position:absolute; inset:0; border-radius:26px;
    background:#d1d5db; transition:background .25s;
}
.notif-slider::after {
    content:''; position:absolute;
    width:20px; height:20px; border-radius:50%;
    background:#fff; top:3px; left:3px;
    box-shadow:0 1px 3px rgba(0,0,0,.2);
    transition:transform .25s;
}
.notif-toggle input[type="checkbox"]:checked + .notif-slider {
    background: linear-gradient(135deg,#1EA38B,#27B371);
}
.notif-toggle input[type="checkbox"]:checked + .notif-slider::after {
    transform: translateX(20px);
}
</style>

<script>
(function () {
    var unsubBtn = document.getElementById('unsubscribePushBtn');
    if (unsubBtn) {
        unsubBtn.addEventListener('click', function () {
            if (!('serviceWorker' in navigator)) return alert('Non supporté sur ce navigateur.');
            navigator.serviceWorker.ready.then(function (reg) {
                return reg.pushManager.getSubscription();
            }).then(function (sub) {
                if (!sub) return alert('Aucun abonnement push actif sur cet appareil.');
                return sub.unsubscribe().then(function () {
                    return fetch('/push-subscription', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ endpoint: sub.endpoint })
                    });
                });
            }).then(function () {
                unsubBtn.textContent = 'Désactivé';
                unsubBtn.disabled = true;
            }).catch(function (e) { console.error(e); });
        });
    }
})();
</script>
@endsection
