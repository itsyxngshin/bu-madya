<?php

namespace App\Livewire\Ibalong;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\IbalongResourceGroup;
use App\Models\IbalongResourceFile;

class ResourceRoom extends Component
{
    use WithFileUploads;

    public $title, $description, $availableAt, $isVisible = true;
    public $uploads = []; // Holds the files during creation
    public $isAdmin = false;

    public function mount()
    {
        $this->isAdmin = in_array(auth('ibalong')->user()->role_id, [1, 2]);
    }

    public function createResourceGroup()
    {
        if (!$this->isAdmin) abort(403);

        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'availableAt' => 'nullable|date',
            'uploads.*' => 'file|max:20480', // 20MB Max per file
        ]);

        $group = IbalongResourceGroup::create([
            'title' => $this->title,
            'description' => $this->description,
            'available_at' => $this->availableAt,
            'is_visible' => $this->isVisible,
        ]);

        foreach ($this->uploads as $file) {
            $path = $file->store('resources', 'public');
            // Format size for display (e.g., 2.5 MB)
            $size = round($file->getSize() / 1048576, 2) . ' MB'; 
            
            IbalongResourceFile::create([
                'group_id' => $group->id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_size' => $size,
            ]);
        }

        $this->reset(['title', 'description', 'availableAt', 'isVisible', 'uploads']);
        session()->flash('success', 'Resource Pack Deployed.');
    }

    public function toggleVisibility($groupId)
    {
        if (!$this->isAdmin) return;
        $group = IbalongResourceGroup::find($groupId);
        $group->update(['is_visible' => !$group->is_visible]);
    }

    public function deleteGroup($groupId)
    {
        if (!$this->isAdmin) return;
        $group = IbalongResourceGroup::find($groupId);
        
        foreach($group->files as $file) {
            Storage::disk('public')->delete($file->file_path);
        }
        $group->delete();
    }

    public function render()
    {
        $query = IbalongResourceGroup::with('files')->latest();

        // If not admin, only show visible AND (no schedule OR schedule <= now)
        if (!$this->isAdmin) {
            $query->where('is_visible', true)
                  ->where(function($q) {
                      $q->whereNull('available_at')
                        ->orWhere('available_at', '<=', now());
                  });
        }

        $resourceGroups = $query->get();

        return view('livewire.ibalong.resource-room', compact('resourceGroups'))
            ->layout('layouts.dashboard');
    }
}