<?php 


namespace App\Livewire\Open;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Event;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')]
class EventsIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'upcoming'; // Options: 'upcoming', 'past', 'all'

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