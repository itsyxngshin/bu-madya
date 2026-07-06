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

    public $name, $email, $role_id, $designation;

    // Rules for creating a new user
    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:ibalong_users,email',
        'role_id' => 'required|integer',
        'designation' => 'required|string|max:255',
    ];

    public function createUser()
    {
        $this->validate();

        // Generate a random 8-character uppercase password
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

        // Reset the form
        $this->reset(['name', 'email', 'role_id', 'designation']);

        // Flash the credentials to the admin
        $roleName = $this->getRoleName($user->role_id);
        session()->flash('success', "ACCOUNT CREATED! 🚀 Give these credentials to the {$roleName}. Email: {$user->email} | Password: {$rawPassword}");
    }

    public function toggleStatus($userId)
    {
        $user = IbalongUser::findOrFail($userId);
        
        // Prevent super admins from deactivating themselves
        if ($user->id === auth('ibalong')->id()) {
            session()->flash('error', "You cannot deactivate your own account.");
            return;
        }

        $user->update(['is_active' => !$user->is_active]);
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
        // Fetch all users except Teams (Role 3), ordered by newest
        $users = IbalongUser::where('role_id', '!=', 3)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.ibalong.admin.user-manager', [
            'users' => $users,
        ])->layout('layouts.dashboard');
    }
}