<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;

class LoginResponses implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = Auth::user();

        // 1. ADMINS -> Admin Dashboard
        if ($user->role->role_name === 'administrator') {
            return redirect()->route('admin.dashboard');
        }

        // 2. DIRECTORS -> Director Dashboard
        if ($user->role->role_name === 'director') {
            return redirect()->route('dashboard'); // Your Director's Center
        }

        // 3. MEMBERS -> Member/Home Page
        // Assuming 'member' is the role, or if they have no specific role
        if ($user->role === 'member') {
            // Option A: Redirect to the public home page
            return redirect()->route('home'); 
            
            // Option B: Redirect to a specific "Member Area" if you have one
            // return redirect()->route('member.area');
        }

        // 4. FALLBACK (Default)
        return redirect()->intended(config('fortify.home'));
    }
}