@extends('layouts.mentorship')

@section('title', 'Découvrir les Mentors - AfriCode')

@section('content')
<div class="container mt-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1" style="color: #0ab285;">
                        <i class="fas fa-user-tie text-primary me-2"></i>
                        Découvrir les Mentors
                    </h1>
                    <p class="text-muted mb-0">Trouvez le mentor parfait pour vous accompagner dans votre apprentissage</p>
                </div>
                <a href="{{ route('mentorship.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('mentorship.mentors') }}" class="row g-3" id="searchForm">
                        <div class="col-md-4">
                            <label for="search" class="form-label fw-bold">
                                <i class="fas fa-search me-2 text-primary"></i>Rechercher
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-user text-muted"></i>
                                </span>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Nom, prénom ou email du mentor..."
                                       autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="specialization" class="form-label fw-bold">
                                <i class="fas fa-filter me-2 text-primary"></i>Spécialisation
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-code text-muted"></i>
                                </span>
                                <select class="form-select" id="specialization" name="specialization">
                                    <option value="">Toutes les spécialisations</option>
                                    <option value="Développement Web Frontend" {{ request('specialization') == 'Développement Web Frontend' ? 'selected' : '' }}>Développement Web Frontend</option>
                                    <option value="Développement Web Backend" {{ request('specialization') == 'Développement Web Backend' ? 'selected' : '' }}>Développement Web Backend</option>
                                    <option value="Développement Mobile" {{ request('specialization') == 'Développement Mobile' ? 'selected' : '' }}>Développement Mobile</option>
                                    <option value="Data Science" {{ request('specialization') == 'Data Science' ? 'selected' : '' }}>Data Science</option>
                                    <option value="Machine Learning" {{ request('specialization') == 'Machine Learning' ? 'selected' : '' }}>Machine Learning</option>
                                    <option value="DevOps" {{ request('specialization') == 'DevOps' ? 'selected' : '' }}>DevOps</option>
                                    <option value="Cybersécurité" {{ request('specialization') == 'Cybersécurité' ? 'selected' : '' }}>Cybersécurité</option>
                                    <option value="UI/UX Design" {{ request('specialization') == 'UI/UX Design' ? 'selected' : '' }}>UI/UX Design</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="sort" class="form-label fw-bold">
                                <i class="fas fa-sort me-2 text-primary"></i>Trier par
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-sort-amount-down text-muted"></i>
                                </span>
                                <select class="form-select" id="sort" name="sort">
                                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nom (A-Z)</option>
                                    <option value="courses" {{ request('sort') == 'courses' ? 'selected' : '' }}>Nombre de cours</option>
                                    <option value="mentorships" {{ request('sort') == 'mentorships' ? 'selected' : '' }}>Mentorats actifs</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>Rechercher
                                </button>
                                <a href="{{ route('mentorship.mentors') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Effacer les filtres
                                </a>
                                @if(request()->hasAny(['search', 'specialization', 'sort']))
                                    <span class="badge bg-info align-self-center ms-2">
                                        {{ $mentors->total() }} résultat(s) trouvé(s)
                                    </span>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Skeleton mentors (pendant soumission formulaire) -->
    <div id="mentors-skeleton" class="row" style="display:none;">
        @for($sk = 0; $sk < 6; $sk++)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="skeleton-mentor-card">
                <div class="sk sk-avatar-lg sk-center"></div>
                <div class="sk sk-title sk-center" style="width:60%; margin-bottom:.4rem;"></div>
                <div class="sk sk-line sk-center" style="width:45%; margin-bottom:.3rem;"></div>
                <div class="sk sk-stars"></div>
                <div class="sk sk-line" style="width:100%; margin:.75rem 0 .3rem;"></div>
                <div class="sk sk-line" style="width:80%; margin-bottom:.75rem;"></div>
                <div class="sk-row" style="justify-content:center; margin-bottom:.75rem; gap:1.5rem;">
                    <div style="display:flex; flex-direction:column; align-items:center; gap:.3rem;">
                        <div class="sk sk-line" style="width:28px;"></div>
                        <div class="sk sk-line-sm" style="width:36px;"></div>
                    </div>
                    <div style="display:flex; flex-direction:column; align-items:center; gap:.3rem;">
                        <div class="sk sk-line" style="width:28px;"></div>
                        <div class="sk sk-line-sm" style="width:36px;"></div>
                    </div>
                </div>
                <div class="sk sk-btn"></div>
            </div>
        </div>
        @endfor
    </div>

    <!-- Mentors Grid -->
    <div id="mentors-grid" class="row">
        @if($mentors->count() > 0)
            @foreach($mentors as $mentor)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card mentor-card h-100">
                        <div class="card-body">
                            <!-- Mentor Avatar and Info -->
                            <div class="text-center mb-3">
                                <div class="mentor-avatar mx-auto mb-3">
                                    {{ substr($mentor->first_name, 0, 1) }}{{ substr($mentor->last_name, 0, 1) }}
                                </div>
                                <h5 class="card-title mb-1">{{ $mentor->first_name }} {{ $mentor->last_name }}</h5>
                                <p class="text-muted small mb-2">{{ $mentor->email }}</p>
                                
                                <!-- Rating -->
                                <div class="rating mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= 4 ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                    <span class="small text-muted ms-1">(4.0)</span>
                                </div>
                            </div>

                            <!-- Bio -->
                            <p class="card-text text-muted small mb-3">
                                {{ Str::limit($mentor->bio ?? 'Formateur expérimenté passionné par l\'enseignement des technologies modernes.', 120) }}
                            </p>

                            <!-- Stats -->
                            <div class="row text-center mb-3">
                                <div class="col-6">
                                    <div class="stat-item">
                                        <h6 class="mb-0 text-primary">{{ $mentor->coursesInstructed ? $mentor->coursesInstructed->count() : 0 }}</h6>
                                        <small class="text-muted">Cours</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-item">
                                        <h6 class="mb-0 text-success">{{ $mentor->mentorships ? $mentor->mentorships->where('status', 'active')->count() : 0 }}</h6>
                                        <small class="text-muted">Mentorats</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Specializations -->
                            <div class="mb-3">
                                <h6 class="small fw-bold mb-2">Spécialisations :</h6>
                                <div class="specialization-tags">
                                    @php
                                        $specializations = ['Développement Web', 'JavaScript', 'Laravel', 'React'];
                                    @endphp
                                    @foreach(array_slice($specializations, 0, 3) as $spec)
                                        <span class="badge bg-light text-dark me-1 mb-1">{{ $spec }}</span>
                                    @endforeach
                                    @if(count($specializations) > 3)
                                        <span class="badge bg-light text-dark">+{{ count($specializations) - 3 }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="d-grid">
                                <a href="{{ route('mentorship.create') }}?mentor={{ $mentor->id }}" class="btn btn-primary">
                                    <i class="fas fa-user-plus me-2"></i>Demander un Mentorat
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-user-tie fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucun mentor trouvé</h5>
                    <p class="text-muted">Essayez de modifier vos critères de recherche.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->

    @if($mentors->hasPages())
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $mentors->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.mentor-card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 0.75rem;
    transition: all 0.3s ease;
}

