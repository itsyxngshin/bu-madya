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
        
        // Delete cover image if it exists
        if ($event->cover_image) {
            Storage::disk('public')->delete($event->cover_image);
        }

        $event->delete();
        session()->flash('message', 'Event deleted successfully.');
    }

    public function render()
    {
        $events = Event::where('title', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $userRole = auth()->user()->role?->role_name;
        if (!in_array($userRole, ['administrator', 'director'])) {
            // If they are just an organization, lock them to their own events
            $events = $events->where('user_id', auth()->id());
        }

        return view('livewire.admin.event-index', [
            'events' => $events
        ]); // Adjust if you use a specific admin layout
    }
}