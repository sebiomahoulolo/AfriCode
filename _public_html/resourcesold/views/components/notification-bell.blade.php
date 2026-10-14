@php
use App\Models\Notification as AppNotification;
$bellUserId    = Auth::id();
$bellNotifs    = AppNotification::where('user_id', $bellUserId)->orderByDesc('created_at')->limit(6)->get();
$bellUnread    = AppNotification::where('user_id', $bellUserId)->where('read', false)->count();

$colorMap = [
    'success' => '#239e79', 'info'    => '#11c06d',
    'danger'  => '#fa1c39', 'warning' => '#fea837',
    'primary' => '#11c06d', 'dark'    => '#374151',
];
$listUrl = match(Auth::user()?->role ?? '') {
    'apprenant'                        => route('notifications.apprenant'),
    'formateur'                        => route('notifications.formateur'),
    default                            => route('admin.notifications.index'),
};
@endphp

<div class="nb-wrap" id="nbWrap" style="position:relative;">
    <button class="nb-btn" id="nbBtn" aria-label="Notifications" aria-expanded="false">
        <i class="fas fa-bell"></i>
        <span class="nb-badge {{ $bellUnread === 0 ? 'nb-badge-hidden' : '' }}" id="nbBadge">
            {{ $bellUnread > 9 ? '9+' : $bellUnread }}
        </span>
    </button>

    <div class="nb-dropdown" id="nbDropdown" aria-hidden="true">
        {{-- Header --}}
        <div class="nb-header">
            <div class="nb-header-left">
                <span class="nb-header-title">Notifications</span>
                @if($bellUnread > 0)
                    <span class="nb-header-count">{{ $bellUnread }} non lue{{ $bellUnread > 1 ? 's' : '' }}</span>
                @endif
            </div>
            @if($bellUnread > 0)
            <button class="nb-read-all" id="nbReadAll" title="Marquer tout comme lu">
                <i class="fas fa-check-double"></i>
            </button>
            @endif
        </div>

        {{-- Liste --}}
        <div class="nb-list" id="nbList">
            @forelse($bellNotifs as $notif)
            @php
                $nbColor = $colorMap[$notif->color ?? 'primary'] ?? '#11c06d';
                $nbIcon  = $notif->icon ?? 'bell';
            @endphp
            <a href="{{ $notif->action_url ?: '#' }}"
               class="nb-item {{ $notif->read ? 'nb-item-read' : 'nb-item-unread' }}"
               data-id="{{ $notif->id }}"
               data-url="{{ $notif->action_url }}"
               onclick="nbMarkRead(event, this)">
                <div class="nb-item-icon" style="background:{{ $nbColor }}20;color:{{ $nbColor }};">
                    <i class="fas fa-{{ $nbIcon }}"></i>
                </div>
                <div class="nb-item-body">
                    <p class="nb-item-title">{{ $notif->title }}</p>
                    <p class="nb-item-msg">{{ Str::limit($notif->message, 68) }}</p>
                    <span class="nb-item-time">{{ $notif->created_at->diffForHumans() }}</span>
                </div>
                @if(!$notif->read)
                <span class="nb-item-dot"></span>
                @endif
            </a>
            @empty
            <div class="nb-empty">
                <i class="fas fa-bell-slash"></i>
                <p>Aucune notification</p>
            </div>
            @endforelse
        </div>

        {{-- Footer --}}
        <div class="nb-footer">
            <a href="{{ $listUrl }}">
                Voir toutes les notifications
                <i class="fas fa-arrow-right" style="font-size:.7rem;margin-left:4px;"></i>
            </a>
        </div>
    </div>
</div>

