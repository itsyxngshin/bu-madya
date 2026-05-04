<?php

namespace App\Livewire\Community;

use Livewire\Component;
use App\Models\Post;

class SearchBar extends Component
{
    public $query = '';

    public function render()
    {
        $results = [];

        // Only search if they have typed at least 2 characters
        if (strlen($this->query) >= 2) {
            $results = Post::where('is_published', true)
                ->where(function($q) {
                    $q->where('title', 'like', '%' . $this->query . '%')
                      ->orWhere('content', 'like', '%' . $this->query . '%');
                })
                ->latest()
                ->take(5) // Limit to top 5 results for the dropdown
                ->get();
        }

        return view('livewire.community.search-bar', [
            'results' => $results
        ]);
    }
}