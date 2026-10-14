@extends('layouts.app')

@section('title', $article->title)

@section('content')
<div class="container py-5">
    <nav class="mb-3"><a href="{{ route('support.faq.index') }}">&larr; Retour à la FAQ</a></nav>
    <h1 class="mb-4">{{ $article->title }}</h1>
    <div class="card">
        <div class="card-body">
            {!! nl2br(e($article->content)) !!}
        </div>
    </div>
    @if($relatedArticles->isNotEmpty())
        <h2 class="h5 mt-4">Articles liés</h2>
        <ul class="list-group">
            @foreach($relatedArticles as $related)
                <li class="list-group-item"><a href="{{ route('support.faq.show', $related) }}">{{ $related->title }}</a></li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
