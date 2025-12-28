<?php

namespace App\Livewire\Open;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use App\Models\News;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class LandingPage extends Component
{
    public $visitorCount = 1;
    public function mount()
    {
        $this->visitorCount = Cache::increment('website_visitor_count');
    }

    public function render()
    {
        // Fetch 2 latest published articles for the grid
        $latestNews = News::where('status', 'Published')
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        return view('livewire.open.landing-page', [
            'latestNews' => $latestNews
        ]); // Assuming a guest layout for landing
    }
}

