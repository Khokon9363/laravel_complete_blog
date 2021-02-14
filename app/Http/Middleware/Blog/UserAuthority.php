<?php

namespace App\Http\Middleware\Blog;

use Closure;

class UserAuthority
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
        if (auth()->user()->role === 1) {
            return redirect('/operator/dashboard');
        }elseif(auth()->user()->role === 2){
            return redirect('/admin/dashboard');
        }
        return $next($request);
    }
}