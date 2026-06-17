<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PengelolaMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !Auth::user()->isPengelola()) {
            abort(403, 'Halaman ini hanya untuk Pengelola Event.');
        }

        return $next($request);
    }
}
