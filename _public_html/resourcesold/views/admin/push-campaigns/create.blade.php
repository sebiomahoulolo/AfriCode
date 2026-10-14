@extends('admin.layouts.app')
@section('title', 'Nouvelle campagne push')
@section('breadcrumb', 'Nouvelle campagne')

@push('styles')
<style>
:root {
    --ac-green:  #11c06d;
    --ac-green2: #239e79;
    --ac-orange: #fea837;
    --ac-navy:   #1a1a2e;
    --ac-red:    #fa1c39;
}

/* ─── Page Header ────────────────────────────────── */
.cp-header {
    display: flex; align-items: center; gap: 16px;
    margin-bottom: 28px;
}
.cp-back-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 16px; border-radius: 10px;
    background: #f3f4f6; border: 1px solid #e5e7eb;
    color: #6b7280; font-size: .83rem; font-weight: 600;
    text-decoration: none; transition: all .15s; flex-shrink: 0;
}
.cp-back-btn:hover { background: #e9ebee; color: #374151; }
.cp-page-title {
    font-size: 1.45rem; font-weight: 800; color: #111827; margin: 0;
}
.cp-page-title i { color: var(--ac-green); }
.cp-page-sub { font-size: .82rem; color: #9ca3af; margin-top: 2px; }

/* ─── Layout ─────────────────────────────────────── */
.cp-layout {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 20px;
    align-items: start;
}
@media (max-width: 1024px) { .cp-layout { grid-template-columns: 1fr; } }

/* ─── Section Card ───────────────────────────────── */
.cp-section {
    background: #fff;
    border-radius: 16px;
    border: 1.5px solid #f0f0f0;
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
    margin-bottom: 16px;
    overflow: hidden;
}
.cp-section-header {
    padding: 14px 20px;
    border-bottom: 1px solid #f3f4f6;
    display: flex; align-items: center; gap: 10px;
}
.cp-section-step {
    width: 26px; height: 26px; border-radius: 8px;
    background: linear-gradient(135deg, var(--ac-green), var(--ac-green2));
    color: #fff; font-size: .72rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.cp-section-step.orange {
    background: linear-gradient(135deg, var(--ac-orange), #e07820);
}
.cp-section-title {
    font-size: .92rem; font-weight: 700; color: #111827;
}
.cp-section-sub {
    font-size: .75rem; color: #9ca3af; margin-left: auto;
}
.cp-section-body { padding: 20px; }

/* ─── Form Fields ────────────────────────────────── */
.cp-field { margin-bottom: 18px; }
.cp-field:last-child { margin-bottom: 0; }
.cp-label {
    display: block; font-size: .8rem; font-weight: 700;
    color: #374151; margin-bottom: 6px;
}
.cp-label .req { color: var(--ac-red); margin-left: 2px; }
.cp-input, .cp-textarea, .cp-select {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    font-size: .88rem;
    color: #1f2937;
    background: #fafafa;
    outline: none;
    transition: border-color .2s, box-shadow .2s, background .2s;
    font-family: inherit;
}
.cp-input:focus, .cp-textarea:focus, .cp-select:focus {
    border-color: var(--ac-green);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(17,192,109,.1);
}
.cp-input.is-invalid, .cp-textarea.is-invalid, .cp-select.is-invalid {
    border-color: var(--ac-red);
}
.cp-textarea { resize: vertical; min-height: 90px; }
.cp-field-error { font-size: .75rem; color: var(--ac-red); margin-top: 4px; }
.cp-field-hint  { font-size: .75rem; color: #9ca3af; margin-top: 4px; }

/* Counter row */
.cp-counter-row {
    display: flex; justify-content: space-between;
    align-items: center; margin-top: 4px;
}
.cp-counter {
    font-size: .72rem; font-weight: 700;
    color: #9ca3af; font-family: monospace;
}
.cp-counter.warn { color: var(--ac-orange); }
.cp-counter.danger { color: var(--ac-red); }

/* URL input group */
.cp-url-group {
    display: flex; border: 1.5px solid #e5e7eb;
    border-radius: 10px; overflow: hidden;
    background: #fafafa;
    transition: border-color .2s, box-shadow .2s;
}
.cp-url-group:focus-within {
    border-color: var(--ac-green);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(17,192,109,.1);
}
.cp-url-prefix {
    padding: 10px 12px;
    background: #f3f4f6;
    border-right: 1px solid #e5e7eb;
    font-size: .78rem; color: #9ca3af;
    white-space: nowrap; flex-shrink: 0;
    display: flex; align-items: center;
}
.cp-url-input {
    flex: 1; border: none; background: transparent;
    padding: 10px 12px; font-size: .88rem; color: #1f2937;
    outline: none; font-family: inherit;
}

/* Target options */
.cp-target-options {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 8px;
}
@media (max-width: 640px) { .cp-target-options { grid-template-columns: 1fr; } }
.cp-target-option {
    border: 1.5px solid #e5e7eb; border-radius: 12px;
    padding: 12px 14px; cursor: pointer;
    display: flex; align-items: flex-start; gap: 10px;
    transition: all .15s; position: relative;
}
.cp-target-option:hover { border-color: #c5e8e0; background: #f6fefe; }
.cp-target-option.selected {
    border-color: var(--ac-green);
    background: rgba(17,192,109,.05);
}
.cp-target-option input[type="radio"] {
    position: absolute; opacity: 0; width: 0; height: 0;
}
.cp-target-radio-dot {
    width: 18px; height: 18px; border-radius: 50%;
    border: 2px solid #d1d5db; flex-shrink: 0; margin-top: 1px;
    display: flex; align-items: center; justify-content: center;
    transition: all .15s;
}
.cp-target-option.selected .cp-target-radio-dot {
    border-color: var(--ac-green);
    background: var(--ac-green);
}
.cp-target-option.selected .cp-target-radio-dot::after {
    content: ''; width: 6px; height: 6px;
    border-radius: 50%; background: #fff;
}
.cp-target-label { font-size: .82rem; font-weight: 700; color: #111827; }
.cp-target-sub   { font-size: .73rem; color: #9ca3af; margin-top: 2px; }

/* Conditional target fields */
.cp-conditional {
    margin-top: 14px; padding: 14px;
    background: #f9fafb; border-radius: 10px;
    border: 1px solid #e5e7eb;
    display: none;
}
.cp-conditional.visible { display: block; }

/* Datetime input */
.cp-datetime-wrap {
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
}
.cp-datetime-wrap .cp-input { flex: 1; min-width: 220px; }
.cp-schedule-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 8px;
    background: rgba(254,168,55,.1); color: #b45309;
    font-size: .73rem; font-weight: 700;
}

/* ─── Sidebar ────────────────────────────────────── */
.cp-sidebar { position: sticky; top: 20px; }

/* Notification Preview */
.cp-preview-card {
    background: #fff;
    border-radius: 16px;
    border: 1.5px solid #f0f0f0;
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
    margin-bottom: 16px;
    overflow: hidden;
}
.cp-preview-header {
    padding: 12px 16px; border-bottom: 1px solid #f3f4f6;
    display: flex; align-items: center; gap: 8px;
}
.cp-preview-header-icon {
    width: 28px; height: 28px; border-radius: 7px;
    background: rgba(17,192,109,.1);
    display: flex; align-items: center; justify-content: center;
    color: var(--ac-green); font-size: .8rem;
}
.cp-preview-title { font-size: .82rem; font-weight: 700; color: #111827; }
.cp-preview-sub   { font-size: .72rem; color: #9ca3af; }

.cp-preview-body { padding: 16px; }

/* Phone mock */
.cp-phone-mock {
    background: #1a1a2e;
    border-radius: 16px; padding: 16px;
    position: relative;
}
.cp-phone-status {
    display: flex; justify-content: space-between;
    align-items: center; margin-bottom: 12px;
    font-size: .65rem; color: rgba(255,255,255,.5);
}
.cp-phone-notif {
    background: rgba(255,255,255,.08);
    backdrop-filter: blur(8px);
    border-radius: 12px; padding: 12px 14px;
    display: flex; gap: 10px; align-items: flex-start;
    border: 1px solid rgba(255,255,255,.1);
}
.cp-notif-icon {
    width: 36px; height: 36px; border-radius: 9px;
    background: linear-gradient(135deg, var(--ac-green), var(--ac-green2));
    flex-shrink: 0; display: flex; align-items: center;
    justify-content: center; color: #fff; font-size: .8rem;
}
.cp-notif-app   { font-size: .65rem; color: rgba(255,255,255,.4); margin-bottom: 3px; }
.cp-notif-title { font-size: .78rem; font-weight: 700; color: #fff; }
.cp-notif-body  { font-size: .72rem; color: rgba(255,255,255,.65); margin-top: 2px; line-height: 1.4; }

/* Tips card */
.cp-tips-card {
    background: linear-gradient(135deg, rgba(17,192,109,.06), rgba(35,158,121,.04));
    border: 1px solid rgba(17,192,109,.15);
    border-radius: 14px; padding: 16px;
}
.cp-tips-title {
    font-size: .8rem; font-weight: 700; color: #065f46;
    margin-bottom: 10px; display: flex; align-items: center; gap: 6px;
}
.cp-tip-row {
    display: flex; gap: 8px; align-items: flex-start;
    margin-bottom: 8px; font-size: .77rem; color: #374151;
    line-height: 1.5;
}
.cp-tip-row:last-child { margin-bottom: 0; }
.cp-tip-dot {
    width: 5px; height: 5px; border-radius: 50%;
    background: var(--ac-green); flex-shrink: 0; margin-top: 6px;
}

/* ─── Action Buttons ─────────────────────────────── */
.cp-actions {
    background: #fff; border-radius: 16px;
    border: 1.5px solid #f0f0f0;
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
    padding: 20px;
    display: flex; flex-direction: column; gap: 10px;
}
.cp-btn {
    display: flex; align-items: center; justify-content: center;
    gap: 8px; padding: 12px 20px; border-radius: 12px;
    font-size: .88rem; font-weight: 700; cursor: pointer;
    border: none; text-decoration: none;
    transition: transform .15s, opacity .15s, box-shadow .15s;
    width: 100%;
}
.cp-btn:hover { transform: translateY(-1px); }
.cp-btn-send {
    background: linear-gradient(135deg, var(--ac-green), var(--ac-green2));
    color: #fff;
    box-shadow: 0 4px 14px rgba(17,192,109,.3);
}
.cp-btn-send:hover { box-shadow: 0 6px 20px rgba(17,192,109,.4); }
.cp-btn-draft {
    background: #f3f4f6; border: 1.5px solid #e5e7eb;
    color: #6b7280;
}
.cp-btn-draft:hover { background: #e9ebee; color: #374151; }
.cp-btn-cancel {
    background: none; color: #9ca3af;
    font-size: .82rem; font-weight: 600;
    padding: 6px;
}
.cp-btn-cancel:hover { color: #6b7280; }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="cp-header">
    <a href="{{ route('admin.push-campaigns.index') }}" class="cp-back-btn">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
    <div>
        <h1 class="cp-page-title">
            <i class="fas fa-plus-circle"></i> Nouvelle campagne push
        </h1>
        <p class="cp-page-sub">Créez et planifiez une notification pour vos abonnés</p>
    </div>
</div>

<form action="{{ route('admin.push-campaigns.store') }}" method="POST" id="campaignForm">
@csrf

<div class="cp-layout">

    {{-- ─── Colonne principale ──────────────────── --}}
    <div>

        {{-- Section 1 : Contenu --}}
        <div class="cp-section">
            <div class="cp-section-header">
                <div class="cp-section-step">1</div>
                <div>
                    <div class="cp-section-title">Contenu de la notification</div>
                </div>
                <div class="cp-section-sub">
                    <i class="fas fa-bell" style="color:var(--ac-green);"></i>
                </div>
            </div>
            <div class="cp-section-body">

                {{-- Titre --}}
                <div class="cp-field">
                    <label class="cp-label">Titre <span class="req">*</span></label>
                    <input type="text" name="title" id="inputTitle"
                        class="cp-input {{ $errors->has('title') ? 'is-invalid' : '' }}"
                        value="{{ old('title') }}"
                        placeholder="Ex : Nouveau cours disponible !"
                        maxlength="100" required>
                    <div class="cp-counter-row">
                        @error('title')
                            <span class="cp-field-error">{{ $message }}</span>
                        @else
                            <span></span>
                        @enderror
                        <span class="cp-counter" id="titleCount">0 / 100</span>
                    </div>
                </div>

                {{-- Message --}}
                <div class="cp-field">
                    <label class="cp-label">Message <span class="req">*</span></label>
                    <textarea name="body" id="inputBody"
                        class="cp-textarea {{ $errors->has('body') ? 'is-invalid' : '' }}"
                        placeholder="Ex : Découvrez notre nouveau cours Python avancé — accessible dès maintenant !"
                        maxlength="255" required>{{ old('body') }}</textarea>
                    <div class="cp-counter-row">
                        @error('body')
                            <span class="cp-field-error">{{ $message }}</span>
                        @else
                            <span></span>
                        @enderror
                        <span class="cp-counter" id="bodyCount">0 / 255</span>
                    </div>
                </div>

                {{-- URL --}}
                <div class="cp-field">
                    <label class="cp-label">URL de destination</label>
                    <div class="cp-url-group">
                        <div class="cp-url-prefix">
                            <i class="fas fa-link me-1"></i> africode.tech
                        </div>
                        <input type="text" name="url" class="cp-url-input"
                            value="{{ old('url', '/courses') }}"
                            placeholder="/courses" maxlength="512">
                    </div>
                    <p class="cp-field-hint">Lien ouvert au clic sur la notification. Commence par <code>/</code>.</p>
                    @error('url')<span class="cp-field-error">{{ $message }}</span>@enderror
                </div>

                {{-- Tag --}}
                <div class="cp-field" style="margin-bottom:0;">
                    <label class="cp-label">Tag d'unicité</label>
                    <input type="text" name="tag" class="cp-input"
                        value="{{ old('tag', 'africode-campaign') }}" maxlength="100">
                    <p class="cp-field-hint">Les notifications avec le même tag se remplacent sur l'appareil de l'utilisateur.</p>
                </div>

            </div>
        </div>

        {{-- Section 2 : Ciblage --}}
        <div class="cp-section">
            <div class="cp-section-header">
                <div class="cp-section-step orange">2</div>
                <div>
                    <div class="cp-section-title">Ciblage des destinataires</div>
                </div>
                <div class="cp-section-sub">
                    <i class="fas fa-crosshairs" style="color:var(--ac-orange);"></i>
                </div>
            </div>
            <div class="cp-section-body">

                <div class="cp-target-options" id="targetOptions">

                    <label class="cp-target-option {{ old('target_type','all')==='all' ? 'selected' : '' }}" data-target="all">
                        <input type="radio" name="target_type" value="all" {{ old('target_type','all')==='all' ? 'checked' : '' }}>
                        <div class="cp-target-radio-dot"></div>
                        <div>
                            <div class="cp-target-label">
                                <i class="fas fa-users me-1" style="color:var(--ac-green);"></i> Tous les abonnés
                            </div>
                            <div class="cp-target-sub">Tous les utilisateurs ayant activé les notifications</div>
                        </div>
                    </label>

                    <label class="cp-target-option {{ old('target_type')==='role' ? 'selected' : '' }}" data-target="role">
                        <input type="radio" name="target_type" value="role" {{ old('target_type')==='role' ? 'checked' : '' }}>
                        <div class="cp-target-radio-dot"></div>
                        <div>
                            <div class="cp-target-label">
                                <i class="fas fa-user-tag me-1" style="color:#11c06d;"></i> Par rôle
                            </div>
                            <div class="cp-target-sub">Apprenants ou formateurs uniquement</div>
                        </div>
                    </label>

                    <label class="cp-target-option {{ old('target_type')==='course_enrolled' ? 'selected' : '' }}" data-target="course_enrolled">
                        <input type="radio" name="target_type" value="course_enrolled" {{ old('target_type')==='course_enrolled' ? 'checked' : '' }}>
                        <div class="cp-target-radio-dot"></div>
                        <div>
                            <div class="cp-target-label">
                                <i class="fas fa-book me-1" style="color:var(--ac-orange);"></i> Par cours
                            </div>
                            <div class="cp-target-sub">Inscrits à un cours spécifique</div>
                        </div>
                    </label>

                    <label class="cp-target-option {{ old('target_type')==='inactive_days' ? 'selected' : '' }}" data-target="inactive_days">
                        <input type="radio" name="target_type" value="inactive_days" {{ old('target_type')==='inactive_days' ? 'checked' : '' }}>
                        <div class="cp-target-radio-dot"></div>
                        <div>
                            <div class="cp-target-label">
                                <i class="fas fa-moon me-1" style="color:#8b5cf6;"></i> Inactifs
                            </div>
                            <div class="cp-target-sub">Apprenants sans activité depuis N jours</div>
                        </div>
                    </label>

                </div>

                {{-- Rôle --}}
                <div class="cp-conditional {{ old('target_type')==='role' ? 'visible' : '' }}" id="condRole">
                    <label class="cp-label">Sélectionner le rôle</label>
                    <select name="target_role" class="cp-select">
                        <option value="apprenant" {{ old('target_role')==='apprenant' ? 'selected' : '' }}>Apprenants</option>
                        <option value="formateur" {{ old('target_role')==='formateur' ? 'selected' : '' }}>Formateurs</option>
                    </select>
                </div>

                {{-- Cours --}}
                <div class="cp-conditional {{ old('target_type')==='course_enrolled' ? 'visible' : '' }}" id="condCourse">
                    <label class="cp-label">Sélectionner le cours</label>
                    <select name="target_course_id" class="cp-select">
                        <option value="">— Choisir un cours —</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('target_course_id')==$course->id ? 'selected' : '' }}>
                                #{{ $course->id }} — {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Inactifs --}}
                <div class="cp-conditional {{ old('target_type')==='inactive_days' ? 'visible' : '' }}" id="condDays">
                    <label class="cp-label">Jours d'inactivité</label>
                    <div style="display:flex;align-items:center;gap:10px;max-width:200px;">
                        <input type="number" name="target_days" class="cp-input"
                            value="{{ old('target_days', 7) }}" min="1" max="365">
                        <span style="font-size:.82rem;color:#6b7280;white-space:nowrap;font-weight:600;">jours</span>
                    </div>
                    <p class="cp-field-hint">Cible les apprenants sans leçon complétée depuis ce nombre de jours.</p>
                </div>

            </div>
        </div>

        {{-- Section 3 : Planification --}}
        <div class="cp-section">
            <div class="cp-section-header">
                <div class="cp-section-step orange">3</div>
                <div>
                    <div class="cp-section-title">Planification de l'envoi</div>
                </div>
                <div class="cp-section-sub">
                    <i class="fas fa-calendar-alt" style="color:var(--ac-orange);"></i>
                </div>
            </div>
            <div class="cp-section-body">
                <div class="cp-datetime-wrap">
                    <input type="datetime-local" name="scheduled_at"
                        class="cp-input {{ $errors->has('scheduled_at') ? 'is-invalid' : '' }}"
                        value="{{ old('scheduled_at') }}" style="max-width:280px;">
                    <div class="cp-schedule-badge">
                        <i class="fas fa-clock"></i> Optionnel
                    </div>
                </div>
                <p class="cp-field-hint" style="margin-top:8px;">Laissez vide pour sauvegarder en brouillon ou envoyer immédiatement avec le bouton ci-dessous.</p>
                @error('scheduled_at')<span class="cp-field-error">{{ $message }}</span>@enderror
            </div>
        </div>

    </div>

    {{-- ─── Sidebar ──────────────────────────────── --}}
    <div class="cp-sidebar">

        {{-- Prévisualisation --}}
        <div class="cp-preview-card">
            <div class="cp-preview-header">
                <div class="cp-preview-header-icon"><i class="fas fa-eye"></i></div>
                <div>
                    <div class="cp-preview-title">Prévisualisation</div>
                    <div class="cp-preview-sub">Apparence sur mobile</div>
                </div>
            </div>
            <div class="cp-preview-body">
                <div class="cp-phone-mock">
                    <div class="cp-phone-status">
                        <span>9:41</span>
                        <div style="display:flex;gap:5px;align-items:center;">
                            <i class="fas fa-signal" style="font-size:.6rem;"></i>
                            <i class="fas fa-wifi" style="font-size:.6rem;"></i>
                            <i class="fas fa-battery-three-quarters" style="font-size:.6rem;"></i>
                        </div>
                    </div>
                    <div class="cp-phone-notif">
                        <div class="cp-notif-icon" style="background:none;padding:0;overflow:hidden;border-radius:9px;">
                            <img src="{{ asset('images/icons/africode-192.png') }}"
                                 alt="AfriCode"
                                 style="width:36px;height:36px;object-fit:cover;border-radius:9px;display:block;"
                                 onerror="this.style.display='none';this.parentElement.innerHTML='<div style=\'width:36px;height:36px;border-radius:9px;background:linear-gradient(135deg,#11c06d,#239e79);display:flex;align-items:center;justify-content:center;\'><span style=\'font-size:.65rem;font-weight:900;color:#fff;letter-spacing:-0.5px;\'>AC</span></div>'">
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div class="cp-notif-app">AfriCode</div>
                            <div class="cp-notif-title" id="prevTitle">Titre de la notification</div>
                            <div class="cp-notif-body" id="prevBody">Votre message apparaîtra ici...</div>
                        </div>
                    </div>
                    <div style="margin-top:8px;font-size:.6rem;color:rgba(255,255,255,.3);text-align:right;">il y a 1 min</div>
                </div>
            </div>
        </div>

        {{-- Boutons d'action --}}
        <div class="cp-actions">
            <button type="submit" name="send_now" value="1"
                class="cp-btn cp-btn-send"
                onclick="return confirm('Envoyer la campagne maintenant à la cible sélectionnée ?')">
                <i class="fas fa-paper-plane"></i> Envoyer maintenant
            </button>
            <button type="submit" name="send_now" value="0" class="cp-btn cp-btn-draft">
                <i class="fas fa-save"></i> Enregistrer en brouillon
            </button>
            <a href="{{ route('admin.push-campaigns.index') }}" class="cp-btn cp-btn-cancel">
                Annuler
            </a>
        </div>

        {{-- Tips --}}
        <div class="cp-tips-card" style="margin-top:16px;">
            <div class="cp-tips-title">
                <i class="fas fa-lightbulb" style="color:var(--ac-green);"></i>
                Conseils pour de meilleures notifications
            </div>
            <div class="cp-tip-row"><div class="cp-tip-dot"></div><div>Titre court et percutant — moins de 50 caractères pour éviter la troncature.</div></div>
            <div class="cp-tip-row"><div class="cp-tip-dot"></div><div>Message clair avec un bénéfice concret pour l'utilisateur.</div></div>
            <div class="cp-tip-row"><div class="cp-tip-dot"></div><div>Envoyez entre 9h et 12h ou 18h–21h pour maximiser le taux d'ouverture.</div></div>
            <div class="cp-tip-row"><div class="cp-tip-dot"></div><div>Évitez plus d'une campagne par semaine pour ne pas saturer vos abonnés.</div></div>
        </div>

    </div>

</div>

</form>
@endsection

@push('scripts')
<script>
(function () {

    /* ── Live preview ───────────────────────── */
    var inputTitle  = document.getElementById('inputTitle');
    var inputBody   = document.getElementById('inputBody');
    var prevTitle   = document.getElementById('prevTitle');
    var prevBody    = document.getElementById('prevBody');
    var titleCount  = document.getElementById('titleCount');
    var bodyCount   = document.getElementById('bodyCount');

    function updateCount(input, el, max) {
        var len = input.value.length;
        el.textContent = len + ' / ' + max;
        el.classList.toggle('warn',   len > max * 0.75 && len < max * 0.9);
        el.classList.toggle('danger', len >= max * 0.9);
    }

    function updatePreview() {
        prevTitle.textContent = inputTitle.value.trim() || 'Titre de la notification';
        prevBody.textContent  = inputBody.value.trim()  || 'Votre message apparaîtra ici...';
        updateCount(inputTitle, titleCount, 100);
        updateCount(inputBody,  bodyCount,  255);
    }

    inputTitle.addEventListener('input', updatePreview);
    inputBody.addEventListener('input',  updatePreview);
    updatePreview();

    /* ── Target radio cards ──────────────────── */
    var options  = document.querySelectorAll('.cp-target-option');
    var radios   = document.querySelectorAll('.cp-target-option input[type="radio"]');
    var condRole   = document.getElementById('condRole');
    var condCourse = document.getElementById('condCourse');
    var condDays   = document.getElementById('condDays');

    function showCond(target) {
        condRole.classList.remove('visible');
        condCourse.classList.remove('visible');
        condDays.classList.remove('visible');
        if (target === 'role')            condRole.classList.add('visible');
        if (target === 'course_enrolled') condCourse.classList.add('visible');
        if (target === 'inactive_days')   condDays.classList.add('visible');
    }

    options.forEach(function (opt) {
        opt.addEventListener('click', function () {
            options.forEach(function (o) { o.classList.remove('selected'); });
            opt.classList.add('selected');
            var radio = opt.querySelector('input[type="radio"]');
            radio.checked = true;
            showCond(radio.value);
        });
    });

    var checked = document.querySelector('.cp-target-option input[type="radio"]:checked');
    if (checked) showCond(checked.value);

})();
</script>
@endpush
