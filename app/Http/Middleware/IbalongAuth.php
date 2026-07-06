<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IbalongAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // Explicitly check the isolated Ibalong guard
        if (!Auth::guard('ibalong')->check()) {
            
            // If it's a Livewire request or AJAX, handle it gracefully
            if ($request->expectsJson() || $request->header('X-Livewire')) {
                abort(401, 'Unauthenticated.');
            }

            // Save the intended URL so they are redirected back after logging in
            session()->put('url.intended', $request->url());
            
            return redirect()->route('ibalong.login');
        }

        return $next($request);
    }
},