.mentor-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.mentor-avatar {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #11c06d, #6610f2);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
}

.rating {
    font-size: 0.9rem;
}

.stat-item {
    padding: 0.5rem;
    border-radius: 0.375rem;
    background-color: #f8f9fa;
}

.specialization-tags .badge {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
}

.card-title {
    color: #212529;
    font-weight: 600;
}

.btn {
    border-radius: 0.5rem;
    font-weight: 500;
}

.form-control:focus,
.form-select:focus {
    border-color: #11c06d;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.card {
    border-radius: 0.75rem;
}

.card-body {
    padding: 1.5rem;
}

.input-group-text {
    border-right: none;
}

.form-control:focus + .input-group-text,
.form-select:focus + .input-group-text,
.form-control:focus ~ .input-group-text,
.form-select:focus ~ .input-group-text {
    border-color: #11c06d;
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

#searchForm .form-label {
    color: #495057;
    margin-bottom: 0.5rem;
}

#searchForm .input-group {
    margin-bottom: 0;
}

.badge.bg-info {
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var sortSelect = document.getElementById('sort');
    var specializationSelect = document.getElementById('specialization');
    var form = document.getElementById('searchForm');

    function submitWithSkeleton() {
        document.getElementById('mentors-grid').style.display = 'none';
        document.getElementById('mentors-skeleton').style.display = 'flex';
        document.getElementById('mentors-skeleton').style.flexWrap = 'wrap';
        form.submit();
    }

    if (sortSelect) sortSelect.addEventListener('change', submitWithSkeleton);
    if (specializationSelect) specializationSelect.addEventListener('change', submitWithSkeleton);

    if (form) {
        form.addEventListener('submit', function() {
            document.getElementById('mentors-grid').style.display = 'none';
            document.getElementById('mentors-skeleton').style.display = 'flex';
            document.getElementById('mentors-skeleton').style.flexWrap = 'wrap';
        });
    }

    // Animation reveal sur les cartes existantes
    document.querySelectorAll('#mentors-grid .col-lg-4').forEach(function(el, i) {
        el.style.opacity = '0';
        el.style.transform = 'translateY(12px)';
        el.style.transition = 'opacity .35s ease, transform .35s ease';
        setTimeout(function() {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, 80 + i * 60);
    });
});
</script>
@endsection
