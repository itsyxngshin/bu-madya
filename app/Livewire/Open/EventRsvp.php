<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\EventTicketMail;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')]
class EventRsvp extends Component
{
    public Event $event;
    public $name = '';
    public $email = '';
    public $classification = 'BU Student';
    public $college_id = '';
    public $program = '';
    public $year_level = '';
    public $organization_name = '';
    public $position = '';

    public $isRegistered = false;
    public $registrationRecord;

    public function mount(Event $event)
    {
        $this->event = $event;

        // Security: Boot them out if this isn't an internal RSVP event
        if (!$this->event->is_internal_rsvp) {
            return redirect()->route('open.events.show', $this->event->slug);
        }

        if (Auth::check()) {
            $this->name = Auth::user()->name;
            $this->email = Auth::user()->email;

            $existing = EventRegistration::where('event_id', $this->event->id)
                                         ->where('user_id', Auth::id())
                                         ->first();
            if ($existing) {
                $this->isRegistered = true;
                $this->registrationRecord = $existing;
            }
        }
    }

    public function register()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'classification' => 'required|string',
        ];

        // Conditional Rules: BU Student
        if ($this->classification === 'BU Student') {
            $rules['college_id'] = 'required|string|max:50';
            $rules['program'] = 'required|string|max:150';
            $rules['year_level'] = 'required|string';
        }

        // Conditional Rules: External Orgs [NEW]
        if (in_array($this->classification, ['CSO/NGO Representative', 'Partner Representative'])) {
            $rules['organization_name'] = 'required|string|max:255';
            $rules['position'] = 'required|string|max:255';
        }

        $this->validate($rules);

        if ($this->event->capacity && $this->event->registrations()->count() >= $this->event->capacity) {
            session()->flash('error', 'Sorry, this event is fully booked.');
            return;
        }

        $ticketCode = 'BUMADYA-' . strtoupper(\Illuminate\Support\Str::random(8));

        $this->registrationRecord = \App\Models\EventRegistration::create([
            'event_id' => $this->event->id,
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'name' => $this->name,
            'email' => $this->email,
            'classification' => $this->classification,
            
            // Student Info
            'college_id' => $this->classification === 'BU Student' ? $this->college_id : null,
            'program' => $this->classification === 'BU Student' ? $this->program : null,
            'year_level' => $this->classification === 'BU Student' ? $this->year_level : null,
            
            // Org Info [NEW]
            'organization_name' => in_array($this->classification, ['CSO/NGO Representative', 'Partner Representative']) ? $this->organization_name : null,
            'position' => in_array($this->classification, ['CSO/NGO Representative', 'Partner Representative']) ? $this->position : null,
            
            'ticket_code' => $ticketCode,
        ]);

        try {
            \Illuminate\Support\Facades\Mail::to($this->email)->send(new \App\Mail\EventTicketMail($this->event, $this->registrationRecord));
        } catch (\Exception $e) {
            \Log::error('Failed to send ticket: ' . $e->getMessage());
        }

        $this->isRegistered = true;
    }

    public function render()
    {
        // Reuses the Luma-style view provided in the previous interaction
        return view('livewire.open.event-rsvp');
    }
}
