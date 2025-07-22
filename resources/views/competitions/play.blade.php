@extends('layouts.layout')

@section('title', 'Participer à la compétition')

@section('content')
<div class="container mt-3 mt-md-5">
    <div class="competition-header mb-4">
        <h1 class="h2"><i class="fas fa-trophy me-2"></i>Compétition : {{ $competition->title }}</h1>
        <p class="lead">{{ $competition->description }}</p>
    </div>
    
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i> <strong>Énoncé du défi :</strong>
        <div class="mt-2">{!! nl2br(e($competition->description ?? 'Aucun énoncé fourni pour cette compétition.')) !!}</div>
    </div>
    
    <div class="card mt-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-code me-2"></i>Éditeur de code AfriCode
        </div>
        <div class="card-body">
            <div class="row mb-3 align-items-center">
                <div class="col-12 col-md-6 mb-2 mb-md-0">
                    <label for="language-select" class="form-label fw-bold mb-0">Langage :</label>
                    <select id="language-select" class="form-select d-inline-block w-auto ms-2">
                        <option value="" selected>Sélectionnez votre langage</option>
                        <option value="javascript">JavaScript</option>
                        <option value="html">HTML + CSS</option>
                        <option value="python3">Python 3</option>
                        <option value="java">Java</option>
                        <option value="php">PHP</option>
                        <option value="cpp">C++</option>
                        <option value="c">C</option>
                    </select>
                </div>
                <div class="col-12 col-md-6 text-md-end">
                    <button id="run-btn" class="btn btn-success">
                        <i class="fas fa-play me-1"></i> Exécuter
                    </button>
                    <button id="evaluate-btn" class="btn btn-primary ms-2">
                        <i class="fas fa-clipboard-check me-1"></i> Évaluer ma solution
                    </button>
                </div>
            </div>
            
            <div id="editor" class="border rounded" style="height: 300px; width: 100%;"></div>
            
            <div id="loading-indicator" class="mt-3 text-center" style="display:none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p class="mt-2 mb-0">Exécution en cours...</p>
            </div>
            
            <div id="output-container" class="mt-4" style="display:none;">
                <div class="alert alert-success d-flex align-items-center d-none" id="output-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <div>Code exécuté avec succès</div>
                </div>
                
                <div class="alert alert-danger d-flex align-items-center d-none" id="output-error">
                    <i class="fas fa-times-circle me-2"></i>
                    <div>Erreur lors de l'exécution</div>
                </div>
                
                <div class="row g-3">
                    <div class="col-12 col-lg-6">
                        <div class="card h-100">
                            <div class="card-header bg-dark text-white">
                                <i class="fas fa-terminal me-2"></i>Résultats
                            </div>
                            <div class="card-body p-0">
                                <pre id="output" class="p-3 mb-0 bg-dark text-white" style="min-height: 100px; overflow: auto;"></pre>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-lg-6" id="error-col">
                        <div class="card h-100">
                            <div class="card-header bg-danger text-white">
                                <i class="fas fa-exclamation-triangle me-2"></i>Erreurs
                            </div>
                            <div class="card-body p-0">
                                <pre id="error-output" class="p-3 mb-0 bg-danger bg-opacity-10 text-danger" style="min-height: 100px; overflow: auto;"></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Preview pour HTML/PHP -->
            <div id="html-preview-container" class="mt-4 d-none">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <i class="fas fa-eye me-2"></i>Prévisualisation
                    </div>
                    <div class="card-body p-0">
                        <iframe id="html-preview" style="width: 100%; height: 400px; border: none;"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-3">
        <a href="{{ route('competitions.show', $competition->slug) }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-1"></i> Retour à la compétition
        </a>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.14/ace.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.14/ext-language_tools.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation de l'éditeur
    const editor = ace.edit("editor");
    editor.setTheme("ace/theme/monokai");
    editor.session.setMode("ace/mode/text");
    editor.setOptions({
        enableBasicAutocompletion: true,
        enableLiveAutocompletion: true,
        fontSize: "14px"
    });

    // Mapping des modes ACE
    const aceModes = {
        'python3': 'python',
        'javascript': 'javascript',
        'php': 'php',
        'java': 'java',
        'c': 'c_cpp',
        'cpp': 'c_cpp',
        'html': 'html',
        'css': 'css',
        'sql': 'sql',
        'django': 'python'
    };

    // Gestion du changement de langage
    const languageSelect = document.getElementById('language-select');
    languageSelect.addEventListener('change', function() {
        const mode = aceModes[this.value] || 'text';
        editor.session.setMode("ace/mode/" + mode);
    });

    // Éléments UI
    const runBtn = document.getElementById('run-btn');
    const output = document.getElementById('output');
    const errorOutput = document.getElementById('error-output');
    const outputSuccess = document.getElementById('output-success');
    const outputError = document.getElementById('output-error');
    const outputContainer = document.getElementById('output-container');
    const loadingIndicator = document.getElementById('loading-indicator');
    const errorCol = document.getElementById('error-col');
    const htmlPreviewContainer = document.getElementById('html-preview-container');
    const htmlPreview = document.getElementById('html-preview');

    // Gestion de l'exécution
    runBtn.addEventListener('click', function() {
        const code = editor.getValue();
        let language = languageSelect.value;
        
        if (!language) {
            alert('Veuillez sélectionner un langage de programmation');
            return;
        }

        // Reset UI
        output.textContent = '';
        errorOutput.textContent = '';
        outputSuccess.classList.add('d-none');
        outputError.classList.add('d-none');
        outputContainer.style.display = 'none';
        htmlPreviewContainer.classList.add('d-none');
        loadingIndicator.style.display = '';

        // Cas particulier pour HTML/PHP
        if (language === 'html' || language === 'php') {
            htmlPreviewContainer.classList.remove('d-none');
            
            if (code.includes('<?php')) {
                // PHP - besoin d'exécution côté serveur
                executeCode(code, 'php').then(data => {
                    displayResult(data);
                    htmlPreview.srcdoc = data.output || '<div style="padding:1em;color:red">Aucune sortie</div>';
                }).catch(error => {
                    htmlPreview.srcdoc = `<div style="padding:1em;color:red">Erreur: ${error.message}</div>`;
                });
            } else {
                // HTML pur - affichage direct
                loadingIndicator.style.display = 'none';
                htmlPreview.srcdoc = code;
            }
            return;
        }

        // Autres langages
        executeCode(code, language).then(displayResult).catch(handleError);
    });

    // Ajout du bouton d'évaluation
    const evaluateButton = document.getElementById('evaluate-btn');
    evaluateButton.addEventListener('click', function() {
        const code = editor.getValue();
        let language = languageSelect.value;
        if (!language) {
            alert('Veuillez sélectionner un langage de programmation');
            return;
        }
        output.textContent = '';
        errorOutput.textContent = '';
        outputSuccess.classList.add('d-none');
        outputError.classList.add('d-none');
        outputContainer.style.display = 'none';
        htmlPreviewContainer.classList.add('d-none');
        loadingIndicator.style.display = '';

        fetch('{{ route('competitions.evaluate', $competition->slug) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                code: code,
                language: language
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        })
        .then(data => {
            loadingIndicator.style.display = 'none';
            outputContainer.style.display = '';
            let rapport = '';
            if (data.results && Array.isArray(data.results)) {
                rapport += '<b>Résultats des cas de test :</b><br><ul>';
                data.results.forEach((test, idx) => {
                    rapport += `<li><b>Test #${idx+1}</b> : `;
                    rapport += test.success ? '<span style="color:green">Réussi</span>' : '<span style="color:red">Échoué</span>';
                    rapport += `<br><b>Entrée :</b> <pre>${test.input ?? ''}</pre>`;
                    rapport += `<b>Sortie attendue :</b> <pre>${test.expected}</pre>`;
                    rapport += `<b>Sortie obtenue :</b> <pre>${test.output}</pre>`;
                    if (test.error) rapport += `<b>Erreur :</b> <pre>${test.error}</pre>`;
                    rapport += '</li>';
                });
                rapport += '</ul>';
            }
            output.innerHTML = rapport;
            errorOutput.textContent = '';
            outputSuccess.classList.toggle('d-none', !data.success);
            outputError.classList.toggle('d-none', data.success);
            errorCol.style.display = data.success ? 'none' : '';
        })
        .catch(error => {
            loadingIndicator.style.display = 'none';
            outputContainer.style.display = '';
            outputError.classList.remove('d-none');
            errorOutput.textContent = error.message;
        });
    });

    // Fonction d'exécution du code
    function executeCode(code, language) {
        return fetch('{{ route('competitions.runCode', $competition->slug) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                code: code, 
                language: language 
            })
        }).then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        });
    }

    // Affichage des résultats
    function displayResult(data) {
        loadingIndicator.style.display = 'none';
        outputContainer.style.display = '';
        
        const resultOutput = data.output || data.stdout || data.run?.output || data.run?.stdout || 'Aucune sortie';
        const resultError = data.error || data.stderr || data.run?.stderr || '';
        
        output.textContent = resultOutput;
        errorOutput.textContent = resultError;

        if (resultError && resultError.trim() !== '') {
            outputError.classList.remove('d-none');
            errorCol.style.display = '';
        } else {
            outputSuccess.classList.remove('d-none');
            errorCol.style.display = 'none';
        }
    }

    // Gestion des erreurs
    function handleError(error) {
        loadingIndicator.style.display = 'none';
        outputContainer.style.display = '';
        outputError.classList.remove('d-none');
        errorOutput.textContent = error.message;
    }
});
</script>
@endsection