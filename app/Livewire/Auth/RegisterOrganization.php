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

        // Auto-generate a unique username from the Organization Name
        $baseUsername = Str::slug($this->name);
        $username = $baseUsername;
        $counter = 1;

        // Ensure it is completely unique in the database
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . '-' . $counter++;
        }

        $orgRole = Role::where('role_name', 'organization')->first();

        $user = User::create([
            'name' => $this->name,
            'username' => $username, // [NEW] Inject the generated username
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role_id' => $orgRole ? $orgRole->id : null, 
        ]);

        \App\Models\Profile::create([
            'user_id' => $user->id,
            // Pass the org name as the first name, and a placeholder for the last name 
            // (unless your database allows last_name to be nullable)
            'first_name' => $this->name, 
            'last_name' => '(Organization)', 
            'college_id' => $this->college_id ?? null, // If you assign them a college during creation
            'bio' => 'Official BU MADYA Partner Organization.', // A nice default bio
            'course' => null,
            'year_level' => null,
        ]);
        
        Auth::login($user);

        return redirect()->route('partner.dashboard'); // Redirect to their org dashboard
    }

    public function render()
    {
        return view('livewire.auth.register-organization');
    }
}
