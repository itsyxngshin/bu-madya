<?php

namespace App\Livewire\Community;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

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
        $post = Post::findOrFail($postId);

        // Security Check: Only the author or an admin can delete it
        $userRole = auth()->user()->role->role_name ?? '';
        if (auth()->id() !== $post->user_id && !in_array($userRole, ['administrator', 'director'])) {
            abort(403, 'Unauthorized action.');
        }

        // Cleanup associated media to save server space
        if ($post->cover_image_path) {
            Storage::disk('public')->delete($post->cover_image_path);
        }
        
        if (!empty($post->gallery)) {
            foreach ($post->gallery as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        $post->delete();

        // Optional: If you have a toast notification listener set up
        // $this->dispatch('notify', ['message' => 'Post deleted successfully.', 'type' => 'success']);
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
        ])->layout('layouts.madya-community');
    }
}
