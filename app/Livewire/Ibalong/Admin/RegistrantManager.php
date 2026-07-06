<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\IbalongRegistration;
use App\Models\IbalongUser;

class RegistrantManager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'pending';
    
    // Modal State
    public $showModal = false;
    public $viewingTeam = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Load full team details and open modal
    public function viewTeamDetails($id)
    {
        // Eager load all the pivot relationships so we don't hit N+1 query issues
        $this->viewingTeam = IbalongRegistration::with([
            'members', 
            'skills', 
            'communityAreas', 
            'experiences', 
            'onlineActivities'
        ])->findOrFail($id);

        $this->showModal = true;
    }

    // Close the modal
    public function closeModal()
    {
        $this->showModal = false;
        $this->viewingTeam = null;
    }

    public function approveTeam($id)
    {
        $registration = IbalongRegistration::with('members')->findOrFail($id);

        if ($registration->status === 'approved') return;

        $teamLeader = $registration->members->where('team_role', 'Team Leader')->first();
        $email = $teamLeader ? $teamLeader->email_address : 'team'.$id.'@bumadya.org';
        $rawPassword = strtoupper(Str::random(8));

        $user = IbalongUser::create([
            'role_id' => 3, 
            'name' => $registration->team_name,
            'slug' => Str::slug($registration->team_name) . '-' . strtolower(Str::random(5)),
            'email' => $email,
            'password' => Hash::make($rawPassword),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $registration->update([
            'status' => 'approved',
            'user_id' => $user->id,
            'account_creation_status' => 'Created'
        ]);
        
        session()->flash('message', "APPROVED! 🚀 Credentials for {$registration->team_name} — Email: {$email} | Password: {$rawPassword}");
        
        // If they approved from inside the modal, close it.
        $this->closeModal();
    }

    public function rejectTeam($id)
    {
        $registration = IbalongRegistration::findOrFail($id);
        $registration->update(['status' => 'rejected']);
        
        session()->flash('message', "Cohort '{$registration->team_name}' was rejected.");
        $this->closeModal();
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