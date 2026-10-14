@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Récompenses</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($rewards as $reward)
            <div class="bg-white rounded-lg shadow-md p-6 {{ $userRewards->contains('id', $reward->id) ? 'border-2 border-green-500' : '' }}">
                <div class="flex items-center justify-center mb-4">
                    <div class="w-24 h-24 flex items-center justify-center bg-yellow-100 rounded-full">
                        <svg class="w-12 h-12 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                
                <h2 class="text-xl font-semibold text-center mb-2">{{ $reward->name }}</h2>
                <p class="text-gray-600 text-center mb-4">{{ $reward->description }}</p>
                
                <div class="flex justify-between items-center text-sm text-gray-500">
                    <span>Type: {{ ucfirst($reward->type) }}</span>
                    <span class="font-semibold text-yellow-600">{{ number_format($reward->amount, 2) }} {{ $reward->currency }}</span>
                </div>

                @if($userRewards->contains('id', $reward->id))
                    <div class="mt-4 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Réclamée
                        </span>
                    </div>
                @else
                    <div class="mt-4">
                        <button onclick="claimReward({{ $reward->id }})" 
                                class="w-full bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">
                            Réclamer
                        </button>
                    </div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('rewards.show', $reward) }}" 
                       class="block text-center bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                        Voir les détails
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
function claimReward(rewardId) {
    if (!confirm('Voulez-vous réclamer cette récompense ?')) {
        return;
    }

    fetch(`/rewards/${rewardId}/claim`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Une erreur est survenue lors de la réclamation de la récompense.');
    });
}
</script>
@endpush
@endsection 