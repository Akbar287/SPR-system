<?php

namespace App\Http\Middleware;

use App\Purchase;
use Closure;

class cekPurchase
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
        $purchase = Purchase::select('id')->where('id', $request->id)->first();
        if(empty($purchase)){
            abort(404);
        }
        return $next($request);
    }
}
