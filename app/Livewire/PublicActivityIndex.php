<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Activity;
use App\Models\SiteStat;

class PublicActivityIndex extends Component
{
    use WithPagination;

    public $search = '';
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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $activities = Activity::with(['user', 'sdgs', 'focals'])
            ->where('status', '!=', 'draft') // Optional: if you add a draft status later
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('nature_of_activity', 'like', '%' . $this->search . '%');
            })
            ->orderBy('start_date', 'desc')
            ->paginate(12);

        return view('livewire.public-activity-index', [
            'activities' => $activities
        ])->layout('layouts.madya-template'); // Ensure this matches your public layout
    }
}
