<?php

namespace App\Http\Middleware;

use App\Beban;
use Closure;

class cekExpense
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
        $diskon = Beban::select('id')->where('id', $request->id)->first();
        if(empty($diskon)){
            abort(404);
        }
        return $next($request);
    }
}
