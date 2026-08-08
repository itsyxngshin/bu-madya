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
    public $editVariations = [];
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
        // Parse the stored JSON
        $variations = is_string($frame->frame_images) ? json_decode($frame->frame_images, true) : ($frame->frame_images ?? []);

        foreach ($variations as $var) {
            $path = is_array($var) ? ($var['path'] ?? '') : $var;
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
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

        // Parse existing variations safely (handles both old flat arrays and new structured JSON)
        $rawVariations = is_string($frame->frame_images)
            ? json_decode($frame->frame_images, true)
            : (is_array($frame->frame_images) ? $frame->frame_images : []);

        $this->editVariations = [];

        foreach ($rawVariations as $var) {
            if (is_array($var)) {
                $this->editVariations[] = [
                    'path' => $var['path'] ?? '',
                    'label' => $var['label'] ?? 'Variation',
                    'caption' => $var['caption'] ?? ''
                ];
            } else {
                // Backwards compatibility for flat arrays
                $this->editVariations[] = [
                    'path' => $var,
                    'label' => 'Variation',
                    'caption' => ''
                ];
            }
        }

        $this->imagesToDelete = [];
    }

    public function updatedNewUploads()
    {
        $this->validate([
            'newUploads.*' => 'image|mimes:png|max:5120',
        ]);

        foreach ($this->newUploads as $upload) {
            $path = $upload->store('frames', 'public');
            $this->editVariations[] = [
                'path' => $path,
                'label' => 'New Variation',
                'caption' => ''
            ];
        }

        $this->newUploads = [];
    }

    public function moveVariationUp($index)
    {
        if ($index > 0) {
            $temp = $this->editVariations[$index];
            $this->editVariations[$index] = $this->editVariations[$index - 1];
            $this->editVariations[$index - 1] = $temp;
        }
    }

    public function moveVariationDown($index)
    {
        if ($index < count($this->editVariations) - 1) {
            $temp = $this->editVariations[$index];
            $this->editVariations[$index] = $this->editVariations[$index + 1];
            $this->editVariations[$index + 1] = $temp;
        }
    }

    public function removeVariation($index)
    {
        if (isset($this->editVariations[$index]['path'])) {
            $this->imagesToDelete[] = $this->editVariations[$index]['path'];
        }
        unset($this->editVariations[$index]);
        $this->editVariations = array_values($this->editVariations);
    }

    public function cancelEdit()
    {
        $this->reset(['editMode', 'editFrameId', 'editTitle', 'editDescription', 'editCaption', 'editVariations', 'imagesToDelete', 'newUploads']);
        $this->resetValidation();
    }

    public function updateFrame()
    {
        $this->validate([
            'editVariations' => 'array|min:1',
            'editVariations.*.label' => 'required|string|max:100',
            'editVariations.*.caption' => 'nullable|string',
        ], [
            'editVariations.min' => 'You must have at least one frame variation uploaded.',
            'editVariations.*.label.required' => 'All variations must have a label.'
        ]);

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
            'frame_images' => json_encode($this->editVariations),
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
