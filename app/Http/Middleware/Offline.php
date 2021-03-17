<?php

namespace App\Http\Middleware;

use Closure;

class Offline
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

        if(auth()->user()->is_offline == 1){
            return $next($request);
        }

        return redirect('login')->with('error',"You'are not authorised!");
    }
}
