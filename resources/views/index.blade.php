@extends('layouts.layout')

@section('title', 'AfriCode - Cours en ligne : Apprenez ce que vous voulez, à votre rythme')

{{-- Section pour les meta tags spécifiques à la page d'accueil --}}
@section('meta_tags')
    <meta name="description" content="AfriCode est une plateforme d'apprentissage en ligne avec plus de 500 cours. Apprenez la programmation, le développement web, mobile, l'IA et plus encore.">
    <meta name="keywords" content="cours en ligne, programmation, développement web, afrique, coder, apprendre à coder, formation informatique">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="AfriCode - Cours en ligne : Apprenez ce que vous voulez, à votre rythme">
    <meta property="og:description" content="AfriCode est une plateforme d'apprentissage en ligne avec plus de 500 cours. Apprenez la programmation, le développement web, mobile, l'IA et plus encore.">
    <meta property="og:image" content="{{ asset('assets/images/africode-og-image.jpg') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/') }}">
    <meta property="twitter:title" content="AfriCode - Cours en ligne : Apprenez ce que vous voulez, à votre rythme">
    <meta property="twitter:description" content="AfriCode est une plateforme d'apprentissage en ligne avec plus de 500 cours. Apprenez la programmation, le développement web, mobile, l'IA et plus encore.">
    <meta property="twitter:image" content="{{ asset('assets/images/africode-og-image.jpg') }}">

    {{-- Schema.org JSON-LD --}}
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "url": "{{ url('/') }}",
      "name": "AfriCode",
      "description": "AfriCode est une plateforme d'apprentissage en ligne avec plus de 500 cours. Apprenez la programmation, le développement web, mobile, l'IA et plus encore.",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ url('/search?q={search_term_string}') }}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
@endsection

@section('content')
    <!-- Hero Section avec recherche -->
    <div class="hero-section position-relative overflow-hidden">
        <div class="hero-overlay"></div>
        <div class="container py-5 position-relative">
            <div class="row min-vh-60 align-items-center">
                <div class="col-lg-7 col-md-9 py-5 text-white">
                    <h1 class="display-3 fw-bold mb-4 hero-title">Des compétences qui vous donnent confiance</h1>
                    <p class="lead mb-4 hero-subtitle">Apprenez à votre rythme avec nos experts. Plus de 500 cours disponibles pour tous les niveaux.</p>
                    
                    <!-- Barre de recherche -->
                    <div class="search-container my-4 position-relative">
                        <form action="" method="GET" class="d-flex">
                            <input class="form-control form-control-lg py-3 ps-4 pe-5 search-input" type="search" name="q" placeholder="Que souhaitez-vous apprendre?" aria-label="Search">
                            <button class="btn  position-absolute end-0 top-0 h-100 px-4 d-flex align-items-center justify-content-center search-btn" type="submit" style="background-color:  #FF8E2A;" >
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-image-decoration position-absolute end-0 top-0 bottom-0 d-none d-lg-block"></div>
    </div>

    <!-- Barre de statistiques améliorée -->
