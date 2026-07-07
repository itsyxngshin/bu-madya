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

    // Member Form State
    public $committee_id, $name, $affiliation, $designation, $role = 'Member', $display_order = 0, $photo;

    // Member Edit Modal State
    public $editModalOpen = false;
    public $edit_id, $edit_committee_id, $edit_name, $edit_affiliation, $edit_designation, $edit_role, $edit_display_order;
    public $new_photo, $existing_photo_path;

    // Quick Add Committee Modal State
    public $createCommitteeModalOpen = false;
    public $new_committee_name, $new_committee_order = 0;

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

        $this->committee_id = $committee->id; // Auto-select the newly created committee
        $this->createCommitteeModalOpen = false;
        $this->reset(['new_committee_name', 'new_committee_order']);
        session()->flash('success', 'Committee created successfully.');
    }

    public function addMember()
    {
        $this->validate([
            'committee_id' => 'required|exists:ibalong_committees,id',
            'name' => 'required|string|max:255',
            'affiliation' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'role' => 'required|in:Head,Member',
            'display_order' => 'required|integer',
            'photo' => 'nullable|image|max:2048', // Optional photo up to 2MB
        ]);

        $path = $this->photo ? $this->photo->store('committees', 'public') : null;

        IbalongCommitteeMember::create([
            'committee_id' => $this->committee_id,
            'name' => $this->name,
            'affiliation' => $this->affiliation,
            'designation' => $this->designation,
            'role' => $this->role,
            'photo_path' => $path,
            'display_order' => $this->display_order,
            'is_active' => true,
        ]);

        $this->reset(['name', 'affiliation', 'designation', 'role', 'display_order', 'photo']);
        session()->flash('success', 'Committee member added successfully.');
    }

    public function openEditModal($id)
    {
        $member = IbalongCommitteeMember::findOrFail($id);

        $this->edit_id = $member->id;
        $this->edit_committee_id = $member->committee_id;
        $this->edit_name = $member->name;
        $this->edit_affiliation = $member->affiliation;
        $this->edit_designation = $member->designation;
        $this->edit_role = $member->role;
        $this->edit_display_order = $member->display_order;
        $this->existing_photo_path = $member->photo_path;
        $this->new_photo = null;

        $this->editModalOpen = true;
    }

    public function updateMember()
    {
        $this->validate([
            'edit_committee_id' => 'required|exists:ibalong_committees,id',
            'edit_name' => 'required|string|max:255',
            'edit_affiliation' => 'nullable|string|max:255',
            'edit_designation' => 'nullable|string|max:255',
            'edit_role' => 'required|in:Head,Member',
            'edit_display_order' => 'required|integer',
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
            'affiliation' => $this->edit_affiliation,
            'designation' => $this->edit_designation,
            'role' => $this->edit_role,
            'display_order' => $this->edit_display_order,
        ]);

        $this->closeModals();
        session()->flash('success', 'Committee member updated successfully.');
    }

    public function closeModals()
    {
        $this->editModalOpen = false;
        $this->createCommitteeModalOpen = false;
        $this->reset(['edit_id', 'edit_committee_id', 'edit_name', 'edit_affiliation', 'edit_designation', 'edit_role', 'edit_display_order', 'new_photo', 'existing_photo_path', 'new_committee_name', 'new_committee_order']);
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
        session()->flash('success', 'Member removed from the roster.');
    }

    public function render()
    {
        $committees = IbalongCommittee::orderBy('display_order', 'asc')->get();

        $membersGrouped = IbalongCommitteeMember::with('committee')
            ->orderBy('role') // Heads first
            ->orderBy('display_order', 'asc')
            ->get()
            ->groupBy('committee.name');

        return view('livewire.ibalong.admin.committee-manager', [
            'committees' => $committees,
            'membersGrouped' => $membersGrouped,
        ])->layout('layouts.dashboard');
    }
}
