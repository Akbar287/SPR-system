<?php

namespace App\Http\Middleware;

use App\Orders;
use Closure;

class cekCart
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
        $order = Orders::select('id')->where('id', $request->id)->first();
        if(empty($order)){
            abort(404);
        }
        return $next($request);
    }
}
