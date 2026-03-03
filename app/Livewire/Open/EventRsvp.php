<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\EventTicketMail;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')]
class EventRsvp extends Component
{
    public Event $event;
    public $registration_method = 'manual';
    public $name = '';
    public $email = '';
    public $classification = 'BU Student';
    public $college_id = '';
    public $program = '';
    public $year_level = '';
    public $organization_name = '';
    public $position = '';
    public $lookup_id = '';
    public $is_verified = false;
    public $verified_user_id = null;
    public $contact_number = '';
    public $school = '';

    // [NEW] The Primary Toggle
    public $is_bu_student = true;
    public $is_representing_org = false;
    public $isRegistered = false;
    public $registrationRecord;

    public function mount(Event $event)
    {
        $this->event = $event;

        if (!$this->event->is_internal_rsvp) {
            return redirect()->route('open.events.show', $this->event->slug);
        }

        // If they are already logged in, instantly verify them
        if (Auth::check()) {
            $this->registration_method = 'account';
            $this->is_verified = true;
            $this->verified_user_id = Auth::id();
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

    // [NEW] Method to lookup user by ID or Email
    public function verifyAccount()
    {
        $this->validateOnly('lookup_id', ['lookup_id' => 'required']);

        $user = User::where('id', $this->lookup_id)
                    ->orWhere('email', $this->lookup_id)
                    ->first();

        if ($user) {
            $this->is_verified = true;
            $this->verified_user_id = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->resetErrorBag('lookup_id');
        } else {
            $this->is_verified = false;
            $this->verified_user_id = null;
            $this->addError('lookup_id', 'Account not found. Please try again or use manual entry.');
        }
    }

    // [NEW] Reset if they switch modes
    public function updatedRegistrationMethod()
    {
        if (!Auth::check()) {
            $this->is_verified = false;
            $this->verified_user_id = null;
            $this->name = '';
            $this->email = '';
            $this->lookup_id = '';
            $this->resetErrorBag();
        }
    }

    public function updatedIsBuStudent($value)
    {
        if ($value) {
            $this->classification = 'BU Student';
            $this->organization_name = '';
            $this->position = '';
        }
        else {
            $this->classification = ''; // Force them to select from the external dropdown
            $this->college_id = '';
            $this->program = '';
            $this->year_level = '';
            $this->is_representing_org = false;
        }
    }

    public function register()
    {
        if ($this->is_bu_student) {
            $this->classification = 'BU Student';
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact_number' => 'required|string|max:20', // [NEW] Required for everyone
            'classification' => 'required|string',
        ];

        if ($this->is_bu_student) {
            $rules['college_id'] = 'required|exists:colleges,id';
            $rules['program'] = 'required|string|max:150';
            $rules['year_level'] = 'required|string';

            if ($this->is_representing_org) {
                $rules['organization_name'] = 'required|string|max:255';
                $rules['position'] = 'required|string|max:255';
            }
        } else {
            // [NEW] Validate school for non-BU students
            $rules['school'] = 'nullable|string|max:255';

            if (in_array($this->classification, ['CSO/NGO Representative', 'Partner Representative'])) {
                $rules['organization_name'] = 'required|string|max:255';
                $rules['position'] = 'required|string|max:255';
            }
        }

        $this->validate($rules);

        if ($this->event->capacity && $this->event->registrations()->count() >= $this->event->capacity) {
            session()->flash('error', 'Sorry, this event is fully booked.');
            return;
        }

        $ticketCode = 'BU-MADYA-' . strtoupper(Str::random(8));

        // [NEW] Use the verified user ID if available, otherwise use Auth, otherwise null
        $finalUserId = $this->verified_user_id ?? Auth::id() ?? null;

        $this->registrationRecord = EventRegistration::create([
            'event_id' => $this->event->id,
            'user_id' => $finalUserId,
            'name' => $this->name,
            'email' => $this->email,
            'classification' => $this->classification,
            'college_id' => $this->classification === 'BU Student' ? $this->college_id : null,
            'program' => $this->classification === 'BU Student' ? $this->program : null,
            'school' => $this->is_bu_student ? 'Bicol University' : $this->school,
            'year_level' => $this->classification === 'BU Student' ? $this->year_level : null,
            'organization_name' => ($this->is_bu_student && $this->is_representing_org) || in_array($this->classification, ['CSO/NGO Representative', 'Partner Representative']) ? $this->organization_name : null,
            'position' => in_array($this->classification, ['CSO/NGO Representative', 'Partner Representative']) ? $this->position : null,
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
        return view('livewire.open.event-rsvp', [
                    'colleges' => \App\Models\College::orderBy('name')->get()
                ]);
    }
}
