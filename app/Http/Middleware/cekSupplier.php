<?php

namespace App\Http\Middleware;

use App\Vendor;
use Closure;

class cekSupplier
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
        $supplier = Vendor::select('id')->where('id', $request->id)->first();
        if(empty($supplier)){
            abort(404);
        }
        return $next($request);
    }
}
