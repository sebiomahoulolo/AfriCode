@extends('admin.layouts.app')
@section('title', 'Notifications — AfriCode Admin')
@section('breadcrumb', 'Notifications')

@section('content')
@php
$colorMap = [
    'success' => ['bg' => '#dcfce7', 'text' => '#239e79', 'border' => '#239e79'],
    'info'    => ['bg' => '#dbeafe', 'text' => '#11c06d', 'border' => '#11c06d'],
    'danger'  => ['bg' => '#fee2e2', 'text' => '#fa1c39', 'border' => '#fa1c39'],
    'warning' => ['bg' => '#ffedd5', 'text' => '#fea837', 'border' => '#fea837'],
    'primary' => ['bg' => '#d1faf4', 'text' => '#11c06d', 'border' => '#11c06d'],
    'dark'    => ['bg' => '#f3f4f6', 'text' => '#374151', 'border' => '#6b7280'],
];
$unreadCount = $notifications->getCollection()->where('read', false)->count();
@endphp

<style>
.notif-card {
    background: #fff;
    border-radius: 14px;
    border: 1.5px solid #f0f0f0;
    padding: 18px 20px;
    margin-bottom: 10px;
    display: flex;
    align-items: flex-start;
    gap: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
    transition: all .2s;
    position: relative;
    overflow: hidden;
}
.notif-card:hover {
    box-shadow: 0 6px 20px rgba(0,0,0,.08);
    transform: translateY(-1px);
}
.notif-card.unread {
    border-color: rgba(17,192,109,.2);
    background: #fafffe;
}
.notif-card.unread::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    border-radius: 14px 0 0 14px;
}
.notif-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .95rem;
}
.notif-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: .75rem;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    border: none;
    transition: all .15s;
    white-space: nowrap;
}
.notif-btn-action {
    background: linear-gradient(135deg,#11c06d,#239e79);
    color: #fff;
}
.notif-btn-action:hover { opacity: .88; color: #fff; }
.notif-btn-read {
    background: rgba(17,192,109,.08);
    border: 1.5px solid rgba(17,192,109,.2);
    color: #11c06d;
}
.notif-btn-read:hover { background: rgba(17,192,109,.15); }
.notif-btn-delete {
    background: rgba(250,28,57,.06);
    border: 1.5px solid rgba(250,28,57,.15);
    color: #fa1c39;
}
.notif-btn-delete:hover { background: rgba(250,28,57,.12); }
.stat-box {
    background: #fff;
    border-radius: 14px;
    border: 1.5px solid #f0f0f0;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
}
.africode-pagination .page-link {
    color: #11c06d;
    border-color: #e5e7eb;
    border-radius: 8px !important;
    margin: 0 2px;
    font-weight: 600;
    font-size: .82rem;
    padding: 6px 12px;
}
.africode-pagination .page-item.active .page-link {
    background: linear-gradient(135deg,#11c06d,#239e79);
    border-color: transparent;
    color: #fff;
    box-shadow: 0 3px 10px rgba(17,192,109,.3);
}
.africode-pagination .page-item.disabled .page-link {
    color: #d1d5db;
    background: #f9fafb;
}
.africode-pagination .page-link:hover:not(.active) {
    background: rgba(17,192,109,.08);
    color: #11c06d;
    border-color: rgba(17,192,109,.3);
}
</style>

<div class="fade-in">

    {{-- Page header --}}
    <div class="admin-card" data-aos="fade-up" style="margin-bottom:1.5rem;">
        <div class="admin-card-header">
            <div>
                <h1 class="admin-card-title" style="font-size:1.4rem;margin-bottom:.3rem;">
                    <i class="fas fa-bell" style="color:#11c06d;margin-right:8px;"></i>Mes notifications
                </h1>
                <p class="admin-card-subtitle">
                    {{ $notifications->total() }} notification{{ $notifications->total() > 1 ? 's' : '' }} au total
                    @if($unreadCount > 0)
                        — <span style="color:#11c06d;font-weight:700;">{{ $unreadCount }} non lue{{ $unreadCount > 1 ? 's' : '' }}</span>
                    @endif
                </p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                @if($notifications->total() > 0)
                <form method="POST" action="{{ route('admin.notifications.markAllRead') }}">
                    @csrf
                    <button type="submit" class="notif-btn notif-btn-read" style="font-size:.8rem;padding:8px 16px;">
                        <i class="fas fa-check-double"></i> Tout marquer comme lu
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    {{-- Stats row --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;margin-bottom:1.5rem;" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-box">
            <div style="width:40px;height:40px;border-radius:11px;background:linear-gradient(135deg,#11c06d,#239e79);display:flex;align-items:center;justify-content:center;color:#fff;font-size:.95rem;flex-shrink:0;">
                <i class="fas fa-bell"></i>
            </div>
            <div>
                <div style="font-size:1.4rem;font-weight:800;color:#111827;line-height:1;">{{ $notifications->total() }}</div>
                <div style="font-size:.72rem;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Total</div>
            </div>
        </div>
        <div class="stat-box">
            <div style="width:40px;height:40px;border-radius:11px;background:linear-gradient(135deg,#11c06d,#6366f1);display:flex;align-items:center;justify-content:center;color:#fff;font-size:.95rem;flex-shrink:0;">
                <i class="fas fa-envelope"></i>
            </div>
            <div>
                <div style="font-size:1.4rem;font-weight:800;color:#111827;line-height:1;">{{ $unreadCount }}</div>
                <div style="font-size:.72rem;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Non lues</div>
            </div>
        </div>
        <div class="stat-box">
            <div style="width:40px;height:40px;border-radius:11px;background:linear-gradient(135deg,#239e79,#11c06d);display:flex;align-items:center;justify-content:center;color:#fff;font-size:.95rem;flex-shrink:0;">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <div style="font-size:1.4rem;font-weight:800;color:#111827;line-height:1;">{{ $notifications->total() - $unreadCount }}</div>
                <div style="font-size:.72rem;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Lues</div>
            </div>
        </div>
        <div class="stat-box">
            @php
                $todayCount = $notifications->getCollection()->filter(fn($n) => $n->created_at->isToday())->count();
            @endphp
            <div style="width:40px;height:40px;border-radius:11px;background:linear-gradient(135deg,#fea837,#FFB366);display:flex;align-items:center;justify-content:center;color:#fff;font-size:.95rem;flex-shrink:0;">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div>
                <div style="font-size:1.4rem;font-weight:800;color:#111827;line-height:1;">{{ $todayCount }}</div>
                <div style="font-size:.72rem;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Aujourd'hui</div>
            </div>
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
    <div style="background:linear-gradient(135deg,rgba(35,158,121,.1),rgba(17,192,109,.1));border:1.5px solid rgba(17,192,109,.25);border-radius:12px;padding:12px 18px;margin-bottom:16px;display:flex;align-items:center;gap:10px;color:#11c06d;font-weight:700;font-size:.85rem;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Notifications list --}}
    <div data-aos="fade-up" data-aos-delay="150">
        @forelse($notifications as $notif)
        @php
            $c = $colorMap[$notif->color ?? 'primary'] ?? $colorMap['primary'];
        @endphp
        <div class="notif-card {{ $notif->read ? '' : 'unread' }}"
             style="{{ !$notif->read ? '--border-left:' . $c['border'] . ';' : '' }}">
            @if(!$notif->read)
            <style>.notif-card.unread::before { background: {{ $c['border'] }}; }</style>
            @endif

            {{-- Icon --}}
            <div class="notif-icon" style="background:{{ $c['bg'] }};color:{{ $c['text'] }};">
                <i class="fas fa-{{ $notif->icon ?? 'bell' }}"></i>
            </div>

            {{-- Content --}}
            <div style="flex:1;min-width:0;">
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:4px;">
                    <span style="font-size:.9rem;font-weight:{{ $notif->read ? '600' : '800' }};color:#111827;">{{ $notif->title }}</span>
                    @if(!$notif->read)
                        <span style="background:#11c06d;color:#fff;font-size:.62rem;font-weight:800;padding:2px 8px;border-radius:10px;letter-spacing:.3px;">NOUVEAU</span>
                    @endif
                </div>
                <p style="font-size:.83rem;color:#6b7280;margin:0 0 8px;line-height:1.6;">{{ $notif->message }}</p>
                <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                    <span style="font-size:.72rem;color:#9ca3af;display:flex;align-items:center;gap:4px;">
                        <i class="fas fa-clock"></i> {{ $notif->created_at->diffForHumans() }}
                    </span>
                    <span style="font-size:.72rem;color:#d1d5db;">{{ $notif->created_at->format('d/m/Y à H:i') }}</span>
                </div>
            </div>

            {{-- Actions --}}
            <div style="display:flex;flex-direction:column;gap:6px;flex-shrink:0;align-items:flex-end;">
                @if($notif->action_url)
                <a href="{{ $notif->action_url }}" class="notif-btn notif-btn-action">
                    <i class="fas fa-external-link-alt" style="font-size:.7rem;"></i>
                    {{ $notif->action_text ?: 'Voir' }}
                </a>
                @endif

                @if(!$notif->read)
                <form method="POST" action="{{ route('admin.notifications.markRead', $notif->id) }}" style="margin:0;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="notif-btn notif-btn-read">
                        <i class="fas fa-check"></i> Marquer lu
                    </button>
                </form>
                @endif

                <form method="POST" action="{{ route('admin.notifications.destroy', $notif->id) }}" style="margin:0;"
                      onsubmit="return confirm('Supprimer cette notification ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="notif-btn notif-btn-delete">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div style="background:#fff;border-radius:16px;border:1.5px dashed #e5e7eb;padding:60px 24px;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,.04);">
            <div style="width:72px;height:72px;border-radius:20px;background:linear-gradient(135deg,rgba(17,192,109,.1),rgba(35,158,121,.1));display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <i class="fas fa-bell-slash" style="font-size:1.8rem;color:#d1d5db;"></i>
            </div>
            <h3 style="font-size:1rem;font-weight:700;color:#374151;margin:0 0 6px;">Aucune notification</h3>
            <p style="font-size:.83rem;color:#9ca3af;margin:0;">Vous n'avez aucune notification pour le moment.</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
    <div class="africode-pagination" style="display:flex;justify-content:center;margin-top:28px;">
        {{ $notifications->links() }}
    </div>
    @endif

</div>
@endsection
