<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->isAdmin()) {
            return $next($request);
        }

        if (! $user->isVerified()) {
            if ($request->expectsJson()) {
                abort(403, 'Your student account is pending administrator verification.');
            }

            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
