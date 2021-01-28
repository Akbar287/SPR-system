<?php

namespace App\Http\Middleware;

use App\OrderFinance;
use App\Orders;
use Closure;

class cekPesanan
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
        $pesanan = Orders::select('id')->where('id', $request->id)->first();
        if(empty($pesanan)){
            abort(404);
        }
        return $next($request);
    }
}
