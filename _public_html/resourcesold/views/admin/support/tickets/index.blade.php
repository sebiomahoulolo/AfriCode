@extends('admin.layouts.app')

@section('title', 'Tickets de support')

@section('content')
<div class="admin-content">
    <div class="admin-content-header">
        <div class="admin-content-header-content">
            <h1 class="admin-content-title"><i class="fas fa-ticket-alt"></i> Tickets de support</h1>
        </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-body">
            @if($tickets->isEmpty())
                <p class="text-muted mb-0">Aucun ticket.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Sujet</th>
                                <th>Utilisateur</th>
                                <th>Statut</th>
                                <th>Créé le</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                                <tr>
                                    <td>{{ $ticket->id }}</td>
                                    <td>{{ $ticket->subject }}</td>
                                    <td>{{ $ticket->user->first_name ?? $ticket->user->email }}</td>
                                    <td><span class="badge bg-secondary">{{ $ticket->status }}</span></td>
                                    <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $tickets->links() }}
            @endif
        </div>
    </div>
</div>
@endsection
