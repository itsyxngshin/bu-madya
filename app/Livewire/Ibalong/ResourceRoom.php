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

    public $isAdmin = false;

    // --- Create State ---
    public $title, $description, $availableAt, $isVisible = true;
    public $uploads = []; 

    // --- Edit State ---
    public $editingGroupId = null;
    public $editTitle = '';
    public $editDescription = '';
    public $editAvailableAt = null;
    public $editIsVisible = true;
    public $newUploads = []; // For adding files to an existing group

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
            'uploads.*' => 'file|max:40960', // 40MB Max per file
        ]);

        $group = IbalongResourceGroup::create([
            'title' => $this->title,
            'description' => $this->description,
            'available_at' => $this->availableAt,
            'is_visible' => $this->isVisible,
        ]);

        if (!empty($this->uploads)) {
            foreach ($this->uploads as $file) {
                $path = $file->store('resources', 'public');
                $size = round($file->getSize() / 1048576, 2) . ' MB'; 
                
                IbalongResourceFile::create([
                    'group_id' => $group->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_size' => $size,
                ]);
            }
        }

        $this->reset(['title', 'description', 'availableAt', 'isVisible', 'uploads']);
        session()->flash('success', 'Resource Pack Deployed.');
    }

    // --- EDIT & UPDATE METHODS ---
    public function editGroup($id)
    {
        if (!$this->isAdmin) return;
        
        $group = IbalongResourceGroup::findOrFail($id);
        $this->editingGroupId = $group->id;
        $this->editTitle = $group->title;
        $this->editDescription = $group->description;
        // Format for HTML datetime-local input
        $this->editAvailableAt = $group->available_at ? $group->available_at->format('Y-m-d\TH:i') : null; 
        $this->editIsVisible = $group->is_visible;
        $this->newUploads = [];
    }

    public function cancelEdit()
    {
        $this->reset(['editingGroupId', 'editTitle', 'editDescription', 'editAvailableAt', 'editIsVisible', 'newUploads']);
    }

    public function updateGroup()
    {
        if (!$this->isAdmin) abort(403);

        $this->validate([
            'editTitle' => 'required|string|max:255',
            'editDescription' => 'nullable|string',
            'editAvailableAt' => 'nullable|date',
            'newUploads.*' => 'file|max:20480',
        ]);

        $group = IbalongResourceGroup::findOrFail($this->editingGroupId);
        $group->update([
            'title' => $this->editTitle,
            'description' => $this->editDescription,
            'available_at' => $this->editAvailableAt,
            'is_visible' => $this->editIsVisible,
        ]);

        // Process newly added files during edit
        if (!empty($this->newUploads)) {
            foreach ($this->newUploads as $file) {
                $path = $file->store('resources', 'public');
                $size = round($file->getSize() / 1048576, 2) . ' MB'; 
                
                IbalongResourceFile::create([
                    'group_id' => $group->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_size' => $size,
                ]);
            }
        }

        $this->cancelEdit();
        session()->flash('success', 'Resource Pack Updated Successfully.');
    }

    // --- DELETE METHODS ---
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
        session()->flash('success', 'Resource Pack Deleted.');
    }

    public function deleteFile($fileId)
    {
        if (!$this->isAdmin) return;
        $file = IbalongResourceFile::findOrFail($fileId);
        
        Storage::disk('public')->delete($file->file_path);
        $file->delete();
        session()->flash('success', 'File removed from pack.');
    }

    public function render()
    {
        $query = IbalongResourceGroup::with('files')->latest();

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