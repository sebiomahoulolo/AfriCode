@extends('layouts.layout')

@section('title', 'AfriCode - Façonnez l\'avenir numérique de l\'Afrique')
@section('content')

{{-- Section pour les meta tags spécifiques à la page d'accueil --}}
@section('meta_tags')
    <meta name="description" content="AfriCode est un programme de formation professionnelle qui façonne la main-d'œuvre de demain. Depuis 2024, nous avons pour mission de démocratiser l'accès à l'éducation technologique en Afrique.">
    <meta name="keywords" content="formation professionnelle, programmation afrique, développement web, cybersécurité, intelligence artificielle, cours en ligne, certification tech">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="AfriCode - Façonnez l'avenir numérique de l'Afrique">
    <meta property="og:description" content="AfriCode est un programme de formation professionnelle qui façonne la main-d'œuvre de demain. Depuis 2024, nous avons pour mission de démocratiser l'accès à l'éducation technologique en Afrique.">
    <meta property="og:image" content="{{ asset('assets/images/africode-og-image.jpg') }}">
<meta name="facebook-domain-verification" content="u57iy063pazf4alikzjmppuwkxtqhz" />
    {{-- Pixels gérés par layouts.layout (x-tracking-pixels) --}}
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/') }}">
    <meta property="twitter:title" content="AfriCode - Façonnez l'avenir numérique de l'Afrique">
    <meta property="twitter:description" content="AfriCode est un programme de formation professionnelle qui façonne la main-d'œuvre de demain. Depuis 2024, nous avons pour mission de démocratiser l'accès à l'éducation technologique en Afrique.">
    <meta property="twitter:image" content="{{ asset('assets/images/africode-og-image.jpg') }}">

    {{-- Schema.org JSON-LD --}}
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "url": "{{ url('/') }}",
      "name": "AfriCode",
      "description": "AfriCode est un programme de formation professionnelle qui façonne la main-d'œuvre de demain. Depuis 2024, nous avons pour mission de démocratiser l'accès à l'éducation technologique en Afrique.",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ url('/search?q={search_term_string}') }}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
@endsection

