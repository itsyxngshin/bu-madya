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
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        // Define the roles that are allowed
        $roles = ['administrator', 'director', 'member', 'alumni'];

        // Check if user is authenticated and has a role
        if (!$user || !in_array($user->role->role_name, $roles)) {
            abort(403, 'Unauthorized action.');
        }

        if (Auth::user()->role->role_name !== $role) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
