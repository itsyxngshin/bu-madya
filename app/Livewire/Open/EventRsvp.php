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
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        if ($this->event->capacity && $this->event->registrations()->count() >= $this->event->capacity) {
            session()->flash('error', 'Sorry, this event is fully booked.');
            return;
        }

        $ticketCode = 'BUMADYA-' . strtoupper(Str::random(8));

        $this->registrationRecord = EventRegistration::create([
            'event_id' => $this->event->id,
            'user_id' => Auth::id(),
            'name' => $this->name,
            'email' => $this->email,
            'ticket_code' => $ticketCode,
        ]);

        try {
            Mail::to($this->email)->send(new EventTicketMail($this->event, $this->registrationRecord));
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
