<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if(!config('app.admin_mode')) {
            abort(403, 'Access denied.');
        }
        return $next($request);
    }
}
