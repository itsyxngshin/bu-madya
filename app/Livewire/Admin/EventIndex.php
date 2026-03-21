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

    public function updatingSearch()
    {
        $this->resetPage();
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
        // 1. Start the query builder using the EVENT model
        // Note: I left 'creator' and 'collaborators' here assuming Events share the same permissions,
        // but removed 'responses' since that is usually just for Evaluations/Forms!
        $query = \App\Models\Event::with(['creator'])->latest();

        // 2. Apply Security / Role Scoping
        if (auth()->user()->role?->role_name !== 'administrator') {
            $query->where(function ($q) {
                // Show it if they created it...
                $q->where('created_by', auth()->id());
            });
        }

        // 3. Apply search filtering FIRST
        if (!empty($this->search)) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        // 4. Paginate ONLY ONCE at the very end of the query chain!
        $events = $query->paginate(10);

        // 5. Return the correct Event view with the Event data
        return view('livewire.admin.event-index', [
            'events' => $events
        ]);
    }
}
