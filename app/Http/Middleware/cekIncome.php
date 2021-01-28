<?php

namespace App\Http\Middleware;

use App\Income;
use Closure;

class cekIncome
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
        $income = Income::select('id')->where('id', $request->id)->first();
        if(empty($income)){
            abort(404);
        }
        return $next($request);
    }
}
