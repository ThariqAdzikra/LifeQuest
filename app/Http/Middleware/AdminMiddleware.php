<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek jika user login DAN dia adalah admin
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request); // Lanjutkan
        }

        // Jika bukan admin, tendang ke dashboard user biasa
        return redirect(route('dashboard'))->with('error', 'Anda tidak punya hak akses!');
    }
}