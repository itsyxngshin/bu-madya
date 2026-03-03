<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Event;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')]
class EventRaffle extends Component
{
    public Event $event;
    public $winners = []; 
    public $winnerList = []; 
    
    // [FIXED] Explicitly declare this as a public property so Alpine can watch it
    public $eligibleAttendees = []; 

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->updatePool(); // Load the pool when the page opens
    }

    public function recordWinner($winnerId)
    {
        if (!in_array($winnerId, $this->winners)) {
            $this->winners[] = $winnerId;
            
            // Fetch the winner's details to display in the UI list
            $winnerRecord = $this->event->registrations()->find($winnerId);
            if ($winnerRecord) {
                // Prepend so the newest winner is at the top of the list
                array_unshift($this->winnerList, [
                    'name' => $winnerRecord->name,
                    'classification' => $winnerRecord->classification,
                    'ticket' => $winnerRecord->ticket_code
                ]);
            }

            // [FIXED] Shrink the pool immediately after someone wins
            $this->updatePool(); 
        }
    }

    public function revokeWinner($ticketCode)
    {
        foreach ($this->winnerList as $key => $winner) {
            if ($winner['ticket'] === $ticketCode) {
                unset($this->winnerList[$key]);
            }
        }
        
        // Re-index the array so Livewire's frontend loop doesn't break
        $this->winnerList = array_values($this->winnerList);
        
        // Note: We deliberately leave them in $this->winners so their ticket 
        // remains "burned" and they can't be drawn a second time!
    }

    // [FIXED] Dedicated method to fetch the latest pool of attendees
    public function updatePool()
    {
        $this->eligibleAttendees = $this->event->registrations()
            ->where('status', 'Attended')
            ->whereNotIn('id', $this->winners)
            ->get(['id', 'name', 'classification', 'ticket_code'])
            ->toArray();
    }

    public function render()
    {
        // We no longer pass data here; the public property handles it automatically
        return view('livewire.admin.event-raffle');
    }
}