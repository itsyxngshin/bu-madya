<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventAttendedMail;
use Livewire\Attributes\Layout;

class EventScanner extends Component
{
    public Event $event;

    // Status properties to show UI feedback
    public $scanStatus = null; // 'success', 'warning', 'error'
    public $scanMessage = '';
    public $lastScannedData = null;

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
            $detailString = '';
            if ($registration->classification === 'BU Student') {
                $detailString = ($registration->college ? $registration->college->name : 'Unknown College') . ' - ' . $registration->year_level;
            } 
            elseif (in_array($registration->classification, ['CSO/NGO Representative', 'Partner Representative'])) {
                $detailString = $registration->organization_name . ' (' . $registration->position . ')';
            }

            $this->lastScannedData = [
                'name' => $registration->name,
                'classification' => $registration->classification,
                'details' => $detailString,
                'ticket_code' => $registration->ticket_code,
            ];

            // Play error sound via frontend event
            $this->dispatch('play-sound', type: 'error');
            return;
        }

        // 3. State: Already Checked In
        if ($registration->status === 'Attended') {
            $this->scanStatus = 'warning';
            $this->scanMessage = 'Already Checked In!';
            $detailString = '';
            if ($registration->classification === 'BU Student') {
                $detailString = ($registration->college ? $registration->college->name : 'Unknown College') . ' - ' . $registration->year_level;
            } 
            elseif (in_array($registration->classification, ['CSO/NGO Representative', 'Partner Representative'])) {
                $detailString = $registration->organization_name . ' (' . $registration->position . ')';
            }

            $this->lastScannedData = [
                'name' => $registration->name,
                'classification' => $registration->classification,
                'details' => $detailString,
                'ticket_code' => $registration->ticket_code,
            ];

            $this->dispatch('play-sound', type: 'warning');
            return;
        }

        // 4. State: Success! Mark as attended
        $registration->update(['status' => 'Attended']);

        if ($registration->status !== 'Attended') {
            
            $registration->update(['status' => 'Attended']);
            
            // [NEW] Dispatch the attendance email
                try {
                    Mail::to($registration->email)->send(new EventAttendedMail($this->event, $registration));
                } catch (\Exception $e) {
                    \Log::error('Failed to send attendance email: ' . $e->getMessage());
                }

            $this->scanStatus = 'success';
            $this->scanMessage = 'Check-in Successful';
            $this->dispatch('play-sound', ['type' => 'success']);

        } else {
            // Already checked in logic...
            $this->scanStatus = 'warning';
            $this->scanMessage = 'Already Checked In';
            $this->dispatch('play-sound', ['type' => 'warning']);
        }

        $this->scanStatus = 'success';
        $this->scanMessage = 'Successfully Checked In!';
        $detailString = '';
            if ($registration->classification === 'BU Student') {
                $detailString = ($registration->college ? $registration->college->name : 'Unknown College') . ' - ' . $registration->year_level;
            } 
            elseif (in_array($registration->classification, ['CSO/NGO Representative', 'Partner Representative'])) {
                $detailString = $registration->organization_name . ' (' . $registration->position . ')';
            }

            $this->lastScannedData = [
                'name' => $registration->name,
                'classification' => $registration->classification,
                'details' => $detailString,
                'ticket_code' => $registration->ticket_code,
            ];

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
        // 1. Determine if the user is a logged-in Admin/Director
        $isAdmin = Auth::check() && in_array(Auth::user()->role?->role_name, ['administrator', 'director']);

        // 2. Set the layout dynamically!
        $layout = $isAdmin ? 'layouts.madya-admin-deck' : 'layouts.madya-template';

        return view('livewire.admin.event-scanner', [
            'stats' => [
                'total' => $this->event->registrations()->count(),
                'attended' => $this->event->registrations()->where('status', 'Attended')->count(),
                'percentage' => $this->event->registrations()->count() > 0 
                    ? round(($this->event->registrations()->where('status', 'Attended')->count() / $this->event->registrations()->count()) * 100) 
                    : 0
            ]
        ])->layout($layout); // <-- Inject the dynamic layout here
    }
}
