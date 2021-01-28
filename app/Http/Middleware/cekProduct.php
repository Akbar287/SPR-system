<?php

namespace App\Http\Middleware;

use App\products;
use Closure;

class cekProduct
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
        $product = products::select('id')->where('id', $request->id)->first();
        if(empty($product)){
            abort(404);
        }
        return $next($request);
    }
}