@section('content')

    <!-- Hero Section Principal -->
    <section class="hero-main px-2 py-2">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <div class="hero-badge">
                            <i class="fas fa-star"></i>
                            <span>Formation tech de référence en Afrique</span>
                        </div>
                        <h1 class="hero-title">
                            Transformez votre passion tech en <span class="text-highlight">carrière d'exception</span>
                        </h1>
                        <p class="hero-subtitle">
                            Rejoignez la révolution numérique africaine ! Formations certifiantes en cybersécurité,
                            développement, IA et data science. 60+ professionnels africains déjà certifiés.
                        </p>
                        <div class="hero-actions">
                            <a href="{{ route('register') }}" class="btn text-white btn-hero w-full focus:outline-none" style="background:linear-gradient(135deg,#1EA38B,#27B371);border:none;" aria-label="Démarrer ma formation sur AfriCode">
                                <i class="fas fa-rocket me-2"></i>
                                Démarrer ma formation
                            </a>
                        </div>
                        
                        <!-- Badges de confiance -->
                        <div class="trust-badges">
                            <div class="trust-item">
                                <i class="fas fa-certificate"></i>
                                <span>Certifications reconnues</span>
                            </div>
                            <div class="trust-item">
                                <i class="fas fa-users"></i>
                                <span>60+ certifiés</span>
                            </div>
                            <div class="trust-item">
                                <i class="fas fa-globe-africa"></i>
                                <span>Présent dans 15 pays</span>
                            </div>
                        </div>
                    </div>
                </div><br><br><br>
                <div class="col-lg-6">
                    <div class="hero-visual">
                        <!-- Composition asymétrique avec formes arrondies organiques (galets) -->
                        <div class="hero-composition">
                            <!-- Image principale (haut gauche) - Femme en jaune, la plus grande -->
                            <div class="composition-item main-image">
                                <div class="rounded-container organic-shape">
                                    <img src="{{ asset('assets/images/pyton.webp') }}" 
                                         alt="Professionnelle tech AfriCode" class="img-fluid">
                                </div>
                            </div>
                            
                            <!-- Image haut droite - Groupe diversifié collaborant -->
                            <div class="composition-item top-right-image">
                                <div class="rounded-container organic-shape">
                                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=400&h=300&fit=crop&crop=faces" 
                                         alt="Groupe diversifié collaborant autour d'un ordinateur portable dans un café" class="img-fluid">
                                </div>
                            </div>
                            
                            <!-- Image bas droite - Homme avec casque et enfant -->
                            <div class="composition-item bottom-right-image">
                                <div class="rounded-container organic-shape">
                                    <img src="{{ asset('assets/images/Africode19.webp') }}" 
                                         alt="Homme avec casque et jeune fille, télétravail en famille" class="img-fluid">
                                </div>
                            </div>
                            
                            
                            <!-- Éléments graphiques décoratifs -->
                            <div class="decorative-elements">
                                <!-- Grille de points bleus en bas à gauche -->
                                <div class="dots-grid">
                                    <svg viewBox="0 0 100 60" class="dots-svg">
                                        <defs>
                                            <pattern id="dotPattern" x="0" y="0" width="8" height="8" patternUnits="userSpaceOnUse">
                                                <circle cx="4" cy="4" r="1.5" fill="#1EA38B" opacity="0.4"/>
                                            </pattern>
                                        </defs>
                                        <rect width="100" height="60" fill="url(#dotPattern)"/>
                                    </svg>
                                </div>
                                
                                <!-- Cercle avec lignes ondulées au centre -->
                                <div class="wavy-circle">
                                    <svg viewBox="0 0 80 80" class="wavy-svg">
                                        <circle cx="40" cy="40" r="35" fill="none" stroke="#1EA38B" stroke-width="2" opacity="0.3" stroke-dasharray="5,5"/>
                                    </svg>
                                </div>
                                
                                <!-- Lignes diagonales en haut à droite -->
                                <div class="diagonal-lines">
                                    <svg viewBox="0 0 60 40" class="lines-svg">
                                        <line x1="0" y1="0" x2="60" y2="40" stroke="#6C757D" stroke-width="1" opacity="0.3"/>
                                        <line x1="0" y1="10" x2="50" y2="40" stroke="#6C757D" stroke-width="1" opacity="0.2"/>
                                        <line x1="0" y1="20" x2="40" y2="40" stroke="#6C757D" stroke-width="1" opacity="0.1"/>
                                        <line x1="10" y1="0" x2="60" y2="30" stroke="#6C757D" stroke-width="1" opacity="0.2"/>
                                        <line x1="20" y1="0" x2="60" y2="20" stroke="#6C757D" stroke-width="1" opacity="0.1"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Éléments flottants tech (plus subtils) -->
                        <div class="floating-elements">
                            <div class="floating-tech python">
                                <i class="fab fa-python"></i>
                                <span>Python</span>
                            </div>
                            <div class="floating-tech react">
                                <i class="fab fa-react"></i>
                                <span>React</span>
                            </div>
                            <div class="floating-tech security">
                                <i class="fas fa-shield-alt"></i>
                                <span>Cyber</span>
                            </div>
                            <div class="floating-tech ai">
                                <i class="fas fa-brain"></i>
                                <span>IA</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Preuve Sociale (Statistiques) -->
    <section class="social-proof">
        <div class="container">
            <div class="stats-card">
                <div class="stats-header text-center mb-4">
                    <h3 class="stats-title">AfriCode en chiffres</h3>
                    <p class="stats-subtitle">La plateforme de formation tech de référence en Afrique</p>
                </div>
                <div class="row g-4 text-center">
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3 class="stat-number">200+</h3>
                            <p class="stat-label">Étudiants formés</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <h3 class="stat-number">50+</h3>
                            <p class="stat-label">Formateurs disponibles</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-6">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <h3 class="stat-number">12+</h3>
                            <p class="stat-label">Entreprises partenaires</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-globe-africa"></i>
                            </div>
                            <h3 class="stat-number">15</h3>
                            <p class="stat-label">Pays africains</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-8 col-12">
                        <div class="stat-item stat-highlight">
                            <div class="stat-icon highlight">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <h3 class="stat-number">60+</h3>
                            <p class="stat-label">Professionnels certifiés</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Domaines de Formation -->
    <section class="domains-section">
        <div class="container">
            <!-- Citation d'impact -->
            <div class="quote-section text-center mb-5">
                <div class="quote-visual">
                    <div class="quote-marks">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <blockquote class="quote-text">
                        Plus de <span class="highlight-underline">60 professionnels africains</span> ont transformé leur carrière grâce à nos formations certifiantes.
                    </blockquote>
                    <div class="quote-author">
                        <span>— Étude d'impact AfriCode 2026</span>
                    </div>
                </div>
            </div>

            <!-- Domaines de formation -->
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Explorez nos domaines d'expertise</h2>
                <p class="section-subtitle">Choisissez votre spécialisation et lancez votre carrière tech</p>
            </div>
            
            <div class="domains-grid" id="domaines">
                <div class="row g-4">
                    @php
                        $domains = [
                            [
                                
                                'title' => 'Cybersécurité', 
                                'image' => asset('assets/images/cyber.webp'), 
                                'description' => 'Protégez l\'infrastructure numérique africaine',
                                'courses' => '12 formations',
                                'gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                                'category' => 'cybersecurity'
                            ],
                            [                                
                                'title' => 'Développement Web', 
                                'image' => asset('assets/images/web.webp'), 
                                'description' => 'Créez des applications web modernes',
                                'courses' => '18 formations',
                                'gradient' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                                'category' => 'web-development'
                            ],
                            [
                                'title' => 'Intelligence Artificielle', 
                                'image' => asset('assets/images/ai.webp'), 
                                'description' => 'Maîtrisez l\'IA et le Machine Learning',
                                'courses' => '15 formations',
                                'gradient' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                                'category' => 'artificial-intelligence'
                            ],
                            [
                                'title' => 'Développement Mobile', 
                                'image' => asset('assets/images/mobile.webp'), 
                                'description' => 'Développez des apps iOS et Android',
                                'courses' => '10 formations',
                                'gradient' => 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
                                'category' => 'mobile-development'
                            ],
                            [
                               
                                'title' => 'Data Science',
                                'image' => asset('assets/images/data.webp'),  
                                'description' => 'Analysez et visualisez les données',
                                'courses' => '14 formations',
                                'gradient' => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
                                'category' => 'data-science'
                            ],
                            [
                                'title' => 'Cloud Computing',
                                'image' => asset('assets/images/cloud.webp'), 
                                'description' => 'Maîtrisez AWS, Azure et GCP',
                                'courses' => '8 formations',
                                'gradient' => 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)',
                                'category' => 'cloud-computing'
                            ],
                            [
                                 
                                'title' => 'Base de données', 
                                'image' => asset('assets/images/bdd.webp'), 
                                'description' => 'Gérez efficacement vos données',
                                'courses' => '9 formations',
                                'gradient' => 'linear-gradient(135deg, #d299c2 0%, #fef9d7 100%)',
                                'category' => 'database'
                            ],
                            [
                                
                                'title' => 'DevOps', 
                                'image' => asset('assets/images/devops.webp'), 
                                'description' => 'Automatisez et optimisez vos déploiements',
                                'courses' => '11 formations',
                                'gradient' => 'linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%)',
                                'category' => 'devops'
                            ]
                        ];
                    @endphp

                    @php
                        $categoryUrls = [
                            'cybersecurity'          => '/cours?category=cybersecurity',
                            'web-development'        => '/cours?category=web-development',
                            'artificial-intelligence'=> '/cours?category=artificial-intelligence',
                            'mobile-development'     => '/cours?category=mobile-development',
                            'data-science'           => '/cours?category=data-science',
                            'cloud-computing'        => '/cours?category=devops-et-cloud',
                            'database'               => '/cours?category=base-de-donnees',
                            'devops'                 => '/cours?category=devops-et-cloud',
                        ];
                    @endphp
                    @foreach($domains as $domain)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="{{ $categoryUrls[$domain['category']] ?? '/cours' }}"
                           class="domain-card d-block text-decoration-none"
                           style="color: inherit;"
                           data-category="{{ $domain['category'] }}">
                            <div class="domain-background" style="background: {{ $domain['gradient'] }}"></div>
                            <div class="domain-content">
                                @if(isset($domain['image']))
            <div class="domain-image mb-3">
                <img src="{{ $domain['image'] }}" alt="{{ $domain['title'] }}" class="img-fluid rounded">
            </div>

        @endif
                                <h4 class="domain-title">{{ $domain['title'] }}</h4>
                                <p class="domain-description">{{ $domain['description'] }}</p>
                                <div class="domain-meta">
                                    <span class="course-count">{{ $domain['courses'] }}</span>
                                    <div style="color: #1EA38B;">
                                    <span>Explorer →</span>
                                </div>
                                </div>

                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="text-center mt-5">
                <a href="/cours" class="btn text-white btn-lg" style="background:linear-gradient(135deg,#1EA38B,#27B371);border:none;">
                    <i class="fas fa-graduation-cap me-2"></i>
                    Découvrir toutes nos formations
                </a>
            </div>
        </div>
    </section>

    <!-- Section Cours Populaires -->
    <section class="popular-courses">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">
                    Nos formations <span class="highlight-underline">les plus demandées</span>
                </h2>
                <p class="section-subtitle">Découvrez les formations qui transforment des carrières</p>
            </div>
            
            <div class="courses-carousel-container">
                <button class="carousel-btn carousel-prev" id="coursesCarouselPrev">
                    <i class="fas fa-chevron-left"></i>
                </button>
                
                <div class="courses-carousel" id="coursesCarousel">
                    @if(isset($popularCourses) && $popularCourses->count() > 0)
                        @foreach($popularCourses as $course)
                    <div class="course-card-enhanced" style="position: relative;">
                        <a href="{{ route('courses.show', $course['slug'] ?? '#') }}" class="card-full-link" aria-label="{{ $course['title'] ?? 'Voir ce cours' }}"></a>
                        <div class="course-image">
                            <img src="{{ asset($course['img'] ?? 'assets/images/default-course.jpg') }}" alt="{{ $course['title'] ?? 'Cours' }}" class="img-fluid">
                            @if(($course['discount_percentage'] ?? 0) > 0)
                                <div class="course-discount-badge">-{{ $course['discount_percentage'] }}%</div>
                            @endif
                            <div  style="background: #FF8E2A;" class="course-level-badge level-{{ strtolower(str_replace('É', 'e', $course['level'] ?? 'intermédiaire')) }}">
                                {{ $course['level'] ?? 'INTERMÉDIAIRE' }}
                            </div>
                            @if($course['is_certifying'] ?? false)
                                <div class="certified-badge">
                                    <i class="fas fa-certificate"></i>Certified
                                </div>
                            @endif
                        </div>
                        <div class="course-content">
                            <div class="course-rating">
                                <div class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= ($course['rating'] ?? 0) ? '' : ' opacity-25' }}"></i>
                                    @endfor
                                    <span class="rating-value">{{ $course['rating'] ?? 0 }}</span>
                                </div>
                            </div>
                            
                            <h4 class="course-title">{{ $course['title'] ?? 'Titre du cours' }}</h4>
                            
                            <div class="course-instructor">
                                @php
                                    $instructorName = $course['instructor'] ?? 'Instructeur';
                                    $initials = strtoupper(substr($instructorName, 0, 1) . substr(explode(' ', $instructorName)[1] ?? '', 0, 1));
                                @endphp
                                <div class="instructor-avatar initials-circle">{{ $initials }}</div>
                                <span class="instructor-name">{{ $instructorName }}</span>
                            </div>
