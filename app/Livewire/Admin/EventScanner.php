<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventAttendedMail;

class EventScanner extends Component
{
    public Event $event;
    public $scanStatus = null; 
    public $scanMessage = '';
    public $lastScannedData = null; // [FIXED] Properly declared as an array
    public $manualCode = '';

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    // Triggered by the camera
    public function processScan($ticketCode)
    {
        $this->verifyTicket(trim($ticketCode));
    }

    // Triggered by the manual form
    public function manualCheckIn()
    {
        $this->validate([
            'manualCode' => 'required|string'
        ]);
        
        $this->verifyTicket(trim($this->manualCode));
        $this->manualCode = ''; // Clear input after submit
    }

    // The core logic for both camera and manual entry
    private function verifyTicket($ticketCode)
    {
        // 1. Find the registration for THIS event (Eager load college to prevent errors)
        $reg = EventRegistration::with('college')
            ->where('event_id', $this->event->id)
            ->where('ticket_code', $ticketCode)
            ->first();

        // 2. If ticket doesn't exist
        if (!$reg) {
            $this->scanStatus = 'error';
            $this->scanMessage = 'Invalid Ticket';
            $this->lastScannedData = null;
            $this->dispatch('play-error-sound'); // [FIXED] Specific sound event
            return;
        }

        // 3. Build the Digital ID Card Data
        $detailString = '';
        if ($reg->classification === 'BU Student') {
            $detailString = ($reg->college ? $reg->college->name : 'Unknown College') . ' - ' . $reg->year_level;
        } elseif (in_array($reg->classification, ['CSO/NGO Representative', 'Partner Representative'])) {
            $detailString = $reg->organization_name . ' (' . $reg->position . ')';
        }

        $this->lastScannedData = [
            'name' => $reg->name,
            'classification' => $reg->classification,
            'details' => $detailString,
            'ticket_code' => $reg->ticket_code,
        ];

        // 4. Process Attendance
        if ($reg->status !== 'Attended') {
            
            $reg->update(['status' => 'Attended']);
            
            // Optional: Send Email (Wrapped in try/catch so it doesn't break the scanner if mail fails)
            try {
                // Using ->queue() instead of ->send() speeds up the scanner dramatically!
                Mail::to($reg->email)->queue(new EventAttendedMail($this->event, $reg));
            } catch (\Exception $e) {
                \Log::error('Failed to send attendance email: ' . $e->getMessage());
            }

            $this->scanStatus = 'success';
            $this->scanMessage = 'Check-in Successful';
            $this->dispatch('play-success-sound'); // [FIXED] Specific sound event

        } else {
            $this->scanStatus = 'warning';
            $this->scanMessage = 'Already Checked In';
            $this->dispatch('play-error-sound'); // [FIXED] Specific sound event
        }
    }

    public function render()
    {
        // Dynamic Layout Check
        $isAdmin = Auth::check() && in_array(Auth::user()->role?->role_name, ['administrator', 'director']);
        $layout = $isAdmin ? 'layouts.madya-admin-deck' : 'layouts.madya-template';

        // Calculate Stats
        $total = $this->event->registrations()->count();
        $attended = $this->event->registrations()->where('status', 'Attended')->count();
        $percentage = $total > 0 ? round(($attended / $total) * 100) : 0;

        return view('livewire.admin.event-scanner', [
            'stats' => [
                'total' => $total,
                'attended' => $attended,
                'percentage' => $percentage
            ]
        ])->layout($layout);
    }
}