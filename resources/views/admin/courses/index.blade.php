@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="page-header d-flex justify-content-between align-items-center">
        <h1 class="h3">Gestion des cours</h1>
        <div>
            <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Nouveau cours
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="dashboard-card mb-4">
        <form action="{{ route('admin.courses.index') }}" method="GET">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="search" class="form-label">Recherche</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Titre, description..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="category" class="form-label">Catégorie</label>
                    <select name="category" id="category" class="form-select">
                        <option value="">Toutes</option>
                        @foreach(\App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="status" class="form-label">Statut</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Tous</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Publié</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Brouillon</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="sort" class="form-label">Trier par</label>
                    <select name="sort" id="sort" class="form-select">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récent</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Plus ancien</option>
                        <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Titre (A-Z)</option>
                        <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Titre (Z-A)</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix (croissant)</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix (décroissant)</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end mb-3">
                    <button type="submit" class="btn btn-primary me-2">Filtrer</button>
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Courses Table -->
    <div class="dashboard-card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Titre</th>
                        <th>Formateur</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Niveau</th>
                        <th>Statut</th>
                        <th>Inscriptions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($courses as $course)
                        <tr>
                            <td>{{ $course->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($course->cover_image_path)
                                        <img src="{{ asset('storage/' . $course->cover_image_path) }}" alt="Course" class="rounded me-2" style="width: 48px; height: 32px; object-fit: cover;">
                                    @else
                                        <div class="rounded bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width: 48px; height: 32px;">
                                            <i class="fas fa-book"></i>
                                        </div>
                                    @endif
                                    <a href="{{ route('admin.courses.show', $course) }}">
                                        {{ $course->title }}
                                    </a>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.users.show', $course->formateur) }}">
                                    {{ $course->formateur->first_name }} {{ $course->formateur->last_name }}
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $course->category->name ?? 'Non catégorisé' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $course->price > 0 ? 'success' : 'warning' }}">
                                    {{ $course->price > 0 ? number_format($course->price, 0, ',', ' ') . ' XOF' : 'Gratuit' }}
                                </span>
                            </td>
                            <td>
                                @if($course->level === 'beginner')
                                    <span class="badge bg-info">Débutant</span>
                                @elseif($course->level === 'intermediate')
                                    <span class="badge bg-primary">Intermédiaire</span>
                                @elseif($course->level === 'advanced')
                                    <span class="badge bg-danger">Avancé</span>
                                @endif
                            </td>
                            <td>
                                @if($course->status === 'published')
                                    <span class="badge bg-success">Publié</span>
                                @else
                                    <span class="badge bg-secondary">Brouillon</span>
                                @endif
                            </td>
                            <td>
                                {{ $course->enrollments_count ?? 0 }}
                            </td>
                            <td>
                                <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(($course->enrollments_count ?? 0) == 0)
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCourse{{ $course->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteCourse{{ $course->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Supprimer le cours</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Êtes-vous sûr de vouloir supprimer ce cours ?</p>
                                                    <p class="fw-bold">{{ $course->title }}</p>
                                                    <p class="text-danger">Cette action supprimera également tous les modules, leçons et quiz associés à ce cours.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('admin.courses.destroy', $course) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <button type="button" class="btn btn-sm btn-danger" disabled title="Impossible de supprimer un cours avec des inscriptions">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">Aucun cours trouvé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            {{ $courses->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
