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

    public Event $event; // The actual model instance

    // Form fields
    public $title;
    public $description;
    public $cover_image; // New upload
    public $old_cover_image; // Existing path
    public $registration_link;
    public $registration_button_text;
    public $start_date;
    public $end_date;
    public $is_active;
    public $photo_upload;
    public $slug;
    protected $rules = [
        'title' => 'required|string|max:255',
        'registration_link' => 'nullable|url',
        'cover_image' => 'nullable|image|max:2048',
    ];
    // 2. Add this method
    

    public function mount($id)
    {
        $this->event = Event::findOrFail($id);

        // Fill the form properties with existing database data
        $this->title = $this->event->title;
        $this->slug = $this->event->slug;
        $this->description = $this->event->description;
        $this->old_cover_image = $this->event->cover_image;
        $this->registration_link = $this->event->registration_link;
        $this->registration_button_text = $this->event->registration_button_text;
        
        // Format dates for HTML input (datetime-local requires Y-m-d\TH:i)
        $this->start_date = $this->event->start_date ? $this->event->start_date->format('Y-m-d\TH:i') : null;
        $this->end_date = $this->event->end_date ? $this->event->end_date->format('Y-m-d\TH:i') : null;
        
        $this->is_active = (bool) $this->event->is_active;
    }

    // Add this hook

    public function updatedPhotoUpload()
    {
        $this->validate(['photo_upload' => 'image|max:3072']);
        $this->dispatch('photo-inserted', url: $this->photo_upload->temporaryUrl());
    }
    public function update()
    {
        $this->validate();

        $imagePath = $this->old_cover_image;

        // Handle new image upload
        if ($this->cover_image) {
            // Delete old image if exists
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
            'registration_link' => $this->registration_link,
            'registration_button_text' => $this->registration_button_text,
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