<div class="dropdown">
    <a class="nav-link dropdown-toggle position-relative" href="#" id="navbarDropdownNotifications" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell fa-fw"></i>
        @if($unreadCount > 0)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ $unreadCount < 100 ? $unreadCount : '99+' }}
            <span class="visually-hidden">notifications non lues</span>
        </span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg shadow animated--grow-in" aria-labelledby="navbarDropdownNotifications">
        <h6 class="dropdown-header">
            Centre de notifications
        </h6>
        
        @if ($notifications->isEmpty())
            <div class="dropdown-item text-center small text-muted py-3">
                <i class="fas fa-check-circle me-1"></i> Aucune notification
            </div>
        @else
            @foreach ($notifications as $notification)
                <a class="dropdown-item d-flex align-items-center {{ $notification->read ? 'text-muted' : 'fw-bold' }}" 
                   href="{{ $notification->action_url ?? route('admin.notifications.markRead', $notification->id) }}">
                    <div class="me-3">
                        <div class="icon-circle bg-{{ $notification->color }}">
                            <i class="fas fa-{{ $notification->icon }} text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-muted">{{ $notification->time_ago }}</div>
                        <span class="{{ $notification->read ? '' : 'fw-bold' }}">{{ $notification->title }}</span>
                        <div class="small">{{ Str::limit($notification->message, 100) }}</div>
                    </div>
                </a>
            @endforeach
        @endif
        
        <a class="dropdown-item text-center small text-primary" href="{{ route('admin.notifications.index') }}">
            Voir toutes les notifications
        </a>
    </div>
</div>

<style>
.icon-circle {
    height: 2.5rem;
    width: 2.5rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
