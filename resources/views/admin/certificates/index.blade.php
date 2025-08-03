@extends('admin.layouts.app')

@section('breadcrumb', 'Certificats')

@push('styles')
<style>
.certificates-dashboard {
    display: grid;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-card.issued {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.stat-card.pending {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
    color: #333;
}

.stat-card.templates {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
    color: #333;
}

.stat-value {
    font-size: 2rem;
    font-weight: bold;
    margin: 0.5rem 0;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.certificates-table-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    overflow: hidden;
}

.table-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.filters-section {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    align-items: center;
}

.filter-input {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    backdrop-filter: blur(10px);
}

.filter-input::placeholder {
    color: rgba(255,255,255,0.7);
}

.btn-primary {
    background: rgba(255,255,255,0.2);
    color: white;
    border: 1px solid rgba(255,255,255,0.3);
    padding: 0.5rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.btn-primary:hover {
    background: rgba(255,255,255,0.3);
    color: white;
    text-decoration: none;
}

.certificates-table {
    width: 100%;
    border-collapse: collapse;
}

.certificates-table th,
.certificates-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.certificates-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #333;
}

.certificates-table tbody tr:hover {
    background: #f8f9fa;
}

.certificate-preview {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.certificate-thumbnail {
    width: 80px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.certificate-info h4 {
    margin: 0 0 0.25rem 0;
    font-size: 0.9rem;
    font-weight: 600;
}

.certificate-info p {
    margin: 0;
    font-size: 0.8rem;
    color: #666;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-issued {
    background: #d4edda;
    color: #155724;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-revoked {
    background: #f8d7da;
    color: #721c24;
}

.actions-menu {
    position: relative;
    display: inline-block;
}

.actions-btn {
    background: none;
    border: none;
    padding: 0.5rem;
    cursor: pointer;
    border-radius: 6px;
    transition: background 0.2s;
}

.actions-btn:hover {
    background: #f0f0f0;
}

.actions-dropdown {
    display: none;
    position: absolute;
    right: 0;
    top: 100%;
    background: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border-radius: 8px;
    padding: 0.5rem 0;
    min-width: 180px;
    z-index: 100;
}

.actions-dropdown.show {
    display: block;
}

.dropdown-item {
    display: block;
    padding: 0.5rem 1rem;
    color: #333;
    text-decoration: none;
    transition: background 0.2s;
}

.dropdown-item:hover {
    background: #f8f9fa;
}

.templates-section {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.templates-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.template-card {
    border: 2px solid #eee;
    border-radius: 12px;
    padding: 1rem;
    text-align: center;
    transition: all 0.3s ease;
}

.template-card:hover {
    border-color: #667eea;
    transform: translateY(-2px);
}

.template-preview {
    width: 100%;
    height: 120px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.pagination-container {
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8f9fa;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .table-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filters-section {
        justify-content: center;
    }
    
    .certificates-table-container {
        overflow-x: auto;
    }
    
    .certificates-table {
        min-width: 900px;
    }
    
    .templates-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<div class="certificates-dashboard">
    <!-- Statistiques des certificats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total des certificats</div>
            <div class="stat-value">{{ $totalCertificates ?? 148 }}</div>
            <small>Depuis le début</small>
        </div>
        <div class="stat-card issued">
            <div class="stat-label">Certificats délivrés</div>
            <div class="stat-value">{{ $issuedCertificates ?? 132 }}</div>
            <small>Ce mois: {{ $monthlyIssued ?? 24 }}</small>
        </div>
        <div class="stat-card pending">
            <div class="stat-label">En attente</div>
            <div class="stat-value">{{ $pendingCertificates ?? 16 }}</div>
            <small>À traiter</small>
        </div>
        <div class="stat-card templates">
            <div class="stat-label">Modèles actifs</div>
            <div class="stat-value">{{ $activeTemplates ?? 5 }}</div>
            <small>Disponibles</small>
        </div>
    </div>

    <!-- Section des modèles de certificats -->
    <div class="templates-section">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="margin: 0; color: #333;">Modèles de certificats</h3>
            <a href="#" class="btn-primary" style="background: #667eea; border: none;">
                + Nouveau modèle
            </a>
        </div>
        
        <div class="templates-grid">
            <div class="template-card">
                <div class="template-preview">🎓</div>
                <h4>Certificat Standard</h4>
                <p>Modèle par défaut pour tous les cours</p>
                <small style="color: #11998e;">✓ Actif</small>
            </div>
            <div class="template-card">
                <div class="template-preview">🏆</div>
                <h4>Certificat Premium</h4>
                <p>Pour les cours avancés et spécialisés</p>
                <small style="color: #11998e;">✓ Actif</small>
            </div>
            <div class="template-card">
                <div class="template-preview">⭐</div>
                <h4>Certificat Excellence</h4>
                <p>Pour les meilleures performances</p>
                <small style="color: #11998e;">✓ Actif</small>
            </div>
        </div>
    </div>

    <!-- Table des certificats -->
    <div class="certificates-table-container">
        <div class="table-header">
            <h2 style="margin: 0;">Tous les certificats</h2>
            <div class="filters-section">
                <input type="text" class="filter-input" placeholder="Rechercher par étudiant..." id="searchInput">
                <select class="filter-input" id="statusFilter">
                    <option value="">Tous les statuts</option>
                    <option value="issued">Délivré</option>
                    <option value="pending">En attente</option>
                    <option value="revoked">Révoqué</option>
                </select>
                <select class="filter-input" id="courseFilter">
                    <option value="">Tous les cours</option>
                    <option value="web">Développement Web</option>
                    <option value="mobile">Développement Mobile</option>
                    <option value="design">Design UI/UX</option>
                </select>
                <a href="#" class="btn-primary">
                    📥 Exporter
                </a>
            </div>
        </div>
        
        <table class="certificates-table">
            <thead>
                <tr>
                    <th>Certificat</th>
                    <th>Étudiant</th>
                    <th>Cours</th>
                    <th>Date de délivrance</th>
                    <th>Score final</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($certificates ?? collect() as $certificate)
                <tr>
                    <td>
                        <div class="certificate-preview">
                            <div class="certificate-thumbnail">🎓</div>
                            <div class="certificate-info">
                                <h4>{{ $certificate->certificate_number ?? 'CERT-' . str_pad($certificate->id ?? 1, 6, '0', STR_PAD_LEFT) }}</h4>
                                <p>{{ $certificate->template_name ?? 'Certificat Standard' }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <strong>{{ $certificate->student->name ?? 'Étudiant Test' }}</strong>
                            <br>
                            <small style="color: #666;">{{ $certificate->student->email ?? 'etudiant@test.com' }}</small>
                        </div>
                    </td>
                    <td>
                        <div>
                            <strong>{{ $certificate->course->title ?? 'Cours de développement Web' }}</strong>
                            <br>
                            <small style="color: #666;">{{ $certificate->course->category->name ?? 'Développement' }}</small>
                        </div>
                    </td>
                    <td>
                        <div>
                            {{ \Carbon\Carbon::parse($certificate->issued_at ?? now())->format('d/m/Y') }}
                            <br>
                            <small style="color: #666;">{{ \Carbon\Carbon::parse($certificate->issued_at ?? now())->format('H:i') }}</small>
                        </div>
                    </td>
                    <td>
                        <div style="text-align: center;">
                            <strong style="color: #11998e; font-size: 1.1rem;">{{ $certificate->final_score ?? 87 }}%</strong>
                            <br>
                            @php
                                $score = $certificate->final_score ?? 87;
                                $grade = $score >= 90 ? 'Excellent' : ($score >= 80 ? 'Très bien' : ($score >= 70 ? 'Bien' : 'Passable'));
                            @endphp
                            <small style="color: #666;">{{ $grade }}</small>
                        </div>
                    </td>
                    <td>
                        <span class="status-badge status-{{ $certificate->status ?? 'issued' }}">
                            @switch($certificate->status ?? 'issued')
                                @case('issued')
                                    ✓ Délivré
                                    @break
                                @case('pending')
                                    ⏳ En attente
                                    @break
                                @case('revoked')
                                    ❌ Révoqué
                                    @break
                                @default
                                    {{ ucfirst($certificate->status ?? 'issued') }}
                            @endswitch
                        </span>
                    </td>
                    <td>
                        <div class="actions-menu">
                            <button class="actions-btn" onclick="toggleActions(this)">
                                ⋮
                            </button>
                            <div class="actions-dropdown">
                                <a href="#" class="dropdown-item" onclick="viewCertificate({{ $certificate->id ?? 1 }})">👁 Voir certificat</a>
                                <a href="#" class="dropdown-item" onclick="downloadCertificate({{ $certificate->id ?? 1 }})">📥 Télécharger PDF</a>
                                <a href="#" class="dropdown-item" onclick="sendCertificate({{ $certificate->id ?? 1 }})">📧 Envoyer par email</a>
                                @if(($certificate->status ?? 'issued') == 'issued')
                                    <a href="#" class="dropdown-item" onclick="revokeCertificate({{ $certificate->id ?? 1 }})">❌ Révoquer</a>
                                @endif
                                @if(($certificate->status ?? 'issued') == 'pending')
                                    <a href="#" class="dropdown-item" onclick="approveCertificate({{ $certificate->id ?? 1 }})">✓ Approuver</a>
                                @endif
                                <a href="#" class="dropdown-item" onclick="regenerateCertificate({{ $certificate->id ?? 1 }})">🔄 Régénérer</a>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <!-- Données de démonstration -->
                <tr>
                    <td>
                        <div class="certificate-preview">
                            <div class="certificate-thumbnail">🎓</div>
                            <div class="certificate-info">
                                <h4>CERT-001234</h4>
                                <p>Certificat Standard</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <strong>Jean Dupont</strong><br>
                            <small style="color: #666;">jean.dupont@email.com</small>
                        </div>
                    </td>
                    <td>
                        <div>
                            <strong>Développement Web Complet</strong><br>
                            <small style="color: #666;">Développement</small>
                        </div>
                    </td>
                    <td>
                        <div>
                            {{ now()->format('d/m/Y') }}<br>
                            <small style="color: #666;">{{ now()->format('H:i') }}</small>
                        </div>
                    </td>
                    <td>
                        <div style="text-align: center;">
                            <strong style="color: #11998e; font-size: 1.1rem;">87%</strong><br>
                            <small style="color: #666;">Très bien</small>
                        </div>
                    </td>
                    <td><span class="status-badge status-issued">✓ Délivré</span></td>
                    <td>
                        <div class="actions-menu">
                            <button class="actions-btn" onclick="toggleActions(this)">⋮</button>
                            <div class="actions-dropdown">
                                <a href="#" class="dropdown-item">👁 Voir certificat</a>
                                <a href="#" class="dropdown-item">📥 Télécharger PDF</a>
                                <a href="#" class="dropdown-item">📧 Envoyer par email</a>
                                <a href="#" class="dropdown-item">❌ Révoquer</a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="certificate-preview">
                            <div class="certificate-thumbnail">🏆</div>
                            <div class="certificate-info">
                                <h4>CERT-001235</h4>
                                <p>Certificat Premium</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <strong>Marie Martin</strong><br>
                            <small style="color: #666;">marie.martin@email.com</small>
                        </div>
                    </td>
                    <td>
                        <div>
                            <strong>Design UI/UX Avancé</strong><br>
                            <small style="color: #666;">Design</small>
                        </div>
                    </td>
                    <td>
                        <div>
                            {{ now()->subDay()->format('d/m/Y') }}<br>
                            <small style="color: #666;">14:30</small>
                        </div>
                    </td>
                    <td>
                        <div style="text-align: center;">
                            <strong style="color: #11998e; font-size: 1.1rem;">92%</strong><br>
                            <small style="color: #666;">Excellent</small>
                        </div>
                    </td>
                    <td><span class="status-badge status-issued">✓ Délivré</span></td>
                    <td>
                        <div class="actions-menu">
                            <button class="actions-btn" onclick="toggleActions(this)">⋮</button>
                            <div class="actions-dropdown">
                                <a href="#" class="dropdown-item">👁 Voir certificat</a>
                                <a href="#" class="dropdown-item">📥 Télécharger PDF</a>
                                <a href="#" class="dropdown-item">📧 Envoyer par email</a>
                                <a href="#" class="dropdown-item">❌ Révoquer</a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="certificate-preview">
                            <div class="certificate-thumbnail">⏳</div>
                            <div class="certificate-info">
                                <h4>CERT-001236</h4>
                                <p>Certificat Standard</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <strong>Pierre Durand</strong><br>
                            <small style="color: #666;">pierre.durand@email.com</small>
                        </div>
                    </td>
                    <td>
                        <div>
                            <strong>Machine Learning</strong><br>
                            <small style="color: #666;">IA & Data</small>
                        </div>
                    </td>
                    <td>
                        <div>
                            {{ now()->subDays(2)->format('d/m/Y') }}<br>
                            <small style="color: #666;">16:45</small>
                        </div>
                    </td>
                    <td>
                        <div style="text-align: center;">
                            <strong style="color: #11998e; font-size: 1.1rem;">75%</strong><br>
                            <small style="color: #666;">Bien</small>
                        </div>
                    </td>
                    <td><span class="status-badge status-pending">⏳ En attente</span></td>
                    <td>
                        <div class="actions-menu">
                            <button class="actions-btn" onclick="toggleActions(this)">⋮</button>
                            <div class="actions-dropdown">
                                <a href="#" class="dropdown-item">👁 Voir certificat</a>
                                <a href="#" class="dropdown-item">✓ Approuver</a>
                                <a href="#" class="dropdown-item">📧 Envoyer par email</a>
                                <a href="#" class="dropdown-item">🔄 Régénérer</a>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="pagination-container">
            <div>
                Affichage de <strong>1-{{ $certificates->count() ?? 3 }}</strong> sur <strong>{{ $certificates->total() ?? 148 }}</strong> certificats
            </div>
            <div>
                {{ $certificates->links() ?? 'Pagination sera ici' }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Fonctions de gestion des actions
function toggleActions(button) {
    const dropdown = button.nextElementSibling;
    const allDropdowns = document.querySelectorAll('.actions-dropdown');
    
    // Fermer tous les autres dropdowns
    allDropdowns.forEach(d => {
        if (d !== dropdown) {
            d.classList.remove('show');
        }
    });
    
    dropdown.classList.toggle('show');
}

// Fermer les dropdowns quand on clique ailleurs
document.addEventListener('click', function(e) {
    if (!e.target.closest('.actions-menu')) {
        document.querySelectorAll('.actions-dropdown').forEach(d => {
            d.classList.remove('show');
        });
    }
});

// Fonctions d'actions des certificats
function viewCertificate(id) {
    alert('Voir le certificat #' + id);
    // Ici vous pouvez ouvrir une modal ou une nouvelle fenêtre avec le certificat
}

function downloadCertificate(id) {
    alert('Téléchargement du certificat #' + id + ' en PDF');
    // Ici vous pouvez générer et télécharger le PDF du certificat
}

function sendCertificate(id) {
    if (confirm('Envoyer le certificat par email à l\'étudiant ?')) {
        alert('Certificat #' + id + ' envoyé par email');
        // Ici vous pouvez faire l'appel API pour envoyer l'email
    }
}

function revokeCertificate(id) {
    if (confirm('Êtes-vous sûr de vouloir révoquer ce certificat ? Cette action est irréversible.')) {
        alert('Certificat #' + id + ' révoqué');
        // Ici vous pouvez faire l'appel API pour révoquer
    }
}

function approveCertificate(id) {
    if (confirm('Approuver ce certificat ?')) {
        alert('Certificat #' + id + ' approuvé');
        // Ici vous pouvez faire l'appel API pour approuver
    }
}

function regenerateCertificate(id) {
    if (confirm('Régénérer ce certificat avec le modèle actuel ?')) {
        alert('Certificat #' + id + ' régénéré');
        // Ici vous pouvez faire l'appel API pour régénérer
    }
}

// Filtres
document.getElementById('searchInput').addEventListener('input', function() {
    // Logique de filtrage par nom d'étudiant
    console.log('Recherche:', this.value);
});

document.getElementById('statusFilter').addEventListener('change', function() {
    // Logique de filtrage par statut
    console.log('Filtre statut:', this.value);
});

document.getElementById('courseFilter').addEventListener('change', function() {
    // Logique de filtrage par cours
    console.log('Filtre cours:', this.value);
});
</script>
@endpush
@endsection
