<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use App\Models\Category;

class AdvancedSearch extends Component
{
    public $query = '';
    public $type = 'all'; // all, courses, lessons, users
    public $filters = [
        'category' => null,
        'level' => null,
        'price' => null,
        'role' => null,
    ];
    public $results = [];
    public $categories = [];
    public $levels = [];
    public $suggestions = [];

    public function mount()
    {
        $this->categories = Category::pluck('name')->toArray();
        $this->levels = \App\Models\Course::distinct()->pluck('level')->toArray();
    }

    public function updatedQuery()
    {
        $this->search();
        $this->updateSuggestions();
    }

    public function updatedType()
    {
        $this->search();
    }

    public function updatedFilters()
    {
        $this->search();
    }

    public function updateSuggestions()
    {
        if (strlen($this->query) >= 2) {
            $this->suggestions = \App\Models\Course::search($this->query)->take(5)->get();
        } else {
            $this->suggestions = [];
        }
    }

    public function search()
    {
        if (strlen($this->query) < 2) {
            $this->results = [];
            return;
        }
        $results = collect();
        if ($this->type === 'all' || $this->type === 'courses') {
            $courses = Course::search($this->query)
                ->when($this->filters['category'], fn($q) => $q->where('category', $this->filters['category']))
                ->when($this->filters['level'], fn($q) => $q->where('level', $this->filters['level']))
                ->when($this->filters['price'], function($q) {
                    if ($this->filters['price'] === 'free') return $q->where('price', 0);
                    if ($this->filters['price'] === 'paid') return $q->where('price', '>', 0);
                })
                ->take(10)->get();
            $results = $results->concat($courses);
        }
        if ($this->type === 'all' || $this->type === 'lessons') {
            $lessons = Lesson::search($this->query)
                ->take(10)->get();
            $results = $results->concat($lessons);
        }
        if ($this->type === 'all' || $this->type === 'users') {
            $users = User::search($this->query)
                ->when($this->filters['role'], fn($q) => $q->where('role', $this->filters['role']))
                ->take(10)->get();
            $results = $results->concat($users);
        }
        $this->results = $results;
    }

    public function render()
    {
        return view('livewire.advanced-search');
    }
} 