@extends('emails.layout')

@section('title', 'Nouveau message de contact — AfriCode')

@section('header_content')
<div class="header-icon">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="white"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
</div>
<p class="header-title">Nouveau message de contact</p>
<p class="header-subtitle">Un visiteur vous a envoyé un message</p>
@endsection

@section('content')
<div class="info-card">
    <p class="info-card-title">Informations du contact</p>
    <div class="info-row">
        <span class="info-label">Nom</span>
        <span class="info-value">{{ $contactData['firstName'] }} {{ $contactData['lastName'] }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Email</span>
        <span class="info-value"><a href="mailto:{{ $contactData['email'] }}" style="color:#1EA38B;text-decoration:none;">{{ $contactData['email'] }}</a></span>
    </div>
    <div class="info-row">
        <span class="info-label">Sujet</span>
        <span class="info-value">{{ $contactData['subject'] }}</span>
    </div>
</div>

<p class="steps-title">Message</p>
<div class="highlight">
    <p style="white-space:pre-wrap;margin:0;">{{ $contactData['message'] }}</p>
</div>

@if(!empty($contactData['attachmentName']))
<div class="tip-box">
    <p class="tip-box-title">Pièce jointe</p>
    <ul><li>{{ $contactData['attachmentName'] }}</li></ul>
</div>
@endif

<hr>
<p class="note">Répondez directement à cet email pour contacter {{ $contactData['firstName'] }} {{ $contactData['lastName'] }}.</p>
@endsection
