<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to check if the authenticated user has any of the specified roles.
 */
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Checks if the user has at least one of the given roles.
     * If not, returns a 403 Unauthorized response.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming HTTP request.
     * @param  \Closure  $next  The next middleware or request handler.
     * @param  mixed  ...$roles  One or more roles to check against the user.
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next, ...$roles)
    {
        // Check if the authenticated user has any of the specified roles
        if (!$request->user()->hasRole($roles)) {
            // If not, return a 403 Unauthorized response
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        // If the user has the required role, continue processing the request
        return $next($request);
    }
    
}
