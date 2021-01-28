<?php

namespace App\Http\Middleware;

use App\CategoryProducts;
use Closure;

class cekCategory
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
        $category = CategoryProducts::select('id')->where('id', $request->id)->first();
        if(empty($category)){
            abort(404);
        }
        return $next($request);
    }
}
