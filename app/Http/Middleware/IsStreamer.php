<?php

namespace App\Http\Middleware;

use Closure;

class IsStreamer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if(!$user)
            return response()->json(['error'=>'Unauthorized'], 401);

        if ($user->hasRole('streamer') || $user->isAdmin()) {
            return $next($request);
        }

        return response()->json(['error'=>'Not streamer'], 403);
    }
}
