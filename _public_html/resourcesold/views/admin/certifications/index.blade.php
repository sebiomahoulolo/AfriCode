@extends('admin.layouts.app')

@section('breadcrumb', 'Certifications')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="admin-card" data-aos="fade-up">
        <div class="admin-card-header">
            <div>
                <h1 class="admin-card-title" style="font-size: 1.5rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-certificate me-2" style="color: #fea837;"></i>
                    Gestion des Certifications
                </h1>
                <p class="admin-card-subtitle mb-1">Liste de tous les étudiants ayant obtenu un certificat</p>
                <p class="admin-card-subtitle small text-muted mb-0" style="font-size: 0.8rem;">Un même apprenant peut avoir plusieurs certificats. Le premier nombre = personnes distinctes certifiées, le second = total des certificats émis.</p>
            </div>
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <span style="background: linear-gradient(135deg, #11c06d, #239e79); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600;">
                    <i class="fas fa-user-graduate"></i> {{ $totalCertifiedApprenants }}Certifié(s)
                </span>
                <span style="background: linear-gradient(135deg, #fea837, #FFB366); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; font-weight: 500;">
                    <i class="fas fa-trophy"></i> {{ $totalCertificates }} Total
                </span>
            </div>
        </div>
    </div>

    <!-- Liste des apprenants certifiés -->
    @if($totalCertifiedApprenants > 0)
    <div class="admin-card mt-4" data-aos="fade-up" data-aos-delay="50">
        <div class="admin-card-header">
            <h3 class="admin-card-title mb-0">
                <i class="fas fa-user-graduate me-2" style="color: #11c06d;"></i>
                Apprenants ayant obtenu une certification ({{ $totalCertifiedApprenants }})
            </h3>
        </div>
        <div class="admin-card-body">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Apprenant</th>
                            <th>Email</th>
                            <th>Nombre de certifications</th>
                            <th>Dernière certification</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($certifiedApprenants as $index => $apprenant)
                        @php
                            $lastCert = $apprenant->certifications->first();
                        @endphp
                        <tr>
                            <td style="color: #6C757D; font-weight: 600;">{{ $index + 1 }}</td>
                            <td>
                                <div style="font-weight: 600; color: #2C3E50;">
                                    {{ $apprenant->first_name ?? 'N/A' }} {{ $apprenant->last_name ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <span style="color: #6C757D;"><i class="fas fa-envelope me-1"></i>{{ $apprenant->email }}</span>
                            </td>
                            <td>
                                <span style="background: rgba(17, 192, 109, 0.15); color: #11c06d; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.9rem; font-weight: 600;">
                                    <i class="fas fa-certificate me-1"></i>{{ $apprenant->certifications_count }}
                                </span>
                            </td>
                            <td style="color: #6C757D;">
                                @if($lastCert && $lastCert->issued_at)
                                    <i class="fas fa-calendar-alt me-1" style="color: #11c06d;"></i>{{ $lastCert->issued_at->format('d/m/Y') }}
                                    <span style="font-size: 0.85rem;">({{ $lastCert->course->title ?? 'N/A' }})</span>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-up" data-aos-delay="100">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" data-aos="fade-up" data-aos-delay="100">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tableau : tous les certificats émis -->
    <div class="admin-card mt-4" data-aos="fade-up" data-aos-delay="100">
        <div class="admin-card-header">
            <h3 class="admin-card-title mb-0">
                <i class="fas fa-list me-2" style="color: #fea837;"></i>
                Tous les certificats émis
                @if($totalCertificates > 0)
                    <span class="text-muted fw-normal" style="font-size: 0.9rem;">({{ $certifications->firstItem() }} à {{ $certifications->lastItem() }} sur {{ $totalCertificates }})</span>
                @endif
            </h3>
        </div>
        <div class="admin-card-body">
            @if($certifications->count() > 0)
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">
                                    <i class="fas fa-hashtag"></i>
                                </th>
                                <th>Étudiant</th>
                                <th>Cours</th>
                                <th>Date d'émission</th>
                                <th>Code de vérification</th>
                                <th style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($certifications as $certification)
                            <tr>
                                <td>
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #fea837, #FFB366); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.9rem;">
                                        {{ strtoupper(substr($certification->user->first_name ?? 'N', 0, 1)) }}{{ strtoupper(substr($certification->user->last_name ?? 'A', 0, 1)) }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div style="font-weight: 600; color: #2C3E50;">
                                            {{ $certification->user->first_name ?? 'N/A' }} {{ $certification->user->last_name ?? 'N/A' }}
                                        </div>
                                        <div style="font-size: 0.85rem; color: #6C757D;">
                                            <i class="fas fa-envelope me-1"></i>
                                            {{ $certification->user->email ?? 'N/A' }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div style="font-weight: 500; color: #2C3E50;">
                                            {{ $certification->course->title ?? 'N/A' }}
                                        </div>
                                        <div style="font-size: 0.85rem; color: #6C757D;">
                                            <i class="fas fa-user-tie me-1"></i>
                                            {{ $certification->course->formateur->first_name ?? 'N/A' }} {{ $certification->course->formateur->last_name ?? 'N/A' }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="color: #6C757D;">
                                        <i class="fas fa-calendar-alt me-1" style="color: #11c06d;"></i>
                                        {{ $certification->issued_at ? $certification->issued_at->format('d/m/Y') : 'N/A' }}
                                    </div>
                                    <div style="font-size: 0.8rem; color: #95A5A6;">
                                        {{ $certification->issued_at ? $certification->issued_at->format('H:i') : '' }}
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <code style="background: rgba(17, 192, 109, 0.1); color: #11c06d; padding: 0.25rem 0.5rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">
                                            {{ $certification->verification_code ?? 'N/A' }}
                                        </code>
                                        @if($certification->verification_code)
                                        <button class="btn btn-sm btn-link p-0"
                                                onclick="copyToClipboard('{{ $certification->verification_code }}')"
                                                title="Copier le code">
                                            <i class="fas fa-copy" style="color: #6C757D;"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="{{ route('admin.certifications.show', $certification->id) }}"
                                           class="admin-btn admin-btn-primary admin-btn-sm"
                                           title="Voir le certificat">
                                            <i class="fas fa-eye"></i>
                                            Voir
                                        </a>
                                        @if($certification->certificate_path)
                                        <a href="{{ asset($certification->certificate_path) }}"
                                           target="_blank"
                                           class="admin-btn admin-btn-secondary admin-btn-sm"
                                           title="Télécharger le certificat">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($certifications->hasPages())
                <div class="mt-4" data-aos="fade-up" data-aos-delay="200">
                    <nav aria-label="Navigation des pages">
                        {{ $certifications->links() }}
                    </nav>
                </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-5" data-aos="fade-up">
                    <div style="font-size: 4rem; color: #E0E0E0; margin-bottom: 1rem;">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <h3 style="color: #6C757D; margin-bottom: 0.5rem;">Aucun certificat pour le moment</h3>
                    <p style="color: #95A5A6;">Les certificats sont délivrés automatiquement lorsqu'un apprenant valide une formation (ex. examen final réussi). Ils apparaîtront ici une fois générés.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Afficher une notification de succès
            const toast = document.createElement('div');
            toast.className = 'alert alert-success';
            toast.style.position = 'fixed';
            toast.style.top = '20px';
            toast.style.right = '20px';
            toast.style.zIndex = '9999';
            toast.style.minWidth = '250px';
            toast.innerHTML = '<i class="fas fa-check-circle me-2"></i>Code copié dans le presse-papiers !';
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }).catch(function(err) {
            console.error('Erreur lors de la copie:', err);
        });
    }
</script>
@endpush
@endsection
