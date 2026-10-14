@extends('layouts.app')

@section('title', 'Conversation')

@section('content')
<div class="container py-5">
    <h1 class="mb-4"><i class="fas fa-comment"></i> Conversation</h1>
    @if(isset($messages) && $messages->isNotEmpty())
        <div class="card">
            <div class="card-body">
                @foreach($messages as $msg)
                    <div class="mb-2"><strong>{{ ($msg->sender_id === auth()->id() ? $msg->sender : $msg->receiver)->first_name ?? 'User' }}</strong> : {{ $msg->content ?? '' }}</div>
                @endforeach
            </div>
        </div>
    @else
        <p class="text-muted">Aucun message dans cette conversation.</p>
    @endif
    <p class="mt-3"><a href="{{ route('chat.index') }}" class="btn btn-outline-secondary">Retour aux messages</a></p>
</div>
@endsection
