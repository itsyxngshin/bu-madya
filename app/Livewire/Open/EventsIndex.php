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
    public $filter = 'upcoming'; // Options: 'upcoming', 'past', 'all'
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

    // Reset pagination when search/filter changes
    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilter() { $this->resetPage(); }

    public function render()
    {
        $query = Event::where('is_active', true);

        // 1. Search Logic
        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        // 2. Filter Logic
        switch ($this->filter) {
            case 'upcoming':
                // Shows events that haven't ended yet (or have no end date)
                $query->where(function($q) {
                    $q->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
                })->orderBy('start_date', 'asc'); // Soonest first
                break;

            case 'past':
                $query->where('end_date', '<', now())
                      ->orderBy('start_date', 'desc'); // Most recent past first
                break;
            
            default: // 'all'
                $query->orderBy('start_date', 'desc');
                break;
        }

        return view('livewire.open.events-index', [
            'events' => $query->paginate(9)
        ]); // Use your main layout
    }
}