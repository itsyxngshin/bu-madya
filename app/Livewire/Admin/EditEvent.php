<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Event;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')]
class EditEvent extends Component
{
    use WithFileUploads;

    public Event $event;

    public $title;
    public $description;
    public $cover_image;
    public $old_cover_image;
    public $is_internal_rsvp; // [NEW]
    public $registration_link;
    public $registration_button_text;
    public $location; // [NEW]
    public $capacity; // [NEW]
    public $start_date;
    public $end_date;
    public $is_active;
    public $photo_upload;
    public $slug;

    protected $rules = [
        'title' => 'required|string|max:255',
        'registration_link' => 'nullable|url',
        'cover_image' => 'nullable|image|max:2048',
        'location' => 'nullable|string|max:255',
        'capacity' => 'nullable|integer|min:1',
    ];

    public function mount($id)
    {
        $this->event = Event::findOrFail($id);

        $this->title = $this->event->title;
        $this->slug = $this->event->slug;
        $this->description = $this->event->description;
        $this->old_cover_image = $this->event->cover_image;

        $this->is_internal_rsvp = (bool) $this->event->is_internal_rsvp; // [NEW]
        $this->registration_link = $this->event->registration_link;
        $this->registration_button_text = $this->event->registration_button_text;

        $this->location = $this->event->location; // [NEW]
        $this->capacity = $this->event->capacity; // [NEW]

        $this->start_date = $this->event->start_date ? $this->event->start_date->format('Y-m-d\TH:i') : null;
        $this->end_date = $this->event->end_date ? $this->event->end_date->format('Y-m-d\TH:i') : null;
        $this->is_active = (bool) $this->event->is_active;
    }

    public function updatedPhotoUpload()
    {
        $this->validate(['photo_upload' => 'image|max:3072']);
        $this->dispatch('photo-inserted', url: $this->photo_upload->temporaryUrl());
    }

    public function update()
    {
        $this->validate();

        $imagePath = $this->old_cover_image;

        if ($this->cover_image) {
            if ($this->old_cover_image) {
                Storage::disk('public')->delete($this->old_cover_image);
            }
            $imagePath = $this->cover_image->store('events', 'public');
        }

        $this->event->update([
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

        return redirect()->route('admin.events.index')->with('message', 'Event updated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.edit-event');
    }
}
