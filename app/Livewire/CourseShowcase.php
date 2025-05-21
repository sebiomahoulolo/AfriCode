<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Course;
use Livewire\WithPagination;

class CourseShowcase extends Component
{
    use WithPagination;

    public $categories = [];
    public $selectedCategory = null;
    public $search = '';

    protected $queryString = [
        'selectedCategory' => ['except' => null, 'as' => 'cat'],
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
        $this->categories = Category::whereHas('courses', function ($query) {
            $query->where('status', 'published')->where('published_at', '<=', now());
        })->orderBy('name')->get();
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->resetPage();
    }

    public function selectAllCategories()
    {
        $this->selectedCategory = null;
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $coursesQuery = Course::query()
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->with([
                // Charger les colonnes nécessaires pour l'accesseur full_name du formateur
                'formateur:id,first_name,last_name', 
                // Vous pourriez charger la catégorie si besoin pour d'autres infos, mais ce n'est pas dans la carte
                // 'category:id,name,slug', 
            ])
            // Charger les données pour l'affichage dans la carte
            // Assurez-vous que votre modèle Course a les colonnes :
            // - title, slug, short_description (ou description si vous préférez), level
            // - price, original_price (si utilisé), cover_image_path
            // - created_at (pour le badge "Nouveau")
            // Les relations 'ratings' et 'enrollments' sont utilisées pour les withAvg/withCount
            ->withAvg('ratings as ratings_avg_rating', 'rating')
            ->withCount([
                'ratings as ratings_count',
                'enrollments as enrollments_count',
                // Si vous avez une relation pour les leçons et que vous voulez le compte :
                // 'lessons as total_lessons_count' // Nécessiterait une relation `lessons()` dans le modèle Course
            ]);

        if ($this->selectedCategory) {
            $coursesQuery->where('category_id', $this->selectedCategory);
        }

        if (!empty($this->search)) {
            $coursesQuery->where(function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('short_description', 'like', '%' . $this->search . '%');
            });
        }
        
        $courses = $coursesQuery->latest('published_at')->paginate(8);
        
        return view('livewire.course-showcase', [
            'courses' => $courses,
        ]);
    }
}