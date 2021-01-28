<?php

namespace App\Http\Middleware;

use App\Materials;
use Closure;

class cekMaterial
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
        $material = Materials::select('id')->where('id', $request->id)->first();
        if(empty($material)){
            abort(404);
        }
        return $next($request);
    }
}
