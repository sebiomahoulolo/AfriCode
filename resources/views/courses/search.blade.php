@extends('layouts.layout')

@section('title', 'Rechercher des formations - AfriCode')

@section('meta_tags')
    <meta name="description" content="Explorez notre catalogue de formations. Trouvez le cours parfait pour développer vos compétences en filtrant par catégorie, niveau et prix.">
    <meta name="keywords" content="formations, cours en ligne, recherche, filtrage, programmation, développement web, afrique, compétences numériques">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/courses/search') }}">
    <meta property="og:title" content="Rechercher des formations - AfriCode">
    <meta property="og:description" content="Explorez notre catalogue de formations. Trouvez le cours parfait pour développer vos compétences en filtrant par catégorie, niveau et prix.">
    <meta property="og:image" content="{{ asset('assets/images/africode-og-image.jpg') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/courses/search') }}">
    <meta property="twitter:title" content="Rechercher des formations - AfriCode">
    <meta property="twitter:description" content="Explorez notre catalogue de formations. Trouvez le cours parfait pour développer vos compétences en filtrant par catégorie, niveau et prix.">
    <meta property="twitter:image" content="{{ asset('assets/images/africode-og-image.jpg') }}">
@endsection

@section('content')
    <!-- Le composant Livewire de recherche et filtrage avancé -->
    @livewire('courses-filter-search')
    
    <!-- Section "Pourquoi apprendre avec AfriCode?" -->
    <section class="why-africode bg-light py-5">
        <div class="container">
            <h2 class="text-center fw-bold mb-5 ud-heading-serif">Pourquoi apprendre avec AfriCode?</h2>
            
            <div class="row g-4 justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4 text-center">
                            <div class="mb-3">
                                <i class="fas fa-laptop-code fa-3x text-primary"></i>
                            </div>
                            <h3 class="h5 fw-bold mb-3">Contenu adapté à l'Afrique</h3>
                            <p class="text-muted mb-0">Nos formations sont conçues pour répondre aux besoins spécifiques du marché africain tout en respectant les standards internationaux.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4 text-center">
                            <div class="mb-3">
                                <i class="fas fa-certificate fa-3x text-primary"></i>
                            </div>
                            <h3 class="h5 fw-bold mb-3">Certifications reconnues</h3>
                            <p class="text-muted mb-0">Obtenez des certifications valorisées par les employeurs et boostez votre CV avec des compétences recherchées.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4 text-center">
                            <div class="mb-3">
                                <i class="fas fa-users fa-3x text-primary"></i>
                            </div>
                            <h3 class="h5 fw-bold mb-3">Communauté active</h3>
                            <p class="text-muted mb-0">Rejoignez une communauté dynamique d'apprenants et d'experts pour échanger, collaborer et progresser ensemble.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5">
                <a href="{{ route('pages.apropos') }}" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-info-circle me-2"></i> En savoir plus sur AfriCode
                </a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Script pour initialiser Alpine.js si nécessaire
    document.addEventListener('DOMContentLoaded', function() {
        // Initialisation personnalisée si nécessaire
    });
</script>
@endpush