<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Course;
use App\Models\Category;
use Livewire\WithPagination;

class CoursesFilterSearch extends Component
{
    use WithPagination;

    // Attributs de recherche et filtrage
    public $search = '';
    public $selectedCategory = null;
    public $priceRange = 'all'; // all, free, paid
    public $minPrice = null;
    public $maxPrice = null;
    public $level = 'all'; // all, beginner, intermediate, advanced
    public $sort = 'latest'; // latest, oldest, price_asc, price_desc, rating
    public $instructorId = null;

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

    public function mount()
    {
        // Chargement des catégories pour le filtre
        $this->categories = Category::whereHas('courses', function ($query) {
            $query->where('status', 'published')->where('published_at', '<=', now());
        })->orderBy('name')->get();
    }

    public function toggleAdvancedFilters()
    {
        $this->showAdvancedFilters = !$this->showAdvancedFilters;
    }

    // Réinitialiser tous les filtres
    public function resetFilters()
    {
        $this->reset(['search', 'selectedCategory', 'priceRange', 'minPrice', 'maxPrice', 'level', 'sort', 'instructorId']);
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
