<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\IncidentReport;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')] // Using your existing admin layout!
class WelfareManager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = ''; // Pending, Under Review, Resolved

    // For the View Modal
    public $selectedTicket = null;
    public $viewModalOpen = false;
    public $adminNotes = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function viewTicket($id)
    {
        $this->selectedTicket = IncidentReport::findOrFail($id);
        $this->adminNotes = $this->selectedTicket->admin_notes; // Load the notes!
        $this->viewModalOpen = true;
    }

    // 3. Add this brand new method to save the notes
    public function saveNotes()
    {
        if ($this->selectedTicket) {
            $this->selectedTicket->update([
                'admin_notes' => $this->adminNotes
            ]);
            session()->flash('success', 'Notes updated successfully for ' . $this->selectedTicket->case_number);
        }
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }


    public function closeTicket()
    {
        $this->selectedTicket = null;
        $this->viewModalOpen = false;
    }

    public function updateStatus($status)
    {
        if ($this->selectedTicket) {
            $this->selectedTicket->update(['status' => $status]);
            session()->flash('success', 'Case ' . $this->selectedTicket->case_number . ' status updated to ' . $status . '.');
        }
    }

    public function render()
    {
        $query = IncidentReport::with('assignedOrganization')->latest();
        $user = auth()->user();

        // [SECURITY LOCKDOWN]
        // If the logged-in user is an organization, force the query to ONLY fetch their assigned tickets.
        if ($user->role?->role_name === 'organization') {
            $query->where('assigned_org_id', $user->id);
        }


        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('case_number', 'like', '%' . $this->search . '%')
                  ->orWhere('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }

        $tickets = $query->paginate(15);

        return view('livewire.admin.welfare-manager', compact('tickets'));
    }
}
