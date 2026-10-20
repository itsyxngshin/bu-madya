<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Event;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')]
class CreateEvent extends Component
{
    use WithFileUploads;

    public $title;
    public $slug;
    public $description;
    public $cover_image;

    // Toggle Mode
    public $is_internal_rsvp = false;

    // External Link details
    public $registration_link;
    public $registration_button_text = 'Register Now';

    // Internal RSVP details
    public $location;
    public $capacity;
    public $allow_self_checkin = false;

    // Dynamic Questionnaire
    public $custom_questions = [];

    public $start_date;
    public $end_date;
    public $is_active = true;
    public $photo_upload;
    public $checkin_start;
    public $checkin_end;

    protected $rules = [
        'title' => 'required|string|max:255',
        'registration_link' => 'nullable|url',
        'cover_image' => 'nullable|image|max:8192',
        'location' => 'nullable|string|max:255',
        'capacity' => 'nullable|integer|min:1',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'checkin_start' => 'nullable|date',
        'checkin_end' => 'nullable|date|after_or_equal:checkin_start',
        'custom_questions.*.question_text' => 'required|string|max:255',
        'custom_questions.*.type' => 'required|in:text,checkbox',
    ];

    public function updatedTitle($value)
    {
        $this->validateOnly('title');
        $this->slug = Str::slug($value);
    }

    public function addQuestion()
    {
        $this->custom_questions[] = [
            'question_text' => '',
            'type' => 'text',
            'is_required' => true
        ];
    }

    public function removeQuestion($index)
    {
        unset($this->custom_questions[$index]);
        $this->custom_questions = array_values($this->custom_questions);
    }

    public function save()
    {
        // 1. Sanitize empty strings to strictly null so nullable validation passes
        $this->capacity = $this->capacity === '' ? null : $this->capacity;
        $this->checkin_start = $this->checkin_start === '' ? null : $this->checkin_start;
        $this->checkin_end = $this->checkin_end === '' ? null : $this->checkin_end;
        $this->location = $this->location === '' ? null : $this->location;
        $this->registration_link = $this->registration_link === '' ? null : $this->registration_link;
        $this->start_date = $this->start_date === '' ? null : $this->start_date;
        $this->end_date = $this->end_date === '' ? null : $this->end_date;

        // 2. Execute Validation
        $this->validate();

        $imagePath = null;
        if ($this->cover_image) {
            $imagePath = $this->cover_image->store('events', 'public');
        }

        $event = Event::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'slug' => Str::slug($this->slug),
            'description' => $this->description,
            'cover_image' => $imagePath,
            'is_internal_rsvp' => $this->is_internal_rsvp,
            'registration_link' => $this->is_internal_rsvp ? null : $this->registration_link,
            'registration_button_text' => $this->registration_button_text,
            'location' => $this->location,
            'capacity' => $this->capacity,
            'allow_self_checkin' => $this->allow_self_checkin,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_active' => $this->is_active,
            'checkin_start' => $this->checkin_start,
            'checkin_end' => $this->checkin_end,
            'custom_questions.*.id' => 'nullable',
                    'custom_questions.*.question_text' => 'required|string|max:5000',
                    'custom_questions.*.type' => 'required|in:text,checkbox',
                    'custom_questions.*.is_required' => 'boolean',
        ]);

        if ($this->is_internal_rsvp && !empty($this->custom_questions)) {
            foreach ($this->custom_questions as $qData) {
                $event->questions()->create([
                    'question_text' => $qData['question_text'],
                    'type' => $qData['type'],
                    'is_required' => $qData['is_required']
                ]);
            }
        }

        session()->flash('message', 'Event created successfully.');

        if (auth()->user()->role?->role_name === 'organization') {
            return redirect()->route('partner.events.index')->with('message', 'Event created successfully!');
        }

        return redirect()->route('admin.events.index')->with('message', 'Event created successfully!');
    }

    public function updatedPhotoUpload()
    {
        $this->validate(['photo_upload' => 'image|max:8192']);
        $this->dispatch('photo-inserted', url: $this->photo_upload->temporaryUrl());
    }

    public function render()
    {
        return view('livewire.admin.create-event');
    }
}
