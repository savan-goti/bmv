<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->prefix('owner')
                ->name('owner.')
                ->group(base_path('routes/owner.php'));

            Route::middleware('web')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));

            Route::middleware('web')
                ->prefix('staff')
                ->name('staff.')
                ->group(base_path('routes/staff.php'));

            Route::middleware('web')
                ->prefix('seller')
                ->name('seller.')
                ->group(base_path('routes/seller.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'detect.bad.clients' => \App\Http\Middleware\DetectBadClients::class,
            'session.guard' => \App\Http\Middleware\SetSessionGuard::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle JWT authentication exceptions for API
        $exceptions->render(function (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access token is invalid. Please login again.',
                    'error' => 'token_invalid'
                ], 401);
            }
        });

        $exceptions->render(function (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access token has expired. Please refresh your token or login again.',
                    'error' => 'token_expired'
                ], 401);
            }
        });

        $exceptions->render(function (\Tymon\JWTAuth\Exceptions\JWTException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access token not found. Please provide a valid token.',
                    'error' => 'token_not_found'
                ], 401);
            }
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Access token is required for this endpoint.',
                    'error' => 'unauthorized'
                ], 401);
            }
        });

        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required. Please login to access this resource.',
                    'error' => 'unauthenticated'
                ], 401);
            }
        });
    })->create();
