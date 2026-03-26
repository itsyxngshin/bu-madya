<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')]
class EventIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $manageModalOpen = false;
    public $selectedEventId = null;
    public $selectedOrgToAdd = ''; // Holds the dropdown selection
    public $orgSearch = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // [NEW] Reset pagination when searching for organizations
    public function updatingOrgSearch()
    {
        $this->resetPage();
    }

    // [NEW] Open the Modal
    public function openCollaborators($id)
    {
        $event = Event::findOrFail($id);

        $userRole = auth()->user()->role?->role_name;
        $isAdmin = in_array($userRole, ['administrator', 'director']);

        if ($event->user_id !== auth()->id() && !$isAdmin) {
            abort(403, 'You do not have permission to manage collaborators for this event.');
        }

        $this->selectedEventId = $id;
        $this->manageModalOpen = true;
        $this->selectedOrgToAdd = ''; // Clear previous selections
    }

    public function closeCollaborators()
    {
        $this->manageModalOpen = false;
        $this->selectedEventId = null;
        $this->selectedOrgToAdd = '';
    }

    // [NEW] Explicitly Add a Collaborator
    public function addCollaborator()
    {
        $this->validate([
            'selectedOrgToAdd' => 'required|exists:users,id'
        ], [
            'selectedOrgToAdd.required' => 'Please select an organization first.'
        ]);

        if ($this->selectedEventId) {
            $event = Event::findOrFail($this->selectedEventId);
            // syncWithoutDetaching prevents errors if they accidentally try to add them twice
            $event->collaborators()->syncWithoutDetaching([$this->selectedOrgToAdd]);

            $this->selectedOrgToAdd = ''; // Reset the dropdown
            session()->flash('collaborator_msg', 'Organization granted access.');
        }
    }

    // [NEW] Explicitly Remove a Collaborator
    public function removeCollaborator($userId)
    {
        if ($this->selectedEventId) {
            $event = Event::findOrFail($this->selectedEventId);
            $event->collaborators()->detach($userId);
            session()->flash('collaborator_msg', 'Organization access revoked.');
        }
    }

    public function render()
    {
        // 1. Fetch Events
        $query = Event::query()->latest();
        $userRole = auth()->user()->role?->role_name;
        $isAdmin = in_array($userRole, ['administrator', 'director']);

        if (!$isAdmin) {
            $query->where(function ($q) {
                $q->where('user_id', auth()->id())
                  ->orWhereHas('collaborators', function ($subQuery) {
                      $subQuery->where('user_id', auth()->id());
                  });
            });
        }

        if (!empty($this->search)) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        $events = $query->paginate(10);

        // 2. Fetch Organizations for the Modal
        $currentCollaborators = collect();
        $availableOrgs = collect();

        if ($this->manageModalOpen && $this->selectedEventId) {
            $event = Event::with('collaborators')->findOrFail($this->selectedEventId);
            $currentCollaborators = $event->collaborators;
            $currentIds = $currentCollaborators->pluck('id')->toArray();

            // Fetch organizations that DO NOT currently have access (for the dropdown)
            $availableOrgs = User::whereHas('role', function($q) {
                $q->where('role_name', 'organization');
            })
            ->where('id', '!=', $event->user_id) // Exclude the creator
            ->whereNotIn('id', $currentIds)      // Exclude those who already have access
            ->orderBy('name')
            ->get();
        }

        return view('livewire.admin.event-index', [
            'events' => $events,
            'currentCollaborators' => $currentCollaborators,
            'availableOrgs' => $availableOrgs,
        ]);
    }

    public function delete($id)
    {
        $event = Event::findOrFail($id);

        // [SECURITY FIX] Ensure only the owner OR an admin can delete
        $userRole = auth()->user()->role?->role_name;
        $isAdmin = in_array($userRole, ['administrator', 'director']);

        if ($event->user_id !== auth()->id() && !$isAdmin) {
            abort(403, 'You do not have permission to delete this event.');
        }

        // Delete cover image if it exists
        if ($event->cover_image) {
            Storage::disk('public')->delete($event->cover_image);
        }

        $event->delete();
        session()->flash('message', 'Event deleted successfully.');
    }

    public function render()
    {
        // 1. Fetch Events
        $query = Event::query()->latest();
        $userRole = auth()->user()->role?->role_name;
        $isAdmin = in_array($userRole, ['administrator', 'director']);

        if (!$isAdmin) {
            $query->where(function ($q) {
                $q->where('user_id', auth()->id())
                  ->orWhereHas('collaborators', function ($subQuery) {
                      $subQuery->where('user_id', auth()->id());
                  });
            });
        }

        if (!empty($this->search)) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        $events = $query->paginate(10);

        // 2. [NEW] Fetch Organizations for the Modal (Only if modal is open)
        $organizations = collect();
        $currentCollaboratorIds = [];

        if ($this->manageModalOpen && $this->selectedEventId) {
            $event = Event::findOrFail($this->selectedEventId);
            $currentCollaboratorIds = $event->collaborators->pluck('id')->toArray();

            // Fetch users with the 'organization' role
            $orgQuery = User::whereHas('role', function($q) {
                $q->where('role_name', 'organization');
            });

            if (!empty($this->orgSearch)) {
                $orgQuery->where('name', 'like', '%' . $this->orgSearch . '%');
            }

            // Get the list, excluding the event creator themselves (they already have access)
            $organizations = $orgQuery->where('id', '!=', $event->user_id)->take(15)->get();
        }

        return view('livewire.admin.event-index', [
            'events' => $events,
            'organizations' => $organizations,
            'currentCollaboratorIds' => $currentCollaboratorIds,
        ]);
    }
}
