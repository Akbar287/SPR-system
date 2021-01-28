<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class cekStatus
{
    public function handle($request, Closure $next)
    {
        if(Auth::user()->is_active == 1){
            if (Auth::user()->roles != 1 && Auth::user()->roles != 2 && Auth::user()->roles != 3) {
                abort(401, 'This action is unauthorized.');
            }
        } else {
            abort(406, 'No Acceptable.');
        }
        return $next($request);
    }
}
