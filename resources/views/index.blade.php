@extends('layouts.layout')

@section('title', 'AfriCode - Façonnez l\'avenir numérique de l\'Afrique')

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
    <section class="hero-main">
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
                            développement, IA et data science. Plus de 96% de nos diplômés trouvent un emploi dans les 6 mois.
                        </p>
                        <div class="hero-actions">
                            <a href="{{ route('register') }}" class="btn btn-primary btn-hero">
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
                                <span>+50,000 diplômés</span>
                            </div>
                            <div class="trust-item">
                                <i class="fas fa-globe-africa"></i>
                                <span>Présent dans 15 pays</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-visual">
                        <div class="hero-images-mosaic">
                            <div class="mosaic-item mosaic-main">
                                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=500&h=600&fit=crop&crop=faces" alt="Étudiants africains en formation tech" class="img-fluid">
                                <div class="mosaic-overlay">
                                    <div class="overlay-content">
                                        <i class="fas fa-play-circle"></i>
                                        <span>Découvrir nos formations</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mosaic-item mosaic-secondary">
                                <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=300&h=350&fit=crop&crop=faces" alt="Développeuse africaine" class="img-fluid">
                                <div class="achievement-badge">
                                    <i class="fas fa-trophy"></i>
                                    <span>Top diplômée 2024</span>
                                </div>
                            </div>
                            <div class="mosaic-item mosaic-tertiary">
                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=280&h=200&fit=crop&crop=faces" alt="Expert cybersécurité" class="img-fluid">
                                <div class="tech-badge">
                                    <span>Cybersécurité</span>
                                </div>
                            </div>
                            <div class="mosaic-item mosaic-accent">
                                <div class="stats-mini">
                                    <div class="stat-mini">
                                        <span class="number">96%</span>
                                        <span class="label">Taux d'emploi</span>
                                    </div>
                                    <div class="stat-mini">
                                        <span class="number">4.9/5</span>
                                        <span class="label">Satisfaction</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Éléments décoratifs flottants -->
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
                            <h3 class="stat-number">50K+</h3>
                            <p class="stat-label">Étudiants formés</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <h3 class="stat-number">500+</h3>
                            <p class="stat-label">Experts formateurs</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-6">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <h3 class="stat-number">200+</h3>
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
                                <i class="fas fa-trophy"></i>
                            </div>
                            <h3 class="stat-number">96%</h3>
                            <p class="stat-label">de nos diplômés trouvent un emploi dans les 6 mois</p>
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
                        Plus de <span class="highlight-underline">50,000 professionnels africains</span> ont transformé leur carrière grâce à nos formations certifiantes.
                    </blockquote>
                    <div class="quote-author">
                        <span>— Étude d'impact AfriCode 2024</span>
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
                                'icon' => 'fas fa-shield-alt', 
                                'title' => 'Cybersécurité', 
                                'description' => 'Protégez l\'infrastructure numérique africaine',
                                'courses' => '12 formations',
                                'gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                                'category' => 'cybersecurity'
                            ],
                            [
                                'icon' => 'fas fa-code', 
                                'title' => 'Développement Web', 
                                'description' => 'Créez des applications web modernes',
                                'courses' => '18 formations',
                                'gradient' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                                'category' => 'web-development'
                            ],
                            [
                                'icon' => 'fas fa-brain', 
                                'title' => 'Intelligence Artificielle', 
                                'description' => 'Maîtrisez l\'IA et le Machine Learning',
                                'courses' => '15 formations',
                                'gradient' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                                'category' => 'artificial-intelligence'
                            ],
                            [
                                'icon' => 'fas fa-mobile-alt', 
                                'title' => 'Développement Mobile', 
                                'description' => 'Développez des apps iOS et Android',
                                'courses' => '10 formations',
                                'gradient' => 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
                                'category' => 'mobile-development'
                            ],
                            [
                                'icon' => 'fas fa-chart-line', 
                                'title' => 'Data Science', 
                                'description' => 'Analysez et visualisez les données',
                                'courses' => '14 formations',
                                'gradient' => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
                                'category' => 'data-science'
                            ],
                            [
                                'icon' => 'fas fa-cloud', 
                                'title' => 'Cloud Computing', 
                                'description' => 'Maîtrisez AWS, Azure et GCP',
                                'courses' => '8 formations',
                                'gradient' => 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)',
                                'category' => 'cloud-computing'
                            ],
                            [
                                'icon' => 'fas fa-database', 
                                'title' => 'Base de données', 
                                'description' => 'Gérez efficacement vos données',
                                'courses' => '9 formations',
                                'gradient' => 'linear-gradient(135deg, #d299c2 0%, #fef9d7 100%)',
                                'category' => 'database'
                            ],
                            [
                                'icon' => 'fas fa-cogs', 
                                'title' => 'DevOps', 
                                'description' => 'Automatisez et optimisez vos déploiements',
                                'courses' => '11 formations',
                                'gradient' => 'linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%)',
                                'category' => 'devops'
                            ]
                        ];
                    @endphp

                    @foreach($domains as $domain)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="domain-card" data-category="{{ $domain['category'] }}">
                            <div class="domain-background" style="background: {{ $domain['gradient'] }}"></div>
                            <div class="domain-content">
                                <div class="domain-icon">
                                    <i class="{{ $domain['icon'] }}"></i>
                                </div>
                                <h4 class="domain-title">{{ $domain['title'] }}</h4>
                                <p class="domain-description">{{ $domain['description'] }}</p>
                                <div class="domain-meta">
                                    <span class="course-count">{{ $domain['courses'] }}</span>
                                </div>
                                <div class="domain-action">
                                    <span>Explorer →</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="text-center mt-5">
                <a href="/cours" class="btn btn-primary btn-lg">
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
                    @php
                        $courses = [
                            [
                                'title' => 'Cybersécurité Éthique & Pentesting',
                                'duration' => '120 heures',
                                'level' => 'INTERMÉDIAIRE',
                                'image' => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=400&h=250&fit=crop',
                                'price' => '299,000 FCFA',
                                'free' => false,
                                'certified' => true,
                                'instructor' => 'Dr. Kofi Asante',
                                'instructor_image' => 'https://randomuser.me/api/portraits/men/32.jpg',
                                'rating' => 4.9,
                                'students' => 2450
                            ],
                            [
                                'title' => 'Développement Full Stack JavaScript',
                                'duration' => '160 heures',
                                'level' => 'DÉBUTANT',
                                'image' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=400&h=250&fit=crop',
                                'price' => 'Gratuit',
                                'free' => true,
                                'certified' => true,
                                'instructor' => 'Aminata Traoré',
                                'instructor_image' => 'https://randomuser.me/api/portraits/women/44.jpg',
                                'rating' => 4.8,
                                'students' => 5200
                            ],
                            [
                                'title' => 'Data Science avec Python & IA',
                                'duration' => '200 heures',
                                'level' => 'AVANCÉ',
                                'image' => 'https://images.unsplash.com/photo-1515879218367-8466d910aaa4?w=400&h=250&fit=crop',
                                'price' => '399,000 FCFA',
                                'free' => false,
                                'certified' => true,
                                'instructor' => 'Prof. Ébène Kouassi',
                                'instructor_image' => 'https://randomuser.me/api/portraits/women/68.jpg',
                                'rating' => 5.0,
                                'students' => 1850
                            ],
                            [
                                'title' => 'Développement Mobile Flutter',
                                'duration' => '100 heures',
                                'level' => 'INTERMÉDIAIRE',
                                'image' => 'https://images.unsplash.com/photo-1507146426996-ef05306b995a?w=400&h=250&fit=crop',
                                'price' => '199,000 FCFA',
                                'free' => false,
                                'certified' => true,
                                'instructor' => 'Ibrahim Diallo',
                                'instructor_image' => 'https://randomuser.me/api/portraits/men/45.jpg',
                                'rating' => 4.7,
                                'students' => 3100
                            ],
                            [
                                'title' => 'Cloud Computing avec AWS',
                                'duration' => '80 heures',
                                'level' => 'INTERMÉDIAIRE',
                                'image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=400&h=250&fit=crop',
                                'price' => '249,000 FCFA',
                                'free' => false,
                                'certified' => true,
                                'instructor' => 'Sarah Kaba',
                                'instructor_image' => 'https://randomuser.me/api/portraits/women/32.jpg',
                                'rating' => 4.9,
                                'students' => 1650
                            ]
                        ];
                    @endphp

                    @foreach($courses as $course)
                    <div class="course-card-enhanced">
                        <div class="course-image">
                            <img src="{{ $course['image'] }}" alt="{{ $course['title'] }}" class="img-fluid">
                            <div class="course-level-badge level-{{ strtolower($course['level']) }}">
                                {{ $course['level'] }}
                            </div>
                            @if($course['free'])
                                <div class="free-badge">
                                    <i class="fas fa-gift"></i>
                                    <span>Gratuit</span>
                                </div>
                            @endif
                            @if($course['certified'])
                                <div class="certified-badge">
                                    <i class="fas fa-certificate"></i>
                                </div>
                            @endif
                        </div>
                        <div class="course-content">
                            <div class="course-rating">
                                <div class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= $course['rating'] ? '' : ' opacity-25' }}"></i>
                                    @endfor
                                    <span class="rating-value">{{ $course['rating'] }}</span>
                                </div>
                                <span class="student-count">{{ number_format($course['students']) }} étudiants</span>
                            </div>
                            
                            <h4 class="course-title">{{ $course['title'] }}</h4>
                            
                            <div class="course-instructor">
                                <img src="{{ $course['instructor_image'] }}" alt="{{ $course['instructor'] }}" class="instructor-avatar">
                                <span class="instructor-name">{{ $course['instructor'] }}</span>
                            </div>
                            
                            <div class="course-meta">
                                <div class="course-duration">
                                    <i class="far fa-clock"></i>
                                    <span>{{ $course['duration'] }}</span>
                                </div>
                                <div class="course-price {{ $course['free'] ? 'free' : '' }}">
                                    <i class="fas fa-tag"></i>
                                    <span>{{ $course['price'] }}</span>
                                </div>
                            </div>
                            
                            <div class="course-actions">
                                <button class="btn btn-primary btn-sm course-enroll">
                                    <i class="fas fa-play me-1"></i>
                                    {{ $course['free'] ? 'Commencer' : 'S\'inscrire' }}
                                </button>
                                <button class="course-save">
                                    <i class="far fa-heart"></i>
                                </button>
                                <button class="course-share">
                                    <i class="fas fa-share-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <button class="carousel-btn carousel-next" id="coursesCarouselNext">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            
            <div class="text-center mt-5">
                <a href="/cours" class="btn btn-primary btn-lg">
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
                                    <h3 class="stat-number-cert">96%</h3>
                                    <p class="stat-desc">de nos certifiés trouvent un emploi dans les 6 mois</p>
                                </div>
                            </div>
                            <div class="stat-item-cert">
                                <div class="stat-icon-cert">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number-cert">45%</h3>
                                    <p class="stat-desc">d'augmentation salariale moyenne après certification</p>
                                </div>
                            </div>
                            <div class="stat-item-cert">
                                <div class="stat-icon-cert">
                                    <i class="fas fa-rocket"></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number-cert">89%</h3>
                                    <p class="stat-desc">obtiennent une promotion dans l'année</p>
                                </div>
                            </div>
                        </div>
                        
                        <a href="{{ route('certificate.verification') }}" class="btn btn-success btn-lg mt-4">
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
                <p class="section-subtitle">Découvrez les parcours inspirants de nos diplômés</p>
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
                                Plus de 50,000 professionnels nous font confiance. 
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
                                    <span>Placement garanti</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="cta-actions-enhanced">
                            <a href="{{ route('register') }}" class="btn btn-primary btn-xl cta-main-btn">
                                <div class="btn-content">
                                    <i class="fas fa-rocket"></i>
                                    <div class="btn-text">
                                        <span class="main-text">Commencer ma formation</span>
                                        <span class="sub-text">Inscription gratuite</span>
                                    </div>
                                </div>
                            </a>
                            
                            <div class="cta-alternative">
                                <p>Ou découvrez nos programmes</p>
                                <a href="/cours" class="btn btn-outline-light btn-lg">
                                    <i class="fas fa-graduation-cap me-2"></i>
                                    Parcourir les formations
                                </a>
                            </div>
                            
                            <div class="trust-indicators">
                                <div class="trust-item">
                                    <div class="trust-number">4.9/5</div>
                                    <div class="trust-label">Satisfaction</div>
                                </div>
                                <div class="trust-item">
                                    <div class="trust-number">96%</div>
                                    <div class="trust-label">Emploi garanti</div>
                                </div>
                                <div class="trust-item">
                                    <div class="trust-number">50K+</div>
                                    <div class="trust-label">Diplômés</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bannière de cookies -->
    <div class="cookie-banner" id="cookieBanner">
        <div class="cookie-content">
            <div class="cookie-text">
                <p>Nous utilisons des cookies pour améliorer votre expérience sur notre site. En continuant, vous acceptez notre utilisation des cookies.</p>
            </div>
            <div class="cookie-actions">
                <a href="#" class="cookie-link">Déclaration de confidentialité</a>
                <button class="cookie-settings">Modifier les paramètres</button>
                <button class="cookie-accept">Accepter</button>
                <button class="cookie-close" aria-label="Fermer">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>

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

    /* Hero Visual Mosaïque */
    .hero-images-mosaic {
        position: relative;
        width: 100%;
        height: 600px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-rows: 2fr 1fr;
        gap: 1.5rem;
        max-width: 500px;
        margin: 0 auto;
    }

    .mosaic-item {
        border-radius: 20px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .mosaic-item:hover {
        transform: scale(1.05) rotate(1deg);
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.2);
    }

    .mosaic-main {
        grid-column: 1;
        grid-row: 1 / 3;
        background: linear-gradient(135deg, var(--africode-primary), var(--africode-highlight-green));
    }

    .mosaic-secondary {
        grid-column: 2;
        grid-row: 1;
        position: relative;
    }

    .mosaic-tertiary {
        grid-column: 2;
        grid-row: 2;
    }

    .mosaic-accent {
        position: absolute;
        top: -20px;
        right: -20px;
        width: 150px;
        height: 150px;
        background: var(--africode-white);
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 10;
    }

    .mosaic-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .mosaic-main:hover .mosaic-overlay {
        opacity: 1;
    }

    .overlay-content {
        text-align: center;
        color: white;
    }

    .overlay-content i {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .achievement-badge {
        position: absolute;
        bottom: 15px;
        left: 15px;
        background: var(--africode-secondary);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tech-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(255, 255, 255, 0.9);
        color: var(--africode-primary);
        padding: 0.5rem 1rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }

    .stats-mini {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .stat-mini {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .stat-mini .number {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--africode-primary);
    }

    .stat-mini .label {
        font-size: 0.75rem;
        color: var(--africode-gray-dark);
        font-weight: 500;
    }

    /* Éléments flottants tech */
    .floating-elements {
        position: absolute;
        inset: 0;
        pointer-events: none;
    }

    .floating-tech {
        position: absolute;
        background: var(--africode-white);
        border-radius: 15px;
        padding: 0.75rem 1rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        animation: float 4s ease-in-out infinite;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .floating-tech.python {
        top: 10%;
        left: -10%;
        color: #3776ab;
        animation-delay: 0s;
    }

    .floating-tech.react {
        top: 20%;
        right: -15%;
        color: #61dafb;
        animation-delay: 1s;
    }

    .floating-tech.security {
        bottom: 30%;
        left: -5%;
        color: var(--africode-accent-red);
        animation-delay: 2s;
    }

    .floating-tech.ai {
        bottom: 15%;
        right: -10%;
        color: var(--africode-primary);
        animation-delay: 1.5s;
    }

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

    /* Hero Visual */
    .hero-visual {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 500px;
    }

    .visual-container {
        position: relative;
        width: 100%;
        height: 100%;
    }

    .hero-svg {
        width: 100%;
        height: 100%;
        max-width: 500px;
    }

    .floating-badges {
        position: absolute;
        inset: 0;
        pointer-events: none;
    }

    .badge-item {
        position: absolute;
        background: var(--africode-white);
        border-radius: var(--africode-border-radius);
        padding: 0.75rem 1rem;
        box-shadow: var(--africode-shadow-md);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        animation: float 3s ease-in-out infinite;
    }

    .badge-1 {
        top: 10%;
        left: 10%;
        animation-delay: 0s;
        color: #3776ab;
    }

    .badge-2 {
        top: 20%;
        right: 15%;
        animation-delay: 1s;
        color: #f7df1e;
        background: #333;
    }

    .badge-3 {
        bottom: 30%;
        left: 5%;
        animation-delay: 2s;
        color: var(--africode-accent-red);
    }

    .badge-4 {
        bottom: 15%;
        right: 10%;
        animation-delay: 1.5s;
        color: var(--africode-primary);
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
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
        padding: 3rem 0;
        background: var(--africode-gray-light);
        margin-top: -50px;
        position: relative;
        z-index: 10;
    }

    .stats-card {
        background: var(--africode-white);
        border-radius: var(--africode-border-radius);
        padding: 3rem 2rem;
        box-shadow: var(--africode-shadow-lg);
        margin: 0 auto;
        max-width: 1000px;
    }

    .stat-item {
        padding: 1rem;
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
        font-weight: 500;
    }

    .stat-highlight .stat-number {
        color: var(--africode-highlight-green);
    }

    /* Section Citation et Domaines */
    .quote-domains {
        padding: 5rem 0;
        background: var(--africode-white);
    }

    .quote-section {
        margin-bottom: 4rem;
    }

    .quote-marks {
        font-size: 3rem;
        color: var(--africode-primary);
        margin-bottom: 1rem;
    }

    .quote-text {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--africode-dark-text);
        line-height: 1.4;
        max-width: 800px;
        margin: 0 auto;
    }

    .highlight-underline {
        position: relative;
        z-index: 1;
    }

    .highlight-underline::after {
        content: '';
        position: absolute;
        bottom: 2px;
        left: 0;
        right: 0;
        height: 8px;
        background: var(--africode-primary);
        z-index: -1;
        opacity: 0.3;
    }

    /* Section Domaines de Formation */
    .domains-section {
        padding: 5rem 0;
        background: var(--africode-white);
    }

    .domain-card {
        background: var(--africode-white);
        border-radius: 20px;
        padding: 0;
        text-align: center;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        height: 100%;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: 2px solid transparent;
    }

    .domain-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        border-color: rgba(255, 255, 255, 0.2);
    }

    .domain-background {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0.9;
        transition: opacity 0.3s ease;
        border-radius: 20px;
    }

    .domain-card:hover .domain-background {
        opacity: 1;
    }

    .domain-content {
        position: relative;
        z-index: 2;
        padding: 2.5rem 1.5rem;
        color: white;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .domain-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        margin: 0 auto 1.5rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .domain-card:hover .domain-icon {
        transform: scale(1.1) rotate(10deg);
        background: rgba(255, 255, 255, 0.3);
    }

    .domain-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: white;
        margin-bottom: 1rem;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }

    .domain-description {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 1.5rem;
        line-height: 1.5;
        text-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
    }

    .domain-meta {
        margin-bottom: 1rem;
    }

    .course-count {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .domain-action {
        font-weight: 600;
        font-size: 1rem;
        color: white;
        opacity: 0.8;
        transition: all 0.3s ease;
    }

    .domain-card:hover .domain-action {
        opacity: 1;
        transform: translateX(5px);
    }

    /* Section Cours Populaires Améliorée */
    .popular-courses {
        padding: 5rem 0;
        background: var(--africode-gray-light);
    }

    .courses-carousel-container {
        position: relative;
        margin: 3rem 0;
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

    .course-card-enhanced {
        flex: 0 0 380px;
        background: var(--africode-white);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
    }

    .course-card-enhanced:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }

    .course-image {
        position: relative;
        height: 220px;
        overflow: hidden;
    }

    .course-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .course-card-enhanced:hover .course-image img {
        transform: scale(1.05);
    }

    .course-level-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
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
        top: 1rem;
        right: 1rem;
        background: rgba(39, 179, 113, 0.95);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        backdrop-filter: blur(10px);
    }

    .certified-badge {
        position: absolute;
        top: 3.5rem;
        right: 1rem;
        background: rgba(255, 255, 255, 0.95);
        color: var(--africode-secondary);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        backdrop-filter: blur(10px);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .course-content {
        padding: 2rem;
    }

    .course-rating {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .stars {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .stars i {
        color: #fbbf24;
        font-size: 0.875rem;
    }

    .rating-value {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--africode-gray-dark);
        margin-left: 0.5rem;
    }

    .student-count {
        font-size: 0.75rem;
        color: var(--africode-gray-dark);
    }

    .course-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--africode-dark-text);
        margin-bottom: 1.5rem;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        line-clamp: 2;
        overflow: hidden;
    }

    .course-instructor {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .instructor-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--africode-gray-medium);
    }

    .instructor-name {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--africode-gray-dark);
    }

    .course-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1rem;
        background: var(--africode-gray-light);
        border-radius: 12px;
    }

    .course-duration, .course-price {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
    }

    .course-duration {
        color: var(--africode-gray-dark);
    }

    .course-price {
        font-weight: 700;
        color: var(--africode-primary);
    }

    .course-price.free {
        color: var(--africode-highlight-green);
    }

    .course-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .course-enroll {
        flex: 1;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        border-radius: 12px;
        border: none;
        background: linear-gradient(135deg, var(--africode-primary), var(--africode-highlight-green));
        color: white;
        transition: all 0.3s ease;
    }

    .course-enroll:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(30, 163, 139, 0.3);
    }

    .course-save, .course-share {
        width: 40px;
        height: 40px;
        border: 2px solid var(--africode-gray-medium);
        background: var(--africode-white);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--africode-gray-dark);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .course-save:hover {
        border-color: var(--africode-accent-red);
        color: var(--africode-accent-red);
        transform: scale(1.05);
    }

    .course-share:hover {
        border-color: var(--africode-primary);
        color: var(--africode-primary);
        transform: scale(1.05);
    }

    .carousel-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: var(--africode-white);
        border: none;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--africode-primary);
        box-shadow: var(--africode-shadow-md);
        cursor: pointer;
        transition: var(--africode-transition);
        z-index: 10;
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

    /* Section Trouver une académie */
    .find-academy {
        padding: 5rem 0;
        background: var(--africode-white);
    }

    .academy-search {
        margin-top: 2rem;
    }

    .search-field {
        position: relative;
    }

    .field-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--africode-gray-dark);
        z-index: 5;
    }

    .search-field .form-control {
        padding-left: 3rem;
        height: 50px;
        border: 2px solid var(--africode-gray-medium);
        border-radius: var(--africode-border-radius);
        transition: var(--africode-transition);
    }

    .search-field .form-control:focus {
        border-color: var(--africode-primary);
        box-shadow: 0 0 0 0.2rem rgba(30, 163, 139, 0.25);
    }

    .search-btn {
        height: 50px;
        padding: 0 2rem;
        font-weight: 600;
    }

    .search-btn:disabled {
        background: var(--africode-gray-medium);
        border-color: var(--africode-gray-medium);
        cursor: not-allowed;
    }

    .academy-image {
        box-shadow: var(--africode-shadow-lg);
    }

    /* Section Certifications Améliorée */
    .certifications-section {
        padding: 5rem 0;
        background: linear-gradient(135deg, #1a365d 0%, #2c5282 100%);
        color: var(--africode-white);
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
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        opacity: 0.5;
    }

    .certifications-content {
        position: relative;
        z-index: 2;
    }

    .certification-stats {
        margin: 3rem 0;
    }

    .stat-item-cert {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
        padding: 2rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }

    .stat-item-cert:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateX(10px);
    }

    .stat-icon-cert {
        width: 60px;
        height: 60px;
        background: rgba(39, 179, 113, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--africode-highlight-green);
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .stat-content {
        flex: 1;
    }

    .stat-number-cert {
        font-size: 3rem;
        font-weight: 800;
        color: var(--africode-highlight-green);
        margin-bottom: 0.5rem;
        line-height: 1;
        text-shadow: 0 2px 10px rgba(39, 179, 113, 0.5);
    }

    .stat-desc {
        color: rgba(255, 255, 255, 0.9);
        margin: 0;
        font-size: 1.1rem;
        line-height: 1.4;
    }

    .certification-visual {
        position: relative;
        height: 500px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .certification-showcase {
        position: relative;
        width: 100%;
        height: 100%;
    }

    .cert-badge {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 280px;
        height: 350px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.85));
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        color: var(--africode-dark-text);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .cert-content {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
    }

    .cert-content i {
        font-size: 4rem;
        color: var(--africode-secondary);
        margin-bottom: 1rem;
    }

    .cert-content h4 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--africode-primary);
        margin-bottom: 0.5rem;
    }

    .cert-content p {
        font-size: 1rem;
        color: var(--africode-gray-dark);
        margin-bottom: 2rem;
    }

    .cert-seal {
        font-size: 3rem;
        color: var(--africode-highlight-green);
        opacity: 0.3;
    }

    .floating-certs {
        position: absolute;
        inset: 0;
    }

    .mini-cert {
        position: absolute;
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 15px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--africode-primary);
        font-size: 1.5rem;
        font-weight: 600;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        animation: float 4s ease-in-out infinite;
        backdrop-filter: blur(10px);
    }

    .cert-1 {
        top: 10%;
        left: 10%;
        animation-delay: 0s;
        color: #ff9900;
    }

    .cert-2 {
        top: 20%;
        right: 15%;
        animation-delay: 1.5s;
        color: #0078d4;
    }

    .cert-3 {
        bottom: 15%;
        left: 20%;
        animation-delay: 3s;
        color: #4285f4;
    }

    .mini-cert span {
        font-size: 0.7rem;
        margin-top: 0.25rem;
    }

    /* Section Prochain emploi */
    .next-job {
        padding: 5rem 0;
        background: var(--africode-gray-light);
    }

    /* Section Témoignages Améliorée */
    .testimonials-enhanced {
        padding: 5rem 0;
        background: var(--africode-gray-light);
        position: relative;
    }

    .testimonials-carousel-container {
        position: relative;
        margin: 3rem 0;
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

    .testimonial-card-enhanced {
        flex: 0 0 420px;
        background: var(--africode-white);
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid var(--africode-gray-medium);
        position: relative;
        overflow: hidden;
    }

    .testimonial-card-enhanced::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, var(--africode-primary), var(--africode-highlight-green));
    }

    .testimonial-card-enhanced:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
        border-color: var(--africode-primary);
    }

    .testimonial-header {
        display: flex;
        align-items: flex-start;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .testimonial-image {
        position: relative;
        flex-shrink: 0;
    }

    .testimonial-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--africode-gray-medium);
        transition: border-color 0.3s ease;
    }

    .testimonial-card-enhanced:hover .testimonial-avatar {
        border-color: var(--africode-primary);
    }

    .success-badge {
        position: absolute;
        bottom: -5px;
        right: -5px;
        width: 30px;
        height: 30px;
        background: var(--africode-highlight-green);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.875rem;
        border: 3px solid var(--africode-white);
    }

    .testimonial-info {
        flex: 1;
    }

    .testimonial-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--africode-dark-text);
        margin-bottom: 0.25rem;
    }

    .testimonial-role {
        font-size: 1rem;
        font-weight: 600;
        color: var(--africode-primary);
        margin-bottom: 0.25rem;
    }

    .testimonial-company {
        font-size: 0.9rem;
        color: var(--africode-gray-dark);
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .testimonial-location {
        font-size: 0.85rem;
        color: var(--africode-gray-dark);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .testimonial-content {
        margin-bottom: 2rem;
    }

    .testimonial-rating {
        display: flex;
        gap: 0.25rem;
        margin-bottom: 1.5rem;
    }

    .testimonial-rating i {
        color: #fbbf24;
        font-size: 1rem;
    }

    .testimonial-quote {
        font-size: 1.1rem;
        line-height: 1.6;
        color: var(--africode-dark-text);
        font-style: italic;
        position: relative;
        margin: 0;
    }

    .testimonial-quote::before {
        content: '"';
        position: absolute;
        top: -10px;
        left: -15px;
        font-size: 3rem;
        color: var(--africode-primary);
        opacity: 0.3;
        font-family: Georgia, serif;
    }

    .testimonial-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--africode-gray-medium);
    }

    .course-badge, .impact-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
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

    .testimonial-carousel-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: var(--africode-white);
        border: none;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--africode-primary);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10;
    }

    .testimonial-carousel-btn:hover {
        background: var(--africode-primary);
        color: var(--africode-white);
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 15px 40px rgba(30, 163, 139, 0.3);
    }

    .testimonial-prev {
        left: -30px;
    }

    .testimonial-next {
        right: -30px;
    }

    /* Indicateurs de navigation */
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
        border: none;
        background: var(--africode-gray-medium);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .indicator.active {
        background: var(--africode-primary);
        transform: scale(1.2);
    }

    .indicator:hover {
        background: var(--africode-primary);
        opacity: 0.7;
    }

    /* Section CTA Final Améliorée */
    .final-cta-enhanced {
        padding: 6rem 0;
        background: linear-gradient(135deg, var(--africode-primary) 0%, var(--africode-highlight-green) 100%);
        position: relative;
        overflow: hidden;
    }

    .final-cta-enhanced::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="hexagons" width="20" height="17.32" patternUnits="userSpaceOnUse"><polygon points="10,0 20,5.77 20,11.55 10,17.32 0,11.55 0,5.77" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23hexagons)"/></svg>');
        opacity: 0.3;
        animation: backgroundMove 30s linear infinite;
    }

    @keyframes backgroundMove {
        0% { transform: translateX(0) translateY(0); }
        100% { transform: translateX(-20px) translateY(-17.32px); }
    }

    .final-cta-card {
        position: relative;
        z-index: 2;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 30px;
        padding: 4rem;
        backdrop-filter: blur(20px);
        border: 2px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.1);
    }

    .cta-background-elements {
        position: absolute;
        inset: 0;
        pointer-events: none;
        overflow: hidden;
        border-radius: 30px;
    }

    .floating-icon {
        position: absolute;
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.7);
        font-size: 1.5rem;
        animation: float 6s ease-in-out infinite;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .icon-1 {
        top: 10%;
        left: 8%;
        animation-delay: 0s;
    }

    .icon-2 {
        top: 15%;
        right: 12%;
        animation-delay: 1.5s;
    }

    .icon-3 {
        bottom: 20%;
        left: 15%;
        animation-delay: 3s;
    }

    .icon-4 {
        bottom: 10%;
        right: 8%;
        animation-delay: 4.5s;
    }

    .cta-content {
        position: relative;
        z-index: 3;
    }

    .cta-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 1rem 2rem;
        border-radius: 50px;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 2rem;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        animation: pulse 3s ease-in-out infinite;
    }

    .final-cta-title {
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 900;
        color: var(--africode-white);
        margin-bottom: 2rem;
        line-height: 1.1;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .highlight-text {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: none;
    }

    .final-cta-subtitle {
        font-size: 1.375rem;
        color: rgba(255, 255, 255, 0.9);
        line-height: 1.6;
        margin-bottom: 3rem;
        max-width: 600px;
    }

    .cta-features {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.1rem;
        font-weight: 500;
    }

    .feature-item i {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .cta-actions-enhanced {
        text-align: center;
    }

    .btn-xl {
        font-size: 1.25rem;
        padding: 1.5rem 3rem;
        border-radius: 60px;
        font-weight: 700;
        text-decoration: none;
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 2rem;
        min-width: 300px;
    }

    .cta-main-btn {
        background: var(--africode-white);
        color: var(--africode-primary);
        border: 3px solid var(--africode-white);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    }

    .cta-main-btn:hover {
        color: var(--africode-primary);
        transform: translateY(-5px);
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
    }

    .btn-content {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .btn-content i {
        font-size: 1.5rem;
    }

    .btn-text {
        display: flex;
        flex-direction: column;
        text-align: left;
    }

    .main-text {
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1;
    }

    .sub-text {
        font-size: 0.9rem;
        opacity: 0.7;
        font-weight: 500;
    }

    .cta-alternative {
        margin: 2rem 0;
    }

    .cta-alternative p {
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 1rem;
        font-size: 1rem;
    }

    .btn-outline-light {
        background: transparent;
        border: 2px solid rgba(255, 255, 255, 0.5);
        color: white;
        padding: 1rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: white;
        color: white;
        transform: translateY(-2px);
    }

    .trust-indicators {
        display: flex;
        justify-content: center;
        gap: 3rem;
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    .trust-item {
        text-align: center;
    }

    .trust-number {
        font-size: 2rem;
        font-weight: 800;
        color: white;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }

    .trust-label {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 500;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .hero-images-mosaic {
            max-width: 450px;
            height: 500px;
        }
        
        .course-card-enhanced {
            flex: 0 0 350px;
        }
        
        .testimonial-card-enhanced {
            flex: 0 0 380px;
        }
    }

    @media (max-width: 992px) {
        .hero-main {
            padding: 100px 0 60px;
            text-align: center;
        }
        
        .hero-images-mosaic {
            max-width: 400px;
            height: 450px;
            margin-top: 3rem;
        }
        
        .trust-badges {
            justify-content: center;
        }
        
        .cta-features {
            grid-template-columns: 1fr;
            text-align: center;
        }
        
        .trust-indicators {
            gap: 2rem;
        }
        
        .floating-elements {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.1rem;
        }
        
        .hero-images-mosaic {
            height: 350px;
            max-width: 300px;
        }
        
        .domain-card {
            border-radius: 15px;
        }
        
        .domain-content {
            padding: 2rem 1rem;
        }
        
        .course-card-enhanced {
            flex: 0 0 320px;
        }
        
        .testimonial-card-enhanced {
            flex: 0 0 350px;
            padding: 2rem;
        }
        
        .testimonial-header {
            flex-direction: column;
            text-align: center;
            gap: 1rem;
        }
        
        .final-cta-card {
            padding: 3rem 2rem;
            border-radius: 20px;
        }
        
        .btn-xl {
            min-width: 280px;
            padding: 1.25rem 2rem;
            font-size: 1.1rem;
        }
        
        .trust-indicators {
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .carousel-prev, .carousel-next,
        .testimonial-prev, .testimonial-next {
            display: none;
        }
    }

    @media (max-width: 576px) {
        .stats-card {
            padding: 2rem 1rem;
        }
        
        .stat-item {
            padding: 0.5rem;
        }
        
        .domain-content {
            padding: 1.5rem 1rem;
        }
        
        .domain-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
        
        .domain-title {
            font-size: 1.25rem;
        }
        
        .course-card-enhanced {
            flex: 0 0 300px;
        }
        
        .testimonial-card-enhanced {
            flex: 0 0 320px;
        }
        
        .cert-badge {
            width: 250px;
            height: 300px;
            padding: 1.5rem;
        }
        
        .mini-cert {
            width: 60px;
            height: 60px;
            font-size: 1.25rem;
        }
        
        .final-cta-title {
            font-size: 2rem;
        }
        
        .final-cta-subtitle {
            font-size: 1.1rem;
        }
    }

    /* Bannière de cookies */
    .cookie-banner {
        position: fixed;
        bottom: 20px;
        left: 20px;
        right: 20px;
        background: var(--africode-white);
        border-radius: var(--africode-border-radius);
        box-shadow: var(--africode-shadow-lg);
        padding: 1.5rem;
        z-index: 1040;
        max-width: 600px;
        margin: 0 auto;
        display: none;
    }

    .cookie-banner.show {
        display: block;
        animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
        from {
            transform: translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .cookie-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .cookie-text p {
        margin: 0;
        font-size: 0.875rem;
        color: var(--africode-dark-text);
    }

    .cookie-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .cookie-link {
        color: var(--africode-primary);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .cookie-link:hover {
        text-decoration: underline;
        color: var(--africode-primary);
    }

    .cookie-settings, .cookie-accept, .cookie-close {
        background: none;
        border: 1px solid var(--africode-gray-medium);
        border-radius: 6px;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        cursor: pointer;
        transition: var(--africode-transition);
    }

    .cookie-accept {
        background: var(--africode-primary);
        color: var(--africode-white);
        border-color: var(--africode-primary);
    }

    .cookie-accept:hover {
        background: var(--africode-highlight-green);
        border-color: var(--africode-highlight-green);
    }

    .cookie-close {
        border: none;
        padding: 0.5rem;
        color: var(--africode-gray-dark);
    }

    .cookie-close:hover {
        color: var(--africode-dark-text);
    }

    .cookie-close:hover {
        color: var(--africode-dark-text);
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

        // Bannière de cookies
        const cookieBanner = document.getElementById('cookieBanner');
        const cookieAccept = document.querySelector('.cookie-accept');
        const cookieClose = document.querySelector('.cookie-close');
        
        // Afficher la bannière après 3 secondes si pas de cookie
        if (cookieBanner && !localStorage.getItem('cookiesAccepted')) {
            setTimeout(() => {
                cookieBanner.classList.add('show');
            }, 3000);
        }
        
        function hideCookieBanner() {
            if (cookieBanner) {
                cookieBanner.classList.remove('show');
                localStorage.setItem('cookiesAccepted', 'true');
            }
        }
        
        if (cookieAccept) {
            cookieAccept.addEventListener('click', hideCookieBanner);
        }
        if (cookieClose) {
            cookieClose.addEventListener('click', hideCookieBanner);
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

        // Gestion du clic sur les cartes de domaine
        const domainCards = document.querySelectorAll('.domain-card');
        domainCards.forEach(card => {
            card.addEventListener('click', function() {
                const category = this.getAttribute('data-category');
                if (category) {
                    // Redirection vers la page des cours avec filtrage par catégorie
                    window.location.href = `/cours?category=${category}`;
                }
            });
        });

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
@endpush