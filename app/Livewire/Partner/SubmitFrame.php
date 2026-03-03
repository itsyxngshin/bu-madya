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
    public $frame_images = [];

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'frame_images' => 'required|array|min:1|max:5', // Max 5 variations per campaign
            'frame_images.*' => 'image|mimes:png|max:5120',
        ]);

        $paths = [];
        foreach ($this->frame_images as $image) {
            $paths[] = $image->store('frames', 'public');
        }

        $slug = \Illuminate\Support\Str::slug($this->title) . '-' . strtolower(\Illuminate\Support\Str::random(5));

        // 3. Create the database record (Defaults to is_approved = false)
        EventFrame::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'slug' => $slug,
            'description' => $this->description,
            'frame_images' => $paths, // [UPDATED] Save the array
            'is_approved' => false, // Requires Admin approval
        ]);

       $this->reset(['title', 'description', 'frame_images']);
        session()->flash('message', 'Campaign with ' . count($paths) . ' variations submitted successfully!');
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
