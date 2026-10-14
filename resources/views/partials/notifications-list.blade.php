@php
$colorMap = [
    'success' => ['bg' => '#dcfce7', 'text' => '#27B371'],
    'info'    => ['bg' => '#dbeafe', 'text' => '#3b82f6'],
    'danger'  => ['bg' => '#fee2e2', 'text' => '#E32D31'],
    'warning' => ['bg' => '#ffedd5', 'text' => '#FF8E2A'],
    'primary' => ['bg' => '#d1faf4', 'text' => '#1EA38B'],
    'dark'    => ['bg' => '#f3f4f6', 'text' => '#374151'],
];
$role = $role ?? 'apprenant';
@endphp

<div style="max-width:760px;margin:0 auto;padding:24px 16px;">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
        <div>
            <h1 style="font-size:1.4rem;font-weight:800;color:#111827;margin:0 0 3px;">
                <i class="fas fa-bell me-2" style="color:#1EA38B;"></i>Mes notifications
            </h1>
            <p style="font-size:.82rem;color:#9ca3af;margin:0;">{{ $notifications->total() }} notification{{ $notifications->total() > 1 ? 's' : '' }} au total</p>
        </div>
        @if($notifications->total() > 0)
        <form method="POST" action="{{ route('notifications.readAll') }}">
            @csrf
            <button type="submit"
                style="background:rgba(30,163,139,.08);border:1.5px solid rgba(30,163,139,.2);color:#1EA38B;padding:8px 16px;border-radius:10px;font-size:.82rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:7px;">
                <i class="fas fa-check-double"></i> Tout marquer comme lu
            </button>
        </form>
        @endif
    </div>

    {{-- Notifications --}}
    @forelse($notifications as $notif)
    @php
        $c = $colorMap[$notif->color ?? 'primary'] ?? $colorMap['primary'];
    @endphp
    <div style="background:#fff;border-radius:14px;border:1.5px solid {{ $notif->read ? '#f0f0f0' : 'rgba(30,163,139,.15)' }};
                padding:16px 18px;margin-bottom:10px;display:flex;align-items:flex-start;gap:14px;
                box-shadow:0 2px 8px rgba(0,0,0,.04);transition:all .15s;
                {{ $notif->read ? 'opacity:.85;' : '' }}">
        <div style="width:40px;height:40px;border-radius:11px;flex-shrink:0;
                    background:{{ $c['bg'] }};color:{{ $c['text'] }};
                    display:flex;align-items:center;justify-content:center;font-size:.9rem;">
            <i class="fas fa-{{ $notif->icon ?? 'bell' }}"></i>
        </div>
        <div style="flex:1;min-width:0;">
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:3px;">
                <strong style="font-size:.88rem;color:#111827;">{{ $notif->title }}</strong>
                @if(!$notif->read)
                    <span style="background:#1EA38B;color:#fff;font-size:.62rem;font-weight:800;padding:1px 7px;border-radius:10px;">Nouveau</span>
                @endif
            </div>
            <p style="font-size:.8rem;color:#6b7280;margin:0 0 5px;line-height:1.55;">{{ $notif->message }}</p>
            <span style="font-size:.72rem;color:#9ca3af;">
                <i class="fas fa-clock me-1"></i>{{ $notif->created_at->diffForHumans() }}
            </span>
        </div>
        <div style="display:flex;flex-direction:column;gap:6px;flex-shrink:0;">
            @if($notif->action_url)
            <a href="{{ $notif->action_url }}"
               style="background:linear-gradient(135deg,#1EA38B,#27B371);color:#fff;padding:6px 12px;border-radius:8px;font-size:.75rem;font-weight:700;text-decoration:none;white-space:nowrap;">
                {{ $notif->action_text ?: 'Voir' }}
            </a>
            @endif
            @if(!$notif->read)
            <form method="POST" action="{{ route('notifications.read', $notif->id) }}">
                @csrf
                <button type="submit"
                    style="background:#f3f4f6;border:1px solid #e5e7eb;color:#6b7280;padding:5px 10px;border-radius:8px;font-size:.72rem;cursor:pointer;width:100%;">
                    <i class="fas fa-check"></i> Lu
                </button>
            </form>
            @endif
        </div>
    </div>
    @empty
    <div style="background:#fff;border-radius:16px;border:1.5px dashed #e5e7eb;padding:48px 24px;text-align:center;">
        <i class="fas fa-bell-slash" style="font-size:2.5rem;color:#d1d5db;display:block;margin-bottom:12px;"></i>
        <p style="font-size:.9rem;color:#9ca3af;margin:0;">Aucune notification pour l'instant.</p>
    </div>
    @endforelse

    {{-- Pagination --}}
    @if($notifications->hasPages())
    <div style="margin-top:20px;">{{ $notifications->links() }}</div>
    @endif

</div>
