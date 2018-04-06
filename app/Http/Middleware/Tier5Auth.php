<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class Tier5Auth
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
        if (Auth::loginUsingId($request->header('x-input-data'))) {
            return $next($request);
        } else {
            return response()->json([
                'status' => false,
                'error' => "Unauthenticated"
            ], 401);
        }
    }
}
