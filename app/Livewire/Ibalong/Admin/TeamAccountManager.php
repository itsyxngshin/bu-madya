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

    public function exportTeamRoster()
    {
        // 1. Enforce RBAC - Only Admins/Super Admins can extract data
        if (!in_array(auth('ibalong')->user()->role_id, [1, 2])) {
            abort(403, 'ACCESS DENIED: Data extraction requires Admin clearance.');
        }

        // 2. Fetch all approved teams and their members
        $teams = IbalongRegistration::with('members')->where('status', 'approved')->get();

        // 3. Set the CSV Headers
        $csvData = "Cohort / Team Name,Affiliation,Member Full Name,Role,Email Address,Mobile Number\n";

        // 4. Loop through and map the data
        foreach ($teams as $team) {
            foreach ($team->members as $member) {
                // We wrap variables in quotes to prevent commas inside names/affiliations from breaking the columns
                $csvData .= sprintf(
                    '"%s","%s","%s","%s","%s","%s"' . "\n",
                    addslashes($team->team_name),
                    addslashes($team->affiliation ?? 'N/A'),
                    addslashes($member->full_name),
                    addslashes($member->team_role),
                    addslashes($member->email_address),
                    addslashes($member->mobile_number ?? 'N/A')
                );
            }
        }

        // 5. Trigger the download sequence
        return response()->streamDownload(function () use ($csvData) {
            echo $csvData;
        }, 'HOI_2026_Cohort_Roster_' . now()->format('Ymd_His') . '.csv');
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
