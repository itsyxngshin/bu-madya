<?php

namespace App\Livewire\Admin\Content;

use Livewire\Component;
use App\Models\Announcement;
use App\Models\Spotlight;

class RequestsBoard extends Component
{
    public $rejectionReason = '';
    public $selectedId = null;
    public $selectedType = null; // 'announcement' or 'spotlight'
    public $showRejectModal = false;

    public function approve($type, $id)
    {
        $model = $type === 'announcement' ? Announcement::find($id) : Spotlight::find($id);

        if ($model) {
            $model->update([
                'status' => 'approved',
                'is_active' => true // Automatically activate upon approval
            ]);

            session()->flash('success', ucfirst($type) . ' approved successfully.');
        }
    }

    public function confirmReject($type, $id)
    {
        $this->selectedType = $type;
        $this->selectedId = $id;
        $this->rejectionReason = '';
        $this->showRejectModal = true;
    }

    public function processReject()
    {
        $this->validate([
            'rejectionReason' => 'required|string|min:5'
        ]);

        $model = $this->selectedType === 'announcement'
            ? Announcement::find($this->selectedId)
            : Spotlight::find($this->selectedId);

        if ($model) {
            $model->update([
                'status' => 'rejected',
                'admin_remarks' => $this->rejectionReason,
                'is_active' => false
            ]);

            session()->flash('success', 'Request rejected and sender notified.');
        }

        $this->showRejectModal = false;
    }

    public function render()
    {

        $layoutFile = in_array(auth()->user()->role?->role_name, ['administrator', 'organization'])
                ? 'layouts.madya-admin-deck'
                : 'layouts.madya-admin';

        return view('livewire.admin.content.requests-board', [
            'pendingAnnouncements' => Announcement::with(['type', 'user'])->where('status', 'pending')->latest()->get(),
            'pendingSpotlights' => Spotlight::with(['category', 'user'])->where('status', 'pending')->latest()->get(),
        ])->layout($layoutFile); // Update layout if using a specific admin layout
    }
}
