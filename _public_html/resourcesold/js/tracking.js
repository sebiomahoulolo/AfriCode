/**
 * AfriCode – Tracking unifié : UTM, attribution, stockage, événements
 * Compatible tous réseaux (Meta, GA4, LinkedIn, TikTok, Twitter) via dataLayer + pixels
 */
(function () {
  'use strict';

  var STORAGE_KEY_UTM = 'africode_utm';
  var STORAGE_KEY_LAST = 'africode_last_visit';
  var EXPIRY_DAYS = 30;

  function getQueryParam(name) {
    var match = new RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
    return match ? decodeURIComponent(match[1].replace(/\+/g, ' ')) : null;
  }

  function getUtmParams() {
    var utm = {};
    var keys = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];
    keys.forEach(function (k) {
      var v = getQueryParam(k);
      if (v) utm[k] = v;
    });
    return Object.keys(utm).length ? utm : null;
  }

  function saveUtm(utm) {
    if (!utm || !window.sessionStorage) return;
    try {
      var payload = {
        utm: utm,
        landing: window.location.href,
        ts: Date.now()
      };
      sessionStorage.setItem(STORAGE_KEY_UTM, JSON.stringify(payload));
      localStorage.setItem(STORAGE_KEY_UTM, JSON.stringify(payload));
      localStorage.setItem(STORAGE_KEY_LAST, String(Date.now()));
    } catch (e) {}
  }

  function loadStoredUtm() {
    try {
      var raw = sessionStorage.getItem(STORAGE_KEY_UTM) || localStorage.getItem(STORAGE_KEY_UTM);
      if (raw) return JSON.parse(raw);
    } catch (e) {}
    return null;
  }

  function pushUtmToDataLayer(stored) {
    window.dataLayer = window.dataLayer || [];
    if (stored && stored.utm) {
      window.dataLayer.push({
        event: 'utm_captured',
        utm_params: stored.utm,
        landing_page: stored.landing || null
      });
    }
  }

  function init() {
    var utm = getUtmParams();
    if (utm) {
      saveUtm(utm);
      pushUtmToDataLayer({ utm: utm, landing: window.location.href });
    } else {
      var stored = loadStoredUtm();
      if (stored) pushUtmToDataLayer(stored);
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  window.AfriCodeTracking = window.AfriCodeTracking || {};
  window.AfriCodeTracking.getStoredUtm = loadStoredUtm;
  window.AfriCodeTracking.saveUtm = saveUtm;
})();
