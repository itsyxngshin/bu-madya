<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Event;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')]
class EventRaffle extends Component
{
    public Event $event;
    public $winners = []; // Stores the IDs of people who already won
    public $winnerList = []; // Stores rich data to display the winner list

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    // This method is called by Alpine.js after the visual spinning finishes
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
    }

    public function render()
    {
        // Fetch ONLY people who have checked in, EXCLUDING previous winners
        $eligibleAttendees = $this->event->registrations()
            ->where('status', 'Attended')
            ->whereNotIn('id', $this->winners)
            ->get(['id', 'name', 'classification', 'ticket_code']);

        return view('livewire.admin.event-raffle', [
            'eligibleAttendees' => $eligibleAttendees
        ]);
    }
}
