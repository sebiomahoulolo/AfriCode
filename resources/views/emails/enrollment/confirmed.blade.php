@extends('emails.layout')

@section('title', 'Inscription confirmée — AfriCode')

@section('header_content')
<div class="header-icon">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="white"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
</div>
<p class="header-title">Inscription confirmée !</p>
<p class="header-subtitle">Vous êtes prêt(e) à commencer votre formation</p>
@endsection

@section('content')
<p class="greeting">Félicitations {{ $user->first_name }} !</p>
<p class="text">
    Votre inscription au cours a bien été confirmée. Vous pouvez dès maintenant accéder à l'ensemble du contenu de la formation.
</p>

<div class="info-card">
    <p class="info-card-title">{{ $course->title }}</p>
    <div class="info-row">
        <span class="info-label">Formateur</span>
        <span class="info-value">{{ $course->formateur->first_name ?? '' }} {{ $course->formateur->last_name ?? '' }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Niveau</span>
        <span class="info-value">
            @php $levels = ['debutant' => 'Débutant', 'intermediaire' => 'Intermédiaire', 'avance' => 'Avancé']; @endphp
            {{ $levels[$course->level] ?? ucfirst($course->level) }}
        </span>
    </div>
    <div class="info-row">
        <span class="info-label">Accès</span>
        <span class="info-value">
            @if($isFree)
                <span class="badge-free">Gratuit</span>
            @else
                <span class="badge-paid">Payant — {{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency ?? 'XOF' }}</span>
            @endif
        </span>
    </div>
    <div class="info-row">
        <span class="info-label">Inscrit le</span>
        <span class="info-value">{{ now()->format('d/m/Y') }}</span>
    </div>
</div>

<p class="steps-title">Par où commencer ?</p>
<div class="step"><div class="step-num">1</div><div class="step-text">Accédez au cours et parcourez la présentation générale</div></div>
<div class="step"><div class="step-num">2</div><div class="step-text">Suivez les modules dans l'ordre recommandé par le formateur</div></div>
<div class="step"><div class="step-num">3</div><div class="step-text">Validez chaque leçon et participez aux quiz pour tester vos connaissances</div></div>
<div class="step"><div class="step-num">4</div><div class="step-text">Posez vos questions dans le forum du cours si vous bloquez</div></div>

<div class="btn-wrap">
    <a href="{{ $courseUrl }}" class="btn">Commencer le cours maintenant</a>
</div>

<hr>
<p class="note">Besoin d'aide ? <a href="mailto:contact@africode.tech">contact@africode.tech</a></p>
@endsection
