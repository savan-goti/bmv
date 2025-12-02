<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DetectBadClients
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ua = $request->header('User-Agent', '');
        $origin = $request->header('Origin', '');
        $referer = $request->header('Referer', '');

        // suspicious UA patterns
        $badUaPatterns = [
            '/PostmanRuntime/i',
            '/curl/i',
            '/python-requests/i',
            '/HTTPie/i',
            '/Insomnia/i',
            '/GuzzleHttp/i',
        ];

        foreach ($badUaPatterns as $pattern) {
            if (preg_match($pattern, $ua)) {
                Log::warning('Blocked suspicious UA', [
                    'ua' => $ua,
                    'ip' => $request->ip(),
                    'route' => $request->path(),
                ]);
                return response('Forbidden', Response::HTTP_FORBIDDEN);
            }
        }

        // Postman sometimes sends Postman-Token header
        if ($request->hasHeader('Postman-Token')) {
            Log::warning('Blocked Postman-Token header', ['ip' => $request->ip(), 'ua' => $ua]);
            return response('Forbidden', Response::HTTP_FORBIDDEN);
        }

        // Check content-type for forms — browsers typically send form data or multipart
        $ct = $request->header('Content-Type', '');
        if (str_contains($ct, 'application/json') && $request->isMethod('post')) {
            // if your HTML form should never send JSON, treat as suspicious
            Log::info('JSON POST to form endpoint', ['ct'=>$ct, 'ip'=>$request->ip()]);
            // optional: block or require additional checks
        }

        return $next($request);
    }
}
