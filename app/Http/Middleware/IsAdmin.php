<?php

namespace App\Http\Middleware;

use Closure;

class IsAdmin
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

        if ($user->hasRole('admin')) {
            return $next($request);
        }

        return response()->json(['error'=>'Not admin'], 403);
    }
}