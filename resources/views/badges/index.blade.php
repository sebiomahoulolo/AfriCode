{{-- @extends('apprenants.layouts.app')

@section('title', 'AfriCode')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Badges</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($badges as $badge)
            <div class="bg-white rounded-lg shadow-md p-6 {{ $userBadges->contains('id', $badge->id) ? 'border-2 border-green-500' : '' }}">
                <div class="flex items-center justify-center mb-4">
                    <img src="{{ asset('storage/badges/' . $badge->icon) }}" 
                         alt="{{ $badge->name }}" 
                         class="w-24 h-24 object-contain">
                </div>
                
                <h2 class="text-xl font-semibold text-center mb-2">{{ $badge->name }}</h2>
                <p class="text-gray-600 text-center mb-4">{{ $badge->description }}</p>
                
                <div class="flex justify-between items-center text-sm text-gray-500">
                    <span>Type: {{ ucfirst($badge->type) }}</span>
                    <span>{{ $badge->points_reward }} points</span>
                </div>

                @if($userBadges->contains('id', $badge->id))
                    <div class="mt-4 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Obtenu
                        </span>
                    </div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('badges.show', $badge) }}" 
                       class="block text-center bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                        Voir les détails
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection  --}}



@extends('apprenants.layouts.app')

@section('title', 'Mes Badges - AfriCode')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- En-tête de page -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Mes Badges</h1>
        <p class="text-gray-600">Découvrez les badges que vous avez obtenus et ceux à débloquer</p>
        
        <!-- Statistiques des badges -->
        <div class="bg-white rounded-lg shadow-sm p-4 mt-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ $userBadges->count() }}</div>
                    <div class="text-sm text-gray-600">Badges obtenus</div>
                </div>
                <div class="text-center p-4 bg-orange-50 rounded-lg">
                    <div class="text-2xl font-bold text-orange-600">{{ $badges->count() - $userBadges->count() }}</div>
                    <div class="text-sm text-gray-600">Badges à débloquer</div>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">{{ $totalPoints }} pts</div>
                    <div class="text-sm text-gray-600">Points totaux</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
        <div class="flex flex-wrap gap-2">
            <button class="badge-filter active" data-filter="all">Tous</button>
            <button class="badge-filter" data-filter="obtenus">Obtenus</button>
            <button class="badge-filter" data-filter="a-debloquer">À débloquer</button>
        </div>
        
        <div class="relative w-full md:w-64">
            <input type="text" placeholder="Rechercher un badge..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Grille de badges -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="badges-container">
        @foreach($badges as $badge)
            <div class="modern-card badge-card {{ $userBadges->contains('id', $badge->id) ? 'badge-obtained' : 'badge-locked' }}" 
                 data-status="{{ $userBadges->contains('id', $badge->id) ? 'obtenu' : 'a-debloquer' }}">
                <!-- En-tête du badge -->
                <div class="flex justify-between items-start mb-4">
                    <span class="badge-modern {{ $badge->type == 'achievement' ? 'badge-primary' : ($badge->type == 'skill' ? 'badge-success' : 'badge-warning') }}">
                        {{ $badge->type == 'achievement' ? 'Réussite' : ($badge->type == 'skill' ? 'Compétence' : 'Participation') }}
                    </span>
                    <span class="text-sm font-semibold text-blue-600">{{ $badge->points_reward }} pts</span>
                </div>
                
                <!-- Icône du badge -->
                <div class="flex items-center justify-center mb-4 p-4">
                    <div class="relative">
                        <img src="{{ asset('storage/badges/' . $badge->icon) }}" 
                             alt="{{ $badge->name }}" 
                             class="w-20 h-20 object-contain {{ $userBadges->contains('id', $badge->id) ? '' : 'opacity-60' }}">
                        
                        @if(!$userBadges->contains('id', $badge->id))
                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Nom et description -->
                <h2 class="text-xl font-semibold text-center mb-2 text-gray-800">{{ $badge->name }}</h2>
                <p class="text-gray-600 text-center mb-4">{{ $badge->description }}</p>
                
                <!-- Statut -->
                <div class="mt-4 text-center">
                    @if($userBadges->contains('id', $badge->id))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Obtenu
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            À débloquer
                        </span>
                    @endif
                </div>

                <!-- Bouton d'action -->
                <div class="mt-6">
                    <a href="{{ route('badges.show', $badge) }}" 
                       class="block w-full text-center bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200 font-medium">
                        Voir les détails
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Message si aucun badge -->
    @if($badges->count() === 0)
        <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-xl font-medium text-gray-700 mb-2">Aucun badge disponible</h3>
            <p class="text-gray-500">Les badges apparaîtront ici une fois créés par les administrateurs.</p>
        </div>
    @endif
</div>

<style>
    .badge-filter {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .badge-filter:hover, .badge-filter.active {
        background: var(--africode-primary);
        color: white;
        border-color: var(--africode-primary);
    }
    
    .badge-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .badge-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--africode-gradient-primary);
    }
    
    .badge-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .badge-obtained {
        border-left: 4px solid var(--africode-secondary);
    }
    
    .badge-locked {
        border-left: 4px solid #cbd5e1;
    }
    
    .badge-card .badge-modern {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filters = document.querySelectorAll('.badge-filter');
        const badgeCards = document.querySelectorAll('.badge-card');
        const searchInput = document.querySelector('input[type="text"]');
        
        // Filtrage par statut
        filters.forEach(filter => {
            filter.addEventListener('click', function() {
                const filterValue = this.getAttribute('data-filter');
                
                // Mettre à jour la classe active
                filters.forEach(f => f.classList.remove('active'));
                this.classList.add('active');
                
                // Filtrer les badges
                badgeCards.forEach(card => {
                    const status = card.getAttribute('data-status');
                    
                    if (filterValue === 'all') {
                        card.style.display = 'block';
                    } else if (filterValue === 'obtenus' && status === 'obtenu') {
                        card.style.display = 'block';
                    } else if (filterValue === 'a-debloquer' && status === 'a-debloquer') {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
        
        // Recherche de badges
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                
                badgeCards.forEach(card => {
                    const badgeName = card.querySelector('h2').textContent.toLowerCase();
                    const badgeDesc = card.querySelector('p').textContent.toLowerCase();
                    
                    if (badgeName.includes(searchTerm) || badgeDesc.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endsection