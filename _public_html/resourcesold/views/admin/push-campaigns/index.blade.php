@extends('admin.layouts.app')
@section('title', 'Campagnes Push')

@push('styles')
<style>
/* ─── Variables ─────────────────────────────────── */
:root {
    --ac-green:  #11c06d;
    --ac-green2: #239e79;
    --ac-orange: #fea837;
    --ac-navy:   #1a1a2e;
    --ac-red:    #fa1c39;
}

/* ─── Page Header ───────────────────────────────── */
.pc-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 28px;
}
.pc-page-title {
    font-size: 1.55rem;
    font-weight: 800;
    color: #111827;
    margin: 0;
    line-height: 1.2;
}
.pc-page-subtitle { font-size: .85rem; color: #6b7280; margin: 4px 0 0; }

/* ─── Stats Row ─────────────────────────────────── */
.pc-stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 28px;
}
.pc-stat-card {
    background: #fff;
    border-radius: 14px;
    padding: 18px 20px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    border: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: transform .2s, box-shadow .2s;
}
.pc-stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.09); }
.pc-stat-icon {
    width: 46px; height: 46px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.pc-stat-icon.green  { background: rgba(17,192,109,.12); color: var(--ac-green); }
.pc-stat-icon.green2 { background: rgba(35,158,121,.12); color: var(--ac-green2); }
.pc-stat-icon.orange { background: rgba(254,168,55,.12);  color: var(--ac-orange); }
.pc-stat-icon.gray   { background: rgba(108,117,125,.1);  color: #6b7280; }
.pc-stat-val  { font-size: 1.75rem; font-weight: 800; line-height: 1; color: #111827; }
.pc-stat-lbl  { font-size: .75rem; color: #6b7280; margin-top: 3px; }
.pc-stat-card-border-top { border-top: 3px solid var(--ac-green); }

/* ─── Alerts ────────────────────────────────────── */
.pc-flash {
    padding: 14px 18px;
    border-radius: 12px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: .9rem;
}
.pc-flash.success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
.pc-flash.error   { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }

/* ─── Table Card ────────────────────────────────── */
.pc-table-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    border: 1px solid #f0f0f0;
    overflow: hidden;
}
.pc-table-header {
    padding: 18px 24px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}
.pc-table-header-title { font-size: .95rem; font-weight: 700; color: #111827; }
.pc-table { width: 100%; border-collapse: collapse; }
.pc-table thead th {
    background: #f8f9fa;
    padding: 11px 16px;
    font-size: .72rem;
    font-weight: 700;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: .06em;
    border-bottom: 1.5px solid #e9ecef;
    white-space: nowrap;
}
.pc-table thead th:first-child { padding-left: 24px; }
.pc-table thead th:last-child  { padding-right: 24px; }
.pc-table tbody tr { border-bottom: 1px solid #f3f4f6; transition: background .15s; }
.pc-table tbody tr:last-child { border-bottom: none; }
.pc-table tbody tr:hover { background: #fafafa; }
.pc-table td { padding: 14px 16px; vertical-align: middle; }
.pc-table td:first-child { padding-left: 24px; }
.pc-table td:last-child  { padding-right: 24px; }

/* ─── Campaign cell ─────────────────────────────── */
.pc-campaign-name { font-weight: 700; font-size: .88rem; color: #111827; margin-bottom: 3px; }
.pc-campaign-body {
    font-size: .76rem; color: #6b7280;
    max-width: 240px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

/* ─── Target badge ──────────────────────────────── */
.pc-target-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 20px;
    background: #f3f4f6; color: #374151;
    font-size: .74rem; font-weight: 600;
    white-space: nowrap;
}

/* ─── Status badge ──────────────────────────────── */
.pc-status {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 11px; border-radius: 20px;
    font-size: .74rem; font-weight: 700;
    white-space: nowrap;
}
.pc-status.draft     { background: #f3f4f6;  color: #374151; }
.pc-status.scheduled { background: #fef9c3;  color: #854d0e; }
.pc-status.sending   { background: #dbeafe;  color: #1e40af; }
.pc-status.sent      { background: #dcfce7;  color: #166534; }
.pc-status.failed    { background: #fee2e2;  color: #991b1b; }
.pc-status-dot {
    width: 6px; height: 6px; border-radius: 50%;
    display: inline-block; flex-shrink: 0;
}
.pc-status.sent .pc-status-dot      { background: #22c55e; }
.pc-status.scheduled .pc-status-dot { background: #f59e0b; animation: pcPulse 1.5s infinite; }
.pc-status.sending .pc-status-dot   { background: #11c06d; animation: pcPulse 1s infinite; }
.pc-status.failed .pc-status-dot    { background: #ef4444; }
.pc-status.draft .pc-status-dot     { background: #9ca3af; }
@keyframes pcPulse { 0%,100%{opacity:1} 50%{opacity:.4} }

/* ─── Actions ───────────────────────────────────── */
.pc-actions { display: flex; gap: 6px; justify-content: flex-end; align-items: center; }
.pc-btn-icon {
    width: 34px; height: 34px;
    border-radius: 9px;
    border: 1.5px solid #e5e7eb;
    background: #fff;
    display: flex; align-items: center; justify-content: center;
    color: #6b7280; font-size: .82rem;
    cursor: pointer; transition: all .18s;
    text-decoration: none;
}
.pc-btn-icon:hover { background: #f3f4f6; color: #111827; border-color: #d1d5db; }
.pc-btn-icon.send  { background: rgba(17,192,109,.08); color: var(--ac-green); border-color: rgba(17,192,109,.25); }
.pc-btn-icon.send:hover { background: var(--ac-green); color: #fff; }
.pc-btn-icon.del   { background: rgba(239,68,68,.06); color: #ef4444; border-color: rgba(239,68,68,.2); }
.pc-btn-icon.del:hover { background: #ef4444; color: #fff; }

/* ─── Empty State ───────────────────────────────── */
.pc-empty {
    text-align: center;
    padding: 56px 24px;
}
.pc-empty-icon {
    width: 72px; height: 72px;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(17,192,109,.1), rgba(35,158,121,.1));
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px;
}
.pc-empty-icon i { font-size: 1.8rem; color: var(--ac-green); }
.pc-empty h5 { font-size: 1.1rem; font-weight: 700; color: #111827; margin-bottom: 8px; }
.pc-empty p  { font-size: .88rem; color: #6b7280; max-width: 340px; margin: 0 auto 20px; }

/* ─── New Campaign Button ───────────────────────── */
.pc-btn-new {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 20px; border-radius: 12px;
    background: linear-gradient(135deg, var(--ac-green), var(--ac-green2));
    color: #fff; font-weight: 700; font-size: .88rem;
    text-decoration: none; border: none; cursor: pointer;
    box-shadow: 0 4px 14px rgba(17,192,109,.3);
    transition: transform .2s, box-shadow .2s;
}
.pc-btn-new:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(17,192,109,.4); color: #fff; }

/* ─── Date cell ─────────────────────────────────── */
.pc-date { font-size: .8rem; color: #374151; }
.pc-date .date-label { font-size: .7rem; color: #9ca3af; display: block; }

/* ─── Recipients ────────────────────────────────── */
.pc-recipients { font-weight: 800; font-size: .95rem; color: #111827; }

/* ─── Responsive ────────────────────────────────── */
@media (max-width: 992px) {
    .pc-stats-row { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 576px) {
    .pc-stats-row { grid-template-columns: 1fr 1fr; }
    .pc-campaign-body { display: none; }
}
</style>
@endpush

@section('content')

{{-- ─── Page Header ──────────────────────────────── --}}
<div class="pc-page-header">
    <div>
        <h1 class="pc-page-title">
            <i class="fas fa-bell me-2" style="color:var(--ac-green);"></i>Campagnes Push
        </h1>
        <p class="pc-page-subtitle">Créez et envoyez des notifications push ciblées à vos abonnés AfriCode</p>
    </div>
    <a href="{{ route('admin.push-campaigns.create') }}" class="pc-btn-new">
        <i class="fas fa-plus"></i> Nouvelle campagne
    </a>
</div>

{{-- ─── Alerts ───────────────────────────────────── --}}
@if(session('success'))
<div class="pc-flash success">
    <i class="fas fa-check-circle" style="font-size:1.1rem;"></i>
    <span>{{ session('success') }}</span>
</div>
@endif
@if(session('error'))
<div class="pc-flash error">
    <i class="fas fa-exclamation-circle" style="font-size:1.1rem;"></i>
    <span>{{ session('error') }}</span>
</div>
@endif

{{-- ─── Stats ────────────────────────────────────── --}}
<div class="pc-stats-row">
    <div class="pc-stat-card pc-stat-card-border-top">
        <div class="pc-stat-icon green"><i class="fas fa-users"></i></div>
        <div>
            <div class="pc-stat-val">{{ number_format($stats['subscribers']) }}</div>
            <div class="pc-stat-lbl">Abonnés actifs</div>
        </div>
    </div>
    <div class="pc-stat-card">
        <div class="pc-stat-icon green2"><i class="fas fa-paper-plane"></i></div>
        <div>
            <div class="pc-stat-val">{{ $stats['sent'] }}</div>
            <div class="pc-stat-lbl">Campagnes envoyées</div>
        </div>
    </div>
    <div class="pc-stat-card">
        <div class="pc-stat-icon orange"><i class="fas fa-clock"></i></div>
        <div>
            <div class="pc-stat-val">{{ $stats['scheduled'] }}</div>
            <div class="pc-stat-lbl">Planifiées</div>
        </div>
    </div>
    <div class="pc-stat-card">
        <div class="pc-stat-icon gray"><i class="fas fa-file-alt"></i></div>
        <div>
            <div class="pc-stat-val">{{ $stats['draft'] }}</div>
            <div class="pc-stat-lbl">Brouillons</div>
        </div>
    </div>
</div>

{{-- ─── Table ────────────────────────────────────── --}}
<div class="pc-table-card">
    <div class="pc-table-header">
        <span class="pc-table-header-title">
            <i class="fas fa-list me-2" style="color:var(--ac-green);"></i>
            Toutes les campagnes
            <span style="background:#f3f4f6;color:#6b7280;font-size:.73rem;padding:2px 8px;border-radius:20px;margin-left:8px;font-weight:600;">
                {{ $campaigns->total() }}
            </span>
        </span>
    </div>

    @if($campaigns->isEmpty())
    <div class="pc-empty">
        <div class="pc-empty-icon"><i class="fas fa-bell-slash"></i></div>
        <h5>Aucune campagne créée</h5>
        <p>Créez votre première campagne push pour toucher directement vos {{ number_format($stats['subscribers']) }} abonnés.</p>
        <a href="{{ route('admin.push-campaigns.create') }}" class="pc-btn-new">
            <i class="fas fa-plus"></i> Créer ma première campagne
        </a>
    </div>
    @else
    <div style="overflow-x:auto;">
        <table class="pc-table">
            <thead>
                <tr>
                    <th>Campagne</th>
                    <th>Cible</th>
                    <th>Statut</th>
                    <th>Destinataires</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($campaigns as $c)
                <tr>
                    <td>
                        <div class="pc-campaign-name">{{ $c->title }}</div>
                        <div class="pc-campaign-body">{{ $c->body }}</div>
                    </td>
                    <td>
                        <span class="pc-target-badge">
                            <i class="fas fa-crosshairs" style="color:var(--ac-orange);font-size:.7rem;"></i>
                            {{ $c->targetLabel() }}
                        </span>
                    </td>
                    <td>
                        <span class="pc-status {{ $c->status }}">
                            <span class="pc-status-dot"></span>
                            {{ $c->statusLabel() }}
                        </span>
                    </td>
                    <td>
                        @if($c->recipients_count > 0)
                            <span class="pc-recipients">{{ number_format($c->recipients_count) }}</span>
                        @else
                            <span style="color:#d1d5db;font-size:.9rem;">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="pc-date">
                            @if($c->sent_at)
                                <i class="fas fa-check-circle" style="color:#22c55e;" class="me-1"></i>
                                {{ $c->sent_at->format('d/m/Y') }}
                                <span class="date-label">{{ $c->sent_at->format('H:i') }}</span>
                            @elseif($c->scheduled_at)
                                <i class="fas fa-clock" style="color:var(--ac-orange);" class="me-1"></i>
                                {{ $c->scheduled_at->format('d/m/Y') }}
                                <span class="date-label">{{ $c->scheduled_at->format('H:i') }}</span>
                            @else
                                <span style="color:#9ca3af;">{{ $c->created_at->format('d/m/Y') }}</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="pc-actions">
                            <a href="{{ route('admin.push-campaigns.show', $c) }}"
                               class="pc-btn-icon" title="Voir le détail">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(!in_array($c->status, ['sent','sending']))
                            <form action="{{ route('admin.push-campaigns.send', $c) }}" method="POST" style="display:contents;">
                                @csrf
                                <button type="submit" class="pc-btn-icon send" title="Envoyer maintenant"
                                    onclick="return confirm('Envoyer « {{ addslashes($c->title) }} » maintenant ?')">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.push-campaigns.destroy', $c) }}" method="POST" style="display:contents;">
                                @csrf @method('DELETE')
                                <button type="submit" class="pc-btn-icon del" title="Supprimer"
                                    onclick="return confirm('Supprimer cette campagne ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($campaigns->hasPages())
    <div style="padding:16px 24px;border-top:1px solid #f3f4f6;">
        {{ $campaigns->links() }}
    </div>
    @endif
    @endif
</div>

@endsection
