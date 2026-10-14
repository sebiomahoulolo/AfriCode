@extends('emails.layout')

@section('title', 'Nouveau cours disponible — AfriCode')

@section('header_content')
<div class="header-icon">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="white"><path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z"/></svg>
</div>
<p class="header-title">Nouveau cours disponible !</p>
<p class="header-subtitle">Une nouvelle opportunité d'apprentissage sur AfriCode</p>
@endsection

@section('content')
<p class="greeting">Bonjour {{ $user->first_name }},</p>
<p class="text">Un nouveau cours vient d'être publié sur AfriCode !</p>

<div class="info-card">
    <p class="info-card-title">« {{ $course->title }} »</p>
</div>

<p class="text">
    C'est une excellente opportunité pour développer vos compétences et progresser dans votre parcours.
    Ne manquez pas cette nouvelle opportunité d'apprentissage !
</p>

<div class="btn-wrap">
    <a href="{{ $courseUrl }}" class="btn">Découvrir ce cours →</a>
</div>
@endsection

@section('footer_extra')
<p style="margin-bottom:10px;">
    <a href="{{ $unsubscribeUrl }}">Se désabonner</a>&nbsp;·&nbsp;<a href="{{ route('courses.index') }}">Voir tous les cours</a>
</p>
@endsection
