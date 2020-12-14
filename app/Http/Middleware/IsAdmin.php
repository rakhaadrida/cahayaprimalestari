<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

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
        if(Auth::user() && ((Auth::user()->roles == 'ADMIN') || (Auth::user()->roles == 'SUPER') || (Auth::user()->roles == 'AR') || (Auth::user()->roles == 'AP') || (Auth::user()->roles == 'GUDANG') || (Auth::user()->roles == 'OFFICE02')))
        {
            return $next($request);
        }
        return redirect('/');
    }
}
