<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoginThrottle
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only apply to login attempts
        if (!$request->is('login') || !$request->isMethod('post')) {
            return $next($request);
        }

        $key = $this->resolveRequestSignature($request);

        if ($this->limiter->tooManyAttempts($key, 5)) {
            $seconds = $this->limiter->availableIn($key);

            return response()->json([
                'message' => "Too many login attempts. Please try again in {$seconds} seconds.",
                'retry_after' => $seconds
            ], 429);
        }

        $this->limiter->hit($key, 60); // 60 seconds window

        $response = $next($request);

        // If login failed, increment attempts
        if ($response->getStatusCode() === 422 || $response->getStatusCode() === 401) {
            $this->limiter->hit($key, 60);
        }

        return $response;
    }

    /**
     * Resolve the request signature for rate limiting.
     */
    protected function resolveRequestSignature(Request $request): string
    {
        return sha1(
            $request->ip() . '|' . $request->input('email', '') . '|' . $request->userAgent()
        );
    }
}
