<?php

namespace App\Http\Middleware;
use Session, Closure;

class AuthPolicy
{
    public function handle($request, Closure $next)
    {
        if (!Session::has('account')) {
          return redirect('/admin/login');
        }

        return $next($request);
    }
}
