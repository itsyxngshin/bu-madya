<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class CommunityManager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = ''; // 'published' or 'draft'
    public $categoryFilter = '';

    // Reset pagination when searching/filtering
    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingCategoryFilter() { $this->resetPage(); }

    public function togglePublish(Post $post)
    {
        $post->update([
            'is_published' => !$post->is_published,
            'published_at' => !$post->is_published ? now() : null // Reset date if newly published
        ]);
        
        // If unpublishing, also un-feature it automatically
        if (!$post->is_published) {
            $post->update(['is_featured' => false]);
        }
    }

    public function toggleFeature(Post $post)
    {
        // Only published posts can be featured
        if ($post->is_published) {
            $post->update(['is_featured' => !$post->is_featured]);
        } else {
            $this->dispatch('notify', ['message' => 'You must publish the post before featuring it.', 'type' => 'error']);
        }
    }

    public function deletePost(Post $post)
    {
        // Cleanup storage to save server space
        if ($post->cover_image_path) {
            Storage::disk('public')->delete($post->cover_image_path);
        }
        
        if (!empty($post->gallery)) {
            foreach ($post->gallery as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        $post->delete();
        $this->dispatch('notify', ['message' => 'Post permanently deleted.', 'type' => 'success']);
    }

    public function clearFlag(Post $post)
    {
        $post->update([
            'is_flagged' => false,
            'flagged_words' => null,
            'is_published' => true, // Auto-publish upon admin approval
            'published_at' => now()
        ]);
        $this->dispatch('notify', ['message' => 'Flag cleared. Post is now published.', 'type' => 'success']);
    }

    public function render()
    {
        $query = Post::with(['author', 'category'])->withCount(['comments', 'elements']);

        // 1. Apply Search
        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%')
                  ->orWhereHas('author', function ($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
        }

        // 2. Apply Status Filter
        if ($this->statusFilter === 'flagged') {
            $query->where('is_flagged', true);
        } elseif ($this->statusFilter === 'published') {
            $query->where('is_published', true)->where('is_flagged', false);
        } elseif ($this->statusFilter === 'draft') {
            $query->where('is_published', false)->where('is_flagged', false);
        }

        // 3. Apply Category Filter
        if ($this->categoryFilter) {
            $query->where('category_id', $this->categoryFilter);
        }

        $posts = $query->latest()->paginate(10);

        return view('livewire.admin.community-manager', [
            'posts' => $posts,
            'categories' => Category::orderBy('name')->get(),
            
            // Quick Stats for the header
            'totalPosts' => Post::count(),
            'publishedPosts' => Post::where('is_published', true)->count(),
            'featuredPosts' => Post::where('is_featured', true)->count(),
        ])->layout('layouts.madya-admin-deck'); // Assuming your admin layout
    }
}