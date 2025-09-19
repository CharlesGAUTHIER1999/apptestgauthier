<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request  The current HTTP request
     * @param Closure $next     The next middleware/handler in the stack
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if "admin_mode" is enabled in the app configuration
        if (!config('app.admin_mode')) {
            // If not enabled → deny access with a 403 Forbidden error
            abort(403, 'Access denied.');
        }

        // Continue the request lifecycle if the check passes
        return $next($request);
    }
}
