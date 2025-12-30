<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // (Keep your existing check ensuring user has a valid role generally)
        $validSystemRoles = ['administrator', 'director', 'member', 'alumni'];
        if (!$user || !in_array($user->role->role_name, $validSystemRoles)) {
            abort(403, 'Unauthorized action.');
        }

        // 2. THE FIX: Check if the user's role is in the list of ALLOWED roles for this specific route
        // This replaces the strict "!==" check
        if (!in_array($user->role->role_name, $roles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
