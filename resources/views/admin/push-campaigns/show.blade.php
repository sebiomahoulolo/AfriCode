@extends('admin.layouts.app')
@section('title', 'Campagne : ' . $campaign->title)

@section('content')
<div class="admin-page-header mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
        <a href="{{ route('admin.push-campaigns.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
        <h1 class="admin-page-title mb-0">
            <i class="fas fa-bell me-2" style="color:#1EA38B;"></i>{{ $campaign->title }}
        </h1>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        @if(!in_array($campaign->status, ['sent', 'sending']))
        <form action="{{ route('admin.push-campaigns.send', $campaign) }}" method="POST">
            @csrf
            <button type="submit" class="btn text-white" style="background:linear-gradient(135deg,#27B371,#1EA38B);border:none;"
                onclick="return confirm('Envoyer cette campagne maintenant ?')">
                <i class="fas fa-paper-plane me-1"></i> Envoyer maintenant
            </button>
        </form>
        <form action="{{ route('admin.push-campaigns.destroy', $campaign) }}" method="POST">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger"
                onclick="return confirm('Supprimer cette campagne ?')">
                <i class="fas fa-trash me-1"></i> Supprimer
            </button>
        </form>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    {{-- Infos principale --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body px-4 py-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <span class="badge bg-{{ $campaign->statusColor() }} fs-6 px-3 py-2">{{ $campaign->statusLabel() }}</span>
                    @if($campaign->sent_at)
                        <span class="text-muted small"><i class="fas fa-check-circle text-success me-1"></i>Envoyée le {{ $campaign->sent_at->format('d/m/Y à H:i') }}</span>
                    @elseif($campaign->scheduled_at)
                        <span class="text-muted small"><i class="fas fa-clock text-warning me-1"></i>Planifiée le {{ $campaign->scheduled_at->format('d/m/Y à H:i') }}</span>
                    @endif
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="p-3" style="background:#f8f9fa;border-radius:10px;">
                            <div class="text-muted small mb-1">Titre</div>
                            <div class="fw-600">{{ $campaign->title }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3" style="background:#f8f9fa;border-radius:10px;">
                            <div class="text-muted small mb-1">Tag</div>
                            <div class="fw-600 font-monospace">{{ $campaign->tag }}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3" style="background:#f8f9fa;border-radius:10px;">
                            <div class="text-muted small mb-1">Message</div>
                            <div>{{ $campaign->body }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3" style="background:#f8f9fa;border-radius:10px;">
                            <div class="text-muted small mb-1">URL</div>
                            <div class="text-break" style="font-size:.88rem;">{{ $campaign->url }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3" style="background:#f8f9fa;border-radius:10px;">
                            <div class="text-muted small mb-1">Cible</div>
                            <div class="fw-600"><i class="fas fa-crosshairs me-1" style="color:#FF8E2A;"></i>{{ $campaign->targetLabel() }}</div>
                        </div>
                    </div>
                </div>

                {{-- Prévisualisation notification --}}
                <div>
                    <div class="text-muted small mb-2 fw-600">Aperçu sur mobile</div>
                    <div style="background:#f1f3f4;border-radius:12px;padding:14px 16px;display:flex;align-items:flex-start;gap:12px;max-width:380px;">
                        <img src="/images/icons/africode-192.png" alt=""
                            style="width:40px;height:40px;border-radius:8px;flex-shrink:0;object-fit:cover;"
                            onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 40 40%22%3E%3Crect width=%2240%22 height=%2240%22 fill=%22%231EA38B%22 rx=%228%22/%3E%3C/svg%3E'">
                        <div style="flex:1;min-width:0;">
                            <div style="font-weight:700;font-size:.85rem;color:#1f2937;">{{ $campaign->title }}</div>
                            <div style="font-size:.78rem;color:#6b7280;margin-top:2px;line-height:1.4;">{{ $campaign->body }}</div>
                            <div style="font-size:.7rem;color:#9ca3af;margin-top:4px;">AfriCode · maintenant</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar stats --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body px-4 py-4">
                <h6 class="fw-700 mb-3">Résultats</h6>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:48px;height:48px;border-radius:12px;background:rgba(30,163,139,.1);display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-users" style="font-size:1.2rem;color:#1EA38B;"></i>
                    </div>
                    <div>
                        <div style="font-size:1.8rem;font-weight:800;line-height:1;">{{ number_format($campaign->recipients_count) }}</div>
                        <div class="text-muted small">destinataire(s)</div>
                    </div>
                </div>
                <hr class="my-3">
                <div class="small text-muted">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Créée par</span>
                        <span class="fw-600 text-dark">{{ $campaign->creator?->first_name }} {{ $campaign->creator?->last_name }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Créée le</span>
                        <span class="fw-600 text-dark">{{ $campaign->created_at->format('d/m/Y') }}</span>
                    </div>
                    @if($campaign->sent_at)
                    <div class="d-flex justify-content-between">
                        <span>Envoyée le</span>
                        <span class="fw-600 text-success">{{ $campaign->sent_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body px-4 py-3">
                <h6 class="fw-700 mb-3 small text-muted text-uppercase">Ciblage détaillé</h6>
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-crosshairs" style="color:#FF8E2A;width:20px;"></i>
                    <span class="small">{{ $campaign->targetLabel() }}</span>
                </div>
                @if($campaign->target_value)
                <div class="mt-2 p-2" style="background:#fff3e0;border-radius:8px;font-size:.75rem;font-family:monospace;">
                    {{ json_encode($campaign->target_value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>.fw-600{font-weight:600}.fw-700{font-weight:700}</style>
@endsection
