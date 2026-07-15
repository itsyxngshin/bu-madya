<?php

namespace App\Livewire\Ibalong;

use Livewire\Component;
use App\Models\IbalongEvent;
use App\Models\IbalongEventRegistration;
use App\Models\IbalongRegistration;
use chillerlan\QRCode\QRCode; // Bring in the new package
use Illuminate\Support\Facades\Mail;
use App\Mail\EventTicketGenerated;

class EventRegistration extends Component
{
    public $event;
    public $teams = [];

    // Form fields
    public $name, $email, $affiliation, $role = 'Audience', $team_id;

    // Success State
    public $isSubmitted = false;
    public $ticket_code;
    public $qrCodeUri;

    public function mount($slug)
    {
        $this->event = IbalongEvent::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $this->teams = IbalongRegistration::where('status', 'approved')
            ->orderBy('team_name', 'asc')
            ->get();
    }

    public function updatedRole($value)
    {
        if ($value !== 'Team Member') {
            $this->team_id = null;
        }
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'affiliation' => 'nullable|string|max:255',
            'role' => 'required|in:Audience,Team Member,Facilitator,VIP',
            'team_id' => 'required_if:role,Team Member|nullable|exists:ibalong_registrations,id',
        ], [
            'team_id.required_if' => 'You must select your official team if registering as a competing Team Member.'
        ]);

        if ($this->event->max_capacity) {
            $currentCount = IbalongEventRegistration::where('event_id', $this->event->id)
                ->where('status', '!=', 'Cancelled')
                ->count();

            if ($currentCount >= $this->event->max_capacity) {
                session()->flash('error', 'Sorry! This event has reached its maximum capacity.');
                return;
            }
        }

        $exists = IbalongEventRegistration::where('event_id', $this->event->id)
            ->where('email', $this->email)
            ->exists();

        if ($exists) {
            session()->flash('error', 'This email is already registered for this event.');
            return;
        }

        $registration = IbalongEventRegistration::create([
            'event_id' => $this->event->id,
            'team_id' => $this->role === 'Team Member' ? $this->team_id : null,
            'name' => $this->name,
            'email' => $this->email,
            'affiliation' => $this->affiliation,
            'role' => $this->role,
        ]);

        $this->ticket_code = $registration->ticket_code;
        // Generates a perfect, standard Base64 SVG string in one line for the WEB VIEW
        $this->qrCodeUri = (new QRCode)->render($this->ticket_code);

        // --- NEW: SEND THE TICKET TO THEIR EMAIL ---
        try {
            Mail::to($this->email)->send(new EventTicketGenerated($this->event, $registration));
        } catch (\Exception $e) {
            // Optional: Log the error if the mail fails, but don't stop the UI from showing success
            \Log::error('Ticket email failed to send: ' . $e->getMessage());
        }
        $this->isSubmitted = true;
    }

    public function render()
    {
        return view('livewire.ibalong.event-registration')->layout('layouts.ibalong-layout');
    }
}
