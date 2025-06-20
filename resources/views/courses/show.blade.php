@extends('layouts.layout')

@section('title', $course->title . ' | AfriCode')

@section('meta_tags')
    <meta name="description" content="{{ $course->short_description }}">
    <meta name="keywords" content="cours en ligne, {{ $course->title }}, {{ $course->category ? $course->category->name : 'programmation' }}, apprendre à coder">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $course->title }} | AfriCode">
    <meta property="og:description" content="{{ $course->short_description }}">
    <meta property="og:image" content="{{ asset($course->cover_image_path ?? 'assets/images/course-placeholder.jpg') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $course->title }} | AfriCode">
    <meta property="twitter:description" content="{{ $course->short_description }}">
    <meta property="twitter:image" content="{{ asset($course->cover_image_path ?? 'assets/images/course-placeholder.jpg') }}">
@endsection

@section('content')
    <!-- Section d'aperçu du cours - Dark background avec infos clés -->
    <div class="course-header bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 mb-3">
                            <li class="breadcrumb-item"><a href="/" class="text-white-50">Accueil</a></li>
                            <li class="breadcrumb-item">
                                <a href="#" class="text-white-50">
                                    {{ $course->category ? $course->category->name : 'Développement' }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active text-white" aria-current="page">{{ $course->title }}</li>
                        </ol>
                    </nav>
                    
                    <h1 class="fw-bold display-5 mb-2">{{ $course->title }}</h1>
                    <p class="lead mb-3">{{ $course->short_description }}</p>
                    
                    <!-- Tags, badges et évaluations -->
                    <div class="d-flex flex-wrap align-items-center mb-3">
                        @php
                            $isBestseller = $course->enrollments->count() > 50;
                            $isNew = $course->created_at && $course->created_at->diffInDays(now()) < 30;
                            $avgRating = $course->ratings->avg('rating') ?? 4.5;
                            $reviewsCount = $course->ratings->count() ?? 0;
                        @endphp
                        
                        @if($isBestseller)
                            <span class="badge bg-warning text-dark me-2 mb-2">Meilleure vente</span>
                        @elseif($isNew)
                            <span class="badge bg-info text-white me-2 mb-2">Nouveau</span>
                        @endif
                        
                        <div class="me-3 mb-2 d-flex align-items-center">
                            <span class="text-warning fw-bold me-1">{{ number_format($avgRating, 1) }}</span>
                            <div class="text-warning me-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= floor($avgRating))
                                        <i class="fas fa-star"></i>
                                    @elseif ($i - 0.5 <= $avgRating)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <a href="#avis" class="text-white-50">({{ $reviewsCount }} avis)</a>
                        </div>
                        <div class="me-3 mb-2">
                            <i class="fas fa-user-graduate me-1"></i> {{ number_format($course->enrollments->count()) }} apprenants
                        </div>
                    </div>
                    
                    <div class="d-flex flex-wrap align-items-center mb-4">
                        <div class="me-3 mb-2">
                            <span class="text-white-50">Créé par</span>
                            <a href="#instructor" class="text-white fw-bold ms-1">
                                {{ $course->formateur ? $course->formateur->first_name . ' ' . $course->formateur->last_name : 'Instructeur AfriCode' }}
                            </a>
                        </div>
                        <div class="me-3 mb-2">
                            <i class="fas fa-clock me-1"></i> Dernière mise à jour {{ $course->updated_at->format('d/m/Y') }}
                        </div>
                        <div class="me-3 mb-2">
                            <i class="fas fa-globe me-1"></i> Français
                        </div>
                        <div class="me-3 mb-2">
                            <i class="fas fa-signal me-1"></i> 
                            @if($course->level === 'beginner')
                                Débutant
                            @elseif($course->level === 'intermediate')
                                Intermédiaire
                            @elseif($course->level === 'advanced')
                                Avancé
                            @else
                                Tous niveaux
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenu principal avec sidebar -->
    <div class="container py-5">
        <div class="row">
            <!-- Contenu principal -->
            <div class="col-lg-8">
                <!-- Aperçu vidéo embed -->
                <div class="bg-dark rounded mb-4 position-relative" style="height: 400px;">
                    <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                        <img src="{{ asset($course->cover_image_path ?? 'assets/images/course-placeholder.jpg') }}" alt="{{ $course->title }}" class="position-absolute w-100 h-100 object-fit-cover opacity-50" style="object-fit: cover; top: 0; left: 0;">
                        <a href="#" class="btn btn-primary btn-lg rounded-circle play-button position-relative d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; z-index: 2;">
                            <i class="fas fa-play fa-2x"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Ce que vous apprendrez -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h2 class="h4 mb-0">Ce que vous apprendrez</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @php
                                // Utiliser les objectifs d'apprentissage du cours ou des valeurs par défaut
                                $learningPoints = $course->learning_objectives ?? [
                                    'Maîtriser les concepts fondamentaux de ce cours',
                                    'Créer des applications professionnelles',
                                    'Comprendre les bonnes pratiques de développement',
                                    'Appliquer ces connaissances dans des projets réels',
                                    'Optimiser les performances de vos applications',
                                    'Travailler efficacement en équipe'
                                ];
                            @endphp

                            @foreach ($learningPoints as $index => $item)
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <i class="fas fa-check text-success"></i>
                                        </div>
                                        <div>
                                            {{ $item }}
                                        </div>
                                    </div>
                                </div>
                                @if ($index % 2 == 1 && $index < count($learningPoints) - 1)
                                    <div class="w-100"></div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Contenu du cours -->
                <div class="card mb-4">
                    <div class="card-header bg-white d-flex justify-content-between">
                        <h2 class="h4 mb-0">Contenu du cours</h2>
                        <div class="text-muted">
                            {{ $course->modules->count() }} sections • 
                            {{ $course->modules->sum(function($module) { return $module->lessons->count(); }) }} leçons • 
                            {{ $course->getEstimatedDuration() ?? '10h' }} de durée totale
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="accordion" id="courseContentAccordion">
                            @forelse($course->modules as $key => $module)
                                <div class="accordion-item">
                                    <h3 class="accordion-header" id="section-{{ $key }}-heading">
                                        <button class="accordion-button {{ $key > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#section-{{ $key }}-content" aria-expanded="{{ $key === 0 ? 'true' : 'false' }}" aria-controls="section-{{ $key }}-content">
                                            <div class="w-100 d-flex justify-content-between">
                                                <span>{{ $module->title }}</span>
                                                <span class="text-muted me-3">{{ $module->lessons->count() }} leçons • {{ $module->lessons->sum('duration_minutes') ?? '45' }} min</span>
                                            </div>
                                        </button>
                                    </h3>
                                    <div id="section-{{ $key }}-content" class="accordion-collapse collapse {{ $key === 0 ? 'show' : '' }}" aria-labelledby="section-{{ $key }}-heading" data-bs-parent="#courseContentAccordion">
                                        <div class="accordion-body p-0">
                                            <ul class="list-group list-group-flush">
                                                @forelse($module->lessons as $lesson)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                                        <div>
                                                            <i class="
                                                                @if ($lesson->type === 'video') fas fa-play-circle text-primary
                                                                @elseif ($lesson->type === 'quiz') fas fa-question-circle text-warning 
                                                                @elseif ($lesson->type === 'exercise') fas fa-file-alt text-success
                                                                @else fas fa-file text-secondary
                                                                @endif me-2"></i>
                                                            {{ $lesson->title }}
                                                            @if ($lesson->is_free)
                                                                <span class="badge bg-info ms-2">Aperçu</span>
                                                            @endif
                                                        </div>
                                                        <div class="text-muted">{{ $lesson->duration_minutes ?? 10 }}:00</div>
                                                    </li>
                                                @empty
                                                    <li class="list-group-item">Ce module n'a pas encore de leçons</li>
                                                @endforelse
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center">
                                    <p class="mb-0">Contenu en cours de développement</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                
                <!-- Prérequis -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h2 class="h4 mb-0">Prérequis</h2>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            @php
                                // Utiliser les prérequis du cours ou des valeurs par défaut
                                $requirements = $course->prerequisites ?? [
                                    'Connaissances de base en programmation',
                                    'Ordinateur avec accès à Internet',
                                    'Motivation pour apprendre et pratiquer'
                                ];
                            @endphp
                            
                            @foreach ($requirements as $requirement)
                                <li class="mb-2">{{ $requirement }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                
                <!-- Description -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h2 class="h4 mb-0">Description</h2>
                    </div>
                    <div class="card-body">
                        <div class="course-description">
                            {!! $course->full_description ??
                                '<p>Ce cours complet vous guidera pas à pas dans l\'apprentissage de cette technologie. Que vous soyez débutant ou avec une expérience préalable, vous trouverez des explications claires et des exercices pratiques pour renforcer votre compréhension.</p>
                                <p>Nous commencerons par les fondamentaux avant de progresser vers des concepts plus avancés. Chaque section comprend des projets concrets pour appliquer ce que vous apprenez.</p>
                                <p>À la fin de ce cours, vous serez capable de créer vos propres applications et d\'appliquer ces connaissances dans des projets professionnels.</p>'
                            !!}
                        </div>
                    </div>
                </div>
                
                <!-- Formateur -->
                <div class="card mb-4" id="instructor">
                    <div class="card-header bg-white">
                        <h2 class="h4 mb-0">Formateur</h2>
                    </div>
                    <div class="card-body">
                        @if($course->formateur)
                            <div class="d-flex mb-3">
                                <img src="{{ $course->formateur->profile_image ?? 'https://randomuser.me/api/portraits/men/32.jpg' }}" alt="{{ $course->formateur->first_name }} {{ $course->formateur->last_name }}" class="rounded-circle me-3" width="80" height="80">
                                <div>
                                    <a href="#" class="h5 text-decoration-none">{{ $course->formateur->first_name }} {{ $course->formateur->last_name }}</a>
                                    <div class="d-flex flex-wrap">
                                        <div class="me-3 mb-1">
                                            <i class="fas fa-star text-warning me-1"></i>
                                            {{ number_format($course->formateur->ratings_avg ?? 4.7, 1) }} Note d'instructeur
                                        </div>
                                        <div class="me-3 mb-1">
                                            <i class="fas fa-comment-alt text-muted me-1"></i>
                                            {{ $course->formateur->ratings_count ?? 120 }} Avis
                                        </div>
                                        <div class="me-3 mb-1">
                                            <i class="fas fa-user-graduate text-muted me-1"></i>
                                            {{ number_format($course->formateur->students_count ?? 850) }} Apprenants
                                        </div>
                                        <div class="mb-1">
                                            <i class="fas fa-play-circle text-muted me-1"></i>
                                            {{ $course->formateur->courses_count ?? 5 }} Cours
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p>{{ $course->formateur->bio ?? 'Formateur passionné avec plusieurs années d\'expérience dans l\'enseignement et le développement professionnel. Expert dans son domaine, il accompagne les apprenants dans leur parcours de compétences.' }}</p>
                        @else
                            <div class="d-flex mb-3">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Instructeur AfriCode" class="rounded-circle me-3" width="80" height="80">
                                <div>
                                    <a href="#" class="h5 text-decoration-none">Instructeur AfriCode</a>
                                    <div class="d-flex flex-wrap">
                                        <div class="me-3 mb-1">
                                            <i class="fas fa-star text-warning me-1"></i>
                                            4.8 Note d'instructeur
                                        </div>
                                        <div class="me-3 mb-1">
                                            <i class="fas fa-comment-alt text-muted me-1"></i>
                                            120 Avis
                                        </div>
                                        <div class="me-3 mb-1">
                                            <i class="fas fa-user-graduate text-muted me-1"></i>
                                            850 Apprenants
                                        </div>
                                        <div class="mb-1">
                                            <i class="fas fa-play-circle text-muted me-1"></i>
                                            5 Cours
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p>Formateur passionné avec plusieurs années d'expérience dans l'enseignement et le développement professionnel. Expert dans son domaine, il accompagne les apprenants dans leur parcours de compétences.</p>
                        @endif
                    </div>
                </div>
                
                <!-- Avis et témoignages -->
                <div class="card mb-4" id="avis">
                    <div class="card-header bg-white">
                        <h2 class="h4 mb-0">Avis des apprenants</h2>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4 align-items-center">
                            <div class="col-md-4 text-center mb-3 mb-md-0">
                                <div class="display-4 fw-bold text-warning">{{ number_format($avgRating, 1) }}</div>
                                <div class="h5 mb-0">Note globale</div>
                                <div class="text-warning mb-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($avgRating))
                                            <i class="fas fa-star"></i>
                                        @elseif ($i - 0.5 <= $avgRating)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="text-muted">{{ $reviewsCount }} avis</div>
                            </div>
                            <div class="col-md-8">
                                <!-- Distribution des notes -->
                                @php
                                    $ratings = [
                                        5 => $course->ratings->where('rating', 5)->count(),
                                        4 => $course->ratings->where('rating', 4)->count(),
                                        3 => $course->ratings->where('rating', 3)->count(),
                                        2 => $course->ratings->where('rating', 2)->count(),
                                        1 => $course->ratings->where('rating', 1)->count()
                                    ];
                                    
                                    // Calculer les pourcentages
                                    $totalRatings = array_sum($ratings);
                                    if ($totalRatings > 0) {
                                        foreach ($ratings as $star => $count) {
                                            $ratings[$star] = round(($count / $totalRatings) * 100);
                                        }
                                    } else {
                                        $ratings = [
                                            5 => 78,
                                            4 => 15,
                                            3 => 5,
                                            2 => 1,
                                            1 => 1
                                        ];
                                    }
                                @endphp
                                
                                @foreach ($ratings as $star => $percentage)
                                    <div class="d-flex align-items-center mb-1">
                                        <div style="width: 40px" class="me-3">{{ $star }} <i class="fas fa-star text-warning"></i></div>
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="ms-3" style="width: 40px;">{{ $percentage }}%</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <hr>
                        
                        <!-- Témoignages -->
                        @php
                            // Utiliser les témoignages du cours ou des témoignages par défaut
                            $testimonials = $course->testimonials ?? [
                                [
                                    'name' => 'Thomas Mensah',
                                    'avatar' => 'https://randomuser.me/api/portraits/men/32.jpg',
                                    'rating' => 5,
                                    'date' => now()->subDays(15),
                                    'comment' => 'Ce cours a dépassé mes attentes. Les explications sont claires et les exercices pratiques m\'ont vraiment aidé à comprendre les concepts.'
                                ],
                                [
                                    'name' => 'Aïcha Diallo',
                                    'avatar' => 'https://randomuser.me/api/portraits/women/44.jpg',
                                    'rating' => 5,
                                    'date' => now()->subDays(30),
                                    'comment' => 'Excellente formation ! J\'ai pu appliquer immédiatement ce que j\'ai appris dans mes projets professionnels. Je recommande vivement.'
                                ],
                                [
                                    'name' => 'Paul Kouassi',
                                    'avatar' => 'https://randomuser.me/api/portraits/men/67.jpg',
                                    'rating' => 4,
                                    'date' => now()->subDays(45),
                                    'comment' => 'Très bon cours, bien structuré avec une progression logique. J\'aurais aimé un peu plus d\'exemples concrets, mais dans l\'ensemble c\'est excellent.'
                                ]
                            ];
                        @endphp
                        
                        @forelse($testimonials as $testimonial)
                            <div class="mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div class="d-flex mb-3">
                                    <img src="{{ $testimonial['avatar'] ?? 'https://randomuser.me/api/portraits/men/'. rand(1, 99) .'.jpg' }}" alt="{{ $testimonial['name'] ?? 'Utilisateur' }}" class="rounded-circle me-3" width="50" height="50">
                                    <div>
                                        <div class="fw-bold">{{ $testimonial['name'] ?? 'Anonyme' }}</div>
                                        <div class="d-flex">
                                            <div class="text-warning me-2">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= ($testimonial['rating'] ?? 5))
                                                        <i class="fas fa-star"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <div class="text-muted small">
                                                @if (isset($testimonial['date']) && is_object($testimonial['date']) && method_exists($testimonial['date'], 'format'))
                                                    {{ $testimonial['date']->format('d M Y') }}
                                                @elseif (isset($testimonial['date']) && is_string($testimonial['date']))
                                                    {{ $testimonial['date'] }}
                                                @else
                                                    {{ now()->subDays(rand(5, 60))->format('d M Y') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-0">{{ $testimonial['comment'] ?? 'Ce cours a dépassé mes attentes. Les explications sont claires et les exercices pratiques m\'ont vraiment aidé à comprendre les concepts.' }}</p>
                            </div>
                        @empty
                            <p class="text-center">Aucun témoignage pour le moment.</p>
                        @endforelse
                        
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-outline-primary">Voir tous les avis</a>
                        </div>
                    </div>
                </div>
                
                <!-- FAQ -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h2 class="h4 mb-0">Questions fréquemment posées</h2>
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="faqAccordion">
                            @php
                                // Utiliser les FAQ du cours ou des FAQ par défaut
                                $faqs = $course->faq ?? [
                                    [
                                        'question' => 'Ce cours est-il adapté aux débutants ?',
                                        'answer' => 'Oui, ce cours est conçu pour être accessible aux débutants tout en offrant également du contenu avancé pour les apprenants plus expérimentés.'
                                    ],
                                    [
                                        'question' => 'Combien de temps ai-je accès au cours ?',
                                        'answer' => 'Une fois inscrit, vous avez un accès à vie au cours, y compris toutes les mises à jour futures du contenu.'
                                    ],
                                    [
                                        'question' => 'Y a-t-il un certificat à la fin du cours ?',
                                        'answer' => 'Oui, vous recevrez un certificat d\'achèvement une fois que vous aurez terminé tous les modules du cours.'
                                    ],
                                    [
                                        'question' => 'Comment puis-je obtenir de l\'aide si je suis bloqué ?',
                                        'answer' => 'Vous pouvez poser vos questions dans la section commentaires de chaque leçon. L\'instructeur et la communauté d\'apprenants sont là pour vous aider.'
                                    ]
                                ];
                            @endphp
                            
                            @foreach ($faqs as $key => $faq)
                                <div class="accordion-item">
                                    <h3 class="accordion-header" id="faq-{{ $key }}-heading">
                                        <button class="accordion-button {{ $key > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faq-{{ $key }}-content" aria-expanded="{{ $key === 0 ? 'true' : 'false' }}" aria-controls="faq-{{ $key }}-content">
                                            {{ $faq['question'] ?? $faq['q'] }}
                                        </button>
                                    </h3>
                                    <div id="faq-{{ $key }}-content" class="accordion-collapse collapse {{ $key === 0 ? 'show' : '' }}" aria-labelledby="faq-{{ $key }}-heading" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            {{ $faq['answer'] ?? $faq['a'] }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar avec boîte d'achat -->
            <div class="col-lg-4">
                <div class="position-sticky" style="top: 20px;">
                    <div class="card course-sidebar mb-4">
                        <div class="card-body p-4">
                            <div class="course-price mb-3">
                                <div class="d-flex align-items-center">
                                    @php
                                        $originalPrice = $course->price * 4;
                                        $discountPercentage = 75;
                                    @endphp
                                    
                                    @if($course->price > 0)
                                        <h3 class="mb-0">{{ number_format($course->price, 2) }} €</h3>
                                        <span class="text-decoration-line-through ms-2 text-muted">{{ number_format($originalPrice, 2) }} €</span>
                                        <span class="ms-2 text-danger fw-bold">{{ $discountPercentage }}% de réduction</span>
                                    @else
                                        <h3 class="mb-0 text-success">Gratuit</h3>
                                    @endif
                                </div>
                                <div class="small text-danger mt-1">
                                    <i class="fas fa-clock me-1"></i> Promotion se termine dans 
                                    <span id="countdown">2 jours</span>
                                </div>
                            </div>
                            
                            <div class="action-buttons">
                                <button type="button" class="btn btn-primary w-100 py-3 mb-2">
                                    <i class="fas fa-cart-plus me-2"></i> Acheter maintenant
                                </button>
                                <button type="button" class="btn btn-outline-dark w-100 py-3 mb-3">
                                    <i class="far fa-heart me-2"></i> Ajouter à la liste de souhaits
                                </button>
                            </div>
                            
                            <div class="course-guarantees text-center">
                                <p class="mb-1 small text-center">Garantie de remboursement de 30 jours</p>
                                <p class="mb-3 small text-center">Accès à vie à la formation</p>
                            </div>
                            
                            <hr>
                            
                            <div class="course-includes mb-3">
                                <h4 class="h6 fw-bold mb-2">Ce cours comprend :</h4>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2"><i class="fas fa-play-circle me-2"></i> {{ $course->getEstimatedDuration() ?? '10h' }} de vidéo à la demande</li>
                                    <li class="mb-2"><i class="fas fa-file me-2"></i> {{ $course->modules->sum(function($module) { return $module->lessons->count(); }) }} articles et ressources</li>
                                    <li class="mb-2"><i class="fas fa-download me-2"></i> {{ rand(5, 15) }} ressources téléchargeables</li>
                                    <li class="mb-2"><i class="fas fa-code me-2"></i> {{ rand(3, 10) }} exercices pratiques</li>
                                    <li class="mb-2"><i class="fas fa-mobile-alt me-2"></i> Accès mobile et TV</li>
                                    <li class="mb-2"><i class="fas fa-certificate me-2"></i> Certificat d'achèvement</li>
                                    <li class="mb-0"><i class="fas fa-comment-alt me-2"></i> Forum de discussion</li>
                                </ul>
                            </div>
                            
                            <div class="text-center">
                                <a href="#" class="text-decoration-none">Code promo?</a>
                            </div>
                            
                            <hr>
                            
                            <div class="share-course text-center">
                                <p class="mb-2">Partagez ce cours</p>
                                <div class="social-share">
                                    <a href="#" class="btn btn-sm btn-outline-dark mx-1"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-dark mx-1"><i class="fab fa-twitter"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-dark mx-1"><i class="fab fa-linkedin-in"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-dark mx-1"><i class="fab fa-whatsapp"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formations apparentées -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h3 class="h5 mb-0">Vous pourriez également aimer</h3>
                        </div>
                        <div class="card-body">
                            @php
                                // Récupérer d'autres cours de la même catégorie
                                $relatedCourses = \App\Models\Course::where('id', '!=', $course->id)
                                    ->where('status', 'published')
                                    ->where('category_id', $course->category_id ?? null)
                                    ->take(3)
                                    ->get();
                                    
                                // Si pas assez de cours liés, obtenir des cours populaires
                                if($relatedCourses->count() < 3) {
                                    $additionalCourses = \App\Models\Course::where('id', '!=', $course->id)
                                        ->where('status', 'published')
                                        ->whereNotIn('id', $relatedCourses->pluck('id')->toArray())
                                        ->take(3 - $relatedCourses->count())
                                        ->get();
                                        
                                    $relatedCourses = $relatedCourses->concat($additionalCourses);
                                }
                            @endphp
                            
                            @forelse($relatedCourses as $related)
                                <div class="d-flex mb-3 {{ !$loop->last ? 'pb-3 border-bottom' : '' }}">
                                    <img src="{{ asset($related->cover_image_path ?? 'assets/images/course-placeholder.jpg') }}" alt="{{ $related->title }}" class="me-3" width="70" height="50" style="object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <a href="{{ route('courses.show', $related->slug) }}" class="text-decoration-none text-dark small fw-bold">{{ $related->title }}</a>
                                        <div class="small text-muted">{{ $related->formateur ? $related->formateur->first_name . ' ' . $related->formateur->last_name : 'Instructeur AfriCode' }}</div>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $relatedRating = $related->ratings->avg('rating') ?? 4.5;
                                            @endphp
                                            <span class="small text-warning me-1">{{ number_format($relatedRating, 1) }}</span>
                                            <div class="text-warning small me-1">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= floor($relatedRating))
                                                        <i class="fas fa-star fa-xs"></i>
                                                    @elseif ($i - 0.5 <= $relatedRating)
                                                        <i class="fas fa-star-half-alt fa-xs"></i>
                                                    @else
                                                        <i class="far fa-star fa-xs"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                        <div class="small fw-bold">{{ $related->price > 0 ? number_format($related->price, 2) . ' €' : 'Gratuit' }}</div>
                                    </div>
                                </div>
                            @empty
                                @php
                                    $defaultRelated = [
                                        [
                                            'title' => 'Formation avancée sur les frameworks',
                                            'slug' => 'formation-avancee-frameworks',
                                            'image' => 'assets/images/th.jpeg',
                                            'instructor' => 'Marie Leclerc',
                                            'rating' => 4.7,
                                            'price' => 24.99
                                        ],
                                        [
                                            'title' => 'Maîtrisez l\'architecture MVC',
                                            'slug' => 'maitrisez-architecture-mvc',
                                            'image' => 'assets/images/th (4).jpeg',
                                            'instructor' => 'Jean Dupont',
                                            'rating' => 4.8,
                                            'price' => 29.99
                                        ],
                                        [
                                            'title' => 'Développement Full-Stack',
                                            'slug' => 'developpement-full-stack',
                                            'image' => 'assets/images/télécharger.jpeg',
                                            'instructor' => 'Ahmed Bamba',
                                            'rating' => 4.9,
                                            'price' => 34.99
                                        ]
                                    ];
                                @endphp
                                
                                @foreach($defaultRelated as $related)
                                    <div class="d-flex mb-3 {{ !$loop->last ? 'pb-3 border-bottom' : '' }}">
                                        <img src="{{ asset($related['image']) }}" alt="{{ $related['title'] }}" class="me-3" width="70" height="50" style="object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <a href="{{ route('courses.show', $related['slug']) }}" class="text-decoration-none text-dark small fw-bold">{{ $related['title'] }}</a>
                                            <div class="small text-muted">{{ $related['instructor'] }}</div>
                                            <div class="d-flex align-items-center">
                                                <span class="small text-warning me-1">{{ number_format($related['rating'], 1) }}</span>
                                                <div class="text-warning small me-1">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= floor($related['rating']))
                                                            <i class="fas fa-star fa-xs"></i>
                                                        @elseif ($i - 0.5 <= $related['rating'])
                                                            <i class="fas fa-star-half-alt fa-xs"></i>
                                                        @else
                                                            <i class="far fa-star fa-xs"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                            <div class="small fw-bold">{{ number_format($related['price'], 2) }} €</div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .course-header {
        padding-top: 60px;
        position: relative;
    }
    
    .play-button:hover {
        background-color: #0b5ed7;
        transform: scale(1.1);
        transition: all 0.3s;
    }
    
    .accordion-item {
        border-left: none;
        border-right: none;
    }
    
    .accordion-button:not(.collapsed) {
        color: #000;
        background-color: #f8f9fa;
        box-shadow: none;
    }
    
    .course-sidebar {
        border-top: 5px solid #0d6efd;
    }
    
    .social-share a:hover {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }
    
    /* Badges */
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    /* Description collapse */
    .course-description {
        max-height: none;
        overflow: visible;
    }
    
    /* Améliore le style des boutons d'accordéon */
    .accordion-button {
        background: linear-gradient(135deg, #1EA38B 0%, #27B371 100%) !important;
        color: white !important;
        font-weight: 600 !important;
        border: none !important;
        padding: 1.25rem 1.5rem !important;
        box-shadow: var(--africode-shadow-sm) !important;
    }

    .accordion-button:not(.collapsed) {
        background: linear-gradient(135deg, #17896E 0%, #229A63 100%) !important;
        color: white !important;
        box-shadow: var(--africode-shadow-md) !important;
    }

    .accordion-button:hover {
        background: linear-gradient(135deg, #17896E 0%, #229A63 100%) !important;
        color: white !important;
        transform: translateY(-1px) !important;
        box-shadow: var(--africode-shadow-lg) !important;
    }

    .accordion-button:focus {
        box-shadow: 0 0 0 0.25rem rgba(30, 163, 139, 0.25) !important;
        border-color: rgba(0,0,0,.125);
    }

    .accordion-item {
        border: 1px solid rgba(30, 163, 139, 0.2) !important;
        border-radius: var(--africode-border-radius) !important;
        margin-bottom: 0.75rem !important;
        overflow: hidden !important;
        box-shadow: var(--africode-shadow-sm) !important;
    }

    .accordion-body {
        background: rgba(30, 163, 139, 0.02) !important;
        border-top: 1px solid rgba(30, 163, 139, 0.1) !important;
    }

    .list-group-item {
        background: white !important;
        border-color: rgba(30, 163, 139, 0.1) !important;
        padding: 1rem 1.5rem !important;
        transition: var(--africode-transition) !important;
    }

    .list-group-item:hover {
        background: rgba(30, 163, 139, 0.05) !important;
        transform: translateX(4px) !important;
    }
    
    /* Pour le sticky sidebar sur desktop */
    @media (min-width: 992px) {
        .course-sidebar {
            position: sticky;
            top: 20px;
        }
    }
    
    /* Animation de l'image d'arrière-plan sur hover */
    .bg-dark.rounded:hover img {
        transform: scale(1.05);
        transition: transform 0.3s ease;
    }
    
    /* Style pour les options de section avec hover */
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush

@push('scripts')
<script>
    // Animation du compteur à rebours
    document.addEventListener('DOMContentLoaded', function() {
        // Date de fin (2 jours à partir d'aujourd'hui)
        const endDate = new Date();
        endDate.setDate(endDate.getDate() + 2);
        
        function updateCountdown() {
            const now = new Date();
            const diff = endDate - now;
            
            // Si la promotion est terminée
            if (diff <= 0) {
                document.getElementById('countdown').textContent = "Expirée";
                return;
            }
            
            // Calcul des jours, heures, minutes
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            
            // Affichage du temps restant
            let timeText = "";
            if (days > 0) {
                timeText = `${days} jour${days > 1 ? 's' : ''}`;
            } else if (hours > 0) {
                timeText = `${hours} heure${hours > 1 ? 's' : ''}`;
            } else {
                timeText = `${minutes} minute${minutes > 1 ? 's' : ''}`;
            }
            
            document.getElementById('countdown').textContent = timeText;
        }
        
        // Mettre à jour toutes les minutes
        updateCountdown();
        setInterval(updateCountdown, 60000);
    });
</script>
@endpush