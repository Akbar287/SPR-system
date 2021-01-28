<?php

namespace App\Http\Middleware;

use App\markets;
use Closure;

class cekMarket
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
        $market = markets::select('id')->where('id', $request->id)->first();
        if(empty($market)){
            abort(404);
        }
        return $next($request);
    }
}
