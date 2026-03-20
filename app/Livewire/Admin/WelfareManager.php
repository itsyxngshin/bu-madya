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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function viewTicket($id)
    {
        $this->selectedTicket = IncidentReport::findOrFail($id);
        $this->viewModalOpen = true;
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

    public function toggleWelfareAccess($userId)
    {
        // Security check: Only administrators can grant this access
        if (auth()->user()->role?->role_name !== 'administrator') {
            abort(403, 'Unauthorized action.');
        }

        $user = \App\Models\User::findOrFail($userId);
        
        // Flip the boolean switch (if true make false, if false make true)
        $user->can_manage_welfare = !$user->can_manage_welfare;
        $user->save();

        $status = $user->can_manage_welfare ? 'granted access to' : 'revoked access from';
        session()->flash('success', "Successfully {$status} {$user->name}.");
    }

    public function render()
    {
        $user = auth()->user();
        $role = $user->role?->role_name;

        // 1. Admins, STRAW Heads, and CSC Presidents always have access.
        $hasStandardAccess = in_array($role, ['administrator', 'straw_head', 'csc_president']);
        
        // 2. Organizations ONLY have access if the Admin flipped their switch to TRUE.
        $hasOrgAccess = ($role === 'organization' && $user->can_manage_welfare === 1);

        if (!$hasStandardAccess && !$hasOrgAccess) {
            abort(403, 'Unauthorized access. You have not been granted permission to view confidential welfare records.');
        }

        $query = IncidentReport::query()->latest();

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