<?php

namespace App\Http\Middleware;
use Session, Closure;

class AuthPolicy
{
    public function handle($request, Closure $next)
    {
        if (!Session::has('account')) {
        	if($request->is('staff/*')){
        		return redirect('/staff/login');
        	}
        	else{
        		return redirect('/admin/login');
        	}
          
        }

        return $next($request);
    }
}
