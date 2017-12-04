<?php

namespace App\Http\Middleware;
use Session, Closure;

class AuthLogin
{
    public function handle($request, Closure $next)
    {
        if (!Session::has('account')) {
          return redirect('/admin/login');
        }

        return $next($request);
    }
}
