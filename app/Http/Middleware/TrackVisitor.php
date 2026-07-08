<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TrackVisitor
{
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $date = now()->toDateString();
        $cacheKey = "visited_{$ip}_{$date}";

        if (!Cache::has($cacheKey)) {
            // Log the unique visitor for today
            Cache::put($cacheKey, true, now()->endOfDay());
            
            // Ensure the global counter exists, then increment
            if (!Cache::has('global_visitor_count')) {
                Cache::put('global_visitor_count', 0);
            }
            Cache::increment('global_visitor_count');
        }

        return $next($request);
    }
}