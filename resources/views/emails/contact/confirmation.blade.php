@extends('emails.layout')

@section('title', 'Confirmation de votre message — AfriCode')

@section('header_content')
<div class="header-icon">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="white"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 16l-5-5 1.41-1.41L10 15.17l7.59-7.59L19 9l-9 9z"/></svg>
</div>
<p class="header-title">Message reçu !</p>
<p class="header-subtitle">Nous vous répondrons dans les plus brefs délais</p>
@endsection

@section('content')
<p class="greeting">Bonjour {{ $contactData['firstName'] }},</p>
<p class="text">
    Nous avons bien reçu votre message et nous vous en remercions. Notre équipe va l'étudier et vous répondre dans les plus brefs délais.
</p>

<div class="info-card">
    <p class="info-card-title">Récapitulatif de votre message</p>
    <div class="info-row">
        <span class="info-label">Sujet</span>
        <span class="info-value">{{ $contactData['subject'] }}</span>
    </div>
</div>

<div class="highlight">
    <p style="white-space:pre-wrap;margin:0;">{{ $contactData['message'] }}</p>
</div>

<div class="next-box">
    <p class="next-box-title">Prochaines étapes</p>
    <ul>
        <li>Notre équipe examine votre demande</li>
        <li>Nous vous répondons sous 24–48h</li>
        <li>Si nécessaire, nous planifions un appel</li>
    </ul>
</div>

<div class="btn-wrap">
    <a href="{{ $coursesUrl }}" class="btn">Découvrir nos formations</a>
</div>
@endsection
