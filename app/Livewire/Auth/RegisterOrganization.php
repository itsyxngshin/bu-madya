<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use App\Models\Role; // Assuming you have a Role model based on your previous code
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')]
class RegisterOrganization extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';

    public function register()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Find your organization role (Adjust 'organization' to whatever is in your database)
        $orgRole = Role::where('role_name', 'organization')->first();

        $user = User::create([
            'name' => $this->name, // We use the User's name field for the Org Name
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role_id' => $orgRole ? $orgRole->id : null,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard'); // Redirect to their org dashboard
    }

    public function render()
    {
        return view('livewire.auth.register-organization');
    }
}
