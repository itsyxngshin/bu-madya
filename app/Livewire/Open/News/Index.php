<?php

namespace App\Livewire\Open\News;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\News;
use App\Models\NewsCategory;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $category = 'All Stories';

    // Reset pagination when filtering
    public function updatedSearch() { $this->resetPage(); }
    public function updatedCategory() { $this->resetPage(); }

    public function setCategory($cat)
    {
        $this->category = $cat;
    }

    public function render()
    {
        // 1. Get Featured Article (Optimized)
        $featured = null;

        if ($this->category === 'All Stories' && empty($this->search)) { 
            $featured = News::query()
                // Optimized: Count only real "likes"
                ->withCount(['votes as likes_count' => function ($query) {
                    $query->where('is_like', true);
                }])
                ->where('status', 'active')
                ->orderBy('likes_count', 'desc') 
                ->orderBy('published_at', 'desc')
                ->first();
        }

        // 2. Get the Grid Query (Optimized)
        $query = News::query()
            ->with('category') // Only load the category relationship
            // PERFORMANCE FIX: Don't load votes, just count them!
            ->withCount(['votes as votes_count' => function ($query) {
                $query->where('is_like', true);
            }])
            ->where('status', 'active');

        if ($featured) {
            $query->where('id', '!=', $featured->id);
        }

        if (!empty($this->search)) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        if ($this->category !== 'All Stories') {
            $query->whereHas('category', function ($q) {
                $q->where('name', $this->category);
            });
        }

        return view('livewire.open.news.index', [
            'featured' => $featured,
            'news' => $query->latest('published_at')->paginate(9),
            'categories' => NewsCategory::pluck('name')->prepend('All Stories'),
        ]);
    }
}
