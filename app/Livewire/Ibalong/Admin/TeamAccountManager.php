<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\IbalongRegistration;
use App\Models\IbalongUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeamAccountManager extends Component
{
    use WithPagination;

    public $search = '';

    // Modals
    public $showModal = false;
    public $viewingTeam = null;
    public $passwordModalOpen = false;
    
    // Password Reset
    public $resettingUserId = null;
    public $admin_password = '';
    public $new_password = '';
    public $generated_password = '';

    public function render()
    {
        $teams = IbalongRegistration::with('user')
            ->where('status', 'approved')
            ->whereNotNull('user_id')
            ->when($this->search, function($query) {
                $query->where('team_name', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function($q) {
                          $q->where('email', 'like', '%' . $this->search . '%');
                      });
            })
            ->paginate(15);

        return view('livewire.ibalong.admin.team-account-manager', compact('teams'))
            ->layout('layouts.dashboard');
    }

    public function viewTeamDetails($id)
    {
        // Load all the relationships needed for the Neo-Brutalist Team Profile Modal
        $this->viewingTeam = IbalongRegistration::with(['members.skills', 'skills', 'communityAreas', 'onlineActivities'])->findOrFail($id);
        $this->showModal = true;
    }

    public function toggleAccountStatus($userId)
    {
        $user = IbalongUser::findOrFail($userId);
        $user->update(['is_active' => !$user->is_active]);
        session()->flash('success', 'Team access credentials updated.');
    }

    public function confirmPasswordReset($userId)
    {
        $this->resettingUserId = $userId;
        $this->admin_password = '';
        $this->new_password = '';
        $this->generated_password = '';
        $this->passwordModalOpen = true;
    }

    public function executePasswordReset()
    {
        $this->validate([
            'admin_password' => 'required|string',
            'new_password' => 'nullable|string|min:8',
        ]);

        if (!Hash::check($this->admin_password, auth('ibalong')->user()->password)) {
            $this->addError('admin_password', 'ACCESS DENIED: Incorrect administrator password.');
            return;
        }

        $user = IbalongUser::findOrFail($this->resettingUserId);
        $passwordToSet = $this->new_password ?: Str::random(10);

        $user->update(['password' => Hash::make($passwordToSet)]);
        $this->generated_password = $passwordToSet;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->passwordModalOpen = false;
        $this->viewingTeam = null;
    }
}