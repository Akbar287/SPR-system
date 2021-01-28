<?php

namespace App\Http\Middleware;

use App\Salary;
use Closure;

class cekSalary
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
        $salary = Salary::select('id')->where('id', $request->id)->first();
        if(empty($salary)){
            abort(404);
        }
        return $next($request);
    }
}
