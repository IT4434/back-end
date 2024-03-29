<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param null $guard
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next, $guard = null): \Illuminate\Http\JsonResponse
    {
        if (!$guard) {
            $guard = 'api';
        }
        if (!auth()->guard($guard)->check()) {
            return response()->json(['error' => __('Unauthorized')], config('response.HTTP_UNAUTHORIZED'));
        }

        return $next($request);
    }
}
