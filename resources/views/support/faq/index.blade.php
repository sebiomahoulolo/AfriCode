@extends('layouts.app')

@section('title', 'FAQ')

@section('content')
<div class="container py-5">
    <h1 class="mb-4"><i class="fas fa-question-circle"></i> Foire aux questions</h1>
    @if($articles->isEmpty())
        <p class="text-muted">Aucun article pour le moment.</p>
    @else
        @foreach($articles as $category => $items)
            <h2 class="h5 mt-4">{{ $category }}</h2>
            <ul class="list-group list-group-flush mb-3">
                @foreach($items as $article)
                    <li class="list-group-item">
                        <a href="{{ route('support.faq.show', $article) }}">{{ $article->title }}</a>
                    </li>
                @endforeach
            </ul>
        @endforeach
    @endif
</div>
@endsection
