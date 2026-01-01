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
    public $registration_link;
    public $registration_button_text = 'Register Now';
    public $start_date;
    public $end_date;
    public $is_active = true;
    public $photo_upload;

    protected $rules = [
        'title' => 'required|string|max:255',
        'registration_link' => 'nullable|url',
        'cover_image' => 'nullable|image|max:2048', // 2MB Max
    ];

    // Auto-generate slug when title updates
    public function updatedTitle($value)
    {
        // Only if creating new
        $this->validateOnly('title'); 
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
            'slug' => $this->slug,
            'description' => $this->description,
            'cover_image' => $imagePath,
            'registration_link' => $this->registration_link,
            'registration_button_text' => $this->registration_button_text,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_active' => $this->is_active,
        ]);

        return redirect()->route('admin.events.index')->with('message', 'Event created successfully!');
    }

    // Add this hook
    public function updatedPhotoUpload()
    {
        $this->validate([
            'photo_upload' => 'image|max:3072', // 3MB Max
        ]);

        // Dispatch event to insert the image tag into the editor
        $this->dispatch('photo-inserted', url: $this->photo_upload->temporaryUrl());
    }

    public function render()
    {
        return view('livewire.admin.create-event');
    }
}