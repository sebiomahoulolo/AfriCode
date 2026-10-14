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
                'formateur:id,first_name,last_name',
                'category:id,name,slug',
                'modules.lessons:id,module_id' // Précharger pour éviter N+1
            ])
            ->withAvg('ratings as ratings_avg_rating', 'rating')
            ->withCount([
                'ratings as ratings_count',
                'enrollments as enrollments_count',
                'modules as modules_count',
                'modules as total_lessons_count' => function($query) {
                    $query->join('lessons', 'modules.id', '=', 'lessons.module_id');
                }
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