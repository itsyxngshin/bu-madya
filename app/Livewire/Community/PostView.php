<?php

namespace App\Livewire\Community;

use Livewire\Component;
use App\Models\Post;

class PostView extends Component
{
    public Post $post;

    public function mount($slug)
    {
        // Fetch the post with its author and category, or throw a 404 if it doesn't exist
        $this->post = Post::with(['author', 'category'])
                          ->where('slug', $slug)
                          ->where('is_published', true)
                          ->firstOrFail();
    }

    public function deletePost($postId = null)
    {
        // Use the passed ID from the modal, or fallback to the mounted post ID
        $idToTarget = $postId ?? $this->post->id;
        $postTarget = \App\Models\Post::findOrFail($idToTarget);

        // Security check
        $isOwnerOrAdmin = auth()->check() && (
            auth()->id() == $postTarget->user_id || 
            in_array(auth()->user()->role->role_name ?? '', ['administrator', 'director'])
        );

        if (!$isOwnerOrAdmin) {
            abort(403, 'Unauthorized action.');
        }

        $postTarget->delete();

        // Redirect back to the feed since this page no longer exists!
        return redirect()->route('community.feed')->with('success', 'Post permanently deleted.');
    }

    public function render()
    {
        return view('livewire.community.post-view')
            ->layout('layouts.madya-community');
    }
}
