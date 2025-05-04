<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        \Log::info('User role: ' . optional(auth()->user())->role);
        if (!auth()->check() || !in_array(auth()->user()->role, $roles)) {
            return response()->json(['message' => 'Unauthorized role'], 403);
        }

        return $next($request);
    }
}
