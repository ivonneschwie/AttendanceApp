<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StudentMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (session('user_type') !== 'student' && session('user_type') !== 'admin') {
            return redirect('/');
        }

        return $next($request);
    }
}
