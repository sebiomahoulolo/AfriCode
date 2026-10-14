@extends('layouts.app')

@section('title', 'Mes tickets de support')

@section('content')
<div class="container py-5">
    <h1 class="mb-4"><i class="fas fa-ticket-alt"></i> Mes tickets de support</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <p><a href="{{ route('support.tickets.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nouveau ticket</a></p>
    @if($tickets->isEmpty())
        <p class="text-muted">Vous n'avez aucun ticket.</p>
    @else
        <div class="list-group">
            @foreach($tickets as $ticket)
                <a href="{{ route('support.tickets.show', $ticket) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <span><strong>{{ $ticket->subject }}</strong> — {{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                    <span class="badge bg-{{ $ticket->status === 'open' ? 'warning' : ($ticket->status === 'resolved' ? 'success' : 'secondary') }}">{{ $ticket->status }}</span>
                </a>
            @endforeach
        </div>
        {{ $tickets->links() }}
    @endif
</div>
@endsection
