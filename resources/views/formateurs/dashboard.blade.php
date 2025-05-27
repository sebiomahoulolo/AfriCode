@extends('formateurs.layouts.app')

@section('title', 'AfriCode - Tableau de bord formateur')

@section('content')
                
                <!-- Stats Section -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h3>{{ $courses->where('status', 'published')->count() }}</h3>
                            <p>Cours actifs</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h3>{{ $totalEnrollments }}</h3>
                            <p>Étudiants inscrits</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h3>{{ number_format($totalRevenue, 0) }} €</h3>
                            <p>Revenus totaux</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h3>{{ number_format($averageRating, 1) }}/5</h3>
                            <p>Note moyenne</p>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Courses -->
                <div class="row mb-4">
                    <div class="col-12 d-flex justify-content-between align-items-center mb-3">
                        <h5 class="m-0">Mes cours</h5>
                        <a href="{{ route('formateur.courses.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Créer un cours
                        </a>
                    </div>

                    @if($courses->isEmpty())
                    <div class="col-12">
                        <div class="alert alert-info">
                            <p class="mb-0">Vous n'avez pas encore créé de cours. Commencez dès maintenant !</p>
                        </div>
                    </div>
                    @else
                        @foreach($courses->take(3) as $course)
                        <div class="col-md-4">
                            <div class="card course-card">
                                <img src="{{ $course->cover_image_path ? asset($course->cover_image_path) : 'https://via.placeholder.com/300x180?text=' . urlencode($course->title) }}" class="card-img-top" alt="{{ $course->title }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $course->title }}</h5>
                                    <p class="card-text">{{ Str::limit($course->short_description, 60) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">{{ $course->students_count ?? 0 }} étudiants</small>
                                        @if($course->status === 'published')
                                            <span class="badge bg-success">Publié</span>
                                        @elseif($course->status === 'draft')
                                            <span class="badge bg-warning">Brouillon</span>
                                        @endif
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('formateur.manage.course', ['courseId' => $course->id]) }}" class="btn btn-sm btn-outline-primary w-100">Gérer</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @if($courses->count() > 3)
                        <div class="col-12 mt-3 text-center">
                            <a href="#" class="btn btn-link">Voir tous mes cours ({{ $courses->count() }})</a>
                        </div>
                        @endif
                    @endif
                </div>
                
                <!-- Recent Activity & Quick Stats -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Activité récente</h5>
                            </div>
                            <div class="card-body">
                                @if($recentActivities->isEmpty())
                                    <p class="text-muted">Aucune activité récente à afficher.</p>
                                @else
                                    @foreach($recentActivities as $activity)
                                        <div class="d-flex align-items-center mb-3">
                                            @if($activity['type'] === 'enrollment')
                                                <span class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user"></i>
                                                </span>
                                                <div>
                                                    <h6 class="mb-0">{{ $activity['message'] }} "{{ $activity['course']->title }}"</h6>
                                                    <small class="text-muted">{{ $activity['user']->first_name }} {{ $activity['user']->last_name }} - {{ $activity['date']->diffForHumans() }}</small>
                                                </div>
                                            @elseif($activity['type'] === 'rating')
                                                <span class="bg-warning rounded-circle text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-star"></i>
                                                </span>
                                                <div>
                                                    <h6 class="mb-0">{{ $activity['message'] }} "{{ $activity['course']->title }}"</h6>
                                                    <small class="text-muted">{{ $activity['user']->first_name }} {{ $activity['user']->last_name }} - {{ $activity['date']->diffForHumans() }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Actions rapides</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('formateur.courses.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus-circle me-2"></i> Créer un cours
                                    </a>
                                    @if($courses->isNotEmpty())
                                    <a href="{{ route('formateur.manage.course', ['courseId' => $courses->first()->id]) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-edit me-2"></i> Gérer le dernier cours
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">État de vos cours</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Cours publiés</span>
                                    <strong>{{ $courses->where('status', 'published')->count() }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>En brouillon</span>
                                    <strong>{{ $courses->where('status', 'draft')->count() }}</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Total</span>
                                    <strong>{{ $courses->count() }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
@endsection