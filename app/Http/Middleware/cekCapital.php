<?php

namespace App\Http\Middleware;

use App\Financials;
use Closure;

class cekCapital
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
        $capital = Financials::select('id')->where('id', $request->id)->first();
        if(empty($capital)){
            abort(404);
        }
        return $next($request);
    }
}
