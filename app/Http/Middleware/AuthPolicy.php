<?php

namespace App\Http\Middleware;
use Session, Closure;

class AuthPolicy
{
    public function handle($request, Closure $next)
    {
        if (!Session::has('level') && Session::get('level' != 1)) {
          return redirect('/admin/login');
        }

        return $next($request);
    }
}
