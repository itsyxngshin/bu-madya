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

    public function render()
    {
        return view('livewire.community.post-view')
            ->layout('layouts.madya-community');
    }
}
