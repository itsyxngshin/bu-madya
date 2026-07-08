<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\IbalongCommittee;
use App\Models\IbalongCommitteeMember;
use Illuminate\Support\Facades\Storage;

class CommitteeManager extends Component
{
    use WithFileUploads;

    // Member Creation Form
    public $committee_id, $name, $email, $mobile_number, $affiliation, $designation, $motivation, $role = 'Member', $display_order = 0, $photo;
    
    // Member Edit Modal State
    public $editModalOpen = false;
    public $edit_id, $edit_committee_id, $edit_name, $edit_email, $edit_mobile_number, $edit_affiliation, $edit_designation, $edit_motivation, $edit_role, $edit_display_order;
    public $new_photo, $existing_photo_path;

    // Quick Add Committee Modal
    public $createCommitteeModalOpen = false;
    public $new_committee_name, $new_committee_order = 0;

    // Edit Committee Modal State
    public $editCommitteeModalOpen = false;
    public $target_committee_id, $edit_committee_name, $edit_committee_order;

    // --- COMMITTEE LOGIC ---

    public function saveNewCommittee()
    {
        $this->validate([
            'new_committee_name' => 'required|string|max:255|unique:ibalong_committees,name',
            'new_committee_order' => 'required|integer',
        ]);

        $committee = IbalongCommittee::create([
            'name' => $this->new_committee_name,
            'display_order' => $this->new_committee_order,
            'is_active' => true,
        ]);

        $this->committee_id = $committee->id;
        $this->createCommitteeModalOpen = false;
        $this->reset(['new_committee_name', 'new_committee_order']);
        session()->flash('success', 'Committee created successfully.');
    }

    public function openEditCommitteeModal($id)
    {
        $committee = IbalongCommittee::findOrFail($id);
        $this->target_committee_id = $committee->id;
        $this->edit_committee_name = $committee->name;
        $this->edit_committee_order = $committee->display_order;
        
        $this->editCommitteeModalOpen = true;
    }

    public function updateCommittee()
    {
        $this->validate([
            'edit_committee_name' => 'required|string|max:255|unique:ibalong_committees,name,' . $this->target_committee_id,
            'edit_committee_order' => 'required|integer',
        ]);

        $committee = IbalongCommittee::findOrFail($this->target_committee_id);
        $committee->update([
            'name' => $this->edit_committee_name,
            'display_order' => $this->edit_committee_order,
        ]);

        $this->closeModals();
        session()->flash('success', 'Committee updated successfully.');
    }

    // --- MEMBER LOGIC ---

    public function addMember()
    {
        $this->validate([
            'committee_id' => 'required|exists:ibalong_committees,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'role' => 'required|in:Head,Member',
            'photo' => 'nullable|image|max:2048',
        ]);

        $path = $this->photo ? $this->photo->store('committees', 'public') : null;

        IbalongCommitteeMember::create([
            'committee_id' => $this->committee_id,
            'name' => $this->name,
            'email' => $this->email,
            'mobile_number' => $this->mobile_number,
            'affiliation' => $this->affiliation,
            'designation' => $this->designation,
            'motivation' => $this->motivation,
            'role' => $this->role,
            'photo_path' => $path,
            'display_order' => $this->display_order,
            'is_active' => true,
        ]);

        $this->reset(['name', 'email', 'mobile_number', 'affiliation', 'designation', 'motivation', 'role', 'display_order', 'photo']);
        session()->flash('success', 'Committee member added successfully.');
    }

    public function openEditModal($id)
    {
        $member = IbalongCommitteeMember::findOrFail($id);
        
        $this->edit_id = $member->id;
        $this->edit_committee_id = $member->committee_id;
        $this->edit_name = $member->name;
        $this->edit_email = $member->email;
        $this->edit_mobile_number = $member->mobile_number;
        $this->edit_affiliation = $member->affiliation;
        $this->edit_designation = $member->designation;
        $this->edit_motivation = $member->motivation;
        $this->edit_role = $member->role;
        $this->edit_display_order = $member->display_order;
        $this->existing_photo_path = $member->photo_path;
        
        $this->editModalOpen = true;
    }

    public function updateMember()
    {
        $this->validate([
            'edit_committee_id' => 'required|exists:ibalong_committees,id',
            'edit_name' => 'required|string|max:255',
            'edit_role' => 'required|in:Head,Member',
            'new_photo' => 'nullable|image|max:2048',
        ]);

        $member = IbalongCommitteeMember::findOrFail($this->edit_id);

        if ($this->new_photo) {
            if ($member->photo_path && Storage::disk('public')->exists($member->photo_path)) {
                Storage::disk('public')->delete($member->photo_path);
            }
            $member->photo_path = $this->new_photo->store('committees', 'public');
        }

        $member->update([
            'committee_id' => $this->edit_committee_id,
            'name' => $this->edit_name,
            'email' => $this->edit_email,
            'mobile_number' => $this->edit_mobile_number,
            'affiliation' => $this->edit_affiliation,
            'designation' => $this->edit_designation,
            'motivation' => $this->edit_motivation,
            'role' => $this->edit_role,
            'display_order' => $this->edit_display_order,
        ]);

        $this->closeModals();
        session()->flash('success', 'Member updated successfully.');
    }

    public function toggleStatus($id)
    {
        $member = IbalongCommitteeMember::findOrFail($id);
        $member->update(['is_active' => !$member->is_active]);
    }

    public function deleteMember($id)
    {
        $member = IbalongCommitteeMember::findOrFail($id);
        if ($member->photo_path && Storage::disk('public')->exists($member->photo_path)) {
            Storage::disk('public')->delete($member->photo_path);
        }
        $member->delete();
        session()->flash('success', 'Member removed.');
    }

    public function closeModals()
    {
        $this->editModalOpen = false;
        $this->createCommitteeModalOpen = false;
        $this->editCommitteeModalOpen = false;
        
        $this->reset([
            'edit_id', 'new_photo', 'existing_photo_path', 
            'new_committee_name', 'new_committee_order',
            'target_committee_id', 'edit_committee_name', 'edit_committee_order'
        ]);
    }

    public function render()
    {
        // Fetch Committees with their members pre-loaded and sorted
        $committees = IbalongCommittee::with(['members' => function($query) {
            $query->orderBy('role', 'asc')->orderBy('display_order', 'asc');
        }])->orderBy('display_order', 'asc')->get();

        return view('livewire.ibalong.admin.committee-manager', [
            'committees' => $committees,
        ])->layout('layouts.dashboard');
    }
}