<div class="stats-bar py-5">
    <div class="container">
        <div class="row text-center justify-content-center">
            <div class="col-6 col-md-3 mb-4">
                <div class="stat-card">
                    <i class="fas fa-users stat-icon text-primary"></i>
                    <div>
                        <strong class="counter">325</strong>
                        <small class="text-muted">Apprenants actifs</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-4">
                <div class="stat-card">
                    <i class="fas fa-laptop-code stat-icon text-success"></i>
                    <div>
                        <strong class="counter">120</strong>
                        <small class="text-muted">Cours disponibles</small>
                    </div>
                </div>
            </div>
            {{-- <div class="col-6 col-md-3 mb-4">
                <div class="stat-card">
                    <i class="fas fa-certificate stat-icon text-warning"></i>
                    <div>
                        <strong class="counter">98</strong>
                        <small class="text-muted">Taux de satisfaction</small>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-6 col-md-3">
                <div class="stat-card">
                    <i class="fas fa-chalkboard-teacher stat-icon text-info"></i>
                    <div>
                        <strong class="counter">32</strong>
                        <small class="text-muted">Instructeurs experts</small>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>
<style>
    .stats-bar {
    background: #f8f9fa; /* Couleur de fond */
    padding: 60px 0;
}

.stat-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease-in-out;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    font-size: 2.5rem;
    background: rgba(0, 123, 255, 0.1);
    padding: 15px;
    border-radius: 50%;
    margin-bottom: 15px;
}

.counter {
    font-weight: bold;
    font-size: 2.5rem;
    color: #343a40;
}

</style>
    <!-- Section CourseShowcase -->
    <div class="py-5 bg-light">
        @livewire('course-showcase')
    </div>

    <!-- Témoignages -->
    <div class="testimonials-section py-5">
        <div class="container">
            <h2 class="text-center mb-5 section-title">Ce que disent nos apprenants</h2>
            
            <div class="row g-4">
                @php
                    $testimonials = [
                        ['img' => 'https://randomuser.me/api/portraits/men/32.jpg', 'name' => 'Thomas Mensah', 'rating' => 5, 'text' => "Les cours d'AfriCode sont extrêmement bien structurés. J'ai réussi à trouver un emploi en tant que développeur web après seulement 3 mois d'apprentissage intensif!", 'course' => 'JavaScript Moderne'],
                        ['img' => 'https://randomuser.me/api/portraits/women/44.jpg', 'name' => 'Aïcha Diallo', 'rating' => 4.5, 'text' => "Je n'avais aucune expérience en programmation avant de commencer. Les instructeurs expliquent tout pas à pas et sont très disponibles pour répondre aux questions.", 'course' => 'Python pour Débutants'],
                        ['img' => 'https://randomuser.me/api/portraits/men/67.jpg', 'name' => 'Moussa Sané', 'rating' => 5, 'text' => "Chaque cours contient des projets pratiques qui m'ont permis de construire un portfolio impressionnant. Le retour sur investissement est incroyable!", 'course' => 'Laravel 10 Avancé'],
                    ];
                @endphp

                @foreach($testimonials as $testimonial)
                <div class="col-md-6 col-lg-4 d-flex" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <img src="{{ $testimonial['img'] }}" class="testimonial-img" alt="{{ $testimonial['name'] }}">
                            <div>
                                <h5 class="testimonial-name">{{ $testimonial['name'] }}</h5>
                                <div class="star-rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($testimonial['rating'])) <i class="fas fa-star"></i>
                                        @elseif ($i - 0.5 <= $testimonial['rating']) <i class="fas fa-star-half-alt"></i>
                                        @else <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <p class="testimonial-text">"{{ $testimonial['text'] }}"</p>
                        <div class="testimonial-footer">
                            <i class="fas fa-book-open"></i>
                            <span>Cours suivi : {{ $testimonial['course'] }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Newsletter -->

@endsection

@push('styles')
<style>
    :root {
        --primary-color: #1EA38B;
        --primary-dark: #167c6a;
        --secondary-color: #FF8E2A;
        --dark-color: #2C3E50;
        --light-color: #F8F9FA;
        --text-color: #333333;
        --text-muted: #6C757D;
    }
    
    /* Hero Section */
    .hero-section {
        background-image: url('{{ asset('assets/images/hero.jpg') }}');
        background-size: cover;
        background-position: center;
        min-height: 80vh;
        position: relative;
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(0,0,0,0.8), rgba(0,0,0,0.4));
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 1.5rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .hero-subtitle {
        font-size: 1.25rem;
        opacity: 0.9;
        margin-bottom: 2rem;
    }

    .search-container {
        max-width: 600px;
    }

    .search-input {
        border: none;
        border-radius: 50px;
        padding: 1rem 1.5rem;
        font-size: 1.1rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .search-btn {
        border-radius: 50px;
        padding: 0.75rem 2rem;
        background-color: var(--primary-color);
        border: none;
        transition: all 0.3s ease;
    }

    .search-btn:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
    }

    /* Stats Bar */
    .stats-bar {
        background-color: white;
        box-shadow: 0 -10px 30px rgba(0,0,0,0.1);
        margin-top: -50px;
        position: relative;
        z-index: 2;
        border-radius: 20px 20px 0 0;
    }

    .stat-card {
        padding: 1.5rem;
        border-radius: 15px;
        background: white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-card i {
        color: var(--primary-color);
    }

    /* Testimonials */
    .testimonials-section {
        background-color: var(--light-color);
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 3rem;
    }

    .testimonial-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        height: 100%;
        transition: transform 0.3s ease;
    }

    .testimonial-card:hover {
        transform: translateY(-5px);
    }

    .testimonial-header {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .testimonial-img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        margin-right: 1rem;
        object-fit: cover;
    }

    .testimonial-name {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .star-rating {
        color: #FFD700;
    }

    .testimonial-text {
        font-size: 1rem;
        line-height: 1.6;
        color: var(--text-color);
        margin-bottom: 1.5rem;
    }

    .testimonial-footer {
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .testimonial-footer i {
        margin-right: 0.5rem;
    }

    /* Newsletter */
    .newsletter-section {
        background-color: var(--primary-color);
        color: white;
    }

    .newsletter-card {
        background: rgba(255,255,255,0.1);
        border-radius: 20px;
        padding: 3rem;
        backdrop-filter: blur(10px);
    }

    .newsletter-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .newsletter-text {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .newsletter-form .form-control {
        border: none;
        border-radius: 50px;
        padding: 1rem 1.5rem;
        font-size: 1rem;
    }

    .newsletter-form .btn {
        border-radius: 50px;
        padding: 1rem 2rem;
        background-color: var(--secondary-color);
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .newsletter-form .btn:hover {
        background-color: #e67e22;
        transform: translateY(-2px);
    }

    .newsletter-form .form-text {
        color: rgba(255,255,255,0.8);
        margin-top: 0.5rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
        }

        .stats-bar {
            margin-top: -30px;
        }

        .stat-card {
            padding: 1rem;
        }

        .newsletter-card {
            padding: 2rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const counters = document.querySelectorAll(".counter");
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const counterObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const min = parseInt(counter.dataset.min) || 0;
                    const max = parseInt(counter.dataset.max);
                    let current = min;
                    const duration = 1500;
                    const stepTime = Math.abs(Math.floor(duration / (max - min)));
                    
                    const timer = setInterval(() => {
                        current += 1;
                        counter.innerText = current;
                        if (current == max) {
                            clearInterval(timer);
                        }
                    }, stepTime > 0 ? stepTime : 20);
                    
                    observer.unobserve(counter);
                }
            });
        }, observerOptions);

        counters.forEach(counter => {
            counterObserver.observe(counter);
        });
    });
</script>
@endpush