<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek jika user login DAN dia adalah ADMIN
        if (Auth::check() && Auth::user()->isAdmin()) {
            // Redirect Admin ke halaman miliknya agar tidak masuk ke halaman player
            return redirect()->route('admin.dashboard');
        }

        // Jika bukan admin (berarti user biasa), silakan lanjut
        return $next($request);
    }
}