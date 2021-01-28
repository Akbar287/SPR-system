<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class cekAdmin
{
    public function handle($request, Closure $next)
    {
        if (Auth::user()->roles != 1) {
            abort(401, 'This action is unauthorized.');
        }
        return $next($request);
    }
}
