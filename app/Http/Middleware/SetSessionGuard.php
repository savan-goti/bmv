<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Session;

class SetSessionGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $guard = null): Response
    {
        $response = $next($request);

        // If user is authenticated, update the session guard
        if ($guard && Auth::guard($guard)->check()) {
            $this->updateSessionGuard($request, $guard);
        }

        return $response;
    }

    /**
     * Update the session record with the guard name.
     */
    protected function updateSessionGuard(Request $request, string $guard): void
    {
        $sessionId = $request->session()->getId();
        
        if ($sessionId) {
            Session::setGuard($sessionId, $guard);
        }
    }
}
