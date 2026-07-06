<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\IbalongPartner;
use Illuminate\Support\Facades\Storage;

class PartnerManager extends Component
{
    use WithFileUploads;

    public $name, $role, $logo, $emphasis = 'medium', $display_order = 0;
    
    // For the edit modal
    public $editModalOpen = false;
    public $edit_id, $edit_name, $edit_role, $edit_emphasis, $edit_display_order;
    public $new_logo;
    public $existing_logo_path;

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
        ]);

        $this->reset(['name', 'role', 'logo', 'emphasis', 'display_order']);
        session()->flash('success', 'Partner successfully added to the roster.');
    }

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