<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TrackVisitor
{
    public function handle(Request $request, Closure $next)
    {
        // Use the user's IP and date to prevent spamming the counter on page refreshes.
        // It counts 1 unique visit per IP address per day.
        $ip = $request->ip();
        $date = now()->toDateString();
        $cacheKey = "visited_{$ip}_{$date}";

        if (!Cache::has($cacheKey)) {
            Cache::put($cacheKey, true, now()->endOfDay());
            // Increment the global total visitor count forever
            Cache::increment('global_visitor_count');
        }

        return $next($request);
    }
}