<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Proposal;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin')] // Assuming you have an admin layout
class ProposalsShow extends Component
{
    public Proposal $proposal;
    public $admin_remarks = '';

    public function mount(Proposal $proposal)
    {
        $this->proposal = $proposal;
        
        // Eager load relationships for the view
        $this->proposal->load(['objectives', 'user', 'college']);
        
        // Pre-fill remarks if they exist
        $this->admin_remarks = $proposal->admin_remarks;
    }

    public function updateStatus($status)
    {
        // 1. Validate Status
        if (!in_array($status, ['approved', 'rejected', 'returned'])) {
            return;
        }

        // 2. Validate Remarks (Required if Rejecting or Returning)
        if (in_array($status, ['rejected', 'returned']) && empty($this->admin_remarks)) {
            $this->addError('admin_remarks', 'Feedback is required when rejecting or returning a proposal.');
            return;
        }

        // 3. Update Database
        $this->proposal->update([
            'status' => $status,
            'admin_remarks' => $this->admin_remarks
        ]);

        // 4. (Optional) Send Email Notification Logic Here
        // Mail::to($this->proposal->email)->send(new ProposalStatusChanged($this->proposal));

        session()->flash('message', "Proposal marked as " . ucfirst($status));
    }

    public function render()
    {
        return view('livewire.admin.proposals-show');
    }
}
