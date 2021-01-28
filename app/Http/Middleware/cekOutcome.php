<?php

namespace App\Http\Middleware;

use App\Outcome;
use Closure;

class cekOutcome
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
        $outcome = Outcome::select('id')->where('id', $request->id)->first();
        if(empty($outcome)){
            abort(404);
        }
        return $next($request);
    }
}
