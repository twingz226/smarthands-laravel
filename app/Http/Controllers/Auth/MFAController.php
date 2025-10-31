<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class MFAController extends Controller
{
    /**
     * Show MFA setup form
     */
    public function showSetup()
    {
        $user = Auth::user();

        if ($user->mfa_secret) {
            return view('auth.mfa-setup', [
                'qrCode' => $this->generateQRCode($user),
                'secret' => $this->formatSecret($user->mfa_secret)
            ]);
        }

        return $this->setupMFA($user);
    }

    /**
     * Setup MFA for user
     */
    private function setupMFA($user)
    {
        $secret = $this->generateSecret();

        $user->update([
            'mfa_secret' => $secret,
            'mfa_enabled' => false
        ]);

        return view('auth.mfa-setup', [
            'qrCode' => $this->generateQRCode($user),
            'secret' => $this->formatSecret($secret)
        ]);
    }

    /**
     * Enable MFA for user
     */
    public function enable(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|numeric|digits:6'
        ]);

        $user = Auth::user();
        $secret = $user->mfa_secret;

        if (!$secret) {
            return redirect()->route('mfa.setup')
                ->withErrors(['error' => 'MFA not properly configured']);
        }

        if ($this->verifyTOTP($secret, $request->verification_code)) {
            $user->update(['mfa_enabled' => true]);

            Log::info('MFA enabled for user', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip()
            ]);

            return redirect()->route('home')
                ->with('success', 'Two-factor authentication has been enabled');
        }

        throw ValidationException::withMessages([
            'verification_code' => 'Invalid verification code'
        ]);
    }

    /**
     * Show MFA verification form
     */
    public function showVerify()
    {
        return view('auth.mfa-verify');
    }

    /**
     * Verify MFA code
     */
    public function verify(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|numeric|digits:6'
        ]);

        $user = Auth::user();
        $secret = $user->mfa_secret;

        if (!$secret || !$user->mfa_enabled) {
            return redirect()->route('mfa.setup');
        }

        if ($this->verifyTOTP($secret, $request->verification_code)) {
            $request->session()->put('mfa_verified', true);

            Log::info('MFA verification successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip()
            ]);

            return redirect()->intended();
        }

        throw ValidationException::withMessages([
            'verification_code' => 'Invalid verification code'
        ]);
    }

    /**
     * Disable MFA
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
            'verification_code' => 'required|numeric|digits:6'
        ]);

        $user = Auth::user();
        $secret = $user->mfa_secret;

        if (!$secret || !$user->mfa_enabled) {
            return redirect()->back()->withErrors(['error' => 'MFA is not enabled']);
        }

        if ($this->verifyTOTP($secret, $request->verification_code)) {
            $user->update([
                'mfa_secret' => null,
                'mfa_enabled' => false
            ]);

            $request->session()->forget('mfa_verified');

            Log::info('MFA disabled for user', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip()
            ]);

            return redirect()->back()->with('success', 'Two-factor authentication has been disabled');
        }

        throw ValidationException::withMessages([
            'verification_code' => 'Invalid verification code'
        ]);
    }

    /**
     * Generate TOTP secret
     */
    private function generateSecret(): string
    {
        return strtoupper(substr(base64_encode(random_bytes(32)), 0, 32));
    }

    /**
     * Format secret for display
     */
    private function formatSecret(string $secret): string
    {
        return chunk_split($secret, 4, ' ');
    }

    /**
     * Generate QR Code URL for authenticator apps
     */
    private function generateQRCode($user): string
    {
        $company = config('app.name', 'Smarthands');
        $account = $user->email;

        return "otpauth://totp/{$company}:{$account}?secret={$user->mfa_secret}&issuer={$company}";
    }

    /**
     * Verify TOTP code
     */
    private function verifyTOTP(string $secret, string $code): bool
    {
        $timeSlice = floor(time() / 30);

        for ($i = -1; $i <= 1; $i++) {
            $expectedCode = $this->generateTOTP($secret, $timeSlice + $i);
            if (hash_equals($expectedCode, str_pad($code, 6, '0', STR_PAD_LEFT))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate TOTP code for given secret and time
     */
    private function generateTOTP(string $secret, int $time): string
    {
        $time = pack('J', $time);
        $hash = hash_hmac('sha1', $time, hex2bin($secret), true);
        $offset = ord(substr($hash, -1)) & 0x0F;

        $code = (
            ((ord($hash[$offset]) & 0x7F) << 24) |
            ((ord($hash[$offset + 1]) & 0xFF) << 16) |
            ((ord($hash[$offset + 2]) & 0xFF) << 8) |
            (ord($hash[$offset + 3]) & 0xFF)
        );

        return str_pad($code % 1000000, 6, '0', STR_PAD_LEFT);
    }
}
