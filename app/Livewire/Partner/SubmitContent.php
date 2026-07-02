<?php

namespace App\Livewire\Partner;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\Announcement;
use App\Models\Spotlight;
use App\Models\AnnouncementType;
use App\Models\SpotlightCategory;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.madya-admin-deck')] // Update if your partner portal uses a different layout
class SubmitContent extends Component
{
    use WithFileUploads;

    public $submissionType = 'announcement'; // 'announcement' or 'spotlight'

    // Form Fields
    public $announcement_type_id = '';
    public $spotlight_category_id = '';
    public $title = '';
    public $message = '';
    public $image; 
    public $link = '';
    public $start_at = '';
    public $end_at = '';

    public function submit()
    {
        if ($this->submissionType === 'announcement') {
            $this->validate([
                'announcement_type_id' => 'required|exists:announcement_types,id',
                'title' => 'required|string|max:255',
                'message' => 'required|string|max:500',
                'start_at' => 'nullable|date',
                'end_at' => 'nullable|date|after_or_equal:start_at',
            ]);

            Announcement::create([
                'announcement_type_id' => $this->announcement_type_id,
                'title' => $this->title,
                'message' => $this->message,
                'start_at' => $this->start_at ?: null,
                'end_at' => $this->end_at ?: null,
                'status' => 'pending',
                'is_active' => false,
                'user_id' => Auth::id(),
            ]);
        } else {
            $this->validate([
                'spotlight_category_id' => 'required|exists:spotlight_categories,id',
                'title' => 'required|string|max:255',
                'image' => 'required|image|max:2048', // max 2MB
                'link' => 'nullable|url',
                'start_at' => 'nullable|date',
                'end_at' => 'nullable|date|after_or_equal:start_at',
            ]);

            // Save the image securely to the public disk
            $imagePath = $this->image->store('spotlights', 'public');

            Spotlight::create([
                'spotlight_category_id' => $this->spotlight_category_id,
                'title' => $this->title,
                'image_path' => $imagePath,
                'link' => $this->link,
                'start_at' => $this->start_at ?: null,
                'end_at' => $this->end_at ?: null,
                'status' => 'pending',
                'is_active' => false,
                'user_id' => Auth::id(),
            ]);
        }

        session()->flash('success', 'Your request has been submitted and is pending OSAS approval.');
        
        // Reset the form fields but keep the type selected
        $this->reset(['title', 'message', 'image', 'link', 'start_at', 'end_at', 'announcement_type_id', 'spotlight_category_id']);
    }

    public function render()
    {
        return view('livewire.partner.submit-content', [
            'announcementTypes' => AnnouncementType::all(),
            'spotlightCategories' => SpotlightCategory::all(),
        ]);
    }
}
