@extends('formateurs.layouts.app')

@section('title', 'AfriCode - Détails revenu')
@section('page-title', 'Détails du revenu')
@section('page-subtitle', 'Informations détaillées sur ce revenu')

@section('content')
<div class="fade-in">
    <div class="card-modern" data-aos="fade-up">
        <div class="card-body-modern">
            <div class="mb-3">
                <a href="{{ route('formateur.earnings.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            <h4 class="mb-4">Informations du revenu</h4>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-body">
                            <h6 class="text-muted mb-3">Cours</h6>
                            <h5>{{ $earning->course->title ?? 'N/A' }}</h5>
                            <p class="text-muted mb-0">
                                <small>ID: {{ $earning->course_id }}</small>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-body">
                            <h6 class="text-muted mb-3">Date</h6>
                            <h5>{{ $earning->created_at->format('d/m/Y H:i') }}</h5>
                            <p class="text-muted mb-0">
                                <small>Période: {{ $earning->period_start }} - {{ $earning->period_end }}</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <h5 class="mb-3">Calcul détaillé</h5>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td><strong>Prix du cours</strong></td>
                                <td class="text-end">{{ number_format($earning->course_price, 0, ',', ' ') }} FCFA</td>
                            </tr>
                            <tr>
                                <td><strong>Part formateur (77%)</strong></td>
                                <td class="text-end text-success">
                                    <strong>{{ number_format($earning->formateur_share, 0, ',', ' ') }} FCFA</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>Part AfriCode (23%)</td>
                                <td class="text-end">{{ number_format($earning->africode_share, 0, ',', ' ') }} FCFA</td>
                            </tr>
                            @if($earning->bonus_amount > 0)
                            <tr>
                                <td><strong>Bonus</strong></td>
                                <td class="text-end text-warning">
                                    <strong>+{{ number_format($earning->bonus_amount, 0, ',', ' ') }} FCFA</strong>
                                </td>
                            </tr>
                            @endif
                            <tr class="table-primary">
                                <td><strong>Total gagné</strong></td>
                                <td class="text-end">
                                    <strong>{{ number_format($earning->total_earnings, 0, ',', ' ') }} FCFA</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-body">
                            <h6 class="text-muted mb-3">Statut du paiement</h6>
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'paid' => 'success',
                                    'processing' => 'info',
                                    'failed' => 'danger'
                                ];
                                $color = $statusColors[$earning->payment_status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }} fs-6">
                                {{ ucfirst($earning->payment_status) }}
                            </span>
                            @if($earning->paid_at)
                                <p class="mt-2 mb-0">
                                    <small class="text-muted">Payé le: {{ $earning->paid_at->format('d/m/Y H:i') }}</small>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-body">
                            <h6 class="text-muted mb-3">Informations supplémentaires</h6>
                            <p class="mb-1">
                                <strong>Nombre d'étudiants:</strong> {{ $earning->student_count }}
                            </p>
                            @if($earning->payment_method)
                                <p class="mb-1">
                                    <strong>Méthode:</strong> {{ ucfirst(str_replace('_', ' ', $earning->payment_method)) }}
                                </p>
                            @endif
                            @if($earning->payment_reference)
                                <p class="mb-0">
                                    <strong>Référence:</strong> {{ $earning->payment_reference }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

