<?php

namespace App\Livewire\Partner;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\EventFrame;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')] // Or whichever layout your logged-in partners use
class SubmitFrame extends Component
{
    use WithFileUploads;

    public $title = '';
    public $description = '';
    public $frame_image;

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            // Strict validation: Must be an image, strictly PNG (for transparency), max 4MB
            'frame_image' => 'required|image|mimes:png|max:4096',
        ]);

        // 1. Store the image securely in storage/app/public/frames
        $path = $this->frame_image->store('frames', 'public');

        // 2. Generate a unique slug for the public URL
        $slug = Str::slug($this->title) . '-' . strtolower(Str::random(5));

        // 3. Create the database record (Defaults to is_approved = false)
        EventFrame::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'slug' => $slug,
            'description' => $this->description,
            'frame_image' => $path,
            'is_approved' => false, // Requires Admin approval
        ]);

        // 4. Reset the form and show a success message
        $this->reset(['title', 'description', 'frame_image']);
        session()->flash('message', 'Frame submitted successfully! It is currently pending admin approval.');
    }

    public function render()
    {
        // Show the user a list of frames they have previously submitted
        $myFrames = EventFrame::where('user_id', auth()->id())->latest()->get();

        return view('livewire.partner.submit-frame', [
            'myFrames' => $myFrames
        ]);
    }
}
