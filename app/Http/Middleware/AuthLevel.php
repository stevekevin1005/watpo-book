<?php

namespace App\Http\Middleware;
use Session, Closure;

class AuthLevel
{
    public function handle($request, Closure $next)
    {
        if (Session::get('account_level') != 1) {
          return redirect('/admin/dashboard');
        }

        return $next($request);
    }
}
