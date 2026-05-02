<?php

namespace App\Livewire\Community;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

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

    public function deletePost($postId)
    {
        $post = \App\Models\Post::findOrFail($postId);

        // Security check: Only the author or an Admin/Director can delete it
        $isOwnerOrAdmin = auth()->check() && (
            auth()->id() == $post->user_id || 
            in_array(auth()->user()->role->role_name ?? '', ['administrator', 'director'])
        );

        if (!$isOwnerOrAdmin) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the post (this cascades to delete comments/reactions too)
        $post->delete();

        // Flash a success message
        session()->flash('success', 'Post permanently deleted.');
    }

    public function clearCategory()
    {
        $this->activeCategoryId = null;
        $this->resetPage();
    }

    public function render()
    {
        $favoriteCategoryId = 0;

        // 1. Calculate Personal Affinity (If logged in)
        if (auth()->check()) {
            // Cache this calculation for 12 hours so it doesn't slow down the feed
            $favoriteCategoryId = Cache::remember('user_fav_cat_'.auth()->id(), 43200, function() {
                return Post::whereHas('comments', fn($q) => $q->where('user_id', auth()->id()))
                    ->select('category_id')
                    ->selectRaw('COUNT(category_id) as interactions')
                    ->groupBy('category_id')
                    ->orderByDesc('interactions')
                    ->value('category_id') ?? 0; // Default to 0 if no interactions
            });
        }

        // 2. Fetch the Algorithmic Feed
        $posts = Post::query()
            ->with(['author', 'category', 'elements'])
            ->where('is_published', true) // Assuming you have a published state
            ->when($this->activeCategoryId, function ($query) {
                // If they click a filter pill, respect that filter
                $query->where('category_id', $this->activeCategoryId);
            })
            ->when(!$this->activeCategoryId, function ($query) use ($favoriteCategoryId) {
                // Algorithmic Sorting (Only applies when viewing "All Updates")
                // Adds a massive 15-point boost if the post matches their favorite category
                $query->orderByRaw("
                    popularity_score + 
                    (CASE WHEN category_id = ? THEN 15 ELSE 0 END) 
                    DESC
                ", [$favoriteCategoryId]);
            })
            // Tie-breaker: Always fall back to newest posts if scores are exactly equal
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.community.post-feed', [
            'posts' => $posts,
            'categories' => Category::orderBy('name')->get()
        ])->layout('layouts.madya-community');
    }
}
