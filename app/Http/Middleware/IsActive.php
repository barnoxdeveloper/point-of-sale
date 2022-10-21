<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsActive
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
        if (Auth::user() && Auth::user()->status == 'ACTIVE') {
            return $next($request);
        }
        $request->session()->flush();
        $request->session()->regenerate();

        return to_route('login')->with('status', 'Your Account Non Active!');
    }
}
