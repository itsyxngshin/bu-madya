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
        // 1. Start the query builder
        // (Removed the 'creator' and 'collaborators' relationships)
        $query = Event::query()->latest();

        // 2. Apply Security / Role Scoping
        // We will use the exact same logic you used in your delete method!
        $userRole = auth()->user()->role?->role_name;
        $isAdmin = in_array($userRole, ['administrator', 'director']);

        if (!$isAdmin) {
            // If they are not an Admin or Director, ONLY show events they created themselves
            $query->where('user_id', auth()->id());
        }

        // 3. Apply search filtering
        if (!empty($this->search)) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        // 4. Paginate
        $events = $query->paginate(10);

        return view('livewire.admin.event-index', [
            'events' => $events
        ]);
    }
}
