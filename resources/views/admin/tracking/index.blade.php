@extends('admin.layouts.app')
@section('breadcrumb', 'Tracking & Analytics')
@section('title', 'Tracking & Analytics')

@push('styles')
<style>
:root {
    --ac-green:  #1EA38B;
    --ac-green2: #27B371;
    --ac-orange: #FF8E2A;
    --ac-navy:   #0d1a18;
    --ac-red:    #E32D31;
}

/* ─── Header ─────────────────────────────────────── */
.tr-header { margin-bottom: 28px; }
.tr-title {
    font-size: 1.55rem; font-weight: 800;
    color: #111827; margin: 0 0 4px;
}
.tr-subtitle { font-size: .85rem; color: #6b7280; }

/* ─── Status Banner ──────────────────────────────── */
.tr-status-banner {
    border-radius: 14px; padding: 16px 20px;
    display: flex; align-items: center; gap: 14px;
    margin-bottom: 28px; border: 1.5px solid;
}
.tr-status-banner.active {
    background: linear-gradient(135deg, rgba(30,163,139,.07), rgba(39,179,113,.05));
    border-color: rgba(30,163,139,.25);
}
.tr-status-banner.inactive {
    background: linear-gradient(135deg, rgba(227,45,49,.06), rgba(239,68,68,.04));
    border-color: rgba(227,45,49,.2);
}
.tr-status-icon {
    width: 44px; height: 44px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.15rem; flex-shrink: 0;
}
.tr-status-banner.active   .tr-status-icon { background: rgba(30,163,139,.12); color: var(--ac-green); }
.tr-status-banner.inactive .tr-status-icon { background: rgba(227,45,49,.1); color: var(--ac-red); }
.tr-status-body strong { font-size: .95rem; font-weight: 700; }
.tr-status-body p { font-size: .82rem; color: #6b7280; margin: 3px 0 0; }
.tr-status-banner.active .tr-status-body strong { color: #065f46; }
.tr-status-banner.inactive .tr-status-body strong { color: #991b1b; }
.tr-status-dot {
    display: inline-block; width: 8px; height: 8px;
    border-radius: 50%; margin-right: 6px; vertical-align: middle;
}
.tr-status-banner.active .tr-status-dot {
    background: var(--ac-green);
    animation: trPulse 2s infinite;
}
.tr-status-banner.inactive .tr-status-dot { background: var(--ac-red); }
@keyframes trPulse { 0%,100%{opacity:1}50%{opacity:.4} }

/* ─── Section Label ──────────────────────────────── */
.tr-section-label {
    font-size: .72rem; font-weight: 700;
    letter-spacing: .1em; color: #9ca3af;
    text-transform: uppercase; margin-bottom: 14px;
    display: flex; align-items: center; gap: 8px;
}
.tr-section-label::after {
    content: ''; flex: 1; height: 1px; background: #f3f4f6;
}

/* ─── Platform Grid ──────────────────────────────── */
.tr-platforms-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 16px; margin-bottom: 28px;
}

/* ─── Platform Card ──────────────────────────────── */
.tr-platform-card {
    background: #fff; border-radius: 16px;
    border: 1.5px solid #f0f0f0;
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
    padding: 20px; display: flex; flex-direction: column;
    gap: 14px; transition: transform .2s, box-shadow .2s;
}
.tr-platform-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.09); }
.tr-platform-head { display: flex; align-items: center; gap: 12px; }
.tr-platform-logo {
    width: 44px; height: 44px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.15rem; color: #fff; flex-shrink: 0;
}
.tr-platform-name  { font-size: .95rem; font-weight: 700; color: #111827; }
.tr-platform-id    { font-size: .75rem; color: #6b7280; margin-top: 1px; font-family: monospace; }
.tr-active-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 9px; border-radius: 20px;
    background: #dcfce7; color: #166534;
    font-size: .72rem; font-weight: 700;
    margin-left: auto; flex-shrink: 0;
}
.tr-active-pill-dot { width: 5px; height: 5px; border-radius: 50%; background: #22c55e; }
.tr-platform-desc { font-size: .8rem; color: #6b7280; line-height: 1.55; }
.tr-platform-link {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 16px; border-radius: 10px;
    color: #fff !important; font-size: .82rem; font-weight: 700;
    text-decoration: none; width: fit-content;
    transition: opacity .2s, transform .15s;
}
.tr-platform-link:hover { opacity: .88; transform: translateY(-1px); }

/* ─── Empty Platforms ────────────────────────────── */
.tr-no-platforms {
    background: #fff; border-radius: 14px;
    border: 1.5px dashed #e5e7eb;
    padding: 40px 24px; text-align: center; margin-bottom: 28px;
}
.tr-no-platforms p { font-size: .88rem; color: #9ca3af; margin-bottom: 12px; }
.tr-no-platforms code {
    background: #f3f4f6; padding: 2px 8px;
    border-radius: 6px; font-size: .82rem; color: #374151;
}

/* ─── Events Card ────────────────────────────────── */
.tr-events-card {
    background: #fff; border-radius: 16px;
    border: 1.5px solid #f0f0f0;
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
    overflow: hidden; margin-bottom: 24px;
}
.tr-events-header {
    padding: 16px 22px; border-bottom: 1px solid #f3f4f6;
    display: flex; align-items: center; gap: 10px;
}
.tr-events-header-icon {
    width: 34px; height: 34px; border-radius: 9px;
    background: rgba(30,163,139,.1);
    display: flex; align-items: center; justify-content: center;
    color: var(--ac-green); font-size: .88rem;
}
.tr-events-header-title { font-size: .92rem; font-weight: 700; color: #111827; }
.tr-events-header-sub   { font-size: .75rem; color: #9ca3af; margin-top: 1px; }
.tr-events-list { padding: 8px 0; }
.tr-event-row {
    display: flex; align-items: flex-start; gap: 14px;
    padding: 12px 22px; border-bottom: 1px solid #f9fafb;
    transition: background .15s;
}
.tr-event-row:last-child { border-bottom: none; }
.tr-event-row:hover { background: #fafafa; }
.tr-event-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: var(--ac-green); flex-shrink: 0; margin-top: 5px;
}
.tr-event-name { font-size: .85rem; font-weight: 700; color: #111827; font-family: monospace; }
.tr-event-desc { font-size: .78rem; color: #6b7280; margin-top: 2px; }
.tr-event-url  { font-size: .73rem; color: #9ca3af; font-family: monospace; margin-top: 3px; }

/* ─── Config Tip ─────────────────────────────────── */
.tr-config-tip {
    background: linear-gradient(135deg, rgba(30,163,139,.06), rgba(39,179,113,.04));
    border: 1px solid rgba(30,163,139,.15);
    border-radius: 12px; padding: 16px 20px;
    display: flex; gap: 12px; align-items: flex-start;
}
.tr-config-tip-icon { color: var(--ac-green); font-size: 1rem; margin-top: 1px; flex-shrink: 0; }
.tr-config-tip p { font-size: .82rem; color: #374151; line-height: 1.6; margin: 0; }
.tr-config-tip code {
    background: rgba(30,163,139,.1); color: #065f46;
    padding: 1px 6px; border-radius: 5px; font-size: .8rem;
}

@media (max-width: 768px) {
    .tr-platforms-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="tr-header">
    <h1 class="tr-title">
        <i class="fas fa-chart-line me-2" style="color:var(--ac-green);"></i>Tracking & Analytics
    </h1>
    <p class="tr-subtitle">Configuration des pixels et outils d'analyse — état en temps réel</p>
</div>

{{-- Status Banner --}}
<div class="tr-status-banner {{ $enabled ? 'active' : 'inactive' }}">
    <div class="tr-status-icon">
        <i class="fas {{ $enabled ? 'fa-shield-alt' : 'fa-exclamation-triangle' }}"></i>
    </div>
    <div class="tr-status-body">
        @if($enabled)
            <strong><span class="tr-status-dot"></span>Tracking actif</strong>
            <p>Les événements PageView, conversions et UTM sont transmis aux plateformes configurées ci-dessous.</p>
        @else
            <strong><span class="tr-status-dot"></span>Tracking désactivé</strong>
            <p>Ajoutez <code>TRACKING_ENABLED=true</code> dans votre fichier <code>.env</code> pour activer les pixels et le dataLayer.</p>
        @endif
    </div>
</div>

{{-- Platforms --}}
<div class="tr-section-label">
    <i class="fas fa-plug" style="color:var(--ac-green);"></i>
    Plateformes connectées
</div>

@php $hasAny = $gtmId || $ga4Enabled || $metaEnabled || $linkedinEnabled || $tiktokEnabled || $twitterEnabled; @endphp

@if(!$hasAny)
<div class="tr-no-platforms">
    <p>Aucun outil de tracking configuré. Définissez au moins <code>GTM_ID</code> ou les variables Meta/GA4 dans <code>.env</code>.</p>
    <p style="color:#9ca3af;font-size:.8rem;">Consultez <code>TRACKING_README.md</code> pour le guide de configuration.</p>
</div>
@else
<div class="tr-platforms-grid">

    @if($gtmId)
    <div class="tr-platform-card">
        <div class="tr-platform-head">
            <div class="tr-platform-logo" style="background:linear-gradient(135deg,#4285F4,#34A853);">
                <i class="fab fa-google"></i>
            </div>
            <div>
                <div class="tr-platform-name">Google Tag Manager</div>
                <div class="tr-platform-id">{{ $gtmId }}</div>
            </div>
            <span class="tr-active-pill"><span class="tr-active-pill-dot"></span>Actif</span>
        </div>
        <p class="tr-platform-desc">Gère tous les tags marketing depuis une seule interface : GA4, Meta Pixel, conversion tracking et plus.</p>
        <a href="https://tagmanager.google.com/" target="_blank" rel="noopener"
           class="tr-platform-link" style="background:linear-gradient(135deg,#4285F4,#34A853);">
            <i class="fas fa-external-link-alt"></i> Ouvrir GTM
        </a>
    </div>
    @endif

    @if($ga4Enabled)
    <div class="tr-platform-card">
        <div class="tr-platform-head">
            <div class="tr-platform-logo" style="background:linear-gradient(135deg,#FF6D00,#FBBC05);">
                <i class="fas fa-chart-area"></i>
            </div>
            <div>
                <div class="tr-platform-name">Google Analytics 4</div>
                <div class="tr-platform-id">{{ $ga4['measurement_id'] ?? '—' }}</div>
            </div>
            <span class="tr-active-pill"><span class="tr-active-pill-dot"></span>Actif</span>
        </div>
        <p class="tr-platform-desc">Rapports de trafic, comportement utilisateur, événements personnalisés et conversion goals.</p>
        <a href="https://analytics.google.com/" target="_blank" rel="noopener"
           class="tr-platform-link" style="background:linear-gradient(135deg,#FF6D00,#FBBC05);">
            <i class="fas fa-external-link-alt"></i> Ouvrir GA4
        </a>
    </div>
    @endif

    @if($metaEnabled)
    <div class="tr-platform-card">
        <div class="tr-platform-head">
            <div class="tr-platform-logo" style="background:linear-gradient(135deg,#1877F2,#0D65D9);">
                <i class="fab fa-facebook-f"></i>
            </div>
            <div>
                <div class="tr-platform-name">Meta (Facebook / Instagram)</div>
                <div class="tr-platform-id">{{ $meta['pixel_id'] ?? '—' }}</div>
            </div>
            <span class="tr-active-pill"><span class="tr-active-pill-dot"></span>Actif</span>
        </div>
        <p class="tr-platform-desc">Suivi des événements publicitaires, audiences personnalisées et attribution des campagnes Meta Ads.</p>
        <a href="https://business.facebook.com/events_manager2/" target="_blank" rel="noopener"
           class="tr-platform-link" style="background:linear-gradient(135deg,#1877F2,#0D65D9);">
            <i class="fas fa-external-link-alt"></i> Gestionnaire d'événements
        </a>
    </div>
    @endif

    @if($linkedinEnabled)
    <div class="tr-platform-card">
        <div class="tr-platform-head">
            <div class="tr-platform-logo" style="background:#0A66C2;">
                <i class="fab fa-linkedin-in"></i>
            </div>
            <div>
                <div class="tr-platform-name">LinkedIn Insight Tag</div>
                <div class="tr-platform-id">{{ $linkedin['partner_id'] ?? '—' }}</div>
            </div>
            <span class="tr-active-pill"><span class="tr-active-pill-dot"></span>Actif</span>
        </div>
        <p class="tr-platform-desc">Retargeting B2B et attribution des campagnes LinkedIn pour toucher les professionnels africains.</p>
        <a href="https://www.linkedin.com/campaignmanager/" target="_blank" rel="noopener"
           class="tr-platform-link" style="background:#0A66C2;">
            <i class="fas fa-external-link-alt"></i> Campaign Manager
        </a>
    </div>
    @endif

    @if($tiktokEnabled)
    <div class="tr-platform-card">
        <div class="tr-platform-head">
            <div class="tr-platform-logo" style="background:linear-gradient(135deg,#010101,#2d2d2d);">
                <i class="fab fa-tiktok"></i>
            </div>
            <div>
                <div class="tr-platform-name">TikTok Pixel</div>
                <div class="tr-platform-id">{{ $tiktok['pixel_id'] ?? '—' }}</div>
            </div>
            <span class="tr-active-pill"><span class="tr-active-pill-dot"></span>Actif</span>
        </div>
        <p class="tr-platform-desc">Événements publicitaires TikTok Ads — idéal pour toucher la jeune génération africaine connectée.</p>
        <a href="https://ads.tiktok.com/" target="_blank" rel="noopener"
           class="tr-platform-link" style="background:linear-gradient(135deg,#010101,#2d2d2d);">
            <i class="fas fa-external-link-alt"></i> TikTok for Business
        </a>
    </div>
    @endif

    @if($twitterEnabled)
    <div class="tr-platform-card">
        <div class="tr-platform-head">
            <div class="tr-platform-logo" style="background:linear-gradient(135deg,#1DA1F2,#0c8fcf);">
                <i class="fab fa-twitter"></i>
            </div>
            <div>
                <div class="tr-platform-name">Twitter / X Ads</div>
                <div class="tr-platform-id">{{ $twitter['pixel_id'] ?? '—' }}</div>
            </div>
            <span class="tr-active-pill"><span class="tr-active-pill-dot"></span>Actif</span>
        </div>
        <p class="tr-platform-desc">Conversion tracking et suivi des événements pour les campagnes publicitaires sur X (ex-Twitter).</p>
        <a href="https://ads.twitter.com/" target="_blank" rel="noopener"
           class="tr-platform-link" style="background:linear-gradient(135deg,#1DA1F2,#0c8fcf);">
            <i class="fas fa-external-link-alt"></i> X Ads Manager
        </a>
    </div>
    @endif

</div>
@endif

{{-- Events --}}
<div class="tr-section-label">
    <i class="fas fa-bolt" style="color:var(--ac-orange);"></i>
    Événements envoyés par le site
</div>

<div class="tr-events-card">
    <div class="tr-events-header">
        <div class="tr-events-header-icon"><i class="fas fa-code"></i></div>
        <div>
            <div class="tr-events-header-title">DataLayer Events</div>
            <div class="tr-events-header-sub">Événements déclenchés automatiquement par AfriCode</div>
        </div>
    </div>
    <div class="tr-events-list">
        <div class="tr-event-row">
            <span class="tr-event-dot"></span>
            <div>
                <div class="tr-event-name">PageView</div>
                <div class="tr-event-desc">Déclenché sur chaque chargement de page — comptabilise le trafic et les sessions</div>
                <div class="tr-event-url">Toutes les routes</div>
            </div>
        </div>
        <div class="tr-event-row">
            <span class="tr-event-dot" style="background:var(--ac-orange);"></span>
            <div>
                <div class="tr-event-name">view_content</div>
                <div class="tr-event-desc">Déclenché quand un visiteur consulte la fiche d'un cours (intérêt fort)</div>
                <div class="tr-event-url">/courses/{slug}</div>
            </div>
        </div>
        <div class="tr-event-row">
            <span class="tr-event-dot" style="background:#22c55e;"></span>
            <div>
                <div class="tr-event-name">purchase</div>
                <div class="tr-event-desc">Déclenché après un paiement réussi — conversion principale</div>
                <div class="tr-event-url">/payment/success</div>
            </div>
        </div>
        <div class="tr-event-row">
            <span class="tr-event-dot" style="background:#3b82f6;"></span>
            <div>
                <div class="tr-event-name">utm_captured</div>
                <div class="tr-event-desc">Déclenché quand un utilisateur arrive via une URL avec paramètres UTM (attribution de campagne)</div>
                <div class="tr-event-url">?utm_source= &nbsp;·&nbsp; utm_campaign= &nbsp;·&nbsp; utm_medium=</div>
            </div>
        </div>
    </div>
</div>

{{-- Config Tip --}}
<div class="tr-config-tip">
    <i class="fas fa-lightbulb tr-config-tip-icon"></i>
    <p>
        <strong>Configuration rapide :</strong> ajoutez les variables dans votre <code>.env</code> —
        <code>GTM_ID=GTM-XXXXXXX</code>, <code>GA4_MEASUREMENT_ID=G-XXXXXXX</code>, <code>META_PIXEL_ID=XXXXXXXXX</code>,
        puis relancez le serveur. Les pixels s'activent instantanément sans redéploiement de code.
    </p>
</div>

@endsection
