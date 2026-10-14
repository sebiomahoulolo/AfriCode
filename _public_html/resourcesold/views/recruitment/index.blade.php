@extends('layouts.layout')

@section('title', 'Recrutement – AfriCode')

@section('content')
<style>
    .recruit-hero { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #fff; padding: 4rem 0 3rem; margin-top: 56px; }
    .recruit-hero .text-gradient { background: linear-gradient(90deg, #fea837, #fa1c39); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .recruit-card { border: none; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); transition: transform 0.2s, box-shadow 0.2s; }
    .recruit-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(254, 168, 55, 0.15); }
    .recruit-card .card-header { background: linear-gradient(135deg, #fea837 0%, #fa1c39 100%); color: #fff; font-weight: 700; padding: 1rem 1.25rem; border: none; }
    .recruit-card .card-body { padding: 1.5rem 1.25rem; }
    .recruit-card ul { padding-left: 1.25rem; margin-bottom: 0; }
    .recruit-card li { margin-bottom: 0.35rem; }
    .btn-postuler { background: linear-gradient(135deg, #239e79 0%, #11c06d 100%); color: #fff; border: none; font-weight: 600; padding: 0.6rem 1.5rem; border-radius: 8px; }
    .btn-postuler:hover { background: linear-gradient(135deg, #11c06d 0%, #158f7a 100%); color: #fff; }
    .section-recruit { padding: 3rem 0; }
    .modalities-box { background: #f8fafc; border-left: 4px solid #fea837; padding: 1.25rem 1.5rem; border-radius: 0 12px 12px 0; }
    .nb-important { background: #fef3c7; border-left: 4px solid #fa1c39; padding: 1rem 1.25rem; border-radius: 0 8px 8px 0; font-size: 0.95rem; }
    .recruit-quick-links { font-size: 1.05rem; }
    .recruit-quick-links .recruit-link { color: rgba(255,255,255,0.95); text-decoration: none; font-weight: 500; border-bottom: 1px solid transparent; transition: color 0.2s, border-color 0.2s; }
    .recruit-quick-links .recruit-link:hover { color: #fea837; border-bottom-color: #fea837; }
    .recruit-card { scroll-margin-top: 80px; }
    .deadline-badge { display: inline-flex; align-items: center; gap: 6px; border-radius: 20px; padding: 3px 12px; font-size: 0.8rem; font-weight: 700; color: #fff !important; }
    .deadline-badge.urgent { background: #fa1c39; }
    .deadline-badge.soon   { background: #fea837; }
    .deadline-badge.ok     { background: #239e79; }
</style>

<section class="recruit-hero">
    <div class="container">
        <div class="text-center mb-4">
            <h1 class="display-5 fw-bold mb-3"> Avis de recrutement – <span class="text-gradient">AfriCode</span></h1>
            <p class="lead mb-0 opacity-90">Dans le cadre du renforcement de son équipe administrative et digitale, <strong>AFRICODE</strong>, entreprise spécialisée dans les <strong>centres de formation numérique</strong>, lance un recrutement pour les postes suivants :</p>
            <div class="recruit-quick-links mt-3">
                @foreach($positions as $position)
                    <a href="#poste-{{ $position->slug }}" class="recruit-link">{{ str_pad($position->places_count, 2, '0', STR_PAD_LEFT) }} {{ $position->title }}</a>
                    @if(!$loop->last)<span class="mx-2 opacity-75">•</span>@endif
                @endforeach
            </div>
        </div>
    </div>
</section>
<section class="section-recruit bg-light">
    <div class="container">
        @foreach($positions as $position)
        <div class="card recruit-card mb-4" id="poste-{{ $position->slug }}">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <span>{{ $position->title }} ({{ $position->places_count }} poste{{ $position->places_count > 1 ? 's' : '' }})</span>
                    @if($position->deadline)
                        @php
                            $daysLeft = (int) now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($position->deadline)->startOfDay(), false);
                            $dlClass  = $daysLeft <= 3 ? 'urgent' : ($daysLeft <= 10 ? 'soon' : 'ok');
                            $dlIcon   = $daysLeft <= 3 ? 'fa-fire' : 'fa-calendar-alt';
                        @endphp
                        <span class="deadline-badge {{ $dlClass }} ms-2">
                            <i class="fas {{ $dlIcon }}"></i>
                            Date limite : {{ \Carbon\Carbon::parse($position->deadline)->locale('fr')->isoFormat('D MMMM YYYY') }}
                            @if($daysLeft >= 0)
                                <span class="opacity-75">({{ $daysLeft == 0 ? 'aujourd\'hui !' : 'J-'.$daysLeft }})</span>
                            @endif
                        </span>
                    @endif
                </div>
                <a href="{{ route('recruitment.form', $position->slug) }}" class="btn btn-sm btn-light text-dark fw-bold">
                    <i class="fas fa-paper-plane me-1"></i> Postuler
                </a>
            </div>
            <div class="card-body">
                @if($position->missions)
                <h6 class="text-dark fw-bold mb-2"> Missions principales</h6>
                <ul class="mb-3">
                    @foreach(explode("\n", trim($position->missions)) as $line)
                        @if(trim($line))
                        <li>{{ trim(preg_replace('/^[\*\-]\s*/', '', $line)) }}</li>
                        @endif
                    @endforeach
                </ul>
                @endif
                @if($position->profil)
                <h6 class="text-dark fw-bold mb-2"><i class="fas fa-user me-1"></i>Profil recherché</h6>
                <ul class="mb-3">
                    @foreach(explode("\n", trim($position->profil)) as $line)
                        @if(trim($line))
                        <li>{{ trim(preg_replace('/^[\*\-]\s*/', '', $line)) }}</li>
                        @endif
                    @endforeach
                </ul>
                @endif
                @if($position->dossier_fournir)
                <h6 class="text-dark fw-bold mb-2"> Dossier à fournir</h6>
                <ul class="mb-0">
                    @foreach(explode("\n", trim($position->dossier_fournir)) as $line)
                        @if(trim($line))
                        <li>{{ trim(preg_replace('/^[\*\-•]\s*/', '', $line)) }}</li>
                        @endif
                    @endforeach
                </ul>
                @endif
                <div class="mt-3 pt-3 border-top">
                    <a href="{{ route('recruitment.form', $position->slug) }}" class="btn btn-postuler"  style="color: white">
                        <i class="fas fa-check-circle me-2"></i> Postuler à ce poste
                    </a>
                </div>
            </div>
        </div>
        @endforeach

        @php
            $latestDeadline = $positions->filter(fn($p) => $p->deadline)->sortByDesc('deadline')->first();
        @endphp
        <div class="modalities-box mt-5">
            <h5 class="fw-bold mb-3" style="color: #1e293b;"> Modalités de candidature</h5>
            <p class="mb-2">Les candidatures doivent être soumises en ligne via :</p>
            <p class="mb-1"><strong> <a href="{{ url('/recrute') }}">www.africode.tech/recrute</a></strong></p>
            @if($latestDeadline)
                <p class="mb-2">
                    <strong> Date limite de dépôt :
                        {{ \Carbon\Carbon::parse($latestDeadline->deadline)->locale('fr')->isoFormat('D MMMM YYYY') }}
                    </strong>
                    @php $dl = (int) now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($latestDeadline->deadline)->startOfDay(), false); @endphp
                    @if($dl >= 0)
                        <span class="badge ms-1" style="background:{{ $dl <= 3 ? '#fa1c39' : ($dl <= 10 ? '#fea837' : '#239e79') }}; font-size:.75rem;">
                            {{ $dl == 0 ? 'Dernier jour !' : 'J-'.$dl }}
                        </span>
                    @else
                        <span class="badge ms-1" style="background:#6b7280; font-size:.75rem;">Clôturé</span>
                    @endif
                </p>
            @endif
            <p class="mb-0 small text-muted"><strong>Documents :</strong> tous les dossiers doivent être déposés au format <strong>pdf</strong> (CV, lettre de motivation, copie du diplôme ou attestation).</p>
        </div>

        <div class="nb-important mt-4">
            <h6 class="fw-bold mb-2"> À noter</h6>
            <ul class="mb-2 ps-3">
                <li>Les candidats présélectionnés après le test QCM seront contactés pour la suite.</li>
                <li>Seuls les candidats retenus seront contactés.</li>
                <li>Résider dans les environs de Cocotomey, Cotonou, Calavi et Cococodji serait un atout.</li>
            </ul>
            <p class="mb-0 small fw-bold">Interprétation du score au test : 0–4 = Niveau insuffisant · 5–6 = Niveau moyen · 7–8 = Bon niveau · 9–10 = Excellent niveau</p>
        </div>
    </div>
</section>
@endsection
