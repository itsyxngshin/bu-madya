<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')]
class EventIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $manageModalOpen = false;
    public $selectedEventId = null;
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

        // Security: Only the Creator, Admin, or Director can manage collaborators
        $userRole = auth()->user()->role?->role_name;
        $isAdmin = in_array($userRole, ['administrator', 'director']);

        if ($event->user_id !== auth()->id() && !$isAdmin) {
            abort(403, 'You do not have permission to manage collaborators for this event.');
        }

        $this->selectedEventId = $id;
        $this->manageModalOpen = true;
        $this->orgSearch = ''; // Clear previous searches
    }

    // [NEW] Close the Modal
    public function closeCollaborators()
    {
        $this->manageModalOpen = false;
        $this->selectedEventId = null;
    }

    // [NEW] The Magic Toggle Method
    public function toggleCollaborator($userId)
    {
        if ($this->selectedEventId) {
            $event = Event::findOrFail($this->selectedEventId);
            
            // Laravel magically adds them if they aren't there, or removes them if they are!
            $event->collaborators()->toggle($userId);
        }
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
