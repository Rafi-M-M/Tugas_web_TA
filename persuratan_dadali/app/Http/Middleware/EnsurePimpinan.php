<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsurePimpinan
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || $request->user()->role !== 'pimpinan') {
            abort(403, 'Akses hanya untuk pimpinan.');
        }

        return $next($request);
    }
}