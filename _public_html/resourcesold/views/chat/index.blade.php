@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="container py-5">
    <h1 class="mb-4"><i class="fas fa-comments"></i> Messages</h1>
    @if(isset($conversations) && $conversations->isNotEmpty())
        <div class="list-group">
            @foreach($conversations as $otherUserId => $messages)
                @php $first = $messages->first(); $other = $first->sender_id === auth()->id() ? $first->receiver : $first->sender; @endphp
                <a href="{{ route('chat.show', $other) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <span>{{ $other->first_name ?? $other->email }}</span>
                    <span class="badge bg-secondary">{{ $messages->count() }} message(s)</span>
                </a>
            @endforeach
        </div>
    @else
        <p class="text-muted">Aucune conversation. Utilisez le site pour échanger avec d'autres utilisateurs.</p>
    @endif
</div>
@endsection
