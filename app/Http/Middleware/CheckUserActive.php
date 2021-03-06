<?php

namespace App\Http\Middleware;

use Closure;

class CheckUserActive
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
        if ($user = \Auth::user()) {
            if (!$user->is_active) {
                throw new \App\Exceptions\InActiveException("in_active");
            }
        }
        
        return $next($request);
    }
}
