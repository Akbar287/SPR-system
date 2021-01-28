<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class cekReseller
{
    public function handle($request, Closure $next)
    {
        if (Auth::user()->roles != 2 && Auth::user()->roles != 3) {
            abort(401, 'This action is unauthorized.');
        }
        return $next($request);
    }
}
