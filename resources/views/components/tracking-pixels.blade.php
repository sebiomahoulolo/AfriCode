@php
    $trackingEnabled = config('tracking.enabled', true);
    $gtmId = config('tracking.gtm_id');
    $meta = config('tracking.meta', []);
    $ga4 = config('tracking.ga4', []);
    $linkedin = config('tracking.linkedin', []);
    $tiktok = config('tracking.tiktok', []);
    $twitter = config('tracking.twitter', []);
    $siteName = config('tracking.site_name', 'AfriCode');
    $pageUrl = url()->current();
    $pageTitle = $attributes->get('title') ?? config('app.name');
@endphp
@if($trackingEnabled)
{{-- dataLayer pour GTM / GA4 / événements personnalisés (try/catch si Tracking Prevention bloque le stockage) --}}
<script>
(function() {
    try {
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            'site': '{{ $siteName }}',
            'page_url': '{{ $pageUrl }}',
            'page_title': '{{ addslashes($pageTitle) }}',
            'environment': '{{ config('app.env') }}'
        });
    } catch (e) { /* Tracking Prevention ou blocage navigateur */ }
})();
</script>

@if($gtmId)
{{-- Google Tag Manager : un seul script gère tout (vous configurez les tags dans GTM) --}}
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','{{ $gtmId }}');</script>
@else
{{-- Pixels individuels (si pas de GTM) --}}

@if(!empty($meta['enabled']) && !empty($meta['pixel_id']))
<!-- Meta (Facebook / Instagram) Pixel -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}
(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '{{ $meta['pixel_id'] }}');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ $meta['pixel_id'] }}&ev=PageView&noscript=1" alt="" /></noscript>
<!-- End Meta Pixel -->
@endif

@if(!empty($ga4['enabled']) && !empty($ga4['measurement_id']))
<!-- Google Analytics 4 -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga4['measurement_id'] }}"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', '{{ $ga4['measurement_id'] }}', { 'send_page_view': true });
</script>
@endif

@if(!empty($linkedin['enabled']) && !empty($linkedin['partner_id']))
<!-- LinkedIn Insight Tag -->
<script type="text/javascript">
_linkedin_partner_id = "{{ $linkedin['partner_id'] }}";
window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
window._linkedin_data_partner_ids.push(_linkedin_partner_id);
</script>
<script type="text/javascript">(function(l) {
if (!l){window.lintrk = function(a,b){window.lintrk.q.push([a,b])};
window.lintrk.q=[]}
var s = document.getElementsByTagName("script")[0];
var b = document.createElement("script");
b.type = "text/javascript"; b.async = true;
b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
s.parentNode.insertBefore(b, s);
})(window.lintrk);</script>
<noscript><img height="1" width="1" style="display:none;" alt="" src="https://px.ads.linkedin.com/collect/?pid={{ $linkedin['partner_id'] }}&fmt=gif" /></noscript>
@endif

@if(!empty($tiktok['enabled']) && !empty($tiktok['pixel_id']))
<!-- TikTok Pixel -->
<script>
!function (w, d, t) {
  w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};
  ttq.load('{{ $tiktok['pixel_id'] }}');
  ttq.page();
}(window, document, 'ttq');
</script>
@endif

@if(!empty($twitter['enabled']) && !empty($twitter['pixel_id']))
<!-- Twitter / X Pixel -->
<script>
!function(e,t,n,s,u,a){e.twq||(s=e.twq=function(){s.exe?s.exe.apply(s,arguments):s.queue.push(arguments);
},s.version='1.1',s.queue=[],u=t.createElement(n),u.async=!0,u.src='https://static.ads-twitter.com/uwt.js',
a=t.getElementsByTagName(n)[0];a.parentNode.insertBefore(u,a))}(window,document,'script');
twq('config','{{ $twitter['pixel_id'] }}');
twq('event','page_view');
</script>
@endif
@endif

{{-- Script de tracking unifié : UTM, stockage attribution, événements. Résilient si Tracking Prevention / adblock bloque storage ou scripts. --}}
<script>
(function() {
    try {
        var STORAGE_KEY_UTM = 'africode_utm';
        function getParam(name) {
            var m = new RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
            return m ? decodeURIComponent(m[1].replace(/\+/g, ' ')) : null;
        }
        function getUtm() {
            var u = {}, k = ['utm_source','utm_medium','utm_campaign','utm_term','utm_content'];
            k.forEach(function(key) { var v = getParam(key); if (v) u[key] = v; });
            return Object.keys(u).length ? u : null;
        }
        function saveUtm(utm) {
            if (!utm) return;
            try {
                var p = { utm: utm, landing: window.location.href, ts: Date.now() };
                sessionStorage.setItem(STORAGE_KEY_UTM, JSON.stringify(p));
                localStorage.setItem(STORAGE_KEY_UTM, JSON.stringify(p));
            } catch (e) { /* Storage bloqué (Tracking Prevention, mode privé, etc.) */ }
        }
        function initUtm() {
            var utm = getUtm();
            if (utm) {
                saveUtm(utm);
                try {
                    window.dataLayer = window.dataLayer || [];
                    window.dataLayer.push({ event: 'utm_captured', utm_params: utm, landing_page: window.location.href });
                } catch (e) {}
            }
        }
        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initUtm);
        else initUtm();

        window.AfriCodeTracking = window.AfriCodeTracking || {};
        window.AfriCodeTracking.track = function(eventName, params) {
            try {
                params = params || {};
                params.event = eventName;
                window.dataLayer = window.dataLayer || [];
                window.dataLayer.push(params);
                if (typeof fbq === 'function') {
                    var map = { sign_up: 'CompleteRegistration', purchase: 'Purchase', view_content: 'ViewContent', contact: 'Contact', lead: 'Lead', search: 'Search' };
                    fbq('track', map[eventName] || eventName, params);
                }
                if (typeof ttq !== 'undefined' && ttq.track) ttq.track(eventName, params);
                if (typeof gtag === 'function') gtag('event', eventName, params);
            } catch (e) {}
        };
        window.AfriCodeTracking.getStoredUtm = function() {
            try {
                var r = sessionStorage.getItem(STORAGE_KEY_UTM) || localStorage.getItem(STORAGE_KEY_UTM);
                return r ? JSON.parse(r) : null;
            } catch (e) { return null; }
        };
    } catch (e) { /* Blocage global (Tracking Prevention, etc.) : tracking désactivé pour cet utilisateur */ }
})();
</script>
@endif
