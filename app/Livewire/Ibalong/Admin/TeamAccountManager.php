<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\IbalongRegistration;
use App\Models\IbalongUser;
use App\Mail\TeamCredentialsMail; // Injecting the Mailer

class TeamAccountManager extends Component
{
    use WithPagination;

    public $search = '';

    // Modal State for Profile
    public $showModal = false;
    public $viewingTeam = null;

    // Modal State for Password Reset
    public $passwordModalOpen = false;
    public $targetUserId = null;
    public $new_password = '';
    public $admin_password = '';
    public $generated_password = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function viewTeamDetails($teamId)
    {
        $this->viewingTeam = IbalongRegistration::with('members')->findOrFail($teamId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->viewingTeam = null;
        $this->passwordModalOpen = false;
        $this->targetUserId = null;
        $this->new_password = '';
        $this->admin_password = '';
        $this->generated_password = null;
    }

    public function toggleAccountStatus($userId)
    {
        $user = IbalongUser::findOrFail($userId);
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'unlocked' : 'locked';
        session()->flash('success', "Account successfully $status.");
    }

    public function confirmPasswordReset($userId)
    {
        $this->targetUserId = $userId;
        $this->passwordModalOpen = true;
        $this->new_password = '';
        $this->admin_password = '';
        $this->generated_password = null;
    }

    public function executePasswordReset()
    {
        $this->validate([
            'admin_password' => 'required',
            'new_password' => 'nullable|min:8'
        ]);

        // 1. Verify Admin Clearance
        if (!Hash::check($this->admin_password, auth('ibalong')->user()->password)) {
            $this->addError('admin_password', 'Admin authorization failed. Incorrect password.');
            return;
        }

        // 2. Fetch the Target User and Team
        $user = IbalongUser::findOrFail($this->targetUserId);
        $team = IbalongRegistration::with('members')->where('user_id', $user->id)->first();

        if (!$team) {
            $this->addError('admin_password', 'System Error: User has no associated team.');
            return;
        }

        // 3. Generate and Apply the Password
        $rawPassword = $this->new_password ?: strtoupper(Str::random(8));

        $user->update([
            'password' => Hash::make($rawPassword)
        ]);

        $this->generated_password = $rawPassword;

        // 4. Dispatch the Automated Email
        $teamLeader = $team->members->where('team_role', 'Team Leader')->first();
        $leaderName = $teamLeader ? $teamLeader->full_name : 'Team Leader';

        Mail::to($user->email)->send(new TeamCredentialsMail(
            $team->team_name,
            $leaderName,
            $user->email,
            $rawPassword
        ));

        session()->flash('success', "Credentials successfully reset and emailed to {$user->email}.");
    }

    public function render()
    {
        $teams = IbalongRegistration::with('user', 'members')
            ->where('status', 'approved')
            ->where(function($query) {
                $query->where('team_name', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function($q) {
                          $q->where('email', 'like', '%' . $this->search . '%');
                      });
            })
            ->paginate(15);

        return view('livewire.ibalong.admin.team-account-manager', compact('teams'))
            ->layout('layouts.dashboard');
    }
}
