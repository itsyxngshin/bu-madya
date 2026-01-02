<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if user is logged in
        if (Auth::check()) {
            
            // 2. Check if their status is suspended (or deleted/hidden)
            if (Auth::user()->status !== 'active') {
                
                // 3. If suspended, log them out immediately
                Auth::guard('web')->logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // 4. Redirect to login with an error message
                return redirect()->route('login')->withErrors([
                    'email' => 'Your account is in lockdown mode (suspended). Please contact the administrator.',
                ]);
            }
        }

        return $next($request);
    }
}