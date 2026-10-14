@extends('formateurs.layouts.app')

@section('title', 'AfriCode - Guide et Contrat')
@section('page-title', 'Guide et Contrat')
@section('page-subtitle', 'Consultez le guide du formateur et le contrat')

@section('header-actions')
    <a href="{{ route('formateur.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour
    </a>
@endsection

@section('styles')
<style>
    .document-tabs {
        border-bottom: 2px solid #e9ecef;
        margin-bottom: 1.5rem;
    }
    .document-tab {
        padding: .75rem 1.5rem;
        background: none;
        border: none;
        border-bottom: 3px solid transparent;
        color: #6c757d;
        font-weight: 500;
        cursor: pointer;
        transition: all .25s;
    }
    .document-tab:hover { color:#1EA38B; background:rgba(30,163,139,.05); }
    .document-tab.active { color:#1EA38B; border-bottom-color:#1EA38B; }

    .document-content { display:none; }
    .document-content.active { display:block; }

    /* Barre de navigation intégrée */
    .pdf-toolbar {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        padding: .6rem 1rem;
        background: #f1f5f4;
        border: 1px solid #dee2e6;
        border-bottom: none;
        border-radius: 10px 10px 0 0;
    }
    .pdf-nav-btn {
        width: 34px; height: 34px;
        border-radius: 50%;
        border: none;
        background: linear-gradient(135deg,#1EA38B,#27B371);
        color: #fff;
        font-size: .85rem;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: all .2s;
        box-shadow: 0 2px 6px rgba(0,0,0,.12);
    }
    .pdf-nav-btn:hover:not(:disabled) { transform:scale(1.1); }
    .pdf-nav-btn:disabled { opacity:.4; cursor:not-allowed; }
    .pdf-page-badge {
        background:#1EA38B; color:#fff;
        font-size:.8rem; font-weight:600;
        padding:.25rem .9rem; border-radius:20px;
        min-width:100px; text-align:center;
    }

    /* Visionneuse */
    .pdf-viewer-wrapper {
        width: 100%;
        height: 68vh;
        border: 1px solid #dee2e6;
        border-radius: 0 0 10px 10px;
        background: #525659;
        overflow-y: auto;
        overflow-x: hidden;
    }
    .pdf-viewer {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1rem .5rem;
        gap: .75rem;
    }
    .pdf-page canvas {
        display: block;
        box-shadow: 0 4px 14px rgba(0,0,0,.4);
        border-radius: 2px;
        max-width: 100%;
    }
</style>
@endsection

@section('content')
    <div class="card-modern" data-aos="fade-up">
        <div class="card-body-modern">

            <div class="document-tabs">
                <button class="document-tab active" onclick="showDocument('guide', this)">
                    <i class="fas fa-book me-2"></i>Guide du Formateur
                </button>
                <button class="document-tab" onclick="showDocument('contrat', this)">
                    <i class="fas fa-file-contract me-2"></i>Contrat
                </button>
            </div>

            @php
                $guideUrl   = asset('storage/documents/GUIDE OFFICIEL DU FORMATEUR CRÉATION D.pdf');
                $contratUrl = asset('storage/documents/CONTRAT DE COLLABORATION ENTRE AFRICODE ET LE FORMATEUR.pdf');
            @endphp

            <!-- Guide du Formateur -->
            <div id="guide-content" class="document-content active">
                <div class="pdf-toolbar">
                    <button class="pdf-nav-btn" onclick="previousPage('guide')" id="guide-prev" title="Précédent" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <span class="pdf-page-badge" id="guide-page-info">Chargement…</span>
                    <button class="pdf-nav-btn" onclick="nextPage('guide')" id="guide-next" title="Suivant" disabled>
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <div class="pdf-viewer-wrapper">
                    <div class="pdf-viewer" id="guide-pdf-container">
                        <div class="text-white mt-4 opacity-75"><i class="fas fa-spinner fa-spin fa-2x"></i></div>
                    </div>
                </div>
                <div id="guide-error" class="alert alert-warning d-none mt-2" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Document introuvable — vérifiez <code>public/storage/documents/</code>
                </div>
            </div>

            <!-- Contrat -->
            <div id="contrat-content" class="document-content">
                <div class="pdf-toolbar">
                    <button class="pdf-nav-btn" onclick="previousPage('contrat')" id="contrat-prev" title="Précédent" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <span class="pdf-page-badge" id="contrat-page-info">Chargement…</span>
                    <button class="pdf-nav-btn" onclick="nextPage('contrat')" id="contrat-next" title="Suivant" disabled>
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <div class="pdf-viewer-wrapper">
                    <div class="pdf-viewer" id="contrat-pdf-container">
                        <div class="text-white mt-4 opacity-75"><i class="fas fa-spinner fa-spin fa-2x"></i></div>
                    </div>
                </div>
                <div id="contrat-error" class="alert alert-warning d-none mt-2" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Document introuvable — vérifiez <code>public/storage/documents/</code>
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

    const pdfDocs  = { guide: null, contrat: null };
    const pdfState = {
        guide:   { current: 1, total: 0 },
        contrat: { current: 1, total: 0 }
    };
    const pdfUrls = {
        guide:   '{{ $guideUrl }}',
        contrat: '{{ $contratUrl }}'
    };

    async function loadPDF(type) {
        const container = document.getElementById(type + '-pdf-container');
        const errorDiv  = document.getElementById(type + '-error');
        try {
            pdfDocs[type] = await pdfjsLib.getDocument(pdfUrls[type]).promise;
            pdfState[type].total = pdfDocs[type].numPages;
            errorDiv.classList.add('d-none');
            await renderPage(type, 1);
        } catch (e) {
            console.error(e);
            container.innerHTML = '';
            errorDiv.classList.remove('d-none');
        }
    }

    async function renderPage(type, pageNum) {
        const container = document.getElementById(type + '-pdf-container');
        if (!container || !pdfDocs[type]) return;

        pdfState[type].current = pageNum;
        container.innerHTML = '<div class="text-white mt-4 opacity-75"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';

        const page     = await pdfDocs[type].getPage(pageNum);
        // Scale adaptatif : on remplit ~90% de la largeur du conteneur
        const wrapper  = container.closest('.pdf-viewer-wrapper');
        const maxW     = (wrapper ? wrapper.clientWidth : 800) * 0.90;
        const baseVp   = page.getViewport({ scale: 1 });
        const scale    = maxW / baseVp.width;
        const viewport = page.getViewport({ scale });

        const canvas  = document.createElement('canvas');
        canvas.height = viewport.height;
        canvas.width  = viewport.width;
        canvas.className = 'pdf-page';

        await page.render({ canvasContext: canvas.getContext('2d'), viewport }).promise;

        container.innerHTML = '';
        const wrap = document.createElement('div');
        wrap.className = 'pdf-page';
        wrap.appendChild(canvas);
        container.appendChild(wrap);

        updateNav(type);
    }

    function updateNav(type) {
        const s = pdfState[type];
        document.getElementById(type + '-page-info').textContent =
            `Page ${s.current} / ${s.total}`;
        document.getElementById(type + '-prev').disabled = s.current <= 1;
        document.getElementById(type + '-next').disabled = s.current >= s.total;
    }

    function previousPage(type) {
        if (pdfState[type].current > 1) renderPage(type, pdfState[type].current - 1);
    }
    function nextPage(type) {
        if (pdfState[type].current < pdfState[type].total) renderPage(type, pdfState[type].current + 1);
    }

    function showDocument(type, btn) {
        document.querySelectorAll('.document-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.document-tab').forEach(el => el.classList.remove('active'));
        document.getElementById(type + '-content').classList.add('active');
        btn.classList.add('active');
        if (!pdfDocs[type]) loadPDF(type);
    }

    document.addEventListener('DOMContentLoaded', () => loadPDF('guide'));
</script>
@endsection

