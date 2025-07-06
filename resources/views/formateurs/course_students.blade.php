@extends('formateurs.layouts.app')

@section('title', 'AfriCode - Étudiants inscrits')
@section('page-title', $course->title)
@section('page-subtitle', 'Liste des étudiants inscrits (' . $enrollments->count() . ' étudiants)')

@section('header-actions')
    <a href="{{ route('formateur.manage.course', $course) }}" class="btn btn-outline-secondary me-2">
        <i class="fas fa-arrow-left me-2"></i>Retour au cours
    </a>
    <button class="btn-secondary-africode" data-bs-toggle="modal" data-bs-target="#exportModal">
        <i class="fas fa-download me-2"></i>Exporter
    </button>
@endsection

@section('styles')
<style>
    .card {
        overflow: hidden;
    }
    
    .student-search {
        margin-bottom: 1.5rem;
    }
    
    .avatar-sm {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .progress-bar {
        height: 8px;
        border-radius: 4px;
    }
    
    .student-item:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .filters {
        margin-bottom: 1.5rem;
    }
</style>
@endsection

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('formateur.dashboard') }}">Tableau de bord</a></li>
            <li class="breadcrumb-item"><a href="{{ route('formateur.manage.course', ['courseId' => $course->id]) }}">{{ $course->title }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Étudiants inscrits</li>
        </ol>
    </nav>
    
    <!-- Course Info Header -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-1">{{ $course->title }}</h5>
                    <p class="text-muted mb-0">{{ $enrollments->total() }} étudiants inscrits</p>
                </div>
                <div>
                    <a href="{{ route('formateur.manage.course', ['courseId' => $course->id]) }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-1"></i> Retour au cours
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="row">
        <div class="col-md-8">
            <form action="{{ route('formateur.courses.students', ['courseId' => $course->id]) }}" method="GET" class="student-search">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Rechercher un étudiant..." name="search" value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="col-md-4">
            <div class="filters d-flex justify-content-end">
                <select class="form-select" name="sort" onchange="this.form.submit()">
                    <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Plus récents</option>
                    <option value="progress-high" {{ request('sort') == 'progress-high' ? 'selected' : '' }}>Progression élevée</option>
                    <option value="progress-low" {{ request('sort') == 'progress-low' ? 'selected' : '' }}>Progression faible</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nom (A-Z)</option>
                </select>
            </div>
        </div>
    </div>
    
    <!-- Students List -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Étudiant</th>
                            <th scope="col">Date d'inscription</th>
                            <th scope="col">Progression</th>
                            <th scope="col">Dernière activité</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($enrollments as $index => $enrollment)
                            <tr class="student-item">
                                <th scope="row">{{ $enrollments->firstItem() + $index }}</th>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $enrollment->user->profile_image_path ? asset($enrollment->user->profile_image_path) : 'https://via.placeholder.com/40' }}" 
                                             alt="{{ $enrollment->user->first_name }}" 
                                             class="avatar-sm me-3">
                                        <div>
                                            <h6 class="mb-0">{{ $enrollment->user->first_name }} {{ $enrollment->user->last_name }}</h6>
                                            <small class="text-muted">{{ $enrollment->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $enrollment->enrolled_at->format('d/m/Y') }}</td>
                                <td>
                                    @php
                                        // Calculer le pourcentage de progression
                                        $totalItems = $course->modules->reduce(function ($count, $module) {
                                            return $count + $module->lessons->count() + $module->quizzes->count();
                                        }, 0);
                                        
                                        $completedItems = $enrollment->user->completedLessons()
                                            ->whereHas('lesson', function($query) use ($course) {
                                                $query->whereHas('module', function($q) use ($course) {
                                                    $q->where('course_id', $course->id);
                                                });
                                            })
                                            ->count();
                                            
                                        $progressPercentage = $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0;
                                    @endphp
                                    
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $progressPercentage }}%;" aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span>{{ $progressPercentage }}%</span>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $lastActivity = $enrollment->user->lastActivity($course->id);
                                    @endphp
                                    
                                    @if($lastActivity)
                                        {{ $lastActivity->created_at->diffForHumans() }}
                                    @else
                                        <span class="text-muted">Aucune activité</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#"><i class="fas fa-envelope me-1"></i> Envoyer un message</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="fas fa-chart-line me-1"></i> Voir le progrès détaillé</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><button class="dropdown-item text-danger" onclick="confirmRemoveStudent({{ $enrollment->id }})"><i class="fas fa-user-minus me-1"></i> Retirer du cours</button></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="py-5">
                                        <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                                        <p class="mb-1">Aucun étudiant inscrit à ce cours.</p>
                                        <p class="text-muted">Les étudiants apparaîtront ici une fois qu'ils se seront inscrits.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $enrollments->links() }}
    </div>

    <!-- Remove Student Confirmation Modal -->
    <div class="modal fade" id="removeStudentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmer le retrait</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir retirer cet étudiant du cours ? Cette action ne peut pas être annulée.</p>
                </div>
                <div class="modal-footer">
                    <form id="removeStudentForm" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">Retirer l'étudiant</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function confirmRemoveStudent(enrollmentId) {
        const form = document.getElementById('removeStudentForm');
        form.action = `/formateur/enrollments/${enrollmentId}/delete`;
        
        const modal = new bootstrap.Modal(document.getElementById('removeStudentModal'));
        modal.show();
    }
</script>
@endsection
