<?php

namespace App\Livewire\Admin\Content;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\Announcement;
use App\Models\Spotlight;
use App\Models\AnnouncementType;
use App\Models\SpotlightCategory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.madya-admin-deck')] // Update to your admin layout if different
class ContentSettings extends Component
{
    use WithFileUploads;

    public $activeTab = 'announcements'; // 'announcements' or 'spotlights'
    public $showModal = false;
    public $isEditing = false;
    public $editId = null;

    // Form fields
    public $type_id = '';
    public $category_id = '';
    public $title = '';
    public $message = '';
    public $link = '';
    public $image; // For new uploads
    public $existing_image; // For showing current image during edit
    public $start_at = '';
    public $end_at = '';
    public $sort_order = 0;
    public $is_active = true;

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetForm();
    }

    public function toggleActive($id, $type)
    {
        if ($type === 'announcement') {
            $record = Announcement::find($id);
        } else {
            $record = Spotlight::find($id);
        }

        if ($record) {
            $record->update(['is_active' => !$record->is_active]);
            session()->flash('success', 'Visibility updated successfully.');
        }
    }

    public function deleteRecord($id, $type)
    {
        if ($type === 'announcement') {
            Announcement::find($id)?->delete();
        } else {
            $spotlight = Spotlight::find($id);
            if ($spotlight) {
                // Delete image from storage
                if (Storage::disk('public')->exists($spotlight->image_path)) {
                    Storage::disk('public')->delete($spotlight->image_path);
                }
                $spotlight->delete();
            }
        }
        session()->flash('success', 'Record deleted successfully.');
    }

    public function openModal($id = null)
    {
        $this->resetForm();
        $this->resetValidation();
        
        if ($id) {
            $this->isEditing = true;
            $this->editId = $id;
            
            if ($this->activeTab === 'announcements') {
                $record = Announcement::find($id);
                $this->type_id = $record->announcement_type_id;
                $this->title = $record->title;
                $this->message = $record->message;
            } else {
                $record = Spotlight::find($id);
                $this->category_id = $record->spotlight_category_id;
                $this->title = $record->title;
                $this->link = $record->link;
                $this->sort_order = $record->sort_order;
                $this->existing_image = $record->image_path;
            }

            $this->is_active = $record->is_active;
            $this->start_at = $record->start_at ? $record->start_at->format('Y-m-d\TH:i') : '';
            $this->end_at = $record->end_at ? $record->end_at->format('Y-m-d\TH:i') : '';
        }

        $this->showModal = true;
    }

    public function save()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'is_active' => 'boolean',
        ];

        if ($this->activeTab === 'announcements') {
            $rules['type_id'] = 'required|exists:announcement_types,id';
            $rules['message'] = 'required|string|max:500';
        } else {
            $rules['category_id'] = 'required|exists:spotlight_categories,id';
            $rules['link'] = 'nullable|url';
            $rules['sort_order'] = 'required|integer';
            
            if (!$this->isEditing || $this->image) {
                $rules['image'] = 'required|image|max:2048';
            }
        }

        $this->validate($rules);

        if ($this->activeTab === 'announcements') {
            $data = [
                'announcement_type_id' => $this->type_id,
                'title' => $this->title,
                'message' => $this->message,
                'start_at' => $this->start_at ?: null,
                'end_at' => $this->end_at ?: null,
                'is_active' => $this->is_active,
                'status' => 'approved', // Admin creations are auto-approved
                'user_id' => Auth::id(),
            ];

            if ($this->isEditing) {
                Announcement::find($this->editId)->update($data);
            } else {
                Announcement::create($data);
            }
        } else {
            $data = [
                'spotlight_category_id' => $this->category_id,
                'title' => $this->title,
                'link' => $this->link,
                'sort_order' => $this->sort_order,
                'start_at' => $this->start_at ?: null,
                'end_at' => $this->end_at ?: null,
                'is_active' => $this->is_active,
                'status' => 'approved',
                'user_id' => Auth::id(),
            ];

            if ($this->image) {
                $data['image_path'] = $this->image->store('spotlights', 'public');
                
                // Cleanup old image on edit
                if ($this->isEditing && $this->existing_image && Storage::disk('public')->exists($this->existing_image)) {
                    Storage::disk('public')->delete($this->existing_image);
                }
            }

            if ($this->isEditing) {
                Spotlight::find($this->editId)->update($data);
            } else {
                Spotlight::create($data);
            }
        }

        session()->flash('success', 'Record saved successfully.');
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['title', 'message', 'type_id', 'category_id', 'link', 'image', 'existing_image', 'start_at', 'end_at', 'isEditing', 'editId']);
        $this->is_active = true;
        $this->sort_order = 0;
    }

    public function render()
    {
        return view('livewire.admin.content.content-settings', [
            'announcements' => Announcement::with('type')->latest()->get(),
            'spotlights' => Spotlight::with('category')->orderBy('sort_order', 'asc')->latest()->get(),
            'announcementTypes' => AnnouncementType::all(),
            'spotlightCategories' => SpotlightCategory::all(),
        ]);
    }
}
