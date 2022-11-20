<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsManager
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
        if (Auth::user() && Auth::user()->roles == 'MANAGER' || Auth::user()->roles == 'ADMINISTRATOR') {
            return $next($request);
        }
        return redirect('/dashboard');
    }
}
