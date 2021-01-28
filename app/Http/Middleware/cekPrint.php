<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class cekPrint
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
        if($request->method == 'lr' ||$request->method ==  'pm' ||$request->method ==  'nc' ||$request->method ==  'ak' || $request->method == 'ju'){
            $information = DB::table('information')->select('created_at')->where('id', 1)->first();
            $time = explode(' ', $information->created_at, -1);
            $time = explode('-', $time['0'], -1);
            if($request->s2 >= $time['0'] && $request->s2 <= date('Y')){
                if($request->s1 >= $time['1'] && $request->s1 <= date('n')){
                    return $next($request);
                } else {
                    abort(404);exit;
                }
            } else {
                abort(404);exit;
            }
        }
    }
}
