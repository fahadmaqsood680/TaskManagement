<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $userRole = auth()->user()->role;
        if (!in_array($userRole, explode(',', $role)) && $userRole !== 'manager') {
            return response()->json(['message' => 'Access Denied'], 403);
        }

        return $next($request);
    }
}
