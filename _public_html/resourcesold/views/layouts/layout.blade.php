<!DOCTYPE html>
<html lang="fr" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- ══ SEO ESSENTIELS ══════════════════════════════════════════════════ --}}
    <title>@yield('title', 'AfriCode — Hub Numérique Africain')</title>
    <meta name="description"  content="@yield('meta_description', 'AfriCode — Hub Numérique Africain. Formations en ligne, développement de solutions, cybersécurité, SaaS et conseil numérique pour les organisations africaines.')">
    <meta name="keywords"     content="@yield('meta_keywords', 'africode, formation numérique, développement web, cybersécurité, SaaS Afrique, coding bootcamp, apprendre à coder, Afrique')">
    <meta name="robots"       content="@yield('meta_robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1')">
    <meta name="author"       content="AfriCode — Hub Numérique Africain">
    <meta name="copyright"    content="AfriCode {{ date('Y') }}">
    <link rel="canonical"     href="@yield('canonical', url()->current())">

    @php
        $metaTitle = $__env->yieldContent('og_title')
            ?: $__env->yieldContent('title', 'AfriCode — Hub Numérique Africain');
        $metaDesc  = $__env->yieldContent('og_description')
            ?: $__env->yieldContent('meta_description', 'AfriCode — Hub Numérique Africain. Formations en ligne, développement de solutions, cybersécurité, SaaS et conseil numérique pour les organisations africaines.');
        $metaImg   = $__env->yieldContent('og_image', asset('images/africode-og.jpg'));
        $metaUrl   = $__env->yieldContent('og_url', url()->current());
    @endphp

    {{-- ══ OPEN GRAPH (Facebook, LinkedIn, WhatsApp…) ══════════════════════ --}}
    <meta property="og:site_name"   content="AfriCode">
    <meta property="og:locale"      content="fr_FR">
    <meta property="og:type"        content="@yield('og_type', 'website')">
    <meta property="og:title"       content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:url"         content="{{ $metaUrl }}">
    <meta property="og:image"       content="{{ $metaImg }}">
    <meta property="og:image:width"  content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt"   content="AfriCode — Hub Numérique Africain">

    {{-- ══ TWITTER / X CARD ═══════════════════════════════════════════════ --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:site"        content="@AfriCodeHub">
    <meta name="twitter:creator"     content="@AfriCodeHub">
    <meta name="twitter:title"       content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDesc }}">
    <meta name="twitter:image"       content="{{ $metaImg }}">

    {{-- ══ JSON-LD — Organisation AfriCode ════════════════════════════════ --}}
    @hasSection('jsonld')
        @yield('jsonld')
    @else
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "EducationalOrganization",
      "name": "AfriCode",
      "alternateName": "AfriCode Hub Numérique Africain",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('images/icons/africode-512.png') }}",
      "description": "Hub Numérique Africain — Formations en ligne, développement de solutions numériques, cybersécurité et SaaS pour les organisations africaines.",
      "sameAs": [
        "https://www.linkedin.com/company/africode",
        "https://www.facebook.com/africodetech"
      ],
      "contactPoint": {
        "@type": "ContactPoint",
        "email": "contact@africode.tech",
        "contactType": "customer service",
        "availableLanguage": ["French"]
      },
      "address": {
        "@type": "PostalAddress",
        "addressCountry": "CI"
      }
    }
    </script>
    @endif

    {{-- ══ STACK META — balises supplémentaires par page ═════════════════ --}}
    @stack('meta')
    @yield('meta_tags')

    {{-- ══ PWA & APP META ═════════════════════════════════════════════════ --}}
    <meta name="theme-color" content="#11c06d">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="AfriCode">
    @laravelPWA

    {{-- ══ FAVICON & TOUCH ICONS ══════════════════════════════════════════ --}}
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/africode-192.png') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">

    {{-- Google Fonts (optionnel, Udemy utilise des polices propriétaires) --}}
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    {{-- Si vous voulez vous rapprocher des polices Udemy, utilisez une pile de polices système comme dans le CSS --}}

    {{-- CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <!-- <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}"> -->
    <!-- <script src="{{ asset('js/bootstrap.bundle.min.js') }}" defer></script> -->
    
    <link rel="stylesheet" href="{{ asset('build/assets/homepage-DVH-Qzv6.css') }}">
      <link rel="stylesheet" href="{{ asset('build/assets/mentorship-KIJuhSZg.css') }}">
<meta name="facebook-domain-verification" content="u57iy063pazf4alikzjmppuwkxtqhz" />
<x-tracking-pixels />
<!-- JS compilé -->
<script type="module" src="{{ asset('build/assets/homepage-DrY67tPO.js') }}"></script>
    <!-- Skeleton Loader global -->
    <link rel="stylesheet" href="{{ asset('css/skeleton.css') }}">
    @stack('styles')

    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body>
    <x-tracking-pixels-body />
    @include('components.navbar')

    {{-- ═══ BANNIÈRE NOTIFICATIONS PUSH ══════════════════════════════════ --}}
    {{-- Desktop : bannière compacte en haut --}}
    <div id="notif-banner-desktop" aria-live="polite" aria-label="Activer les notifications"
         style="display:none;position:fixed;top:72px;left:50%;transform:translateX(-50%);z-index:10001;
                width:calc(100% - 32px);max-width:540px;
                background:#fff;border-radius:18px;
                box-shadow:0 8px 40px rgba(0,0,0,.15);
                border:1.5px solid rgba(17,192,109,.2);
                padding:14px 18px;
                animation:nbSlideDown .35s cubic-bezier(.4,0,.2,1);">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="background:linear-gradient(135deg,#11c06d,#239e79);width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(17,192,109,.35);">
                <i class="fas fa-bell" style="color:#fff;font-size:1.1rem;"></i>
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-weight:700;font-size:.9rem;color:#111827;">Restez informé avec AfriCode</div>
                <div style="font-size:.77rem;color:#6b7280;margin-top:1px;">Nouveaux cours · Rappels · Actualités</div>
            </div>
            <div style="display:flex;gap:8px;flex-shrink:0;align-items:center;">
                <button id="notif-accept" style="background:linear-gradient(135deg,#11c06d,#239e79);color:#fff;border:none;padding:8px 18px;border-radius:22px;font-weight:700;font-size:.82rem;cursor:pointer;white-space:nowrap;box-shadow:0 3px 10px rgba(17,192,109,.3);">
                    <i class="fas fa-bell me-1"></i>Activer
                </button>
                <button id="notif-dismiss" style="background:#f3f4f6;color:#9ca3af;border:none;width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;transition:background .2s;" title="Pas maintenant">&times;</button>
            </div>
        </div>
    </div>

    {{-- Mobile : bottom sheet --}}
    <div id="notif-sheet-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:10001;backdrop-filter:blur(4px);" aria-hidden="true"></div>
    <div id="notif-banner-mobile" role="dialog" aria-modal="true" aria-label="Activer les notifications"
         style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:10002;
                background:#fff;border-radius:24px 24px 0 0;
                padding:0 0 env(safe-area-inset-bottom,16px);
                box-shadow:0 -8px 40px rgba(0,0,0,.2);
                transform:translateY(100%);transition:transform .4s cubic-bezier(.4,0,.2,1);">
        {{-- Handle bar --}}
        <div style="display:flex;justify-content:center;padding:12px 0 8px;">
            <div style="width:40px;height:4px;border-radius:2px;background:#d1d5db;"></div>
        </div>
        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:0 20px 16px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#11c06d,#239e79);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(17,192,109,.35);">
                    <i class="fas fa-bell" style="color:#fff;font-size:1.3rem;"></i>
                </div>
                <div>
                    <div style="font-weight:800;font-size:1rem;color:#111827;">Activer les notifications</div>
                    <div style="font-size:.78rem;color:#6b7280;">Gratuit · Désactivable à tout moment</div>
                </div>
            </div>
            <button id="notif-sheet-close" style="background:#f3f4f6;border:none;width:34px;height:34px;border-radius:50%;cursor:pointer;color:#9ca3af;font-size:1.1rem;display:flex;align-items:center;justify-content:center;">&times;</button>
        </div>
        {{-- Avantages --}}
        <div style="padding:0 20px 20px;display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:12px 14px;display:flex;align-items:center;gap:10px;">
                <div style="width:34px;height:34px;border-radius:10px;background:rgba(35,158,121,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-graduation-cap" style="color:#239e79;font-size:.9rem;"></i>
                </div>
                <div>
                    <div style="font-weight:700;font-size:.78rem;color:#14532d;">Nouveaux cours</div>
                    <div style="font-size:.7rem;color:#16a34a;">En avant-première</div>
                </div>
            </div>
            <div style="background:#fff7ed;border:1px solid #fed7aa;border-radius:12px;padding:12px 14px;display:flex;align-items:center;gap:10px;">
                <div style="width:34px;height:34px;border-radius:10px;background:rgba(254,168,55,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-fire" style="color:#fea837;font-size:.9rem;"></i>
                </div>
                <div>
                    <div style="font-weight:700;font-size:.78rem;color:#7c2d12;">Offres exclusives</div>
                    <div style="font-size:.7rem;color:#ea580c;">Promotions limitées</div>
                </div>
            </div>
            <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;padding:12px 14px;display:flex;align-items:center;gap:10px;">
                <div style="width:34px;height:34px;border-radius:10px;background:rgba(59,130,246,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-trophy" style="color:#11c06d;font-size:.9rem;"></i>
                </div>
                <div>
                    <div style="font-weight:700;font-size:.78rem;color:#1e3a8a;">Vos badges</div>
                    <div style="font-size:.7rem;color:#11c06d;">Suivi de progression</div>
                </div>
            </div>
            <div style="background:#fdf4ff;border:1px solid #e9d5ff;border-radius:12px;padding:12px 14px;display:flex;align-items:center;gap:10px;">
                <div style="width:34px;height:34px;border-radius:10px;background:rgba(168,85,247,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-calendar-check" style="color:#a855f7;font-size:.9rem;"></i>
                </div>
                <div>
                    <div style="font-weight:700;font-size:.78rem;color:#581c87;">Rappels cours</div>
                    <div style="font-size:.7rem;color:#a855f7;">Ne rien rater</div>
                </div>
            </div>
        </div>
        {{-- Boutons --}}
        <div style="padding:0 20px 24px;display:flex;flex-direction:column;gap:10px;">
            <button id="notif-accept-mobile" style="width:100%;background:linear-gradient(135deg,#11c06d,#239e79);color:#fff;border:none;padding:15px;border-radius:16px;font-weight:700;font-size:1rem;cursor:pointer;box-shadow:0 6px 20px rgba(17,192,109,.35);">
                <i class="fas fa-bell me-2"></i>Activer les notifications
            </button>
            <button id="notif-dismiss-mobile" style="width:100%;background:transparent;color:#9ca3af;border:none;padding:10px;border-radius:12px;font-weight:600;font-size:.88rem;cursor:pointer;">
                Pas maintenant
            </button>
        </div>
    </div>

    <style>
    @keyframes nbSlideDown { from{opacity:0;transform:translateX(-50%) translateY(-16px)} to{opacity:1;transform:translateX(-50%) translateY(0)} }
    </style>

    {{-- ═══ BANNIÈRE INSTALLATION PWA ═══════════════════════════════════ --}}
    <div id="pwa-banner" style="display:none; position:fixed; bottom:0; left:0; right:0; z-index:10000; background:linear-gradient(135deg,#11c06d 0%,#239e79 100%); color:#fff; padding:12px 20px; box-shadow:0 -4px 20px rgba(0,0,0,.2);">
        <div style="max-width:700px; margin:0 auto; display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
            <img src="{{ asset('images/icons/africode-192.png') }}" style="width:44px; height:44px; border-radius:12px; flex-shrink:0;" alt="AfriCode">
            <div style="flex:1; min-width:180px;">
                <div style="font-weight:700; font-size:.95rem;">Installer l'application AfriCode</div>
                <div style="font-size:.8rem; opacity:.9;">Accès rapide depuis votre écran d'accueil · Disponible hors ligne</div>
            </div>
            <div style="display:flex; gap:10px; align-items:center;">
                <button id="pwa-install-btn" style="background:#fff; color:#11c06d; border:none; padding:9px 22px; border-radius:22px; font-weight:700; font-size:.9rem; cursor:pointer; white-space:nowrap;">
                    <i class="fas fa-download me-1"></i>Installer
                </button>
                <button id="pwa-dismiss-btn" style="background:rgba(255,255,255,.2); border:none; color:#fff; width:34px; height:34px; border-radius:50%; cursor:pointer; font-size:1.2rem; display:flex; align-items:center; justify-content:center;">&times;</button>
            </div>
        </div>
    </div>

    <script>
    (function () {
        // ── PWA Install ──────────────────────────────────
        var deferredPrompt = null;
        var pwaBanner = document.getElementById('pwa-banner');

        window.addEventListener('beforeinstallprompt', function (e) {
            e.preventDefault();
            deferredPrompt = e;
            if (!localStorage.getItem('pwa-dismissed')) {
                setTimeout(function () { pwaBanner.style.display = 'block'; }, 3000);
            }
        });

        document.getElementById('pwa-install-btn').addEventListener('click', function () {
            pwaBanner.style.display = 'none';
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then(function (c) {
                    if (c.outcome === 'accepted') localStorage.setItem('pwa-installed', '1');
                    deferredPrompt = null;
                });
            }
        });

        document.getElementById('pwa-dismiss-btn').addEventListener('click', function () {
            pwaBanner.style.display = 'none';
            localStorage.setItem('pwa-dismissed', '1');
        });

        window.addEventListener('appinstalled', function () {
            pwaBanner.style.display = 'none';
            deferredPrompt = null;
        });

        // ── Notifications Push ───────────────────────────
        var isMobile   = window.innerWidth < 768;
        var bannerD    = document.getElementById('notif-banner-desktop');
        var bannerM    = document.getElementById('notif-banner-mobile');
        var overlayM   = document.getElementById('notif-sheet-overlay');

        function showNotifBannerIfNeeded() {
            if (!('Notification' in window)) return;
            if (Notification.permission !== 'default') return;
            if (sessionStorage.getItem('notif-asked')) return;
            var delay = isMobile ? 5000 : 8000;
            setTimeout(function () {
                if (isMobile) {
                    overlayM.style.display = 'block';
                    bannerM.style.display  = 'block';
                    requestAnimationFrame(function () {
                        bannerM.style.transform = 'translateY(0)';
                    });
                } else {
                    bannerD.style.display = 'block';
                }
            }, delay);
        }

        function hideBanners() {
            bannerD.style.display = 'none';
            overlayM.style.display = 'none';
            bannerM.style.transform = 'translateY(100%)';
            setTimeout(function () { bannerM.style.display = 'none'; }, 400);
            sessionStorage.setItem('notif-asked', '1');
        }

        function doSubscribe() {
            hideBanners();
            Notification.requestPermission().then(function (permission) {
                if (permission === 'granted' && 'serviceWorker' in navigator) {
                    navigator.serviceWorker.ready.then(function (reg) {
                        subscribeUserToPush(reg);
                    });
                }
            });
        }

        document.getElementById('notif-accept').addEventListener('click', doSubscribe);
        document.getElementById('notif-dismiss').addEventListener('click', hideBanners);
        document.getElementById('notif-accept-mobile').addEventListener('click', doSubscribe);
        document.getElementById('notif-dismiss-mobile').addEventListener('click', hideBanners);
        document.getElementById('notif-sheet-close').addEventListener('click', hideBanners);
        overlayM.addEventListener('click', hideBanners);

        function subscribeUserToPush(registration) {
            var vapidKey = '{{ config("services.vapid.public_key", "") }}';
            if (!vapidKey) return;
            var appServerKey = urlBase64ToUint8Array(vapidKey);
            registration.pushManager.subscribe({ userVisibleOnly: true, applicationServerKey: appServerKey })
                .then(function (sub) {
                    return fetch('/push-subscription', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                        body: JSON.stringify(sub)
                    });
                })
                .then(function () {
                    if ('Notification' in window && Notification.permission === 'granted') {
                        new Notification('AfriCode', {
                            body: 'Notifications activées ! Vous ne raterez plus rien.',
                            icon: '/images/icons/africode-192.png',
                            tag:  'africode-welcome',
                        });
                    }
                })
                .catch(function (err) { console.warn('Push subscription error:', err); });
        }

        function urlBase64ToUint8Array(base64String) {
            var padding = '='.repeat((4 - base64String.length % 4) % 4);
            var base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
            var rawData = window.atob(base64);
            var outputArray = new Uint8Array(rawData.length);
            for (var i = 0; i < rawData.length; ++i) { outputArray[i] = rawData.charCodeAt(i); }
            return outputArray;
        }

        document.addEventListener('DOMContentLoaded', showNotifBannerIfNeeded);
    })();
    </script>

    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: 60px; margin-bottom: 0; border-radius: 0;">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 60px; margin-bottom: 0; border-radius: 0;">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert" style="margin-top: 60px; margin-bottom: 0; border-radius: 0;">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert" style="margin-top: 60px; margin-bottom: 0; border-radius: 0;">
            <i class="fas fa-info-circle me-2"></i>
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div>
        @yield('content')
    </div>

    @include('components.footer') <!-- Inclure le footer -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    
    <!-- Alpine.js (nécessaire pour les interactions dynamiques) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Livewire Scripts -->
    @livewireScripts
    
    <script>
        AOS.init({
            once: true,
            duration: 800,
            offset: 100
        });

        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
    
    @stack('scripts')

    {{-- ═══ BANNIÈRE CONSENTEMENT COOKIES ══════════════════════════════ --}}
    <div id="cookie-banner" role="dialog" aria-label="Consentement cookies" style="display:none; position:fixed; bottom:0; left:0; right:0; z-index:99999; background:#fff; border-top:1px solid #e5e7eb; box-shadow:0 -4px 24px rgba(0,0,0,.12); padding:16px 20px;">
        <div style="max-width:1100px; margin:0 auto; display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
            <div style="flex:1; min-width:240px;">
                <p style="margin:0; font-size:.88rem; color:#374151; line-height:1.5;">
                    <i class="fas fa-cookie-bite" style="color:#11c06d; margin-right:6px;"></i>
                    <strong>Vos préférences de confidentialité</strong> — Nous utilisons des cookies pour améliorer votre expérience, analyser le trafic et personnaliser le contenu.
                    <a href="{{ route('pages.privacy') }}" style="color:#11c06d; font-weight:600;">En savoir plus</a>
                </p>
            </div>
            <div style="display:flex; gap:8px; flex-wrap:wrap; flex-shrink:0;">
                <button id="cookie-refuse" style="background:#f3f4f6; color:#6b7280; border:1px solid #e5e7eb; padding:8px 16px; border-radius:8px; font-size:.83rem; font-weight:600; cursor:pointer; white-space:nowrap;">Refuser</button>
                <button id="cookie-customize" style="background:#fff; color:#11c06d; border:1.5px solid #11c06d; padding:8px 16px; border-radius:8px; font-size:.83rem; font-weight:600; cursor:pointer; white-space:nowrap;">Personnaliser</button>
                <button id="cookie-accept" style="background:linear-gradient(135deg,#11c06d,#239e79); color:#fff; border:none; padding:8px 20px; border-radius:8px; font-size:.83rem; font-weight:700; cursor:pointer; white-space:nowrap;">Accepter tout</button>
            </div>
        </div>
    </div>

    {{-- ═══ MODAL PRÉFÉRENCES COOKIES ═══════════════════════════════════ --}}
    <div id="cookie-modal-overlay" style="display:none; position:fixed; inset:0; z-index:100000; background:rgba(0,0,0,.5); align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:16px; max-width:540px; width:calc(100% - 32px); max-height:90vh; overflow-y:auto; box-shadow:0 24px 64px rgba(0,0,0,.2);">
            <div style="padding:24px 24px 0; border-bottom:1px solid #e5e7eb; margin-bottom:0; display:flex; justify-content:space-between; align-items:center;">
                <h3 style="margin:0; font-size:1.1rem; font-weight:700; color:#1a1a2e;">
                    <i class="fas fa-sliders-h me-2" style="color:#11c06d;"></i>Gérer mes préférences
                </h3>
                <button id="cookie-modal-close" style="background:none; border:none; font-size:1.4rem; color:#9ca3af; cursor:pointer; line-height:1;">&times;</button>
            </div>
            <div style="padding:20px 24px;">
                <p style="font-size:.85rem; color:#6b7280; margin-bottom:20px;">Vous pouvez activer ou désactiver chaque catégorie de cookies. Les cookies nécessaires ne peuvent pas être désactivés car ils garantissent le bon fonctionnement du site.</p>

                <!-- Nécessaires -->
                <div style="border:1px solid #e5e7eb; border-radius:12px; padding:16px; margin-bottom:12px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <i class="fas fa-shield-alt" style="color:#11c06d; font-size:1rem;"></i>
                            <strong style="font-size:.9rem; color:#1a1a2e;">Cookies nécessaires</strong>
                        </div>
                        <span style="background:#dcfce7; color:#16a34a; font-size:.75rem; font-weight:700; padding:3px 10px; border-radius:20px;">Toujours actifs</span>
                    </div>
                    <p style="font-size:.8rem; color:#6b7280; margin:0;">Authentification, sécurité de session, panier, préférences essentielles. Indispensables au fonctionnement du site.</p>
                </div>

                <!-- Analytics -->
                <div style="border:1px solid #e5e7eb; border-radius:12px; padding:16px; margin-bottom:12px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <i class="fas fa-chart-bar" style="color:#11c06d; font-size:1rem;"></i>
                            <strong style="font-size:.9rem; color:#1a1a2e;">Cookies d'analyse</strong>
                        </div>
                        <label style="position:relative; display:inline-block; width:44px; height:24px; cursor:pointer;">
                            <input type="checkbox" id="cookie-analytics" style="opacity:0; width:0; height:0;">
                            <span class="cookie-toggle" data-for="analytics" style="position:absolute; inset:0; background:#d1d5db; border-radius:24px; transition:.3s; cursor:pointer;"></span>
                        </label>
                    </div>
                    <p style="font-size:.8rem; color:#6b7280; margin:0;">Nous aident à comprendre comment les visiteurs interagissent avec le site (pages vues, durée, clics). Aucune donnée personnelle identifiable.</p>
                </div>

                <!-- Marketing -->
                <div style="border:1px solid #e5e7eb; border-radius:12px; padding:16px; margin-bottom:20px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <i class="fas fa-bullhorn" style="color:#f59e0b; font-size:1rem;"></i>
                            <strong style="font-size:.9rem; color:#1a1a2e;">Cookies marketing</strong>
                        </div>
                        <label style="position:relative; display:inline-block; width:44px; height:24px; cursor:pointer;">
                            <input type="checkbox" id="cookie-marketing" style="opacity:0; width:0; height:0;">
                            <span class="cookie-toggle" data-for="marketing" style="position:absolute; inset:0; background:#d1d5db; border-radius:24px; transition:.3s; cursor:pointer;"></span>
                        </label>
                    </div>
                    <p style="font-size:.8rem; color:#6b7280; margin:0;">Permettent de vous proposer des contenus personnalisés et des publicités pertinentes, en lien avec votre parcours de formation.</p>
                </div>

                <div style="display:flex; gap:10px;">
                    <button id="cookie-save-prefs" style="flex:1; background:linear-gradient(135deg,#11c06d,#239e79); color:#fff; border:none; padding:10px; border-radius:10px; font-weight:700; font-size:.9rem; cursor:pointer;">
                        <i class="fas fa-save me-2"></i>Enregistrer mes choix
                    </button>
                    <button id="cookie-accept-all-modal" style="flex:1; background:#fff; color:#11c06d; border:1.5px solid #11c06d; padding:10px; border-radius:10px; font-weight:700; font-size:.9rem; cursor:pointer;">
                        Accepter tout
                    </button>
                </div>
                <p style="font-size:.73rem; color:#9ca3af; text-align:center; margin-top:12px; margin-bottom:0;">
                    Vous pouvez modifier vos préférences à tout moment depuis notre <a href="{{ route('pages.privacy') }}" style="color:#11c06d;">politique de confidentialité</a>.
                </p>
            </div>
        </div>
    </div>

    <style>
    .cookie-toggle::before {
        content:''; position:absolute; height:18px; width:18px;
        left:3px; bottom:3px; background:white; border-radius:50%; transition:.3s;
    }
    input:checked + .cookie-toggle { background:#11c06d; }
    input:checked + .cookie-toggle::before { transform:translateX(20px); }
    </style>

    <script>
    (function () {
        var COOKIE_KEY = 'africode_cookie_consent';
        var banner     = document.getElementById('cookie-banner');
        var overlay    = document.getElementById('cookie-modal-overlay');
        var saved      = localStorage.getItem(COOKIE_KEY);

        function hideBanner() { banner.style.display = 'none'; }
        function showBanner() { banner.style.display = 'block'; }
        function hideModal()  { overlay.style.display = 'none'; }
        function showModal()  {
            overlay.style.display = 'flex';
            var prefs = JSON.parse(localStorage.getItem(COOKIE_KEY) || '{}');
            document.getElementById('cookie-analytics').checked  = !!prefs.analytics;
            document.getElementById('cookie-marketing').checked  = !!prefs.marketing;
        }

        if (!saved) { setTimeout(showBanner, 1500); }

        document.getElementById('cookie-accept').addEventListener('click', function () {
            localStorage.setItem(COOKIE_KEY, JSON.stringify({ necessary:true, analytics:true, marketing:true, date: new Date().toISOString() }));
            hideBanner();
        });

        document.getElementById('cookie-refuse').addEventListener('click', function () {
            localStorage.setItem(COOKIE_KEY, JSON.stringify({ necessary:true, analytics:false, marketing:false, date: new Date().toISOString() }));
            hideBanner();
        });

        document.getElementById('cookie-customize').addEventListener('click', function () {
            hideBanner();
            showModal();
        });

        document.getElementById('cookie-modal-close').addEventListener('click', hideModal);
        overlay.addEventListener('click', function (e) { if (e.target === overlay) hideModal(); });

        document.getElementById('cookie-save-prefs').addEventListener('click', function () {
            var analytics = document.getElementById('cookie-analytics').checked;
            var marketing = document.getElementById('cookie-marketing').checked;
            localStorage.setItem(COOKIE_KEY, JSON.stringify({ necessary:true, analytics:analytics, marketing:marketing, date: new Date().toISOString() }));
            hideModal();
            hideBanner();
        });

        document.getElementById('cookie-accept-all-modal').addEventListener('click', function () {
            localStorage.setItem(COOKIE_KEY, JSON.stringify({ necessary:true, analytics:true, marketing:true, date: new Date().toISOString() }));
            hideModal();
            hideBanner();
        });

        // Exposer une fonction globale pour rouvrir les préférences
        window.openCookiePreferences = function () { showModal(); };
    })();
    </script>

    {{-- ═══ BOUTON SCROLL TO TOP (mobile & desktop) ═══════════════════ --}}
    <button id="scrollTopBtn" aria-label="Remonter en haut" title="Haut de page">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="18 15 12 9 6 15"/>
        </svg>
    </button>

    <style>
    #scrollTopBtn {
        position: fixed;
        bottom: 24px;
        right: 20px;
        z-index: 8000;
        width: 46px;
        height: 46px;
        border-radius: 50%;
        border: none;
        cursor: pointer;
        background: linear-gradient(135deg, #11c06d, #239e79);
        color: #fff;
        box-shadow: 0 4px 18px rgba(17,192,109,.40);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transform: translateY(12px) scale(.85);
        transition: opacity .3s ease, visibility .3s ease, transform .3s cubic-bezier(.4,0,.2,1), box-shadow .25s;
    }
    #scrollTopBtn svg { width: 20px; height: 20px; }
    #scrollTopBtn.stt-visible {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }
    #scrollTopBtn:hover {
        box-shadow: 0 8px 28px rgba(17,192,109,.55);
        transform: translateY(-3px) scale(1.05);
    }
    #scrollTopBtn:active { transform: scale(.95); }

    /* Sur desktop : taille légèrement plus grande, positionné plus haut si bannière PWA visible */
    @media (min-width: 768px) {
        #scrollTopBtn { width: 50px; height: 50px; bottom: 32px; right: 28px; }
        #scrollTopBtn svg { width: 22px; height: 22px; }
    }
    /* Décale vers le haut quand la bannière PWA est visible */
    body.pwa-banner-open #scrollTopBtn { bottom: 90px; }
    </style>

    <script>
    (function () {
        var btn = document.getElementById('scrollTopBtn');
        var THRESHOLD = 300;

        function onScroll() {
            if (window.scrollY > THRESHOLD) {
                btn.classList.add('stt-visible');
            } else {
                btn.classList.remove('stt-visible');
            }
        }

        window.addEventListener('scroll', onScroll, { passive: true });

        btn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        /* Synchronise la classe body quand la bannière PWA s'affiche */
        var pwaBanner = document.getElementById('pwa-banner');
        if (pwaBanner) {
            var pwaObserver = new MutationObserver(function () {
                document.body.classList.toggle('pwa-banner-open', pwaBanner.style.display !== 'none');
            });
            pwaObserver.observe(pwaBanner, { attributes: true, attributeFilter: ['style'] });
        }
    })();
    </script>

    {{-- ═══ PROTECTION GLOBALE (clic droit + DevTools + raccourcis) ══════ --}}
    @include('partials.security-lock')
</body>
</html>
