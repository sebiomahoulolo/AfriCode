@extends('layouts.app')

@section('title', $achievement->name ?? 'Succès')

@section('content')
<div class="container py-5">
    <h1 class="mb-4"><i class="fas fa-trophy"></i> {{ $achievement->name ?? $achievement->title ?? 'Succès' }}</h1>
    <div class="card">
        <div class="card-body">
            <p>{{ $achievement->description ?? 'Aucune description.' }}</p>
            @if(isset($users) && $users->isNotEmpty())
                <h5>Utilisateurs ayant ce succès</h5>
                <ul class="list-group list-group-flush">
                    @foreach($users as $u)
                        <li class="list-group-item">{{ $u->first_name ?? $u->email }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
    <p class="mt-3"><a href="{{ route('achievements.index') }}" class="btn btn-outline-secondary">Retour aux succès</a></p>
</div>
@endsection
