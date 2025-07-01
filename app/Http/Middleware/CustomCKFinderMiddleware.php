<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomCKFinderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->guard('admin')->check()) {
            config(['ckfinder.authentication' => function() use ($request) {
                return false;
            }] );
        } else {
            config(['ckfinder.authentication' => function() use ($request) {
                return true;
            }] );
        }

        return $next($request);
    }
}