<style>
/* ─── Notification Bell ──────────────────── */
.nb-btn {
    position: relative;
    width: 38px; height: 38px; border-radius: 10px;
    border: 1.5px solid #e5e7eb;
    background: #fff;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #6b7280; font-size: .95rem;
    transition: all .15s;
    flex-shrink: 0;
}
.nb-btn:hover { background: #f3f4f6; color: #11c06d; border-color: #c5e8e0; }
.nb-btn.nb-active { color: #11c06d; border-color: #11c06d; background: rgba(17,192,109,.06); }

.nb-badge {
    position: absolute; top: -5px; right: -5px;
    background: #fa1c39; color: #fff;
    font-size: .62rem; font-weight: 800;
    min-width: 17px; height: 17px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    padding: 0 4px; border: 2px solid #fff;
    transition: transform .2s;
}
.nb-badge-hidden { display: none; }

/* ─── Dropdown ───────────────────────────── */
.nb-dropdown {
    position: absolute; top: calc(100% + 10px); right: 0;
    width: 340px;
    background: #fff;
    border-radius: 16px;
    border: 1.5px solid #f0f0f0;
    box-shadow: 0 12px 40px rgba(0,0,0,.14);
    z-index: 9999;
    overflow: hidden;
    opacity: 0; visibility: hidden; pointer-events: none;
    transform: translateY(-8px) scale(.97);
    transition: opacity .18s, transform .18s, visibility .18s;
}
.nb-dropdown.nb-open {
    opacity: 1; visibility: visible; pointer-events: all;
    transform: translateY(0) scale(1);
}

/* ─── Header ─────────────────────────────── */
.nb-header {
    padding: 14px 16px;
    border-bottom: 1px solid #f3f4f6;
    display: flex; align-items: center; justify-content: space-between;
}
.nb-header-left { display: flex; align-items: center; gap: 8px; }
.nb-header-title { font-size: .88rem; font-weight: 800; color: #111827; }
.nb-header-count {
    background: #fee2e2; color: #fa1c39;
    font-size: .68rem; font-weight: 800;
    padding: 2px 7px; border-radius: 10px;
}
.nb-read-all {
    background: rgba(17,192,109,.08);
    border: 1px solid rgba(17,192,109,.2);
    color: #11c06d;
    width: 28px; height: 28px; border-radius: 8px;
    cursor: pointer; font-size: .78rem;
    display: flex; align-items: center; justify-content: center;
    transition: all .15s;
}
.nb-read-all:hover { background: #11c06d; color: #fff; }

/* ─── List ───────────────────────────────── */
.nb-list { max-height: 310px; overflow-y: auto; }
.nb-list::-webkit-scrollbar { width: 4px; }
.nb-list::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }

.nb-item {
    display: flex; align-items: flex-start; gap: 11px;
    padding: 12px 16px;
    text-decoration: none;
    border-bottom: 1px solid #f9fafb;
    transition: background .12s;
    position: relative;
    cursor: pointer;
}
.nb-item:hover { background: #fafafa; }
.nb-item-unread { background: rgba(17,192,109,.03); }
.nb-item-read { opacity: .8; }

.nb-item-icon {
    width: 36px; height: 36px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: .82rem; flex-shrink: 0;
}
.nb-item-body { flex: 1; min-width: 0; }
.nb-item-title {
    font-size: .8rem; font-weight: 700; color: #111827;
    margin: 0 0 2px; white-space: nowrap;
    overflow: hidden; text-overflow: ellipsis;
}
.nb-item-msg {
    font-size: .74rem; color: #6b7280;
    margin: 0 0 3px; line-height: 1.45;
}
.nb-item-time { font-size: .68rem; color: #9ca3af; }
.nb-item-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: #11c06d; flex-shrink: 0; margin-top: 5px;
}

.nb-empty {
    padding: 32px 16px; text-align: center; color: #9ca3af;
}
.nb-empty i { font-size: 1.8rem; margin-bottom: 8px; display: block; }
.nb-empty p { font-size: .82rem; margin: 0; }

/* ─── Footer ─────────────────────────────── */
.nb-footer {
    padding: 11px 16px;
    border-top: 1px solid #f3f4f6;
    text-align: center;
}
.nb-footer a {
    font-size: .8rem; font-weight: 700;
    color: #11c06d; text-decoration: none;
    display: inline-flex; align-items: center;
}
.nb-footer a:hover { color: #178A73; }
</style>

<script>
(function () {
    var wrap     = document.getElementById('nbWrap');
    var btn      = document.getElementById('nbBtn');
    var dropdown = document.getElementById('nbDropdown');
    var badge    = document.getElementById('nbBadge');
    var readAll  = document.getElementById('nbReadAll');
    var csrfMeta = document.querySelector('meta[name="csrf-token"]');
    var csrf     = csrfMeta ? csrfMeta.content : '';

    /* ── Toggle ─────────────────────────── */
    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        var open = dropdown.classList.contains('nb-open');
        dropdown.classList.toggle('nb-open', !open);
        btn.classList.toggle('nb-active', !open);
        btn.setAttribute('aria-expanded', String(!open));
        dropdown.setAttribute('aria-hidden', String(open));
    });

    document.addEventListener('click', function (e) {
        if (!wrap.contains(e.target)) {
            dropdown.classList.remove('nb-open');
            btn.classList.remove('nb-active');
            btn.setAttribute('aria-expanded', 'false');
        }
    });

    /* ── Mark single as read ────────────── */
    window.nbMarkRead = function (e, el) {
        var id  = el.dataset.id;
        var url = el.dataset.url;

        fetch('/notifications/' + id + '/read', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
        });

        el.classList.remove('nb-item-unread');
        el.classList.add('nb-item-read');
        var dot = el.querySelector('.nb-item-dot');
        if (dot) dot.remove();

        updateBadge(-1);

        if (url && url !== '#' && url !== 'null') {
            e.preventDefault();
            setTimeout(function () { window.location.href = url; }, 120);
        }
    };

    /* ── Mark all as read ───────────────── */
    if (readAll) {
        readAll.addEventListener('click', function () {
            fetch('/notifications/read-all', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
            }).then(function () {
                document.querySelectorAll('.nb-item-unread').forEach(function (item) {
                    item.classList.remove('nb-item-unread');
                    item.classList.add('nb-item-read');
                    var dot = item.querySelector('.nb-item-dot');
                    if (dot) dot.remove();
                });
                setBadge(0);
                readAll.remove();
                var cnt = document.querySelector('.nb-header-count');
                if (cnt) cnt.remove();
            });
        });
    }

    /* ── Badge helpers ──────────────────── */
    var currentCount = parseInt(badge ? badge.textContent : '0') || 0;

    function setBadge(n) {
        currentCount = Math.max(0, n);
        if (!badge) return;
        badge.textContent = currentCount > 9 ? '9+' : currentCount;
        badge.classList.toggle('nb-badge-hidden', currentCount === 0);
    }

    function updateBadge(delta) {
        setBadge(currentCount + delta);
    }

    /* ── Polling every 30s ──────────────── */
    function pollCount() {
        fetch('/notifications/count', { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (data) { setBadge(data.unread); })
            .catch(function () {});
    }

    setInterval(pollCount, 30000);
})();
</script>
