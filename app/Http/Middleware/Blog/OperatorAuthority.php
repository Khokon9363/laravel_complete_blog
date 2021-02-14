<?php

namespace App\Http\Middleware\Blog;

use Closure;

class OperatorAuthority
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
        }elseif(auth()->user()->role === 2){
            return redirect('/admin/dashboard');
        }
        return $next($request);
    }
}