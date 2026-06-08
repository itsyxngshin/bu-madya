<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Activity;
use App\Models\SiteStat;
use Illuminate\Support\Facades\Session;

class PublicActivityShow extends Component
{
    public $activity;
    public $visitorCount = 1;

    public function mount($slug)
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

        $this->activity = Activity::with(['user', 'sdgs', 'focals', 'participants'])
                                  ->where('slug', $slug)
                                  ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.public-activity-show')->layout('layouts.madya-template');
    }
}
