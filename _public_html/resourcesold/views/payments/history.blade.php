@extends('layouts.app')

@section('title', 'Historique des paiements')

@section('content')
<div class="container py-5">
    <h1 class="mb-4"><i class="fas fa-history"></i> Historique des paiements</h1>
    @if(isset($payments) && $payments->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Cours / Référence</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td>{{ $payment->paid_at?->format('d/m/Y H:i') ?? $payment->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ number_format($payment->amount ?? 0, 0, ',', ' ') }} {{ $payment->currency ?? 'XOF' }}</td>
                            <td><span class="badge bg-{{ ($payment->status ?? '') === 'succeeded' ? 'success' : 'secondary' }}">{{ $payment->status ?? '—' }}</span></td>
                            <td>{{ $payment->course->title ?? $payment->transaction_id ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $payments->links() }}
    @else
        <p class="text-muted">Vous n'avez aucun paiement enregistré.</p>
    @endif
</div>
@endsection
