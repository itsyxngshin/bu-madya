<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\EventFrame;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('layouts.madya-admin-deck')]
class FrameManager extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $filter = 'all'; 

    // Metadata Edit Properties
    public $editMode = false;
    public $editFrameId = null;
    
    #[Rule('required|string|max:255')]
    public $editTitle = '';
    
    #[Rule('nullable|string')]
    public $editDescription = '';
    
    #[Rule('nullable|string')]
    public $editCaption = '';

    // Variation Edit Properties
    public $editImages = [];
    public $imagesToDelete = [];
    public $newUploads = [];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilter() { $this->resetPage(); }

    public function toggleApproval(EventFrame $frame)
    {
        $frame->update(['is_approved' => !$frame->is_approved]);
        $status = $frame->is_approved ? 'approved and published.' : 'unpublished.';
        session()->flash('message', "Frame successfully {$status}");
    }

    public function deleteFrame(EventFrame $frame)
    {
        if (is_array($frame->frame_images)) {
            foreach ($frame->frame_images as $imagePath) {
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
        } elseif ($frame->frame_image) {
            Storage::disk('public')->delete($frame->frame_image);
        }

        $frame->delete();
        session()->flash('message', 'Campaign and all variations permanently deleted.');
    }

    public function editFrame(EventFrame $frame)
    {
        $this->editMode = true;
        $this->editFrameId = $frame->id;
        $this->editTitle = $frame->title;
        $this->editDescription = $frame->description;
        $this->editCaption = $frame->caption;

        // Load existing variations into the state
        $this->editImages = is_array($frame->frame_images) 
            ? $frame->frame_images 
            : (empty($frame->frame_image) ? [] : [$frame->frame_image]);
            
        $this->imagesToDelete = [];
    }

    public function updatedNewUploads()
    {
        $this->validate([
            'newUploads.*' => 'image|mimes:png|max:5120', // Ensure they remain transparent PNGs
        ]);

        foreach ($this->newUploads as $upload) {
            // Store directly to frames to get a valid public path for rendering in the preview
            $path = $upload->store('frames', 'public');
            $this->editImages[] = $path;
        }

        $this->newUploads = [];
    }

    public function moveImageUp($index)
    {
        if ($index > 0) {
            $temp = $this->editImages[$index];
            $this->editImages[$index] = $this->editImages[$index - 1];
            $this->editImages[$index - 1] = $temp;
        }
    }

    public function moveImageDown($index)
    {
        if ($index < count($this->editImages) - 1) {
            $temp = $this->editImages[$index];
            $this->editImages[$index] = $this->editImages[$index + 1];
            $this->editImages[$index + 1] = $temp;
        }
    }

    public function removeImage($index)
    {
        // Track the path so we can delete it from storage when they click 'Save Changes'
        $this->imagesToDelete[] = $this->editImages[$index];
        unset($this->editImages[$index]);
        $this->editImages = array_values($this->editImages);
    }

    public function cancelEdit()
    {
        $this->reset(['editMode', 'editFrameId', 'editTitle', 'editDescription', 'editCaption', 'editImages', 'imagesToDelete', 'newUploads']);
        $this->resetValidation();
    }

    public function updateFrame()
    {
        $this->validate();

        if (empty($this->editImages)) {
            $this->addError('editImages', 'You must have at least one frame variation uploaded.');
            return;
        }

        $frame = EventFrame::findOrFail($this->editFrameId);
        
        // Clean up the removed files from the server
        foreach ($this->imagesToDelete as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $frame->update([
            'title' => $this->editTitle,
            'description' => $this->editDescription,
            'caption' => $this->editCaption,
            'frame_images' => $this->editImages, // Saves the new reordered array
        ]);

        session()->flash('message', 'Campaign details and variations successfully updated.');
        $this->cancelEdit();
    }

    public function render()
    {
        $query = EventFrame::with('user')->latest();

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
        }

        if ($this->filter === 'pending') {
            $query->where('is_approved', false);
        } elseif ($this->filter === 'approved') {
            $query->where('is_approved', true);
        }

        return view('livewire.admin.frame-manager', [
            'frames' => $query->paginate(12)
        ]);
    }
}