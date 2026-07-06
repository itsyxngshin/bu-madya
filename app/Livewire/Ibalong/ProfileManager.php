<?php

namespace App\Livewire\Ibalong;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileManager extends Component
{
    public $name, $email, $designation;
    public $current_password, $new_password, $new_password_confirmation;

    public function mount()
    {
        $user = auth('ibalong')->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->designation = $user->designation;
    }

    public function updateProfile()
    {
        $user = auth('ibalong')->user();

        $this->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
        ]);

        $user->update([
            'name' => $this->name,
            'designation' => $this->designation,
        ]);

        session()->flash('profile_success', 'Profile information updated successfully.');
    }

    public function updatePassword()
    {
        $user = auth('ibalong')->user();

        $this->validate([
            'current_password' => 'required|current_password:ibalong',
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        
        session()->flash('password_success', 'Your password has been securely updated.');
    }

    public function render()
    {
        return view('livewire.ibalong.profile-manager')->layout('layouts.dashboard');
    }
}