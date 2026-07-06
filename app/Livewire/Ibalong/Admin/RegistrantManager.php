<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\IbalongRegistration;

class RegistrantManager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'pending';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function approveTeam($id)
    {
        // For now, we update the status. The password minting/linking logic 
        // will go here once we decide on the exact authentication flow.
        $registration = IbalongRegistration::findOrFail($id);
        $registration->update(['status' => 'approved']);
        
        session()->flash('message', "Cohort '{$registration->team_name}' has been approved.");
    }

    public function rejectTeam($id)
    {
        $registration = IbalongRegistration::findOrFail($id);
        $registration->update(['status' => 'rejected']);
        
        session()->flash('message', "Cohort '{$registration->team_name}' was rejected.");
    }

    public function render()
    {
        $registrations = IbalongRegistration::with('members')
            ->where('status', 'like', '%' . $this->statusFilter . '%')
            ->where('team_name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.ibalong.admin.registrant-manager', [
            'registrations' => $registrations
        ])->layout('layouts.dashboard');
    }
}