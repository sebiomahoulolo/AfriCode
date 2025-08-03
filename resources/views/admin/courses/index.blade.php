@extends('admin.layouts.app')

@section('breadcrumb', 'Gestion des cours')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="admin-card" data-aos="fade-up">
        <div class="admin-card-header">
            <div>
                <h1 class="admin-card-title" style="font-size: 1.5rem; margin-bottom: 0.5rem;">Gestion des cours</h1>
                <p class="admin-card-subtitle">Gérer tous les cours de la plateforme</p>
            </div>
            <div class="admin-card-actions">
                <a href="{{ route('admin.courses.create') }}" style="background: linear-gradient(135deg, #1EA38B, #27B371); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.3s;">
                    <i class="fas fa-plus-circle"></i> Nouveau cours
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="admin-grid admin-grid-4" style="margin-bottom: 2rem;">
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="100">
            <div class="admin-stats-icon">
                <i class="fas fa-book"></i>
            </div>
            <div class="admin-stats-number">{{ $stats['total'] ?? 0 }}</div>
            <div class="admin-stats-label">Total des cours</div>
        </div>
        
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="200">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #FF8E2A, #FFB366);">
                <i class="fas fa-eye"></i>
            </div>
            <div class="admin-stats-number">{{ $stats['published'] ?? 0 }}</div>
            <div class="admin-stats-label">Cours publiés</div>
        </div>
        
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="300">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #E32D31, #FF6B6B);">
                <i class="fas fa-edit"></i>
            </div>
            <div class="admin-stats-number">{{ $stats['draft'] ?? 0 }}</div>
            <div class="admin-stats-label">Brouillons</div>
        </div>
        
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="400">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #6C757D, #95A5A6);">
                <i class="fas fa-users"></i>
            </div>
            <div class="admin-stats-number">{{ $stats['enrollments'] ?? 0 }}</div>
            <div class="admin-stats-label">Inscriptions</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="admin-card" data-aos="fade-up" data-aos-delay="100" style="margin-bottom: 2rem;">
        <div class="admin-card-header">
            <h3 class="admin-card-title">Filtres de recherche</h3>
        </div>
        <div class="admin-card-body">
            <form action="{{ route('admin.courses.index') }}" method="GET">
                <div class="admin-grid admin-grid-4" style="gap: 1rem;">
                    <div>
                        <label for="search" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Recherche</label>
                        <input type="text" name="search" id="search" placeholder="Titre, description..." value="{{ request('search') }}" style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; transition: all 0.3s;">
                    </div>
                    <div>
                        <label for="category" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Catégorie</label>
                        <select name="category" id="category" style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; transition: all 0.3s;">
                            <option value="">Toutes</option>
                            @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="status" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Statut</label>
                        <select name="status" id="status" style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; transition: all 0.3s;">
                            <option value="">Tous</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Publié</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Brouillon</option>
                        </select>
                    </div>
                    <div>
                        <label for="sort" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Trier par</label>
                        <select name="sort" id="sort" style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; transition: all 0.3s;">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récent</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Plus ancien</option>
                            <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Titre (A-Z)</option>
                            <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Titre (Z-A)</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix (croissant)</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix (décroissant)</option>
                        </select>
                    </div>
                </div>
                <div style="margin-top: 1.5rem; display: flex; gap: 1rem;">
                    <button type="submit" style="background: linear-gradient(135deg, #1EA38B, #27B371); color: white; padding: 0.75rem 2rem; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.3s;">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                    <a href="{{ route('admin.courses.index') }}" style="background: #6C757D; color: white; padding: 0.75rem 2rem; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.3s;">
                        <i class="fas fa-times"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Courses Grid -->
    <div class="admin-grid admin-grid-3" data-aos="fade-up" data-aos-delay="200">
        @forelse ($courses as $course)
            <div class="admin-card" style="overflow: hidden;">
                @if($course->cover_image_path)
                    <div style="height: 180px; background-image: url('{{ asset($course->cover_image_path) }}'); background-size: cover; background-position: center; position: relative;">
                        <div style="position: absolute; top: 1rem; right: 1rem;">
                            @if ($course->status === 'published')
                                <span style="background: rgba(39, 179, 113, 0.9); color: white; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600; backdrop-filter: blur(10px);">
                                    <i class="fas fa-eye"></i> Publié
                                </span>
                            @else
                                <span style="background: rgba(108, 117, 125, 0.9); color: white; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600; backdrop-filter: blur(10px);">
                                    <i class="fas fa-edit"></i> Brouillon
                                </span>
                            @endif
                        </div>
                    </div>
                @else
                    <div style="height: 180px; background: linear-gradient(135deg, #1EA38B, #27B371); display: flex; align-items: center; justify-content: center; position: relative;">
                        <i class="fas fa-book" style="font-size: 3rem; color: white; opacity: 0.3;"></i>
                        <div style="position: absolute; top: 1rem; right: 1rem;">
                            @if ($course->status === 'published')
                                <span style="background: rgba(39, 179, 113, 0.9); color: white; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600; backdrop-filter: blur(10px);">
                                    <i class="fas fa-eye"></i> Publié
                                </span>
                            @else
                                <span style="background: rgba(108, 117, 125, 0.9); color: white; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600; backdrop-filter: blur(10px);">
                                    <i class="fas fa-edit"></i> Brouillon
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
                
                <div style="padding: 1.5rem;">
                    <div style="margin-bottom: 1rem;">
                        @if($course->category)
                            <span style="background: linear-gradient(135deg, rgba(255, 142, 42, 0.1), rgba(255, 179, 102, 0.1)); color: #FF8E2A; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600;">
                                {{ $course->category->name }}
                            </span>
                        @endif
                    </div>
                    
                    <h4 style="font-size: 1.1rem; font-weight: 600; color: #333; margin-bottom: 0.5rem; line-height: 1.4;">
                        {{ Str::limit($course->title, 50) }}
                    </h4>
                    
                    <p style="color: #6C757D; font-size: 0.9rem; margin-bottom: 1rem; line-height: 1.5;">
                        {{ Str::limit($course->short_description, 80) }}
                    </p>
                    
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #1EA38B, #27B371); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.8rem;">
                            {{ strtoupper(substr($course->formateur->first_name, 0, 1)) }}{{ strtoupper(substr($course->formateur->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight: 500; color: #333; font-size: 0.9rem;">{{ $course->formateur->first_name }} {{ $course->formateur->last_name }}</div>
                            <div style="font-size: 0.8rem; color: #6C757D;">{{ $course->created_at->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <div style="font-weight: 600; color: #1EA38B; font-size: 1.1rem;">
                            @if($course->price > 0)
                                {{ number_format($course->price, 0, ',', ' ') }} €
                            @else
                                Gratuit
                            @endif
                        </div>
                        <div style="font-size: 0.8rem; color: #6C757D;">
                            <i class="fas fa-users"></i> {{ $course->enrollments->count() }} inscrit(s)
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="{{ route('admin.courses.show', $course) }}" style="flex: 1; background: linear-gradient(135deg, #1EA38B, #27B371); color: white; padding: 0.5rem; border-radius: 6px; text-decoration: none; text-align: center; font-size: 0.9rem; font-weight: 500; transition: all 0.3s;">
                            <i class="fas fa-eye"></i> Voir
                        </a>
                        <a href="{{ route('admin.courses.edit', $course) }}" style="flex: 1; background: linear-gradient(135deg, #FF8E2A, #FFB366); color: white; padding: 0.5rem; border-radius: 6px; text-decoration: none; text-align: center; font-size: 0.9rem; font-weight: 500; transition: all 0.3s;">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <button onclick="confirmAdminAction('Êtes-vous sûr de vouloir supprimer ce cours ?', () => document.getElementById('delete-course-{{ $course->id }}').submit())" style="background: linear-gradient(135deg, #E32D31, #FF6B6B); color: white; padding: 0.5rem 0.75rem; border: none; border-radius: 6px; cursor: pointer; font-size: 0.9rem; transition: all 0.3s;">
                            <i class="fas fa-trash"></i>
                        </button>
                        <form id="delete-course-{{ $course->id }}" action="{{ route('admin.courses.destroy', $course) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; color: #6C757D;">
                <i class="fas fa-book" style="font-size: 4rem; margin-bottom: 1.5rem; opacity: 0.3;"></i>
                <div style="font-size: 1.2rem; font-weight: 500; margin-bottom: 0.5rem;">Aucun cours trouvé</div>
                <div style="font-size: 1rem; margin-bottom: 2rem;">Commencez par créer votre premier cours.</div>
                <a href="{{ route('admin.courses.create') }}" style="background: linear-gradient(135deg, #1EA38B, #27B371); color: white; padding: 1rem 2rem; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.3s;">
                    <i class="fas fa-plus-circle"></i> Créer un cours
                </a>
            </div>
        @endforelse
    </div>
    
    @if($courses->hasPages())
        <div style="margin-top: 2rem; display: flex; justify-content: center;" data-aos="fade-up" data-aos-delay="300">
            {{ $courses->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Hover effects pour les cartes de cours
        document.querySelectorAll('.admin-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.15)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 0 10px rgba(0, 0, 0, 0.05)';
            });
        });
        
        // Hover effects pour les boutons
        document.querySelectorAll('a[style*="background: linear-gradient"], button[style*="background: linear-gradient"]').forEach(element => {
            element.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
            });
            
            element.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });
    });
</script>
@endpush

