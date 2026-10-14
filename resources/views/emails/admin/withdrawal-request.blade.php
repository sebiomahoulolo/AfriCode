@extends('emails.layout')

@section('title', 'Demande de retrait formateur — AfriCode Admin')

@section('header_content')
<div class="header-icon">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="white"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
</div>
<p class="header-title">Demande de retrait</p>
<p class="header-subtitle">Un formateur a déposé une demande de retrait</p>
@endsection

@section('styles')
.amount { font-size:1.2rem; font-weight:800; color:#1EA38B; }
@endsection

@section('content')
<div class="highlight">
    <p><strong>Formateur :</strong> {{ $formateurName }}</p>
    @if($formateur && $formateur->email)
    <p><strong>Email :</strong> {{ $formateur->email }}</p>
    @endif
    <p><strong>Montant :</strong> <span class="amount">{{ $amountFormatted }}</span></p>
    <p><strong>Méthode :</strong> {{ ucfirst(str_replace('_', ' ', $withdrawal->payment_method ?? '')) }}</p>
    <p><strong>Compte / Référence :</strong> {{ $withdrawal->payment_account ?? '—' }}</p>
</div>

<p class="text">Merci de traiter cette demande depuis l'interface d'administration (retraits formateurs).</p>

<div class="btn-wrap">
    <a href="{{ $withdrawalsUrl }}" class="btn">Voir les demandes de retrait</a>
</div>
@endsection
