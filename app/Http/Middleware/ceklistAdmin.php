<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
class ceklistAdmin
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
        $admin = User::select('id')->where('id', $request->id)->first();
        if(empty($admin)){
            abort(404);
        }
        return $next($request);
    }
}
