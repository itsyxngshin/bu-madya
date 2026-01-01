<?php

namespace App\Livewire\Open;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use App\Models\SiteStat;
use App\Models\News;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class LandingPage extends Component
{
    public $visitorCount = 1;
    public function mount()
    {
        // 1. Check if this specific user has already been counted in this session
        if (!Session::has('has_visited_site')) {
            
            // 2. Increment the database value securely
            SiteStat::where('key', 'visitor_count')->increment('value');
            
            // 3. Mark this user as counted for this browser session
            Session::put('has_visited_site', true);
        }

        // 4. Retrieve the current total (cache it briefly to reduce DB queries on high traffic)
        // We remember it for 10 minutes, or fetch directly if you want instant real-time
        $this->visitorCount = SiteStat::where('key', 'visitor_count')->value('value');
    }

    public function render()
    {
        // Fetch 2 latest published articles for the grid
        $latestNews = News::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        return view('livewire.open.landing-page', [
            'latestNews' => $latestNews
        ]); // Assuming a guest layout for landing
    }
}

