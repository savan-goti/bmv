<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if ($guard === 'owner') {
                    return redirect()->route('owner.dashboard');
                }else if ($guard === 'admin') {
                    return redirect()->route('admin.dashboard');
                }else if ($guard === 'staff') {
                    return redirect()->route('staff.dashboard');
                }else if ($guard === 'seller') {
                    return redirect()->route('seller.dashboard');
                }
                
                return redirect()->route('owner.login');
            }
        }

        return $next($request);
    }
}
