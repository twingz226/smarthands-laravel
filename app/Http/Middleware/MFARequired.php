<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MFARequired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Skip MFA check for certain routes
        if ($this->shouldSkipMFA($request)) {
            return $next($request);
        }

        // If user has MFA enabled but hasn't verified
        if ($user && $user->mfa_enabled && !$request->session()->has('mfa_verified')) {
            return redirect()->route('mfa.verify');
        }

        return $next($request);
    }

    /**
     * Check if MFA should be skipped for this request
     */
    private function shouldSkipMFA(Request $request): bool
    {
        $skipRoutes = [
            'mfa.verify',
            'mfa.setup',
            'mfa.enable',
            'mfa.disable',
            'logout'
        ];

        return in_array($request->route()->getName(), $skipRoutes);
    }
}
