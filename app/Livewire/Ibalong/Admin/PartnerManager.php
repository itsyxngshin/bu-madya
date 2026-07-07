<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\IbalongPartner;
use Illuminate\Support\Facades\Storage;

class PartnerManager extends Component
{
    use WithFileUploads;

    // Create Form State
    public $name, $role, $logo, $emphasis = 'medium', $display_order = 0;

    // Edit Modal State
    public $editModalOpen = false;
    public $edit_id, $edit_name, $edit_role, $edit_emphasis, $edit_display_order;
    public $new_logo;
    public $existing_logo_path;

    // Rules for Creating
    protected $rules = [
        'name' => 'required|string|max:255',
        'role' => 'required|string|max:255',
        'logo' => 'required|image|max:2048', // Max 2MB
        'emphasis' => 'required|in:small,medium',
        'display_order' => 'required|integer',
    ];

    public function addPartner()
    {
        $this->validate();

        $path = $this->logo->store('partners', 'public');

        IbalongPartner::create([
            'name' => $this->name,
            'role' => $this->role,
            'logo_path' => $path,
            'emphasis' => $this->emphasis,
            'display_order' => $this->display_order,
            'is_active' => true,
        ]);

        $this->reset(['name', 'role', 'logo', 'emphasis', 'display_order']);
        session()->flash('success', 'Partner successfully added to the roster.');
    }

    // --- EDIT LOGIC --- //

    public function openEditModal($id)
    {
        $partner = IbalongPartner::findOrFail($id);

        $this->edit_id = $partner->id;
        $this->edit_name = $partner->name;
        $this->edit_role = $partner->role;
        $this->edit_emphasis = $partner->emphasis;
        $this->edit_display_order = $partner->display_order;
        $this->existing_logo_path = $partner->logo_path;
        $this->new_logo = null; // Clear any previous temporary uploads

        $this->editModalOpen = true;
    }

    public function updatePartner()
    {
        $this->validate([
            'edit_name' => 'required|string|max:255',
            'edit_role' => 'required|string|max:255',
            'edit_emphasis' => 'required|in:small,medium',
            'edit_display_order' => 'required|integer',
            'new_logo' => 'nullable|image|max:2048', // Image is optional during edit
        ]);

        $partner = IbalongPartner::findOrFail($this->edit_id);

        // Handle Image Replacement
        if ($this->new_logo) {
            // Delete old image from storage
            if (Storage::disk('public')->exists($partner->logo_path)) {
                Storage::disk('public')->delete($partner->logo_path);
            }
            // Save new image
            $partner->logo_path = $this->new_logo->store('partners', 'public');
        }

        // Update database records
        $partner->update([
            'name' => $this->edit_name,
            'role' => $this->edit_role,
            'emphasis' => $this->edit_emphasis,
            'display_order' => $this->edit_display_order,
        ]);

        $this->closeModals();
        session()->flash('success', 'Partner information updated successfully.');
    }

    public function closeModals()
    {
        $this->editModalOpen = false;
        $this->reset(['edit_id', 'edit_name', 'edit_role', 'edit_emphasis', 'edit_display_order', 'new_logo', 'existing_logo_path']);
    }

    // --- END EDIT LOGIC --- //

    public function toggleStatus($id)
    {
        $partner = IbalongPartner::findOrFail($id);
        $partner->update(['is_active' => !$partner->is_active]);
    }

    public function deletePartner($id)
    {
        $partner = IbalongPartner::findOrFail($id);

        // Delete the image file from storage
        if (Storage::disk('public')->exists($partner->logo_path)) {
            Storage::disk('public')->delete($partner->logo_path);
        }

        $partner->delete();
        session()->flash('success', 'Partner removed successfully.');
    }

    public function render()
    {
        $partners = IbalongPartner::orderBy('display_order', 'asc')->get();

        return view('livewire.ibalong.admin.partner-manager', [
            'partners' => $partners
        ])->layout('layouts.dashboard');
    }
}
