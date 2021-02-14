<?php

namespace App\Http\Middleware\Blog;

use Closure;

class AdminAuthority
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
        if (auth()->user()->role === 0) {
            return redirect('/user/dashboard');
        }elseif(auth()->user()->role === 1){
            return redirect('/operator/dashboard');
        }
        return $next($request);
    }
}