<style>
    .instructor-avatar.initials-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: #1EA38B;
    color: #FF8E2A;
    font-weight: bold;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>                            
                            
                            <div class="course-meta">
                                <div class="course-duration">
                                    <i class="far fa-clock"></i>
                                    <span>{{ $course['duration'] ?? 'Non spécifié' }}</span>
                                </div>
                                <div class="course-price {{ ($course['price'] ?? 0) == 0 ? 'free' : '' }}">
                                    <i class="fas fa-tag"></i>
                                    @if(($course['old_price'] ?? 0) > 0 && ($course['price'] ?? 0) < ($course['old_price'] ?? 0))
                                        <span class="course-old-price">{{ number_format($course['old_price'], 0, ',', ' ') }} FCFA</span>
                                        <span class="course-current-price">{{ number_format($course['price'], 0, ',', ' ') }} FCFA</span>
                                    @else
                                        <span>{{ ($course['price'] ?? 0) == 0 ? 'Gratuit' : number_format($course['price'] ?? 0, 0, ',', ' ') . ' FCFA' }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="course-actions" style="position: relative; z-index: 2;">
                                <a href="{{ route('courses.show', $course['slug'] ?? '#') }}" class="btn btn-sm text-white course-enroll" style="background:linear-gradient(135deg,#1EA38B,#27B371);border:none;">
                                    <i class="fas fa-play me-1"></i>
                                    {{ ($course['price'] ?? 0) == 0 ? 'Commencer' : 'S\'inscrire' }}
                                </a>
                                <button class="course-save" style="position: relative; z-index: 2;">
                                    <i class="far fa-heart"></i>
                                </button>
                                <button class="course-share" style="position: relative; z-index: 2;">
                                    <i class="fas fa-share-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <p class="text-muted">Aucun cours disponible pour le moment. ( Bientôt
)</p>
                        </div>
                    @endif
                </div>
                
                <button class="carousel-btn carousel-next" id="coursesCarouselNext">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            
            <div class="text-center mt-5">
                <a href="/cours" class="btn text-white btn-lg" style="background:linear-gradient(135deg,#1EA38B,#27B371);border:none;">
                    <i class="fas fa-graduation-cap me-2"></i>
                    Découvrir toutes nos formations
                </a>
            </div>
        </div>
    </section>

    <!-- Section Certifications Améliorée -->
    <section class="certifications-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="certifications-content">
                        <h2 class="section-title text-white">Pourquoi nos certifications font la différence ?</h2>
                        <p class="section-subtitle text-light mb-4">
                            Nos certifications sont reconnues par les plus grandes entreprises africaines et internationales.
                        </p>
                        
                        <div class="certification-stats">
                            <div class="stat-item-cert">
                                <div class="stat-icon-cert">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number-cert">60+</h3>
                                    <p class="stat-desc">professionnels certifiés AfriCode</p>
                                </div>
                            </div>
                            <div class="stat-item-cert">
                                <div class="stat-icon-cert">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number-cert">25%</h3>
                                    <p class="stat-desc">d'augmentation salariale moyenne après certification</p>
                                </div>
                            </div>
                            <div class="stat-item-cert">
                                <div class="stat-icon-cert">
                                    <i class="fas fa-rocket"></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number-cert">45%</h3>
                                    <p class="stat-desc">obtiennent une promotion dans l'année</p>
                                </div>
                            </div>
                        </div>
                        
                        <a href="{{ route('certificate.verification') }}" class="btn text-white btn-lg mt-4" style="background:linear-gradient(135deg,#27B371,#1EA38B);border:none;">
                            <i class="fas fa-award me-2"></i>
                            Découvrir nos certifications
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="certification-visual">
                        <div class="certification-showcase">
                            <div class="cert-badge cert-main">
                                <div class="cert-content">
                                    <i class="fas fa-medal"></i>
                                    <h4>Certification AfriCode</h4>
                                    <p>Cybersécurité Avancée</p>
                                    <div class="cert-seal">
                                        <i class="fas fa-certificate"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="floating-certs">
                                <div class="mini-cert cert-1">
                                    <i class="fab fa-aws"></i>
                                    <span>AWS</span>
                                </div>
                                <div class="mini-cert cert-2">
                                    <i class="fab fa-microsoft"></i>
                                    <span>Azure</span>
                                </div>
                                <div class="mini-cert cert-3">
                                    <i class="fab fa-google"></i>
                                    <span>GCP</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Témoignages Améliorée -->
    <section class="testimonials-enhanced">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Ils ont transformé leur carrière avec AfriCode</h2>
                <p class="section-subtitle">Découvrez les parcours inspirants de nos certifiés</p>
            </div>
            
            <div class="testimonials-carousel-container">
                <button class="testimonial-carousel-btn testimonial-prev" id="testimonialPrev">
                    <i class="fas fa-chevron-left"></i>
                </button>
                
                <div class="testimonials-carousel" id="testimonialsCarousel">
                    @php
                        $testimonials = [
                            [
                                'name' => 'Aminata Traoré',
                                'role' => 'Lead Cybersecurity Analyst',
                                'company' => 'Orange Cyberdéfense',
                                'location' => 'Abidjan, Côte d\'Ivoire',
                                'image' => 'https://randomuser.me/api/portraits/women/44.jpg',
                                'text' => "Grâce à la formation en cybersécurité d'AfriCode, j'ai pu passer de technicienne IT à Lead Analyst chez Orange. Les certifications obtenues m'ont ouvert toutes les portes !",
                                'rating' => 5,
                                'course' => 'Cybersécurité Avancée',
                                'salary_increase' => '+85%'
                            ],
                            [
                                'name' => 'Kwame Asante',
                                'role' => 'Full Stack Developer',
                                'company' => 'Jumia Technologies',
                                'location' => 'Lagos, Nigeria',
                                'image' => 'https://randomuser.me/api/portraits/men/32.jpg',
                                'text' => "En 8 mois, je suis passé de vendeur à développeur full stack. La pédagogie pratique d'AfriCode et le mentorat personnalisé ont fait toute la différence.",
                                'rating' => 5,
                                'course' => 'Développement Full Stack',
                                'salary_increase' => '+150%'
                            ],
                            [
                                'name' => 'Fatou Diop',
                                'role' => 'Data Scientist',
                                'company' => 'Société Générale',
                                'location' => 'Dakar, Sénégal',
                                'image' => 'https://randomuser.me/api/portraits/women/68.jpg',
                                'text' => "Le programme Data Science m'a permis de maîtriser Python, ML et l'IA. Aujourd'hui, j'aide les banques à prendre de meilleures décisions grâce aux données.",
                                'rating' => 5,
                                'course' => 'Data Science & IA',
                                'salary_increase' => '+120%'
                            ],
                            [
                                'name' => 'Ibrahim Kone',
                                'role' => 'DevOps Engineer',
                                'company' => 'Microsoft Africa',
                                'location' => 'Le Cap, Afrique du Sud',
                                'image' => 'https://randomuser.me/api/portraits/men/45.jpg',
                                'text' => "Les formations cloud et DevOps d'AfriCode sont de niveau international. J'ai pu décrocher un poste chez Microsoft grâce aux certifications AWS et Azure obtenues.",
                                'rating' => 5,
                                'course' => 'Cloud & DevOps',
                                'salary_increase' => '+95%'
                            ],
                            [
                                'name' => 'Mariam Ouedraogo',
                                'role' => 'Mobile App Developer',
                                'company' => 'Entrepreneur (FashionTech)',
                                'location' => 'Ouagadougou, Burkina Faso',
                                'image' => 'https://randomuser.me/api/portraits/women/32.jpg',
                                'text' => "Après la formation Flutter, j'ai créé ma propre app de mode qui cartonne ! AfriCode m'a donné les compétences techniques et l'esprit entrepreneurial.",
                                'rating' => 5,
                                'course' => 'Développement Mobile',
                                'salary_increase' => 'Entrepreneur'
                            ]
                        ];
                    @endphp

                    @foreach($testimonials as $testimonial)
                    <div class="testimonial-card-enhanced">
                        <div class="testimonial-header">
                            <div class="testimonial-image">
                                <img src="{{ $testimonial['image'] }}" alt="{{ $testimonial['name'] }}" class="testimonial-avatar">
                                <div class="success-badge">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                            <div class="testimonial-info">
                                <h5 class="testimonial-name">{{ $testimonial['name'] }}</h5>
                                <p class="testimonial-role">{{ $testimonial['role'] }}</p>
                                <p class="testimonial-company">{{ $testimonial['company'] }}</p>
                                <p class="testimonial-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ $testimonial['location'] }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="testimonial-content">
                            <div class="testimonial-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $testimonial['rating'] ? '' : ' opacity-25' }}"></i>
                                @endfor
                            </div>
                            <blockquote class="testimonial-quote">
                                "{{ $testimonial['text'] }}"
                            </blockquote>
                        </div>
                        
                        <div class="testimonial-footer">
                            <div class="course-badge">
                                <i class="fas fa-graduation-cap"></i>
                                <span>{{ $testimonial['course'] }}</span>
                            </div>
                            <div class="impact-badge">
                                <i class="fas fa-chart-line"></i>
                                <span>{{ $testimonial['salary_increase'] }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <button class="testimonial-carousel-btn testimonial-next" id="testimonialNext">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            
            <!-- Indicateurs de navigation -->
            <div class="carousel-indicators">
                @foreach($testimonials as $index => $testimonial)
                    <button class="indicator {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}"></button>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Section CTA Final Améliorée -->
    <section class="final-cta-enhanced">
        <div class="container">
            <div class="final-cta-card">
                <div class="cta-background-elements">
                    <div class="floating-icon icon-1">
                        <i class="fas fa-code"></i>
                    </div>
                    <div class="floating-icon icon-2">
                        <i class="fas fa-brain"></i>
                    </div>
                    <div class="floating-icon icon-3">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="floating-icon icon-4">
                        <i class="fas fa-rocket"></i>
                    </div>
                </div>
                
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="cta-content">
                            <div class="cta-badge">
                                <i class="fas fa-fire"></i>
                                <span>Rejoignez l'élite tech africaine</span>
                            </div>
                            <h2 class="final-cta-title">
                                Votre transformation numérique commence <span class="highlight-text">aujourd'hui</span>
                            </h2>
                            <p class="final-cta-subtitle">
                                Plus de 200 apprenants nous font confiance.
                                Rejoignez la communauté tech la plus dynamique d'Afrique et
                                transformez votre passion en expertise reconnue.
                            </p>
                            
                            <div class="cta-features">
                                <div class="feature-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Formations certifiantes</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Accompagnement personnalisé</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Réseau professionnel exclusif</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Accès à vie aux ressources</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="cta-actions-enhanced">
                            <a href="{{ route('register') }}" class="btn text-white btn-xl cta-main-btn" style="background:linear-gradient(135deg,#1EA38B,#27B371);border:none;">
                                <div class="btn-content">
                                    <i class="fas fa-user-plus"></i>
                                    <div class="btn-text">
                                        <span class="main-text">Créer mon compte</span>
                                        <span class="sub-text">Gratuit — 2 minutes</span>
                                    </div>
                                </div>
                            </a>

                            <div class="cta-alternative">
                                <p>Pas encore convaincu ?</p>
                                <a href="{{ route('courses.index') }}" class="btn btn-outline-light btn-lg">
                                    <i class="fas fa-search me-2"></i>
                                    Explorer le catalogue
                                </a>
                            </div>
                            
                            <div class="trust-indicators">
                                <div class="trust-item">
                                    <div class="trust-number">4.5/5</div>
                                    <div class="trust-label">Satisfaction</div>
                                </div>
                            
                                <div class="trust-item">
                                    <div class="trust-number">60+</div>
                                    <div class="trust-label">Certifiés</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('styles')
@vite('resources/css/homepage.css')
<style>
    /* Variables globales AfriCode */
    :root {
        --africode-primary: #1EA38B;
        --africode-secondary: #FF8E2A;
        --africode-accent-red: #E32D31;
        --africode-highlight-green: #27B371;
        --africode-white: #FFFFFF;
        --africode-dark-text: #333333;
        --africode-gray-light: #F8F9FA;
        --africode-gray-medium: #E9ECEF;
        --africode-gray-dark: #6C757D;
        --africode-gradient-primary: linear-gradient(135deg, var(--africode-primary) 0%, var(--africode-highlight-green) 100%);
        --africode-gradient-accent: linear-gradient(135deg, var(--africode-secondary) 0%, #FFB366 100%);
        --africode-shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
        --africode-shadow-md: 0 4px 8px rgba(0, 0, 0, 0.15);
        --africode-shadow-lg: 0 8px 25px rgba(0, 0, 0, 0.15);
        --africode-border-radius: 12px;
        --africode-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Reset et base */
    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        line-height: 1.6;
        color: var(--africode-dark-text);
        overflow-x: hidden;
    }

    /* Hero Section Amélioré */
    .hero-main {
        background: linear-gradient(135deg,
            rgba(30, 163, 139, 0.08) 0%,
            rgba(255, 142, 42, 0.05) 50%,
            rgba(39, 179, 113, 0.08) 100%);
        padding: 120px 0 80px;
        position: relative;
        overflow: hidden;
        min-height: 100vh;
        display: flex;
        align-items: center;
    }

    .hero-main::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 50%, rgba(30, 163, 139, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255, 142, 42, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 40% 80%, rgba(39, 179, 113, 0.1) 0%, transparent 50%);
        animation: backgroundFlow 20s ease-in-out infinite;
    }

    @keyframes backgroundFlow {
        0%, 100% { transform: scale(1) rotate(0deg); }
        50% { transform: scale(1.1) rotate(1deg); }
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, var(--africode-primary), var(--africode-highlight-green));
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 2rem;
        box-shadow: var(--africode-shadow-md);
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .hero-title {
        font-size: clamp(2.5rem, 6vw, 4.5rem);
        font-weight: 900;
        line-height: 1.1;
        margin-bottom: 2rem;
        color: var(--africode-dark-text);
        letter-spacing: -0.02em;
    }

    .text-highlight {
        background: linear-gradient(135deg, var(--africode-primary) 0%, var(--africode-highlight-green) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        position: relative;
    }

    .text-highlight::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(135deg, var(--africode-primary) 0%, var(--africode-highlight-green) 100%);
        border-radius: 2px;
    }

    .hero-subtitle {
        font-size: 1.375rem;
        line-height: 1.6;
        margin-bottom: 3rem;
        color: var(--africode-gray-dark);
        max-width: 600px;
        font-weight: 400;
    }

    .btn-hero {
        padding: 1.25rem 3rem;
        font-weight: 700;
        border-radius: 50px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        font-size: 1.125rem;
        position: relative;
        overflow: hidden;
    }

    .btn-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s;
    }

    .btn-hero:hover::before {
        left: 100%;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--africode-primary) 0%, var(--africode-highlight-green) 100%);
        border: none;
        color: var(--africode-white);
        box-shadow: 0 10px 30px rgba(30, 163, 139, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(30, 163, 139, 0.4);
        color: var(--africode-white);
    }

    /* Trust badges */
    .trust-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        margin-top: 3rem;
    }

    .trust-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.9rem;
        color: var(--africode-gray-dark);
        font-weight: 500;
    }

    .trust-item i {
        color: var(--africode-primary);
        font-size: 1.125rem;
    }

    /* Hero Visual - Composition Organique */
    .hero-visual {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 600px;
        padding: 2rem;
    }

    .hero-composition {
        position: relative;
        width: 100%;
        height: 100%;
        max-width: 550px;
        max-height: 500px;
    }

    .composition-item {
        position: absolute;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .composition-item:hover {
        transform: scale(1.05);
        z-index: 10;
    }

    /* Image principale (femme en jaune) - top left, la plus grande */
    .main-image {
        top: 0;
        left: 0;
        width: 280px;
        height: 350px;
        z-index: 3;
    }

    /* Image collaboration - top right */
    .top-right-image {
        top: 50px;
        right: 0;
        width: 200px;
        height: 160px;
        z-index: 2;
    }

    /* Image télétravail familial - bottom right */
    .bottom-right-image {
        bottom: 0;
        right: 30px;
        width: 180px;
        height: 140px;
        z-index: 2;
    }

    /* Contenants organiques très arrondis (galets) */
    .rounded-container.organic-shape {
        width: 100%;
        height: 100%;
        border-radius: 60% 40% 70% 30% / 50% 60% 40% 50%;
        overflow: hidden;
        box-shadow: 
            0 20px 60px rgba(0, 0, 0, 0.15),
            0 8px 25px rgba(30, 163, 139, 0.1);
        position: relative;
        background: linear-gradient(135deg, 
            rgba(30, 163, 139, 0.05) 0%, 
            rgba(255, 142, 42, 0.03) 100%);
        animation: organicFloat 8s ease-in-out infinite;
    }

    .main-image .rounded-container.organic-shape {
        border-radius: 65% 35% 75% 25% / 55% 65% 35% 45%;
        animation-delay: 0s;
    }

    .top-right-image .rounded-container.organic-shape {
        border-radius: 45% 55% 30% 70% / 60% 40% 60% 40%;
        animation-delay: 2s;
    }

    .bottom-right-image .rounded-container.organic-shape {
        border-radius: 70% 30% 40% 60% / 45% 55% 45% 55%;
        animation-delay: 4s;
    }

    @keyframes organicFloat {
        0%, 100% { 
            transform: translateY(0px) rotate(0deg);
            border-radius: var(--initial-radius, 60% 40% 70% 30% / 50% 60% 40% 50%);
        }
        25% { 
            transform: translateY(-8px) rotate(0.5deg);
            border-radius: 40% 60% 50% 50% / 70% 30% 60% 40%;
        }
        50% { 
            transform: translateY(-5px) rotate(0deg);
            border-radius: 50% 50% 60% 40% / 40% 70% 30% 60%;
        }
        75% { 
            transform: translateY(-10px) rotate(-0.5deg);
            border-radius: 70% 30% 40% 60% / 55% 45% 65% 35%;
        }
    }

    .rounded-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: all 0.4s ease;
    }

    .composition-item:hover .rounded-container img {
        transform: scale(1.1);
    }

    /* Éléments décoratifs */
    .decorative-elements {
        position: absolute;
        inset: 0;
        pointer-events: none;
        z-index: 1;
    }

    /* Grille de points bleus - bas gauche */
    .dots-grid {
        position: absolute;
        bottom: 20px;
        left: 20px;
        width: 100px;
        height: 60px;
        opacity: 0.6;
    }

    .dots-svg {
        width: 100%;
        height: 100%;
        animation: dotsPulse 4s ease-in-out infinite;
    }

    @keyframes dotsPulse {
        0%, 100% { opacity: 0.6; transform: scale(1); }
        50% { opacity: 0.8; transform: scale(1.1); }
    }

    /* Cercle ondulé - centre */
    .wavy-circle {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 80px;
        height: 80px;
        opacity: 0.4;
    }

    .wavy-svg {
        width: 100%;
        height: 100%;
        animation: circleRotate 20s linear infinite;
    }

    @keyframes circleRotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Lignes diagonales - haut droite */
    .diagonal-lines {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 60px;
        height: 40px;
        opacity: 0.5;
    }

    .lines-svg {
        width: 100%;
        height: 100%;
        animation: linesShimmer 6s ease-in-out infinite;
    }

    @keyframes linesShimmer {
        0%, 100% { opacity: 0.5; }
        50% { opacity: 0.8; }
    }

    /* Badges tech flottants (plus discrets) */
    .floating-elements {
        position: absolute;
        inset: 0;
        pointer-events: none;
        z-index: 5;
    }

    .floating-tech {
        position: absolute;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        padding: 0.5rem 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        animation: subtleFloat 6s ease-in-out infinite;
        opacity: 0.8;
        transition: all 0.3s ease;
    }

    .floating-tech:hover {
        opacity: 1;
        transform: scale(1.05);
    }

    .floating-tech.python {
        top: 15%;
        left: 5%;
        animation-delay: 0s;
        color: #3776ab;
    }

    .floating-tech.react {
        top: 25%;
        right: 10%;
        animation-delay: 2s;
        color: #61dafb;
    }

    .floating-tech.security {
        bottom: 25%;
        left: 8%;
        animation-delay: 4s;
        color: var(--africode-accent-red);
    }

    .floating-tech.ai {
        bottom: 10%;
        right: 15%;
        animation-delay: 1s;
        color: var(--africode-primary);
    }

    @keyframes subtleFloat {
        0%, 100% { 
            transform: translateY(0px) translateX(0px);
            opacity: 0.8;
        }
        25% { 
            transform: translateY(-5px) translateX(2px);
            opacity: 0.9;
        }
        50% { 
            transform: translateY(-3px) translateX(-1px);
            opacity: 1;
        }
        75% { 
            transform: translateY(-7px) translateX(1px);
            opacity: 0.9;
        }
    };
    /* Impact Stats */
    .impact-stats {
        border-top: 1px solid var(--africode-gray-medium);
        padding-top: 2rem;
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--africode-primary);
        margin-bottom: 0.5rem;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.9rem;
        color: var(--africode-gray-dark);
        margin: 0;
    }

    /* Sections */
    .main-programs {
        padding: 5rem 0;
        background: var(--africode-gray-light);
    }

    .section-header {
        margin-bottom: 4rem;
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--africode-dark-text);
        margin-bottom: 1rem;
    }

    .section-subtitle {
        font-size: 1.125rem;
        color: var(--africode-gray-dark);
        max-width: 600px;
        margin: 0 auto;
    }

    /* Section Preuve Sociale */
    .social-proof {
        padding: 4rem 0;
        background: linear-gradient(135deg, 
            rgba(30, 163, 139, 0.05) 0%, 
            rgba(255, 142, 42, 0.03) 100%);
    }

    .stats-card {
        background: var(--africode-white);
        border-radius: 20px;
        padding: 3rem 2rem;
        box-shadow: var(--africode-shadow-lg);
        position: relative;
        overflow: hidden;
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--africode-gradient-primary);
    }

    .stats-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--africode-dark-text);
        margin-bottom: 0.5rem;
    }

    .stats-subtitle {
        color: var(--africode-gray-dark);
        margin-bottom: 2rem;
    }

    .stat-item {
        text-align: center;
        padding: 1rem;
        transition: var(--africode-transition);
    }

    .stat-item:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--africode-primary), var(--africode-highlight-green));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: white;
        font-size: 1.5rem;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--africode-primary);
        margin-bottom: 0.5rem;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.9rem;
        color: var(--africode-gray-dark);
        margin: 0;
    }

    .stat-highlight .stat-icon {
        background: linear-gradient(135deg, var(--africode-secondary), #FFB366);
    }

    .stat-highlight .stat-number {
        color: var(--africode-secondary);
    }

    /* Responsive pour la composition organique */
    @media (max-width: 992px) {
        .hero-composition {
            max-width: 400px;
            max-height: 350px;
        }

        .main-image {
            width: 200px;
            height: 250px;
        }

        .top-right-image {
            width: 150px;
            height: 120px;
            top: 30px;
        }

        .bottom-right-image {
            width: 130px;
            height: 100px;
        }

        .floating-tech {
            font-size: 0.7rem;
            padding: 0.4rem 0.6rem;
        }
    }

    @media (max-width: 768px) {
        .hero-visual {
            height: 400px;
            padding: 1rem;
        }

        .hero-composition {
            max-width: 300px;
            max-height: 250px;
        }

        .main-image {
            width: 150px;
            height: 180px;
        }

        .top-right-image {
            width: 120px;
            height: 90px;
            top: 20px;
        }

        .bottom-right-image {
            width: 100px;
            height: 80px;
        }

        .decorative-elements .dots-grid,
        .decorative-elements .wavy-circle,
        .decorative-elements .diagonal-lines {
            display: none;
        }

        .floating-tech {
            display: none;
        }
    }

    /* Sections domaines et cours */
    .domains-section {
        padding: 5rem 0;
        background: var(--africode-gray-light);
    }

    .quote-section {
        margin-bottom: 4rem;
        padding: 3rem 2rem;
        background: var(--africode-white);
        border-radius: 20px;
        box-shadow: var(--africode-shadow-md);
    }

    .quote-visual {
        max-width: 800px;
        margin: 0 auto;
    }

    .quote-marks {
        font-size: 4rem;
        color: var(--africode-primary);
        opacity: 0.3;
        margin-bottom: 1rem;
    }

    .quote-text {
        font-size: 1.5rem;
        line-height: 1.6;
        color: var(--africode-dark-text);
        font-style: italic;
        margin-bottom: 1.5rem;
    }

    .highlight-underline {
        position: relative;
        color: var(--africode-primary);
        font-weight: 700;
    }

    .highlight-underline::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(135deg, var(--africode-primary), var(--africode-highlight-green));
        border-radius: 2px;
    }

    .quote-author {
        color: var(--africode-gray-dark);
        font-weight: 500;
    }

    /* Cartes de domaines */
    .domains-grid {
        margin-top: 3rem;
    }

    .domain-card {
        background: var(--africode-white);
        border-radius: 20px;
        padding: 2rem;
        height: 100%;
        position: relative;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: var(--africode-shadow-sm);
        border: 1px solid var(--africode-gray-medium);
    }

    .domain-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--africode-shadow-lg);
        border-color: var(--africode-primary);
    }

    .domain-background {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        border-radius: 20px 20px 0 0;
    }

    .domain-content {
        position: relative;
        z-index: 2;
    }

    .domain-icon {
        width: 60px;
        height: 60px;
        background: var(--africode-gray-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--africode-primary);
        margin-bottom: 1.5rem;
        transition: var(--africode-transition);
    }

    .domain-card:hover .domain-icon {
        background: var(--africode-primary);
        color: var(--africode-white);
        transform: scale(1.1);
    }

    .domain-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--africode-dark-text);
        margin-bottom: 1rem;
    }

    .domain-description {
        color: var(--africode-gray-dark);
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }

    .domain-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .course-count {
        font-size: 0.875rem;
        color: var(--africode-primary);
        font-weight: 600;
    }

    .domain-action {
        color: var(--africode-primary);
        font-weight: 600;
        opacity: 0;
        transform: translateX(-10px);
        transition: all 0.3s ease;
    }

    .domain-card:hover .domain-action {
        opacity: 1;
        transform: translateX(0);
    }

    /* Section cours populaires */
    .popular-courses {
        padding: 5rem 0;
        background: var(--africode-white);
    }

    .courses-carousel-container {
        position: relative;
        margin: 2rem 0;
    }

    .courses-carousel {
        display: flex;
        gap: 2rem;
        overflow-x: auto;
        scroll-behavior: smooth;
        padding: 1rem 0;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .courses-carousel::-webkit-scrollbar {
        display: none;
    }

    .carousel-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: var(--africode-white);
        border: 2px solid var(--africode-primary);
        color: var(--africode-primary);
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--africode-transition);
        z-index: 10;
        box-shadow: var(--africode-shadow-md);
    }

    .carousel-btn:hover {
        background: var(--africode-primary);
        color: var(--africode-white);
        transform: translateY(-50%) scale(1.1);
    }

    .carousel-prev {
        left: -25px;
    }

    .carousel-next {
        right: -25px;
    }

    .card-full-link {
        position: absolute;
        inset: 0;
        z-index: 1;
        border-radius: 20px;
    }

    .course-card-enhanced {
        background: var(--africode-white);
        border-radius: 20px;
        overflow: hidden;
        min-width: 320px;
        box-shadow: var(--africode-shadow-lg);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid var(--africode-gray-medium);
    }

    .course-card-enhanced:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .course-image {
        position: relative;
        height: 200px;
        overflow: hidden;
        
    }

    .course-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .course-card-enhanced:hover .course-image img {
        transform: scale(1.1);
    }

    .course-discount-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--africode-secondary);
        color: white;
        padding: 0.4rem 0.75rem;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 700;
        z-index: 2;
    }

    .course-level-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        padding: 0.5rem 1rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .level-débutant {
        background: rgba(39, 179, 113, 0.9);
        color: white;
    }

    .level-intermédiaire {
        background: rgba(255, 142, 42, 0.9);
        color: white;
    }

    .level-avancé {
        background: rgba(227, 45, 49, 0.9);
        color: white;
    }

    .free-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--africode-highlight-green);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .certified-badge {
        position: absolute;
        bottom: 15px;
        right: 15px;
        background: rgba(255, 255, 255, 0.9);
        color: var(--africode-primary);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .course-old-price {
        text-decoration: line-through;
        color: var(--africode-gray-medium);
        font-size: 0.9rem;
        margin-right: 0.5rem;
    }

    .course-current-price {
        font-weight: 700;
        color: var(--africode-primary);
    }

    .course-content {
        padding: 1.5rem;
    }

    .course-rating {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .stars {
        display: flex;
        gap: 0.25rem;
        align-items: center;
    }

    .stars i {
        color: #FFD700;
        font-size: 0.875rem;
    }

    .rating-value {
        margin-left: 0.5rem;
        font-weight: 600;
        color: var(--africode-dark-text);
    }

    .student-count {
        font-size: 0.875rem;
        color: var(--africode-gray-dark);
    }

    .course-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--africode-dark-text);
        margin-bottom: 1rem;
        line-height: 1.4;
    }

    .course-instructor {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .instructor-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
    }

    .instructor-name {
        font-size: 0.875rem;
        color: var(--africode-gray-dark);
    }

    .course-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid var(--africode-gray-medium);
    }

    .course-duration,
    .course-price {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
    }

    .course-duration {
        color: var(--africode-gray-dark);
    }

    .course-price {
        color: var(--africode-primary);
        font-weight: 600;
    }

    .course-price.free {
        color: var(--africode-highlight-green);
    }

    .course-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .course-enroll {
        flex: 1;
        margin-right: 0.75rem;
    }

    .course-save,
    .course-share {
        background: none;
        border: none;
        color: var(--africode-gray-dark);
        padding: 0.5rem;
        border-radius: 50%;
        cursor: pointer;
        transition: var(--africode-transition);
    }

    .course-save:hover,
    .course-share:hover {
        background: var(--africode-gray-light);
        color: var(--africode-primary);
    }

    /* Section certifications */
    .certifications-section {
        padding: 5rem 0;
        background: linear-gradient(135deg, var(--africode-primary) 0%, var(--africode-highlight-green) 100%);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .certifications-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="white" opacity="0.1"/></svg>') repeat;
        background-size: 50px 50px;
    }

    .certifications-content {
        position: relative;
        z-index: 2;
    }

    .certification-stats {
        margin: 2rem 0;
    }

    .stat-item-cert {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        backdrop-filter: blur(10px);
        transition: var(--africode-transition);
    }

    .stat-item-cert:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateX(10px);
    }

    .stat-icon-cert {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .stat-content {
        flex: 1;
    }

    .stat-number-cert {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.25rem;
    }

    .stat-desc {
        opacity: 0.9;
        font-size: 0.875rem;
    }

    .certification-visual {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 400px;
    }

    .certification-showcase {
        position: relative;
    }

    .cert-badge {
        background: var(--africode-white);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        text-align: center;
        max-width: 300px;
    }

    .cert-content i {
        font-size: 3rem;
        color: var(--africode-primary);
        margin-bottom: 1rem;
    }

    .cert-content h4 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--africode-dark-text);
        margin-bottom: 0.5rem;
    }

    .cert-content p {
        color: var(--africode-gray-dark);
        margin-bottom: 1rem;
    }

    .cert-seal {
        font-size: 2rem;
        color: var(--africode-secondary);
    }

    .floating-certs {
        position: absolute;
        inset: 0;
    }

    .mini-cert {
        position: absolute;
        background: var(--africode-white);
        border-radius: 10px;
        padding: 0.75rem;
        box-shadow: var(--africode-shadow-md);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        animation: float 4s ease-in-out infinite;
    }

    .cert-1 {
        top: 10%;
        left: -20%;
        animation-delay: 0s;
        color: #FF9900;
    }

    .cert-2 {
        top: 60%;
        right: -25%;
        animation-delay: 1.5s;
        color: #0078D4;
    }

    .cert-3 {
        bottom: 20%;
        left: -15%;
        animation-delay: 3s;
        color: #4285F4;
    }

    /* Section témoignages */
    .testimonials-enhanced {
        padding: 5rem 0;
        background: var(--africode-gray-light);
    }

    .testimonials-carousel-container {
        position: relative;
        margin: 2rem 0;
    }

    .testimonials-carousel {
        display: flex;
        gap: 2rem;
        overflow-x: auto;
        scroll-behavior: smooth;
        padding: 1rem 0;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .testimonials-carousel::-webkit-scrollbar {
        display: none;
    }

    .testimonial-carousel-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: var(--africode-white);
        border: 2px solid var(--africode-primary);
        color: var(--africode-primary);
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--africode-transition);
        z-index: 10;
        box-shadow: var(--africode-shadow-md);
    }

    .testimonial-carousel-btn:hover {
        background: var(--africode-primary);
        color: var(--africode-white);
        transform: translateY(-50%) scale(1.1);
    }

    .testimonial-prev {
        left: -25px;
    }

    .testimonial-next {
        right: -25px;
    }

    .testimonial-card-enhanced {
        background: var(--africode-white);
        border-radius: 20px;
        padding: 2rem;
        min-width: 350px;
        box-shadow: var(--africode-shadow-md);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid var(--africode-gray-medium);
    }

    .testimonial-card-enhanced:hover {
        transform: translateY(-10px);
        box-shadow: var(--africode-shadow-lg);
    }

    .testimonial-header {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .testimonial-image {
        position: relative;
    }

    .testimonial-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
    }

    .success-badge {
        position: absolute;
        bottom: -5px;
        right: -5px;
        background: var(--africode-highlight-green);
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
    }

    .testimonial-info {
        flex: 1;
    }

    .testimonial-name {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--africode-dark-text);
        margin-bottom: 0.25rem;
    }

    .testimonial-role {
        font-weight: 600;
        color: var(--africode-primary);
        margin-bottom: 0.25rem;
    }

    .testimonial-company {
        color: var(--africode-gray-dark);
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .testimonial-location {
        color: var(--africode-gray-dark);
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .testimonial-content {
        margin-bottom: 1.5rem;
    }

    .testimonial-rating {
        display: flex;
        gap: 0.25rem;
        margin-bottom: 1rem;
    }

    .testimonial-quote {
        font-style: italic;
        line-height: 1.6;
        color: var(--africode-dark-text);
        margin: 0;
    }

    .testimonial-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .course-badge,
    .impact-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .course-badge {
        background: rgba(30, 163, 139, 0.1);
        color: var(--africode-primary);
    }

    .impact-badge {
        background: rgba(39, 179, 113, 0.1);
        color: var(--africode-highlight-green);
    }

    .carousel-indicators {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 2rem;
    }

    .indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--africode-gray-medium);
        border: none;
        cursor: pointer;
        transition: var(--africode-transition);
    }

    .indicator.active {
        background: var(--africode-primary);
    }

    /* Section CTA finale */
    .final-cta-enhanced {
        padding: 5rem 0;
        background: linear-gradient(135deg, var(--africode-dark-text) 0%, #2c3e50 100%);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .final-cta-card {
        position: relative;
        z-index: 2;
    }

    .cta-background-elements {
        position: absolute;
        inset: 0;
        opacity: 0.1;
    }

    .floating-icon {
        position: absolute;
        font-size: 3rem;
        animation: float 6s ease-in-out infinite;
    }

    .icon-1 {
        top: 20%;
        left: 10%;
        animation-delay: 0s;
    }

    .icon-2 {
        top: 60%;
        right: 15%;
        animation-delay: 1.5s;
    }

    .icon-3 {
        bottom: 30%;
        left: 20%;
        animation-delay: 3s;
    }

    .icon-4 {
        top: 30%;
        right: 30%;
        animation-delay: 4.5s;
    }

    .cta-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--africode-secondary);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 2rem;
    }

    .final-cta-title {
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 900;
        margin-bottom: 1.5rem;
        line-height: 1.2;
    }

    .highlight-text {
        background: linear-gradient(135deg, var(--africode-secondary) 0%, #FFB366 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .final-cta-subtitle {
        font-size: 1.25rem;
        line-height: 1.6;
        opacity: 0.9;
        margin-bottom: 2rem;
        max-width: 600px;
    }

    .cta-features {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin: 2rem 0;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.95rem;
    }

    .feature-item i {
        color: var(--africode-highlight-green);
        font-size: 1.25rem;
    }

    .cta-actions-enhanced {
        text-align: center;
    }

    .btn-xl {
        padding: 1.5rem 3rem;
        font-size: 1.25rem;
        border-radius: 50px;
    }

    .cta-main-btn {
        background: linear-gradient(135deg, var(--africode-primary), var(--africode-highlight-green));
        border: none;
        color: white;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        margin-bottom: 2rem;
        box-shadow: 0 15px 40px rgba(30, 163, 139, 0.4);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .cta-main-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 50px rgba(30, 163, 139, 0.5);
        color: white;
    }

    .btn-content {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .btn-text {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .main-text {
        font-weight: 700;
    }

    .sub-text {
        font-size: 0.875rem;
        opacity: 0.9;
    }

    .cta-alternative {
        margin: 2rem 0;
    }

    .cta-alternative p {
        margin-bottom: 1rem;
        opacity: 0.8;
    }

    .trust-indicators {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-top: 2rem;
    }

    .trust-item {
        text-align: center;
    }

    .trust-number {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--africode-secondary);
    }

    .trust-label {
        font-size: 0.875rem;
        opacity: 0.8;
    }

</style>
@endpush

@push('scripts')
@vite('resources/js/homepage.js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Gestion de la bannière d'alerte
        const alertBanner = document.querySelector('.alert-banner');
        const alertClose = document.querySelector('.alert-close');
        
        if (alertClose) {
            alertClose.addEventListener('click', function() {
                alertBanner.style.transform = 'translateY(-100%)';
                setTimeout(() => {
                    alertBanner.style.display = 'none';
                    // Ajuster le padding du hero
                    document.querySelector('.hero-main').style.paddingTop = '80px';
                }, 300);
            });
        }

        // Animation des compteurs
        const statNumbers = document.querySelectorAll(".stat-number");
        
        const animateCounter = (element, target) => {
            let current = 0;
            const isPercentage = element.textContent.includes('%');
            const suffix = isPercentage ? '%' : (element.textContent.includes('M') ? 'M' : (element.textContent.includes('K') ? 'K' : '+'));
            const numericTarget = parseInt(target);
            const increment = numericTarget / 100;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= numericTarget) {
                    element.textContent = numericTarget + (suffix !== '+' ? suffix : (numericTarget < 100 ? '' : '+'));
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current) + (suffix !== '+' ? suffix : (current < 100 ? '' : '+'));
                }
            }, 20);
        };

        // Observer pour détecter quand les éléments entrent dans la vue
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const target = entry.target;
                    const text = target.textContent;
                    const numericValue = parseFloat(text.replace(/[^0-9.]/g, ''));
                    animateCounter(target, numericValue);
                    observer.unobserve(target);
                }
            });
        }, {
            threshold: 0.5
        });

        statNumbers.forEach(stat => {
            observer.observe(stat);
        });

        // Carrousel des cours
        const coursesCarousel = document.getElementById('coursesCarousel');
        const prevBtn = document.getElementById('coursesCarouselPrev');
        const nextBtn = document.getElementById('coursesCarouselNext');
        
        if (coursesCarousel && prevBtn && nextBtn) {
            let scrollAmount = 340; // largeur de carte + gap
            
            prevBtn.addEventListener('click', () => {
                coursesCarousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            });
            
            nextBtn.addEventListener('click', () => {
                coursesCarousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            });
        }

        // Gestion de la recherche d'académie
        const locationInput = document.getElementById('location');
        const academyNameInput = document.getElementById('academy-name');
        const searchBtn = document.querySelector('.search-btn');
        
        function toggleSearchButton() {
            if (searchBtn) {
                const hasLocation = locationInput && locationInput.value.trim().length > 0;
                const hasName = academyNameInput && academyNameInput.value.trim().length > 0;
                searchBtn.disabled = !(hasLocation || hasName);
            }
        }
        
        if (locationInput) {
            locationInput.addEventListener('input', toggleSearchButton);
        }
        if (academyNameInput) {
            academyNameInput.addEventListener('input', toggleSearchButton);
        }

        // Animation d'apparition progressive
        const animateOnScroll = () => {
            const elements = document.querySelectorAll('.domain-card, .course-card, .stat-item-cert');
            
            elements.forEach((element, index) => {
                const elementTop = element.getBoundingClientRect().top;
                const elementVisible = 150;
                
                if (elementTop < window.innerHeight - elementVisible) {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }
            });
        };

        // Initialiser les éléments avec opacity 0
        document.querySelectorAll('.domain-card, .course-card, .stat-item-cert').forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px)';
            element.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
        });

        // Écouter le scroll
        window.addEventListener('scroll', animateOnScroll);
        animateOnScroll(); // Appeler une fois au chargement

        // Smooth scroll pour les liens d'ancrage
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Effet parallax léger pour le hero
        const hero = document.querySelector('.hero-main');
        if (hero) {
            window.addEventListener('scroll', () => {
                const scrolled = window.pageYOffset;
                const parallax = scrolled * 0.3;
                hero.style.transform = `translateY(${parallax}px)`;
            });
        }

        // Mapping des slugs de domaines vers les slugs réels des catégories
        const categorySlugMapping = {
            'cybersecurity': 'cybersecurite',
            'web-development': 'developpement-web',
            'artificial-intelligence': 'intelligence-artificielle',
            'mobile-development': 'developpement-mobile',
            'data-science': 'data-science',
            'cloud-computing': 'devops-et-cloud',
            'database': 'base-de-donnees',
            'devops': 'devops-et-cloud'
        };


        // Carrousel des témoignages
        const testimonialsCarousel = document.getElementById('testimonialsCarousel');
        const testimonialPrev = document.getElementById('testimonialPrev');
        const testimonialNext = document.getElementById('testimonialNext');
        const indicators = document.querySelectorAll('.carousel-indicators .indicator');
        
        if (testimonialsCarousel && testimonialPrev && testimonialNext) {
            let currentTestimonial = 0;
            const testimonialCards = testimonialsCarousel.querySelectorAll('.testimonial-card-enhanced');
            const totalTestimonials = testimonialCards.length;
            
            function showTestimonial(index) {
                const cardWidth = testimonialCards[0].offsetWidth + 32; // largeur + gap
                testimonialsCarousel.scrollTo({
                    left: index * cardWidth,
                    behavior: 'smooth'
                });
                
                // Mettre à jour les indicateurs
                indicators.forEach((indicator, i) => {
                    indicator.classList.toggle('active', i === index);
                });
                
                currentTestimonial = index;
            }
            
            testimonialPrev.addEventListener('click', () => {
                currentTestimonial = currentTestimonial > 0 ? currentTestimonial - 1 : totalTestimonials - 1;
                showTestimonial(currentTestimonial);
            });
            
            testimonialNext.addEventListener('click', () => {
                currentTestimonial = currentTestimonial < totalTestimonials - 1 ? currentTestimonial + 1 : 0;
                showTestimonial(currentTestimonial);
            });
            
            // Gestion des indicateurs
            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    showTestimonial(index);
                });
            });
            
            // Auto-play du carrousel (optionnel)
            setInterval(() => {
                currentTestimonial = currentTestimonial < totalTestimonials - 1 ? currentTestimonial + 1 : 0;
                showTestimonial(currentTestimonial);
            }, 8000); // Change toutes les 8 secondes
        }
    });
</script>

<!-- Système d'aspects marketing -->
<script src="{{ asset('js/marketing-automation.js') }}"></script>

@endpush