<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ValidateRecaptcha
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $recaptchaResponse = $request->input('g-recaptcha-response');
        
        // Skip reCAPTCHA validation in local environment if not configured
        if (app()->environment('local') && !config('services.recaptcha.secret')) {
            return $next($request);
        }
        
        // Validate reCAPTCHA response
        $validator = Validator::make($request->all(), [
            'g-recaptcha-response' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return $this->handleValidationFailure($request);
        }
        
        // Verify reCAPTCHA with Google
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret'),
            'response' => $recaptchaResponse,
            'remoteip' => $request->ip(),
        ]);
        
        $result = $response->json();
        
        if (!$result['success']) {
            return $this->handleValidationFailure($request, 'reCAPTCHA verification failed. Please try again.');
        }
        
        return $next($request);
    }
    
    /**
     * Handle reCAPTCHA validation failure.
     */
    private function handleValidationFailure(Request $request, string $message = 'Please complete the reCAPTCHA verification.'): Response
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'errors' => ['g-recaptcha-response' => [$message]]
            ], 422);
        }
        
        return back()
            ->withErrors(['g-recaptcha-response' => $message])
            ->withInput();
    }
}
