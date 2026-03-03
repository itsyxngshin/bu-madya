<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EventFrame;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')]
class FrameManager extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'all'; // all, pending, approved

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilter() { $this->resetPage(); }

    public function toggleApproval(EventFrame $frame)
    {
        $frame->update(['is_approved' => !$frame->is_approved]);

        $status = $frame->is_approved ? 'approved and published.' : 'unpublishd.';
        session()->flash('message', "Frame successfully {$status}");
    }

    public function deleteFrame(EventFrame $frame)
    {
        if ($frame->frame_image) {
            Storage::disk('public')->delete($frame->frame_image);
        }
        $frame->delete();
        session()->flash('message', 'Frame permanently deleted.');
    }

    public function render()
    {
        $query = EventFrame::with('user')->latest();

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
        }

        if ($this->filter === 'pending') {
            $query->where('is_approved', false);
        } elseif ($this->filter === 'approved') {
            $query->where('is_approved', true);
        }

        return view('livewire.admin.frame-manager', [
            'frames' => $query->paginate(12)
        ]);
    }
}
