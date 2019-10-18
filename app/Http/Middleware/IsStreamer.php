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

        if ($user &&  ($user->hasRole('streamer') || $user->isAdmin()) ) {
            return $next($request);
        }

        return response()->json(['error'=>'Unauthorized'], 401);
    }
}
