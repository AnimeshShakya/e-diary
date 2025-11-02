<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): mixed
    {
        $user = $request->user();

        if (! $user || ! $user->isAdmin()) {
            abort(403, 'Unauthorized - admin only');
        }

        return $next($request);
    }
}
