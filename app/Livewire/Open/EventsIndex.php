<?php

namespace App\Livewire\Open;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Event;
use Livewire\Attributes\Layout;
use App\Models\SiteStat;
use Illuminate\Support\Facades\Session;

#[Layout('layouts.madya-template')]
class EventsIndex extends Component
{
    use WithPagination;

    public $search = '';
    // Change the default filter to 'all' so everything loads immediately
    public $filter = 'all';
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

        // 4. Retrieve the current total
        $this->visitorCount = SiteStat::where('key', 'visitor_count')->value('value');
    }

    // Reset pagination when search/filter changes
    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilter() { $this->resetPage(); }

    public function render()
    {
        $query = Event::where('is_active', true);

        // 1. Search Logic
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // 2. Filter & Smart Sorting Logic
        switch ($this->filter) {
            case 'upcoming':
                // Shows events that haven't ended yet
                $query->where(function($q) {
                    $q->whereNull('end_date')
                      ->orWhere('end_date', '>=', now()->startOfDay());
                })->orderBy('start_date', 'asc'); // Nearest upcoming first
                break;

            case 'past':
                $query->whereNotNull('end_date')
                      ->where('end_date', '<', now()->startOfDay())
                      ->orderBy('start_date', 'desc'); // Most recent past first
                break;

            default: // 'all'
                // 'ALL' FILTER (Default):
                // Puts active/upcoming events at the TOP (0), and past events at the BOTTOM (1)
                $query->orderByRaw("CASE WHEN end_date >= ? OR end_date IS NULL THEN 0 ELSE 1 END ASC", [now()->startOfDay()])
                      // Then sort by start date (newest first) within those two groups
                      ->orderBy('start_date', 'desc');
                break;
        }

        return view('livewire.open.events-index', [
            'events' => $query->paginate(9)
        ]);
    }
}
