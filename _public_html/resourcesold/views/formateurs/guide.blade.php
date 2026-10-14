@extends('formateurs.layouts.app')

@section('title', 'AfriCode - Guide et Contrat')
@section('page-title', 'Guide et Contrat')
@section('page-subtitle', 'Consultez le guide du formateur et le contrat')

@section('header-actions')
    <a href="{{ route('formateur.dashboard') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-2"></i>Retour
    </a>
@endsection

@section('styles')
<style>
    /* ── Onglets ── */
    .doc-tabs { display:flex; border-bottom:2px solid #e9ecef; margin-bottom:.75rem; }
    .doc-tab {
        padding:.5rem 1.25rem; background:none; border:none;
        border-bottom:3px solid transparent; margin-bottom:-2px;
        color:#6c757d; font-weight:500; font-size:.9rem; cursor:pointer; transition:all .2s;
    }
    .doc-tab:hover  { color:#11c06d; background:rgba(17,192,109,.05); }
    .doc-tab.active { color:#11c06d; border-bottom-color:#11c06d; }

    .doc-panel { display:none; }
    .doc-panel.active { display:flex; flex-direction:column; }

    /* ── Barre titre ── */
    .doc-titlebar {
        background:linear-gradient(135deg,#11c06d,#239e79);
        color:white; padding:.5rem 1.25rem;
        border-radius:8px 8px 0 0;
        font-size:.85rem; font-weight:600;
        user-select:none; -webkit-user-select:none;
    }

    /* ── Visionneuse LinkedIn style ── */
    .doc-wrap {
        max-width: 820px;
        margin: 0 auto;
        width: 100%;
    }
    .lkd-viewer {
        position:relative;
        background:#1b1f23;
        height:calc(100vh - 130px);
        min-height:560px;
        display:flex;
        flex-direction:column;
        align-items:center;
        justify-content:space-between;
        border-radius:0 0 8px 8px;
        overflow:hidden;
        user-select:none;
        -webkit-user-select:none;
    }

    /* Zone canvas */
    .lkd-canvas-wrap {
        flex:1;
        width:100%;
        display:flex;
        align-items:flex-start;
        justify-content:center;
        padding:.5rem 0;
        overflow-y:auto;
        overflow-x:hidden;
        position:relative;
    }

    .lkd-canvas-wrap canvas {
        display:block;
        box-shadow:0 8px 40px rgba(0,0,0,.6);
        border-radius:2px;
    }

    /* Flèches navigation */
    .lkd-arrow {
        position:absolute;
        top:50%;
        transform:translateY(-50%);
        width:40px; height:40px;
        background:rgba(255,255,255,.15);
        border:none; border-radius:50%;
        color:white; font-size:1rem;
        display:flex; align-items:center; justify-content:center;
        cursor:pointer; transition:background .2s; z-index:20;
    }
    .lkd-arrow:hover:not(:disabled) { background:rgba(255,255,255,.35); }
    .lkd-arrow:disabled { opacity:.25; cursor:default; }
    .lkd-arrow.prev { left:.75rem; }
    .lkd-arrow.next { right:.75rem; }

    /* Barre bas */
    .lkd-bar {
        width:100%;
        display:flex; align-items:center; justify-content:center; gap:.75rem;
        padding:.5rem .75rem;
        background:rgba(0,0,0,.35);
        color:rgba(255,255,255,.8);
        font-size:.82rem;
        flex-shrink:0;
    }
    .lkd-bar .page-input {
        width:48px; text-align:center;
        background:rgba(255,255,255,.12);
        border:1px solid rgba(255,255,255,.25);
        color:white; border-radius:4px;
        padding:.15rem .3rem; font-size:.82rem;
    }
    .lkd-bar .page-input:focus { outline:none; background:rgba(255,255,255,.2); }

    /* Spinner chargement */
    .lkd-spinner {
        color:rgba(255,255,255,.7);
        font-size:.9rem;
        display:flex; flex-direction:column; align-items:center; gap:.75rem;
    }
    .spin { animation:spin .8s linear infinite; font-size:2rem; }
    @keyframes spin { to { transform:rotate(360deg); } }
</style>
@endsection

@section('content')
@php
    $guideUrl   = asset('storage/documents/GUIDE OFFICIEL DU FORMATEUR CRÉATION D.pdf');
    $contratUrl = asset('storage/documents/CONTRAT DE COLLABORATION ENTRE AFRICODE ET LE FORMATEUR.pdf');
@endphp

<div class="card-modern" data-aos="fade-up">
    <div class="card-body-modern" style="padding:1rem;">

        <div class="doc-wrap">
        <div class="doc-tabs">
            <button class="doc-tab active" onclick="switchDoc('guide', this)">
                <i class="fas fa-book me-1"></i>Guide du Formateur
            </button>
            <button class="doc-tab" onclick="switchDoc('contrat', this)">
                <i class="fas fa-file-contract me-1"></i>Contrat
            </button>
        </div>
        </div>

        {{-- ── GUIDE ── --}}
        <div id="panel-guide" class="doc-panel active">
            <div class="doc-wrap">
                <div class="doc-titlebar">
                    <i class="fas fa-file-pdf me-2"></i>Guide Officiel du Formateur
                </div>
                <div class="lkd-viewer">
                    <div class="lkd-canvas-wrap" id="guide-wrap">
                        <div class="lkd-spinner" id="guide-spinner">
                            <i class="fas fa-circle-notch spin"></i>Chargement…
                        </div>
                        <canvas id="guide-canvas" style="display:none;"
                            oncontextmenu="return false;" ondragstart="return false;"></canvas>
                    </div>
                    <button class="lkd-arrow prev" id="guide-prev" onclick="go('guide',-1)" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="lkd-arrow next" id="guide-next" onclick="go('guide',1)" disabled>
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <div class="lkd-bar">
                        <span>Page</span>
                        <input class="page-input" id="guide-num" type="number" min="1" value="1"
                               onchange="jumpTo('guide', this.value)">
                        <span>sur <span id="guide-total">—</span></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── CONTRAT ── --}}
        <div id="panel-contrat" class="doc-panel">
            <div class="doc-wrap">
                <div class="doc-titlebar">
                    <i class="fas fa-file-contract me-2"></i>Contrat de Collaboration AfriCode
                </div>
                <div class="lkd-viewer">
                    <div class="lkd-canvas-wrap" id="contrat-wrap">
                        <div class="lkd-spinner" id="contrat-spinner">
                            <i class="fas fa-circle-notch spin"></i>Chargement…
                        </div>
                        <canvas id="contrat-canvas" style="display:none;"
                            oncontextmenu="return false;" ondragstart="return false;"></canvas>
                    </div>
                    <button class="lkd-arrow prev" id="contrat-prev" onclick="go('contrat',-1)" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="lkd-arrow next" id="contrat-next" onclick="go('contrat',1)" disabled>
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <div class="lkd-bar">
                        <span>Page</span>
                        <input class="page-input" id="contrat-num" type="number" min="1" value="1"
                               onchange="jumpTo('contrat', this.value)">
                        <span>sur <span id="contrat-total">—</span></span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
pdfjsLib.GlobalWorkerOptions.workerSrc =
    'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

const URLS = {
    guide:   '{{ $guideUrl }}',
    contrat: '{{ $contratUrl }}'
};

const state = {
    guide:   { pdf: null, page: 1, total: 0, rendering: false },
    contrat: { pdf: null, page: 1, total: 0, rendering: false }
};

/* ── Chargement PDF ── */
async function loadPDF(type) {
    try {
        const pdf = await pdfjsLib.getDocument(URLS[type]).promise;
        state[type].pdf   = pdf;
        state[type].total = pdf.numPages;
        state[type].page  = 1;

        document.getElementById(type + '-total').textContent = pdf.numPages;
        document.getElementById(type + '-num').max = pdf.numPages;
        document.getElementById(type + '-spinner').style.display = 'none';

        const canvas = document.getElementById(type + '-canvas');
        canvas.style.display = 'block';

        await render(type, 1);
    } catch(e) {
        document.getElementById(type + '-spinner').innerHTML =
            '<i class="fas fa-exclamation-triangle" style="color:#fea837;font-size:2rem;"></i>' +
            '<span style="color:#fea837;">Fichier introuvable</span>';
    }
}

/* ── Rendu d'une page ── */
async function render(type, pageNum) {
    const s = state[type];
    if (!s.pdf || s.rendering) return;
    s.rendering = true;

    const canvas = document.getElementById(type + '-canvas');
    const wrap   = document.getElementById(type + '-wrap');
    const ctx    = canvas.getContext('2d');

    const page   = await s.pdf.getPage(pageNum);
    const baseVp = page.getViewport({ scale: 1 });

    /* Largeur fiable : on mesure le .lkd-viewer parent (toujours visible) */
    const viewer = wrap.closest('.lkd-viewer');
    const availW = viewer?.offsetWidth
                || document.querySelector('.doc-wrap')?.offsetWidth
                || (window.innerWidth - 320);
    const scale  = (availW - 16) / baseVp.width;

    const vp = page.getViewport({ scale });
    canvas.width  = vp.width;
    canvas.height = vp.height;

    await page.render({ canvasContext: ctx, viewport: vp }).promise;

    s.rendering = false;
    updateBar(type);
}

/* ── Mise à jour contrôles ── */
function updateBar(type) {
    const s = state[type];
    document.getElementById(type + '-num').value   = s.page;
    document.getElementById(type + '-prev').disabled = s.page <= 1;
    document.getElementById(type + '-next').disabled = s.page >= s.total;
}

/* ── Navigation ── */
async function go(type, dir) {
    const s = state[type];
    const np = s.page + dir;
    if (np < 1 || np > s.total) return;
    s.page = np;
    await render(type, s.page);
}

async function jumpTo(type, val) {
    const s = state[type];
    const p = Math.max(1, Math.min(parseInt(val) || 1, s.total));
    s.page = p;
    await render(type, s.page);
}

/* ── Clavier (flèches) ── */
document.addEventListener('keydown', async e => {
    const active = document.querySelector('.doc-panel.active')?.id.replace('panel-', '');
    if (!active) return;
    if (e.key === 'ArrowRight' || e.key === 'ArrowDown') { e.preventDefault(); await go(active, 1); }
    if (e.key === 'ArrowLeft'  || e.key === 'ArrowUp')   { e.preventDefault(); await go(active, -1); }
});

/* ── Swipe mobile ── */
(function() {
    let startX = 0;
    document.querySelectorAll('.lkd-viewer').forEach(v => {
        v.addEventListener('touchstart', e => { startX = e.touches[0].clientX; }, {passive:true});
        v.addEventListener('touchend',   e => {
            const dx = e.changedTouches[0].clientX - startX;
            const type = v.closest('.doc-panel').id.replace('panel-', '');
            if (dx < -50) go(type, 1);
            if (dx >  50) go(type, -1);
        }, {passive:true});
    });
})();

/* ── Changement d'onglet ── */
function switchDoc(type, btn) {
    document.querySelectorAll('.doc-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.doc-tab').forEach(t => t.classList.remove('active'));
    document.getElementById('panel-' + type).classList.add('active');
    btn.classList.add('active');
    if (!state[type].pdf) loadPDF(type);
    else render(type, state[type].page);
}

/* ── Blocage clic droit ── */
document.addEventListener('contextmenu', e => e.preventDefault());
document.addEventListener('dragstart',   e => e.preventDefault());

/* ── Init ── */
document.addEventListener('DOMContentLoaded', () => loadPDF('guide'));
</script>
@endsection
