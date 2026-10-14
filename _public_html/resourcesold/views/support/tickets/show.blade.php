@extends('layouts.app')

@section('title', 'Ticket #' . $ticket->id)

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Ticket #{{ $ticket->id }} — {{ $ticket->subject }}</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Statut : <strong>{{ $ticket->status }}</strong> — Priorité : {{ $ticket->priority }}</span>
            <small class="text-muted">{{ $ticket->created_at->format('d/m/Y H:i') }}</small>
        </div>
        <div class="card-body">
            <p>{{ $ticket->description }}</p>
        </div>
    </div>
    <h5>Réponses</h5>
    @foreach($ticket->replies as $reply)
        <div class="card mb-2">
            <div class="card-body py-2">
                <small class="text-muted">{{ $reply->user->first_name ?? $reply->user->email }} — {{ $reply->created_at->format('d/m/Y H:i') }}{{ $reply->is_staff_reply ? ' (Équipe)' : '' }}</small>
                <p class="mb-0 mt-1">{{ $reply->content }}</p>
            </div>
        </div>
    @endforeach
    <form action="{{ route('support.tickets.reply', $ticket) }}" method="POST" class="mt-4">
        @csrf
        <div class="mb-3">
            <label for="content" class="form-label">Répondre</label>
            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="3" required></textarea>
            @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-primary">Envoyer la réponse</button>
    </form>
    <p class="mt-3"><a href="{{ route('support.tickets.index') }}" class="btn btn-outline-secondary">Retour aux tickets</a></p>
</div>
@endsection
