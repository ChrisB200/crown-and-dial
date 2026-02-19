<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->must_change_password) {
            // Allow the security page and password update endpoint
            if (!$request->routeIs('admin.security') && !$request->routeIs('password.update') && !$request->routeIs('logout')) {
                return redirect()->route('admin.security')->with('status', 'Please change your password before continuing.');
            }
        }

        return $next($request);
    }
}
