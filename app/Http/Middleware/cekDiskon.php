<?php

namespace App\Http\Middleware;

use App\Discount;
use Closure;

class cekDiskon
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
        $diskon = Discount::select('id')->where('id', $request->id)->first();
        if(empty($diskon)){
            abort(404);
        }
        return $next($request);
    }
}
