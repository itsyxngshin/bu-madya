<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Event;
use App\Models\EventRegistration;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')]
class EventScanner extends Component
{
    public Event $event;

    // Status properties to show UI feedback
    public $scanStatus = null; // 'success', 'warning', 'error'
    public $scanMessage = '';
    public $lastScannedName = '';

    // Manual entry fallback
    public $manualCode = '';

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    public function processScan($ticketCode)
    {
        // 1. Find the registration for this specific event
        $registration = EventRegistration::where('event_id', $this->event->id)
            ->where('ticket_code', $ticketCode)
            ->first();

        // 2. State: Invalid Ticket (Not found)
        if (!$registration) {
            $this->scanStatus = 'error';
            $this->scanMessage = 'Invalid Ticket Code or wrong event.';
            $this->lastScannedName = '';

            // Play error sound via frontend event
            $this->dispatch('play-sound', type: 'error');
            return;
        }

        // 3. State: Already Checked In
        if ($registration->status === 'Attended') {
            $this->scanStatus = 'warning';
            $this->scanMessage = 'Already Checked In!';
            $this->lastScannedName = $registration->name;

            $this->dispatch('play-sound', type: 'warning');
            return;
        }

        // 4. State: Success! Mark as attended
        $registration->update(['status' => 'Attended']);

        $this->scanStatus = 'success';
        $this->scanMessage = 'Successfully Checked In!';
        $this->lastScannedName = $registration->name;

        $this->dispatch('play-sound', type: 'success');

        // Reset manual code if used
        $this->manualCode = '';
    }

    public function manualCheckIn()
    {
        $this->validate(['manualCode' => 'required|string']);
        $this->processScan(strtoupper($this->manualCode));
    }

    public function getStatsProperty()
    {
        $total = $this->event->registrations()->count();
        $attended = $this->event->registrations()->where('status', 'Attended')->count();

        return [
            'total' => $total,
            'attended' => $attended,
            'percentage' => $total > 0 ? round(($attended / $total) * 100) : 0
        ];
    }

    public function render()
    {
        return view('livewire.admin.event-scanner');
    }
}
