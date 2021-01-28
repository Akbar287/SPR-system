<?php

namespace App\Http\Middleware;

use App\products;
use Closure;

class cekProducted
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
        $producted = products::select('id')->where('id', $request->id)->first();
        if(empty($producted)){
            abort(404);
        }
        return $next($request);
    }
}
