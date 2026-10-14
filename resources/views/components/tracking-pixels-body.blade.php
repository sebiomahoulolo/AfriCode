@php
    $trackingEnabled = config('tracking.enabled', true);
    $gtmId = config('tracking.gtm_id');
@endphp
@if($trackingEnabled && $gtmId)
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}" height="0" width="0" style="display:none;visibility:hidden" title="GTM"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
@endif
