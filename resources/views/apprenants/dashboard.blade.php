
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord | AfriCode</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3461FF;
            --secondary-color: #FF8B34;
            --light-bg: #f8f9fa;
            --border-radius: 10px;
        }
        
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            background: linear-gradient(180deg, #3461FF 0%, #2B4BC9 100%);
            color: white;
            min-height: 100vh;
            padding-top: 2rem;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 0.5rem;
            border-radius: 6px;
            padding: 10px 15px;
            font-size: 0.95rem;
        }
        
        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }
        
        .sidebar .nav-link.active {
            background-color: #ffffff;
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .sidebar .logo {
            color: white;
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 2rem;
            padding-left: 15px;
        }
        
        .main-content {
            padding: 2rem;
        }
        
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
            transition: transform 0.2s;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
            border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
        }
        
        .progress {
            height: 10px;
            border-radius: 5px;
        }
        
        .badge-custom {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: normal;
            font-size: 0.8rem;
        }
        
        .badge-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .badge-warning {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .avatar-lg {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .challenge-card {
            border-left: 4px solid var(--primary-color);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .course-img {
            height: 160px;
            object-fit: cover;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            padding: 0.25rem 0.5rem;
        }
        
        .stat-card {
            padding: 1.5rem;
            display: flex;
            align-items: center;
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.5rem;
        }
        
        .stat-primary {
            background-color: rgba(52, 97, 255, 0.1);
            color: var(--primary-color);
        }
        
        .stat-secondary {
            background-color: rgba(255, 139, 52, 0.1);
            color: var(--secondary-color);
        }
        
        .stat-success {
            background-color: rgba(21, 196, 83, 0.1);
            color: #15c453;
        }
        
        .stat-info {
            background-color: rgba(13, 202, 240, 0.1);
            color: #0dcaf0;
        }
        
        .nav-pills .nav-link.active {
            background-color: var(--primary-color);
        }
        
        .top-nav {
            height: 60px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="logo">
                    <i class="fas fa-code me-2"></i> AfriCode
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('apprenant.dashboard') }}">
                            <i class="fas fa-home me-2"></i> Tableau de bord
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('courses.index') }}">
                            <i class="fas fa-book me-2"></i> Catalogue de cours
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('apprenant.profile') }}">
                            <i class="fas fa-user me-2"></i> Mon profil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pages.forumapp') }}">
                            <i class="fas fa-users me-2"></i> Communauté
                        </a>
                    </li>
                    <li class="nav-item mt-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-light btn-sm w-100">
                                <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Top Navigation -->
                <div class="d-flex justify-content-between align-items-center py-3 top-nav mb-4">
                    <h4 class="m-0">Tableau de Bord</h4>
                    <div class="d-flex align-items-center">
                        <div class="position-relative me-3">
                            <i class="fas fa-bell fs-5"></i>
                            <span class="badge rounded-pill bg-danger notification-badge">{{ $certifications->count() }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <img src="{{ Auth::user()->profile_image_path ? asset(Auth::user()->profile_image_path) : asset('assets/images/default-avatar.png') }}" alt="User Avatar" class="avatar me-2">
                            <div class="dropdown">
                                <a class="dropdown-toggle text-decoration-none text-dark" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="d-none d-md-inline">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="{{ route('apprenant.profile') }}"><i class="fas fa-user me-2"></i> Mon profil</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}" class="dropdown-item p-0">
                                            @csrf
                                            <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i> Déconnexion</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile & Progress Overview -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <img src="{{ Auth::user()->profile_image_path ? asset(Auth::user()->profile_image_path) : asset('assets/images/default-avatar.png') }}" alt="Profile Picture" class="avatar-lg mb-3">
                                <h5>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h5>
                                <p class="text-muted">{{ Auth::user()->title ?? 'Apprenant' }}</p>
                                <div class="d-flex justify-content-center mb-3">
                                    @php
                                        // Calcul du niveau basé sur le nombre de cours complétés (à ajuster selon votre logique)
                                        $completedCourses = $enrollments->where('completed_at', '!=', null)->count();
                                        $level = max(1, min(10, ceil($completedCourses / 2)));
                                        
                                        // XP fictifs basés sur le nombre de leçons complétées (à ajuster selon votre logique)
                                        $xp = $completedCourses * 100 + $certifications->count() * 250;
                                    @endphp
                                    <span class="badge badge-custom badge-primary me-2">Niveau {{ $level }}</span>
                                    <span class="badge badge-custom badge-warning">{{ $xp }} XP</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <small>Progression globale</small>
                                    @php
                                        $avgProgress = $enrollments->isEmpty() 
                                            ? 0 
                                            : $enrollments->avg('progress_percentage');
                                    @endphp
                                    <small>{{ round($avgProgress) }}%</small>
                                </div>
                                <div class="progress mb-4">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $avgProgress }}%" aria-valuenow="{{ $avgProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <a href="{{ route('apprenant.profile') }}" class="btn btn-outline-primary btn-sm">Modifier le profil</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>Mes formations en cours</span>
                                <a href="#" class="text-decoration-none">Voir tout</a>
                            </div>
                            <div class="card-body">
                                @forelse($enrollments as $enrollment)
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <h6 class="mb-0">{{ $enrollment->course->title }}</h6>
                                            <small class="text-muted">{{ $enrollment->course->modules->count() }} modules - {{ $enrollment->course->getLessonsCount() }} leçons</small>
                                        </div>
                                        <span class="badge badge-custom badge-primary">{{ round($enrollment->progress_percentage) }}%</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $enrollment->progress_percentage }}%" aria-valuenow="{{ $enrollment->progress_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-4">
                                    <i class="fas fa-book-open fs-1 text-muted mb-3"></i>
                                    <p>Vous n'êtes actuellement inscrit à aucun cours.</p>
                                    <a href="{{ route('courses.index') }}" class="btn btn-primary btn-sm">Découvrir les cours</a>
                                </div>
                                @endforelse
                            </div>
                            @if($enrollments->isNotEmpty())
                            <div class="card-footer bg-white">
                                <a href="{{ route('apprenant.course.access', ['courseId' => $enrollments->first()->course->id]) }}" class="btn btn-primary btn-sm">Continuer ma formation</a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Statistics Row -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="stat-icon stat-primary">
                                <i class="fas fa-book"></i>
                            </div>
                            <div>
                                <p class="mb-0 text-muted">Cours en cours</p>
                                <h3 class="mb-0">{{ $enrollments->where('completed_at', null)->count() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="stat-icon stat-secondary">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <div>
                                <p class="mb-0 text-muted">Certifications</p>
                                <h3 class="mb-0">{{ $certifications->count() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="stat-icon stat-success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                <p class="mb-0 text-muted">Cours complétés</p>
                                <h3 class="mb-0">{{ $enrollments->where('completed_at', '!=', null)->count() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="stat-icon stat-info">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div>
                                <p class="mb-0 text-muted">Progression moyenne</p>
                                <h3 class="mb-0">{{ $enrollments->avg('progress_percentage') > 0 ? round($enrollments->avg('progress_percentage')) : 0 }}%</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Challenges and Certifications -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>Défis en cours</span>
                                <button class="btn btn-sm btn-outline-primary">Nouveau défi</button>
                            </div>
                            <div class="card-body">
                                <div class="challenge-card p-3 mb-3 border-start border-primary border-3 rounded">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">Créer une page responsive avec Bootstrap</h6>
                                            <p class="text-muted mb-0 small">Défi quotidien • 100 XP</p>
                                        </div>
                                        <span class="badge bg-success">Facile</span>
                                    </div>
                                    <div class="mt-2">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small>Progression</small>
                                            <small>2/5 tâches</small>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="challenge-card p-3 mb-3 border-start border-warning border-3 rounded">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">Développer un jeu de quiz en JavaScript</h6>
                                            <p class="text-muted mb-0 small">Défi hebdomadaire • 250 XP</p>
                                        </div>
                                        <span class="badge bg-warning">Intermédiaire</span>
                                    </div>
                                    <div class="mt-2">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small>Progression</small>
                                            <small>3/8 tâches</small>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 37.5%" aria-valuenow="37.5" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="challenge-card p-3 border-start border-danger border-3 rounded">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">Intégrer une API REST avec JavaScript</h6>
                                            <p class="text-muted mb-0 small">Projet de groupe • 500 XP</p>
                                        </div>
                                        <span class="badge bg-danger">Avancé</span>
                                    </div>
                                    <div class="mt-2">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small>Progression</small>
                                            <small>1/10 tâches</small>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <span>Mes certifications</span>
                            </div>
                            <div class="card-body">
                                @forelse($certifications as $certification)
                                <div class="d-flex align-items-center mb-3 p-2 bg-light rounded">
                                    <i class="fas fa-certificate text-primary me-3 fs-4"></i>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $certification->course->title }}</h6>
                                        <small class="text-muted">Obtenue le {{ $certification->issue_date->format('d/m/Y') }}</small>
                                    </div>
                                    <a href="{{ route('apprenant.certification.download', ['certificationId' => $certification->id]) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                                @empty
                                <div class="text-center py-4">
                                    <i class="fas fa-certificate fs-1 text-muted mb-3"></i>
                                    <p>Vous n'avez pas encore obtenu de certification.</p>
                                    <p class="small text-muted">Complétez un cours à 100% pour obtenir une certification.</p>
                                </div>
                                @endforelse
                                
                                @if($enrollments->isNotEmpty() && $enrollments->where('progress_percentage', '>=', 50)->where('progress_percentage', '<', 100)->isNotEmpty())
                                <div class="mt-4">
                                    <h6>Certifications à venir</h6>
                                    @foreach($enrollments->where('progress_percentage', '>=', 50)->where('progress_percentage', '<', 100)->take(2) as $nearCompletion)
                                    <div class="d-flex align-items-center p-2 bg-light rounded opacity-75 mb-2">
                                        <i class="fas fa-certificate text-secondary me-3 fs-4"></i>
                                        <div class="w-100">
                                            <h6 class="mb-0">{{ $nearCompletion->course->title }}</h6>
                                            <div class="progress mt-1" style="height: 5px;">
                                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $nearCompletion->progress_percentage }}%" aria-valuenow="{{ $nearCompletion->progress_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small class="text-muted">{{ round($nearCompletion->progress_percentage) }}% complété</small>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Community and Recommended Courses -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>Communauté</span>
                                <a href="#" class="text-decoration-none">Voir tout</a>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-start">
                                            <img src="https://via.placeholder.com/40" alt="User Avatar" class="avatar me-3">
                                            <div>
                                                <h6 class="mb-1">Ahmed Diallo</h6>
                                                <p class="mb-1 small">J'ai besoin d'aide avec la mise en page flexbox. Quelqu'un peut m'aider ?</p>
                                                <small class="text-muted">Il y a 35 minutes • 3 réponses</small>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-start">
                                            <img src="https://via.placeholder.com/40" alt="User Avatar" class="avatar me-3">
                                            <div>
                                                <h6 class="mb-1">Fatima Nkosi</h6>
                                                <p class="mb-1 small">Je viens de terminer le défi JavaScript ! Voici mon projet : [lien]</p>
                                                <small class="text-muted">Il y a 2 heures • 8 réponses</small>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-start">
                                            <img src="https://via.placeholder.com/40" alt="User Avatar" class="avatar me-3">
                                            <div>
                                                <h6 class="mb-1">David Okafor</h6>
                                                <p class="mb-1 small">Webinaire sur React.js prévu pour demain à 18h. N'oubliez pas de vous inscrire !</p>
                                                <small class="text-muted">Il y a 5 heures • 12 réponses</small>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer bg-white">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Poster un message...">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <span>Formations recommandées</span>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100">
                                            <img src="https://via.placeholder.com/300x160?text=ReactJS" class="course-img" alt="ReactJS Course">
                                            <div class="card-body">
                                                <h6>ReactJS : Développement front-end moderne</h6>
                                                <p class="text-muted small">4 semaines • 30 €</p>
                                                <div class="d-grid">
                                                    <button class="btn btn-sm btn-outline-primary">Voir les détails</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100">
                                            <img src="https://via.placeholder.com/300x160?text=PHP+MySQL" class="course-img" alt="PHP MySQL Course">
                                            <div class="card-body">
                                                <h6>PHP et MySQL : Programmation côté serveur</h6>
                                                <p class="text-muted small">6 semaines • 35 €</p>
                                                <div class="d-grid">
                                                    <button class="btn btn-sm btn-outline-primary">Voir les détails</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white text-center">
                                <a href="#" class="btn btn-outline-primary btn-sm">Explorer le catalogue</a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>