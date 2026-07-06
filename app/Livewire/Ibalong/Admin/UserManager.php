<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\IbalongUser;

class UserManager extends Component
{
    use WithPagination;

    // Creation State
    public $name, $email, $role_id, $designation;

    // Edit Modal State
    public $editModalOpen = false;
    public $edit_user_id, $edit_name, $edit_email, $edit_role_id, $edit_designation;

    // Password Reset State
    public $passwordModalOpen = false;
    public $reset_user_id;
    public $admin_password; // To verify admin's identity
    public $generated_password; // To show the newly minted password

    public function createUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:ibalong_users,email',
            'role_id' => 'required|integer',
            'designation' => 'required|string|max:255',
        ]);

        $rawPassword = strtoupper(Str::random(8));

        $user = IbalongUser::create([
            'role_id' => $this->role_id,
            'name' => $this->name,
            'slug' => Str::slug($this->name) . '-' . strtolower(Str::random(5)),
            'email' => $this->email,
            'password' => Hash::make($rawPassword),
            'designation' => $this->designation,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->reset(['name', 'email', 'role_id', 'designation']);

        $roleName = $this->getRoleName($user->role_id);
        session()->flash('success', "ACCOUNT CREATED! 🚀 Give these credentials to the {$roleName}. Email: {$user->email} | Password: {$rawPassword}");
    }

    // --- EDIT USER LOGIC --- //

    public function openEditModal($id)
    {
        $user = IbalongUser::findOrFail($id);
        $this->edit_user_id = $user->id;
        $this->edit_name = $user->name;
        $this->edit_email = $user->email;
        $this->edit_role_id = $user->role_id;
        $this->edit_designation = $user->designation;

        $this->editModalOpen = true;
    }

    public function updateUser()
    {
        $this->validate([
            'edit_name' => 'required|string|max:255',
            'edit_email' => 'required|email|unique:ibalong_users,email,' . $this->edit_user_id,
            'edit_role_id' => 'required|integer',
            'edit_designation' => 'required|string|max:255',
        ]);

        $user = IbalongUser::findOrFail($this->edit_user_id);
        $user->update([
            'name' => $this->edit_name,
            'email' => $this->edit_email,
            'role_id' => $this->edit_role_id,
            'designation' => $this->edit_designation,
        ]);

        $this->closeModals();
        session()->flash('success', "Profile for '{$user->name}' has been updated.");
    }

    // --- PASSWORD RESET LOGIC --- //

    public function confirmPasswordReset($id)
    {
        $this->reset_user_id = $id;
        $this->admin_password = '';
        $this->generated_password = null;
        $this->passwordModalOpen = true;
    }

    public function executePasswordReset()
    {
        $this->validate([
            'admin_password' => 'required',
        ]);

        // Verify the logged-in admin's password before changing another user's data
        if (!Hash::check($this->admin_password, auth('ibalong')->user()->password)) {
            $this->addError('admin_password', 'Incorrect admin authorization password. Access Denied.');
            return;
        }

        $user = IbalongUser::findOrFail($this->reset_user_id);
        $newPassword = strtoupper(Str::random(8));

        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        // Display the new password in the modal
        $this->generated_password = $newPassword;
        session()->flash('success', "Password for {$user->name} has been securely reset.");
    }

    public function toggleStatus($userId)
    {
        $user = IbalongUser::findOrFail($userId);

        if ($user->id === auth('ibalong')->id()) {
            session()->flash('error', "You cannot deactivate your own account.");
            return;
        }

        $user->update(['is_active' => !$user->is_active]);
    }

    public function closeModals()
    {
        $this->editModalOpen = false;
        $this->passwordModalOpen = false;
        $this->reset(['edit_user_id', 'reset_user_id', 'admin_password', 'generated_password']);
    }

    private function getRoleName($roleId)
    {
        return match ((int) $roleId) {
            1 => 'Super Admin',
            2 => 'System Admin',
            3 => 'Cohort Team',
            4 => 'Judge',
            5 => 'Facilitator / Mentor',
            default => 'Unknown',
        };
    }

    public function render()
    {
        $users = IbalongUser::where('role_id', '!=', 3)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.ibalong.admin.user-manager', [
            'users' => $users,
        ])->layout('layouts.dashboard');
    }
}
