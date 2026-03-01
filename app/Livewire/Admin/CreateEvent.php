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
    public $is_internal_rsvp = false; // [NEW]

    // External Link details
    public $registration_link;
    public $registration_button_text = 'Register Now';

    // Internal RSVP details [NEW]
    public $location;
    public $capacity;

    public $start_date;
    public $end_date;
    public $is_active = true;
    public $photo_upload;

    protected $rules = [
        'title' => 'required|string|max:255',
        'registration_link' => 'nullable|url',
        'cover_image' => 'nullable|image|max:2048',
        'location' => 'nullable|string|max:255',
        'capacity' => 'nullable|integer|min:1',
    ];

    public function updatedTitle($value)
    {
        $this->validateOnly('title');
        $this->slug = Str::slug($value); // Optionally auto-fill slug here
    }

    public function save()
    {
        $this->validate();

        $imagePath = null;
        if ($this->cover_image) {
            $imagePath = $this->cover_image->store('events', 'public');
        }

        Event::create([
            'title' => $this->title,
            'slug' => Str::slug($this->slug),
            'description' => $this->description,
            'cover_image' => $imagePath,
            'is_internal_rsvp' => $this->is_internal_rsvp, // [NEW]
            'registration_link' => $this->is_internal_rsvp ? null : $this->registration_link, // Clear link if using internal
            'registration_button_text' => $this->registration_button_text,
            'location' => $this->location, // [NEW]
            'capacity' => $this->capacity, // [NEW]
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_active' => $this->is_active,
        ]);

        return redirect()->route('admin.events.index')->with('message', 'Event created successfully!');
    }

    public function updatedPhotoUpload()
    {
        $this->validate(['photo_upload' => 'image|max:3072']);
        $this->dispatch('photo-inserted', url: $this->photo_upload->temporaryUrl());
    }

    public function render()
    {
        return view('livewire.admin.create-event');
    }
}
