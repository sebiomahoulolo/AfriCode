@extends('admin.layouts.app')

@section('breadcrumb', 'Candidatures recrutement')

@section('content')
<div class="fade-in">
    <!-- En-tête -->
    <div class="admin-card" data-aos="fade-up">
        <div class="admin-card-header">
            <div>
                <h1 class="admin-card-title" style="font-size: 1.5rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-users me-2" style="color: #11c06d;"></i>
                    Candidatures
                </h1>
                <p class="admin-card-subtitle">Toutes les candidatures reçues</p>
            </div>
            <a href="{{ route('admin.recruitment.index') }}" class="admin-card-actions" style="background: linear-gradient(135deg, #6C757D, #95A5A6); color: white; padding: 0.75rem 1.25rem; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.3s; display: inline-flex; align-items: center;">
                <i class="fas fa-arrow-left me-2"></i> Retour recrutement
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="admin-card" data-aos="fade-up" data-aos-delay="100" style="margin-bottom: 2rem;">
        <div class="admin-card-header">
            <h3 class="admin-card-title">Filtres de recherche</h3>
        </div>
        <div class="admin-card-body">
            <form method="GET" action="{{ route('admin.recruitment.applications.index') }}">
                <div class="admin-grid admin-grid-4" style="gap: 1rem;">
                    <div>
                        <label for="search" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Recherche</label>
                        <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="Nom, email, téléphone..." style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem;">
                    </div>
                    <div>
                        <label for="position" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Poste</label>
                        <select name="position" id="position" style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem;">
                            <option value="">Tous les postes</option>
                            @foreach($positions as $p)
                                <option value="{{ $p->id }}" {{ request('position') == $p->id ? 'selected' : '' }}>{{ $p->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="status" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Statut</label>
                        <select name="status" id="status" style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem;">
                            <option value="">Tous</option>
                            <option value="form_submitted" {{ request('status') == 'form_submitted' ? 'selected' : '' }}>Formulaire</option>
                            <option value="test_completed" {{ request('status') == 'test_completed' ? 'selected' : '' }}>Test complété</option>
                        </select>
                    </div>
                    <div style="display: flex; gap: 0.75rem; align-items: flex-end;">
                        <button type="submit" style="background: linear-gradient(135deg, #11c06d, #239e79); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.3s;">
                            <i class="fas fa-search me-1"></i> Filtrer
                        </button>
                        <a href="{{ route('admin.recruitment.applications.index') }}" style="background: #6C757D; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.3s;">
                            <i class="fas fa-times me-1"></i> Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau -->
    <div class="admin-table-card" data-aos="fade-up" data-aos-delay="150">
        <div class="admin-table-header">
            <h3 class="admin-table-title">Liste ({{ $applications->total() }})</h3>
            @if($applications->total() > 0)
                <div style="color: #6C757D; font-size: 0.9rem;">
                    Affichage de {{ $applications->firstItem() }} à {{ $applications->lastItem() }} sur {{ $applications->total() }} candidature{{ $applications->total() > 1 ? 's' : '' }}
                </div>
            @endif
        </div>
        <div class="admin-table-body">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Poste</th>
                            <th>Score</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $app)
                        <tr>
                            <td>
                                <div style="font-weight: 600; color: #333;">{{ $app->nom_prenoms }}</div>
                            </td>
                            <td style="color: #333;">{{ $app->email }}</td>
                            <td style="color: #6C757D;">{{ $app->telephone ?? '—' }}</td>
                            <td>
                                <span style="background: rgba(17, 192, 109, 0.1); color: #11c06d; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.85rem;">{{ $app->jobPosition->title }}</span>
                            </td>
                            <td>
                                @if($app->test_score !== null)
                                    <span style="background: {{ $app->test_score >= 7 ? 'rgba(35, 158, 121, 0.15)' : ($app->test_score >= 5 ? 'rgba(255, 193, 7, 0.2)' : 'rgba(108, 117, 125, 0.15)') }}; color: {{ $app->test_score >= 7 ? '#239e79' : ($app->test_score >= 5 ? '#d4a017' : '#6C757D') }}; padding: 0.25rem 0.6rem; border-radius: 15px; font-size: 0.85rem; font-weight: 600;">{{ $app->test_score }}/10</span>
                                @else
                                    <span style="color: #adb5bd;">—</span>
                                @endif
                            </td>
                            <td>
                                @if($app->status === 'test_completed')
                                    <span style="background: rgba(35, 158, 121, 0.15); color: #239e79; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600;"><i class="fas fa-check-circle me-1"></i> Complété</span>
                                @else
                                    <span style="background: rgba(23, 162, 184, 0.15); color: #17a2b8; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600;"><i class="fas fa-clock me-1"></i> En cours</span>
                                @endif
                            </td>
                            <td style="color: #6C757D;">{{ $app->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.recruitment.applications.show', $app) }}" style="color: #11c06d; padding: 0.5rem 0.75rem; border-radius: 6px; text-decoration: none; font-weight: 500; transition: all 0.2s; display: inline-flex; align-items: center;" title="Voir le détail">
                                    <i class="fas fa-eye me-1"></i> Détail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 3rem; color: #6C757D;">
                                <i class="fas fa-file-alt" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                                <div style="font-size: 1.1rem; font-weight: 500; margin-bottom: 0.5rem;">Aucune candidature</div>
                                <div style="font-size: 0.9rem;">Modifiez les filtres ou attendez de nouvelles candidatures.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($applications->hasPages() || $applications->total() > 0)
            <div class="admin-table-pagination" style="padding: 1.5rem; border-top: 1px solid #E9ECEF;">
                @if($applications->hasPages())
                    <nav aria-label="Navigation des pages" style="display: flex; justify-content: center;">
                        <ul class="pagination" style="justify-content: center; margin: 0; padding: 0; list-style: none; display: flex; flex-wrap: wrap; gap: 0.5rem;">
                            @if($applications->onFirstPage())
                                <li style="margin: 0;">
                                    <span style="color: #6c757d; background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 0.5rem 0.75rem; border-radius: 0.375rem; cursor: not-allowed; opacity: 0.65; display: inline-flex; align-items: center; min-width: 38px;"><i class="fas fa-chevron-left"></i> Précédent</span>
                                </li>
                            @else
                                <li style="margin: 0;">
                                    <a href="{{ $applications->appends(request()->except('page'))->previousPageUrl() }}" aria-label="Précédent" style="color: #11c06d; background-color: white; border: 1px solid #dee2e6; padding: 0.5rem 0.75rem; border-radius: 0.375rem; text-decoration: none; transition: all 0.2s ease; display: inline-flex; align-items: center; min-width: 38px;"><i class="fas fa-chevron-left"></i> Précédent</a>
                                </li>
                            @endif
                            @foreach($applications->getUrlRange(max(1, $applications->currentPage() - 2), min($applications->lastPage(), $applications->currentPage() + 2)) as $page => $url)
                                @if($page == $applications->currentPage())
                                    <li style="margin: 0;">
                                        <span style="color: white; background-color: #11c06d; border: 1px solid #11c06d; padding: 0.5rem 0.75rem; border-radius: 0.375rem; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; min-width: 38px;">{{ $page }}</span>
                                    </li>
                                @else
                                    <li style="margin: 0;">
                                        <a href="{{ $applications->appends(request()->except('page'))->url($page) }}" style="color: #11c06d; background-color: white; border: 1px solid #dee2e6; padding: 0.5rem 0.75rem; border-radius: 0.375rem; text-decoration: none; transition: all 0.2s ease; display: inline-flex; align-items: center; justify-content: center; min-width: 38px;">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach
                            @if($applications->hasMorePages())
                                <li style="margin: 0;">
                                    <a href="{{ $applications->appends(request()->except('page'))->nextPageUrl() }}" aria-label="Suivant" style="color: #11c06d; background-color: white; border: 1px solid #dee2e6; padding: 0.5rem 0.75rem; border-radius: 0.375rem; text-decoration: none; transition: all 0.2s ease; display: inline-flex; align-items: center; min-width: 38px;">Suivant <i class="fas fa-chevron-right"></i></a>
                                </li>
                            @else
                                <li style="margin: 0;">
                                    <span style="color: #6c757d; background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 0.5rem 0.75rem; border-radius: 0.375rem; cursor: not-allowed; opacity: 0.65; display: inline-flex; align-items: center; min-width: 38px;">Suivant <i class="fas fa-chevron-right"></i></span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.admin-table-pagination ul a:hover { background-color: rgba(17, 192, 109, 0.1) !important; border-color: #11c06d !important; }
.admin-table-header { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; }
</style>
@endpush
