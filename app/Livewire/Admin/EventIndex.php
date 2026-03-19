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
        // 1. Start the query builder (DO NOT paginate yet)
        $query = Evaluation::with(['creator', 'collaborators'])->withCount('responses')->latest();

        if (auth()->user()->role?->role_name !== 'administrator') {
            $query->where(function ($q) {
                // Show it if they created it...
                $q->where('created_by', auth()->id())
                  // OR if they are a collaborator
                  ->orWhereHas('collaborators', function ($q2) {
                      $q2->where('user_id', auth()->id());
                  });
            });
        }

        $evaluations = $query->paginate(10);
        // 2. Apply search filtering (if needed)
        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        // 3. Paginate ONLY at the very end of the query chain!
        $events = $query->paginate(10);

        return view('livewire.admin.event-index', [
            'events' => $events
        ]);
    }
}
