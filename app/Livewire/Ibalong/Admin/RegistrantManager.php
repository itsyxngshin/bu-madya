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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function approveTeam($id)
    {
        // 1. Fetch the registration and its members
        $registration = IbalongRegistration::with('members')->findOrFail($id);

        // Prevent double-processing
        if ($registration->status === 'approved') {
            return;
        }

        // 2. Extract Team Leader's email (Fallback to a generated one if missing)
        $teamLeader = $registration->members->where('team_role', 'Team Leader')->first();
        $email = $teamLeader ? $teamLeader->email_address : 'team'.$id.'@bumadya.org';

        // 3. Generate a secure, 8-character uppercase password
        $rawPassword = strtoupper(Str::random(8));

        // 4. Create the Ibalong User Account (Role ID 3 = Team)
        $user = IbalongUser::create([
            'role_id' => 3, 
            'name' => $registration->team_name,
            'slug' => Str::slug($registration->team_name) . '-' . strtolower(Str::random(5)),
            'email' => $email,
            'password' => Hash::make($rawPassword),
            'is_active' => true,
            'email_verified_at' => now(), // Auto-verify for hackathon speed
        ]);

        // 5. Link account and update status
        $registration->update([
            'status' => 'approved',
            'user_id' => $user->id,
            'account_creation_status' => 'Created'
        ]);
        
        // 6. Flash the success message WITH the credentials so the Admin can copy them
        session()->flash('message', "APPROVED! 🚀 Credentials for {$registration->team_name} — Email: {$email} | Password: {$rawPassword}");
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