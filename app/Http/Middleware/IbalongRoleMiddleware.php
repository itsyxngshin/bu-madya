<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IbalongRoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Ensure the user is authenticated first
        if (!Auth::guard('ibalong')->check()) {
            return redirect()->route('ibalong.login');
        }

        // 2. Get the user's role ID (cast to string to match route parameters)
        $userRole = (string) Auth::guard('ibalong')->user()->role_id;

        // 3. Check if their role is in the allowed list for this route
        if (!in_array($userRole, $roles)) {
            abort(403, 'UNAUTHORIZED: You do not have clearance to access this sector.');
        }

        return $next($request);
    }
}