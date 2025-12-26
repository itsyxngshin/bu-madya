<?php

namespace App\Livewire\Open\News;

use Livewire\Component;
use Livewire\Attributes\Layout; 
use App\Models\News;

#[Layout('layouts.madya-template')]
class Show extends Component
{
    // public $articleId; // <--- Remove this (not needed anymore)
    public $article;

    public function mount($slug)
    {
        // 1. Fetch Article
        // REMOVED 'author' from with() because it is a column, not a relationship.
        $this->article = News::with(['category', 'votes', 'sdgs']) 
            ->where('slug', $slug)
            ->firstOrFail();

        // 2. Security: Prevent access to drafts (unless it's the creator)
        if ($this->article->status !== 'active' && auth()->id() !== $this->article->user_id) {
            abort(404);
        }
    }

    public function render()
    {
        return view('livewire.open.news.show');
    }
}