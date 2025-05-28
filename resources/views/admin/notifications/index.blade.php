@extends('admin.layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Notifications</h1>
        
        <div>
            <form action="{{ route('admin.notifications.markAllRead') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-check-double fa-fw"></i> Marquer tout comme lu
                </button>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Toutes les notifications</h6>
        </div>
        <div class="card-body">
            @if($notifications->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                    <p class="mb-0">Vous n'avez pas de notifications.</p>
                </div>
            @else
                <div class="notifications-list">
                    @foreach($notifications as $notification)
                        <div class="notification-item p-3 border-bottom {{ $notification->read ? 'bg-light' : '' }}">
                            <div class="d-flex">
                                <div class="me-3">
                                    <div class="icon-circle bg-{{ $notification->color }} text-white">
                                        <i class="fas fa-{{ $notification->icon }}"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h6 class="{{ $notification->read ? '' : 'fw-bold' }}">
                                            {{ $notification->title }}
                                        </h6>
                                        <small class="text-muted">{{ $notification->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <p class="mb-1">{{ $notification->message }}</p>
                                    <div class="d-flex align-items-center">
                                        <small class="text-muted me-3">{{ $notification->time_ago }}</small>
                                        
                                        <div class="actions">
                                            @if($notification->action_url)
                                                <a href="{{ $notification->action_url }}" class="btn btn-sm btn-primary">
                                                    {{ $notification->action_text ?? 'Voir détails' }}
                                                </a>
                                            @endif
                                            
                                            @if(!$notification->read)
                                                <form action="{{ route('admin.notifications.markRead', $notification->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                        <i class="fas fa-check fa-fw"></i> Marquer comme lu
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" class="d-inline ms-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette notification?')">
                                                    <i class="fas fa-trash-alt fa-fw"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.icon-circle {
    height: 40px;
    width: 40px;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notification-item:hover {
    background-color: rgba(0,0,0,0.01);
}
</style>
@endsection
