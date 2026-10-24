<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Event;
use Livewire\Attributes\Layout;

class EventRaffle extends Component
{
    public Event $event;
    public $winners = [];
    public $winnerList = [];
    public $eligibleAttendees = [];

    // [NEW] Toggle state for the Raffle Mode
    public $raffleMode = 'slot'; // Options: 'slot' or 'wheel'

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->updatePool();
    }

    // [NEW] Switch modes safely
    public function switchMode($mode)
    {
        if (in_array($mode, ['slot', 'wheel'])) {
            $this->raffleMode = $mode;
            $this->dispatch('raffle-mode-changed', mode: $mode);
        }
    }

    public function recordWinner($winnerId)
    {
        if (!in_array($winnerId, $this->winners)) {
            $this->winners[] = $winnerId;

            $winnerRecord = $this->event->registrations()->find($winnerId);
            if ($winnerRecord) {
                array_unshift($this->winnerList, [
                    'name' => $winnerRecord->name,
                    'classification' => $winnerRecord->classification,
                    'ticket' => $winnerRecord->ticket_code
                ]);
            }

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

        $this->winnerList = array_values($this->winnerList);
    }

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
        $layoutFile = in_array(auth()->user()->role?->role_name, ['administrator', 'organization'])
            ? 'layouts.madya-admin-deck'
            : 'layouts.madya-admin';

        return view('livewire.admin.event-raffle')->layout($layoutFile);
    }
}
