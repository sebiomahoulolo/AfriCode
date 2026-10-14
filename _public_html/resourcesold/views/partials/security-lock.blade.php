{{--
    AfriCode — Protection clic droit & DevTools
    Inclure juste avant </body> dans chaque layout
--}}

{{-- Overlay DevTools (affiché si inspection détectée) --}}
<div id="ac-lock-overlay" style="
    display:none;position:fixed;inset:0;z-index:2147483647;
    background:linear-gradient(135deg,#0f1629 0%,#1a1a2e 60%,#0f3460 100%);
    align-items:center;justify-content:center;flex-direction:column;
    font-family:'Segoe UI',system-ui,sans-serif;text-align:center;padding:24px;">

    <div style="display:flex;gap:4px;align-items:flex-end;margin-bottom:24px;">
        <span style="display:block;width:7px;height:18px;border-radius:4px;background:#fea837;"></span>
        <span style="display:block;width:7px;height:26px;border-radius:4px;background:#fa1c39;"></span>
        <span style="display:block;width:7px;height:34px;border-radius:4px;background:#11c06d;"></span>
    </div>

    <div style="width:72px;height:72px;border-radius:20px;background:rgba(250,28,57,.15);border:2px solid rgba(250,28,57,.3);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#fa1c39" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
        </svg>
    </div>

    <h2 style="font-size:1.3rem;font-weight:800;color:#fff;margin:0 0 8px;letter-spacing:-.3px;">Accès non autorisé</h2>
    <p style="font-size:.88rem;color:rgba(255,255,255,.55);max-width:320px;margin:0 auto 20px;line-height:1.6;">
        L'inspection du code source de cette plateforme est interdite. Tout le contenu est protégé par la propriété intellectuelle d'AfriCode.
    </p>

    <div style="background:rgba(250,28,57,.1);border:1px solid rgba(250,28,57,.25);border-radius:12px;padding:12px 20px;font-size:.8rem;color:rgba(255,255,255,.5);max-width:300px;">
        <svg style="vertical-align:middle;margin-right:6px;" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fa1c39" stroke-width="2.5">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        Fermez les outils de développement pour continuer
    </div>
</div>

<script>
(function(){
'use strict';

/* ── 1. Clic droit bloqué sur tout le document ── */
document.addEventListener('contextmenu', function(e){
    e.preventDefault();
    e.stopPropagation();
    return false;
}, true);

/* ── 2. Sélection de texte désactivée (CSS + JS) ── */
document.addEventListener('selectstart', function(e){
    /* On autorise la sélection dans les champs de saisie */
    var tag = (e.target.tagName || '').toLowerCase();
    if(tag==='input'||tag==='textarea'||e.target.isContentEditable) return;
    e.preventDefault();
    return false;
}, true);

/* ── 3. Glisser-déposer d'images et de liens bloqué ── */
document.addEventListener('dragstart', function(e){
    if(e.target.tagName==='IMG'||e.target.tagName==='A'){
        e.preventDefault();
        return false;
    }
}, true);

/* ── 4. Raccourcis clavier bloqués ── */
document.addEventListener('keydown', function(e){
    var key = e.key || '';

    /* F12 */
    if(key==='F12'){ e.preventDefault(); showOverlay(); return; }

    /* Ctrl/Cmd + Shift + I/J/C/K/U */
    if((e.ctrlKey||e.metaKey) && e.shiftKey &&
       ['i','j','c','k','I','J','C','K'].indexOf(key) !== -1){
        e.preventDefault(); showOverlay(); return;
    }

    /* Ctrl/Cmd + U (voir source) */
    if((e.ctrlKey||e.metaKey) && (key==='u'||key==='U')){
        e.preventDefault(); showOverlay(); return;
    }

    /* Ctrl/Cmd + S (enregistrer) */
    if((e.ctrlKey||e.metaKey) && (key==='s'||key==='S')){
        e.preventDefault(); return;
    }

    /* Ctrl/Cmd + P (imprimer) */
    if((e.ctrlKey||e.metaKey) && (key==='p'||key==='P')){
        e.preventDefault(); return;
    }

    /* Ctrl/Cmd + A (tout sélectionner) — bloqué hors formulaires */
    if((e.ctrlKey||e.metaKey) && (key==='a'||key==='A')){
        var tag = (document.activeElement && document.activeElement.tagName || '').toLowerCase();
        if(tag!=='input'&&tag!=='textarea'&&!document.activeElement.isContentEditable){
            e.preventDefault(); return;
        }
    }
}, true);

/* ── 5. Détection ouverture DevTools (taille fenêtre) ── */
@if(app()->isProduction())
var overlay  = document.getElementById('ac-lock-overlay');
var shown    = false;
var threshold = 160;

function showOverlay(){
    if(overlay){ overlay.style.display='flex'; shown=true; }
}
function hideOverlay(){
    if(overlay && shown){
        var w = window.outerWidth - window.innerWidth;
        var h = window.outerHeight - window.innerHeight;
        if(w<=threshold && h<=threshold){ overlay.style.display='none'; shown=false; }
    }
}
function checkDevTools(){
    var w = window.outerWidth - window.innerWidth;
    var h = window.outerHeight - window.innerHeight;
    if((w>threshold||h>threshold) && !shown) showOverlay();
}
window.addEventListener('resize', function(){ checkDevTools(); hideOverlay(); });
setInterval(checkDevTools, 1000);
checkDevTools();
@endif

})();
</script>

{{-- CSS global de protection --}}
<style>
/* Désactive la sélection de texte sur le contenu (pas les inputs) */
body *:not(input):not(textarea):not([contenteditable]) {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
/* Inputs et formulaires restent utilisables */
input, textarea, [contenteditable], select {
    -webkit-user-select: text !important;
    user-select: text !important;
}
/* Désactive le drag sur les images */
img { -webkit-user-drag: none; user-drag: none; pointer-events: none; }
/* Réactive le pointer-events sur les liens et boutons qui contiennent des images */
a img, button img { pointer-events: auto; }
</style>
