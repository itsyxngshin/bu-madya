<?php

namespace App\Livewire\Community;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;
use App\Models\Category;

class PostFeed extends Component
{
    use WithPagination;

    public $activeCategoryId = null;

    // Reset pagination when the category filter changes
    public function setCategory($categoryId)
    {
        $this->activeCategoryId = $categoryId;
        $this->resetPage();
    }

    public function clearCategory()
    {
        $this->activeCategoryId = null;
        $this->resetPage();
    }

    public function render()
    {
        // Eager load author, category, and the counts of elements/comments
        $query = Post::with(['author', 'category'])
                     ->withCount(['elements', 'comments'])
                     ->where('is_published', true);

        // Apply category filter if one is selected
        if ($this->activeCategoryId) {
            $query->where('category_id', $this->activeCategoryId);
        }

        // Order by featured first, then newest
        $posts = $query->orderByDesc('is_featured')
                       ->orderByDesc('published_at')
                       ->paginate(12);

        return view('livewire.community.post-feed', [
            'posts' => $posts,
            'categories' => Category::orderBy('name')->get(),
        ])->layout('layouts.madya-template');
    }
}
