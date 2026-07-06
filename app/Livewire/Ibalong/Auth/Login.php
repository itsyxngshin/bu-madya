<?php

namespace App\Livewire\Ibalong\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    public function authenticate()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Use the custom 'ibalong' guard we set up in config/auth.php
        if (Auth::guard('ibalong')->attempt(['email' => $this->email, 'password' => $this->password, 'is_active' => true], $this->remember)) {
            
            // Update last login timestamp
            $user = Auth::guard('ibalong')->user();
            $user->update(['last_login_at' => now()]);

            // Regenerate session to prevent fixation
            session()->regenerate();

            // Redirect to the dashboard (you will create this route later)
            return redirect()->intended(route('ibalong.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records, or the account is inactive.',
        ]);
    }

    public function render()
    {
        return view('livewire.ibalong.auth.login')->layout('layouts.ibalong-layout');
    }
}