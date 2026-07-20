<?php

namespace App\Livewire\Ibalong;

use Livewire\Component;
use App\Models\IbalongEvent;
use App\Models\IbalongEventRegistration;
use App\Models\IbalongEventAttendance;

class EventScanner extends Component
{
    public $event;
    public $lastScanStatus = null; // 'success' or 'error'
    public $lastScanMessage = '';
    public $scannedRegistrant = null;

    public function mount($slug)
    {
        $this->event = IbalongEvent::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // 1. Check Security Permissions
        $user = auth('ibalong')->user();
        $isAdminOrFacilitator = $user && in_array($user->role_id, [1, 2]); // Check your exact admin roles

        if (!$this->event->allow_self_checkin && !$isAdminOrFacilitator) {
            abort(403, 'ACCESS DENIED: This terminal is restricted to authorized Facilitators only.');
        }
    }

    public function processScan($ticketCode)
    {
        // Reset previous state
        $this->scannedRegistrant = null;
        $this->lastScanStatus = null;

        // 1. Find the registration
        $registration = IbalongEventRegistration::where('ticket_code', $ticketCode)
            ->where('event_id', $this->event->id)
            ->first();

        if (!$registration) {
            $this->lastScanStatus = 'error';
            $this->lastScanMessage = 'INVALID TICKET: Code does not exist for this event.';
            return;
        }

        if ($registration->status !== 'Approved') {
            $this->lastScanStatus = 'error';
            $this->lastScanMessage = 'TICKET BLOCKED: Status is ' . strtoupper($registration->status) . '.';
            return;
        }

        // 2. Check if already scanned
        $alreadyScanned = IbalongEventAttendance::where('registration_id', $registration->id)->exists();

        if ($alreadyScanned) {
            $this->scannedRegistrant = $registration;
            $this->lastScanStatus = 'error';
            $this->lastScanMessage = 'ALREADY SCANNED IN: This ticket was already used.';
            return;
        }

        // 3. Record Attendance
        IbalongEventAttendance::create([
            'registration_id' => $registration->id,
            'scanned_at' => now(),
            'scanned_by' => auth('ibalong')->user() ? auth('ibalong')->user()->name : 'Self Check-In Kiosk',
        ]);

        $this->scannedRegistrant = $registration;
        $this->lastScanStatus = 'success';
        $this->lastScanMessage = 'ACCESS GRANTED';
    }

    public function render()
    {
        return view('livewire.ibalong.event-scanner')->layout('layouts.ibalong-layout');
    }
}
