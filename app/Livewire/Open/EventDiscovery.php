<?php

namespace App\Livewire\Open;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Event;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')]
class EventDiscovery extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'upcoming'; // 'upcoming', 'past', or 'all'

    // Reset pagination when someone types in the search bar or changes the filter
    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilter() { $this->resetPage(); }

    public function render()
    {
        // 1. Only show Published events and Eager Load the Organizer
        $query = Event::with('organizer')->where('is_active', true);

        // 2. Apply Search
        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%')
                  ->orWhereHas('organizer', function($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
        }

        // 3. Apply Time Filter
        if ($this->filter === 'upcoming') {
            $query->where('start_date', '>=', now()->startOfDay())
                  ->orderBy('start_date', 'asc'); // Soonest events first
        } elseif ($this->filter === 'past') {
            $query->where('start_date', '<', now()->startOfDay())
                  ->orderBy('start_date', 'desc'); // Most recent past events first
        } else {
            $query->orderBy('start_date', 'desc');
        }

        return view('livewire.open.event-discovery', [
            'events' => $query->paginate(12)
        ]);
    }
}