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
        $query = Event::query()
            ->where('title', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc');
        
        // 2. Apply multi-tenant security scoping
        $userRole = auth()->user()->role?->role_name;
        if (!in_array($userRole, ['administrator', 'director'])) {
            // Lock organizations to only their own events
            $query->where('user_id', auth()->id());
        }

        // 3. Paginate ONLY at the very end of the query chain!
        $events = $query->paginate(10);

        return view('livewire.admin.event-index', [
            'events' => $events
        ]);
    }
}