<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAuthenticatedUserMatchesRouteUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $authenticatedUser = $request->user();

        if (!$authenticatedUser) {
            abort(401, 'Unauthenticated.');
        }

        $routeUser = $request->route('user');
        $routeUserId = is_object($routeUser) ? $routeUser->getKey() : (string) $routeUser;

        if ((string) $authenticatedUser->getAuthIdentifier() !== (string) $routeUserId) {
            abort(403, 'Forbidden.');
        }

        return $next($request);
    }
}
