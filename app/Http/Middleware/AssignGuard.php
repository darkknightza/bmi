<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AssignGuard
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard)
    {
        if(auth()->user()){
            auth()->guard('web')->logout();
            return redirect()->route('login');
        }
        Auth::shouldUse($guard);
        return $next($request);
    }
}
