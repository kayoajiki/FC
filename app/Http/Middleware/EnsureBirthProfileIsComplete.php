<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class EnsureBirthProfileIsComplete
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        $isOnboardingRoute = $request->routeIs('birth-profile');
        $isLogout = $request->routeIs('logout');

        $missingBirthInfo = !$user->birth_date || !$user->birth_time || !$user->birth_place || !$user->birth_date_finalized_at;

        if ($missingBirthInfo && !$isOnboardingRoute && !$isLogout) {
            return redirect()->route('birth-profile');
        }

        return $next($request);
    }
}

