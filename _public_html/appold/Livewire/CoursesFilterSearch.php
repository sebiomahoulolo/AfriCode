<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Course;
use App\Models\Category;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class CoursesFilterSearch extends Component
{
    use WithPagination;

    // Attributs de recherche et filtrage
    public $search = '';
    public $selectedCategory = null;
    public $priceRange = 'all'; // all, free, paid
    public $minPrice = null;
    public $maxPrice = null;
    public $level = 'all'; // all, debutant, intermediaire, avance, tous_niveaux
    public $sort = 'latest'; // latest, oldest, price_asc, price_desc, rating
    public $instructorId = null;
    public $isCertifying = null; // null, true, false
    public $duration = 'all'; // all, short (< 5h), medium (5-20h), long (> 20h)

    // Variables pour les filtres avancés
    public $showAdvancedFilters = false;
    public $categories = [];

    // Pour la pagination
    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategory' => ['except' => null, 'as' => 'cat'],
        'priceRange' => ['except' => 'all'],
        'level' => ['except' => 'all'],
        'sort' => ['except' => 'latest'],
        'page' => ['except' => 1],
    ];

    public function mount($initialFilters = [])
    {
        // Chargement des catégories pour le filtre
        $this->categories = Category::whereHas('courses', function ($query) {
            $query->where('status', 'published')->where('published_at', '<=', now());
        })->orderBy('name')->get();
        
        // Prendre d'abord les paramètres de l'URL via request()
        $request = request();
        
        // Appliquer les filtres depuis l'URL ou les filtres initiaux
        $this->search = $initialFilters['search'] ?? $request->get('search', '');
        $this->selectedCategory = $initialFilters['selectedCategory'] ?? $request->get('cat');
        $this->level = $initialFilters['level'] ?? $request->get('level', 'all');
        $this->priceRange = $initialFilters['priceRange'] ?? $request->get('priceRange', 'all');
        $this->sort = $initialFilters['sort'] ?? $request->get('sort', 'latest');
        
        // Afficher les filtres avancés si on a des filtres actifs
        $this->showAdvancedFilters = !empty($this->search) || 
                                   !empty($this->selectedCategory) || 
                                   $this->level !== 'all' || 
                                   $this->priceRange !== 'all' || 
                                   $this->sort !== 'latest';
    }

    public function toggleAdvancedFilters()
    {
        $this->showAdvancedFilters = !$this->showAdvancedFilters;
    }

    // Réinitialiser tous les filtres
    public function resetFilters()
    {
        $this->reset(['search', 'selectedCategory', 'priceRange', 'minPrice', 'maxPrice', 'level', 'sort', 'instructorId', 'isCertifying', 'duration']);
        $this->resetPage();
    }

    // Méthodes pour manipuler les filtres
    public function selectCategory($categoryId)
    {
        $this->selectedCategory = ($this->selectedCategory == $categoryId) ? null : $categoryId;
        $this->resetPage();
    }

    public function setPriceRange($range)
    {
        $this->priceRange = $range;
        $this->resetPage();
    }

    public function setLevel($level)
    {
        $this->level = $level;
        $this->resetPage();
    }

    public function setSort($sort)
    {
        $this->sort = $sort;
        $this->resetPage();
    }

    // Réinitialiser la page quand la recherche change
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Debug: vérifier les valeurs des filtres
        Log::info('Filtres appliqués:', [
            'level' => $this->level,
            'search' => $this->search,
            'selectedCategory' => $this->selectedCategory,
            'priceRange' => $this->priceRange,
            'sort' => $this->sort
        ]);

        $query = Course::query()
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->with([
                'formateur:id,first_name,last_name',
                'category:id,name,slug',
            ])
            ->withAvg('ratings as ratings_avg_rating', 'rating')
            ->withCount([
                'ratings as ratings_count',
                'enrollments as enrollments_count',
            ]);

        // Appliquer les filtres
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('short_description', 'like', '%' . $this->search . '%')
                  ->orWhere('full_description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        if ($this->level !== 'all') {
            $query->where('level', $this->level);
        }

        if ($this->priceRange === 'free') {
            $query->where('price', 0);
        } elseif ($this->priceRange === 'paid') {
            $query->where('price', '>', 0);
        } elseif ($this->minPrice !== null && $this->maxPrice !== null) {
            $query->whereBetween('price', [$this->minPrice, $this->maxPrice]);
        } elseif ($this->minPrice !== null) {
            $query->where('price', '>=', $this->minPrice);
        } elseif ($this->maxPrice !== null) {
            $query->where('price', '<=', $this->maxPrice);
        }

        if ($this->instructorId) {
            $query->where('formateur_id', $this->instructorId);
        }

        if ($this->isCertifying !== null) {
            $query->where('is_certifying', $this->isCertifying);
        }

        // Appliquer le tri
        switch ($this->sort) {
            case 'oldest':
                $query->oldest('published_at');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderByDesc('ratings_avg_rating');
                break;
            case 'popular':
                $query->orderByDesc('enrollments_count');
                break;
            case 'latest':
            default:
                $query->latest('published_at');
                break;
        }

        $courses = $query->paginate(12);

        return view('livewire.courses-filter-search', [
            'courses' => $courses,
        ]);
    }
}
