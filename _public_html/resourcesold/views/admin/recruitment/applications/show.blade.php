@extends('admin.layouts.app')

@section('breadcrumb', 'Détail candidature')

@section('content')
<div class="fade-in">
    <div class="admin-card" data-aos="fade-up">
        <div class="admin-card-header">
            <div>
                <h1 class="admin-card-title" style="font-size: 1.5rem;">
                    <i class="fas fa-user me-2" style="color: #11c06d;"></i>
                    {{ $application->nom_prenoms }}
                </h1>
                <p class="admin-card-subtitle mb-0">Poste : {{ $application->jobPosition->title }} · {{ $application->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.recruitment.applications.index') }}" class="btn btn-sm" style="background: #6C757D; color: white; padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none;">
                    <i class="fas fa-arrow-left me-1"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="admin-card" data-aos="fade-up" data-aos-delay="100">
                <div class="admin-card-header">
                    <h3 class="admin-card-title mb-0">Informations personnelles</h3>
                </div>
                <div class="admin-card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr><td class="text-muted" width="140">Nom & Prénoms</td><td><strong>{{ $application->nom_prenoms }}</strong></td></tr>
                        <tr><td class="text-muted">Sexe</td><td>{{ $application->sexe ? ucfirst($application->sexe) : '—' }}</td></tr>
                        <tr><td class="text-muted">Email</td><td><a href="mailto:{{ $application->email }}">{{ $application->email }}</a></td></tr>
                        <tr><td class="text-muted">Téléphone</td><td>{{ $application->telephone ?? '—' }}</td></tr>
                        <tr><td class="text-muted">Date de naissance</td><td>{{ $application->date_naissance ? \Carbon\Carbon::parse($application->date_naissance)->format('d/m/Y') : '—' }}</td></tr>
                        <tr><td class="text-muted">Adresse</td><td>{{ $application->adresse ?? '—' }}</td></tr>
                        <tr><td class="text-muted">Niveau d'études</td><td>{{ $application->niveau_etudes ?? '—' }}</td></tr>
                        <tr><td class="text-muted">Source offre</td><td>{{ $application->offer_source ? ucfirst(str_replace('_', ' ', $application->offer_source)) : '—' }}{{ $application->offer_source_other ? ' (' . $application->offer_source_other . ')' : '' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="admin-card" data-aos="fade-up" data-aos-delay="150">
                <div class="admin-card-header">
                    <h3 class="admin-card-title mb-0">Test QCM & Documents</h3>
                </div>
                <div class="admin-card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr><td class="text-muted" width="140">Statut</td><td>@if($application->status === 'test_completed')<span class="badge bg-success">Test complété</span>@else<span class="badge bg-info">{{ $application->status }}</span>@endif</td></tr>
                        <tr><td class="text-muted">Score</td><td>@if($application->test_score !== null)<strong>{{ $application->test_score }}/10</strong> ({{ ucfirst($application->getScoreLevel()) }})@else—@endif</td></tr>
                        <tr><td class="text-muted">CV</td><td>@if($application->cv_path)<a href="{{ route('admin.recruitment.applications.document', ['application' => $application, 'type' => 'cv']) }}" target="_blank" rel="noopener">Télécharger</a>@else—@endif</td></tr>
                        <tr><td class="text-muted">Lettre motivation</td><td>@if($application->lettre_path)<a href="{{ route('admin.recruitment.applications.document', ['application' => $application, 'type' => 'lettre']) }}" target="_blank" rel="noopener">Télécharger</a>@else—@endif</td></tr>
                        @if($application->form_data && isset($application->form_data['diplome_path']))
                        <tr><td class="text-muted">Diplôme</td><td><a href="{{ route('admin.recruitment.applications.document', ['application' => $application, 'type' => 'diplome']) }}" target="_blank" rel="noopener">Télécharger</a></td></tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($application->form_data && count($application->form_data) > 0)
    <div class="admin-card" data-aos="fade-up" data-aos-delay="200">
        <div class="admin-card-header">
            <h3 class="admin-card-title mb-0">Données complémentaires (poste)</h3>
        </div>
        <div class="admin-card-body">
            <table class="table table-sm">
                @foreach($application->form_data as $key => $value)
                    @if(!in_array($key, ['diplome_path', 'portfolio_path']) && $value !== null && $value !== '')
                    <tr>
                        <td class="text-muted" width="200">{{ str_replace('form_', '', $key) }}</td>
                        <td>{{ is_array($value) ? json_encode($value) : $value }}</td>
                    </tr>
                    @endif
                @endforeach
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
