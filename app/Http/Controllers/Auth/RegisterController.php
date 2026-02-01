<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RegistrationLog;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Rules\Recaptcha;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                'confirmed'
            ],
        ];

        // Only require reCAPTCHA if site key is configured
        if (config('services.recaptcha.key')) {
            $rules['g-recaptcha-response'] = ['required', new Recaptcha()];
        }

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'customer',
            'failed_login_attempts' => 0,
            'is_locked' => false,
            'lockout_time' => null,
            'mfa_secret' => null,
            'mfa_enabled' => false,
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        try {
            $this->validator($request->all())->validate();

            event(new \Illuminate\Auth\Events\Registered($user = $this->create($request->all())));

            // Don't auto-login the user, redirect to login with success message
            // $this->guard()->login($user);

            // Log successful registration
            RegistrationLog::create([
                'email' => $request->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'success',
                'request_data' => [
                    'name' => $request->name,
                    'email' => $request->email,
                    // Don't log password
                ],
            ]);

            if ($response = $this->registered($request, $user)) {
                return $response;
            }

            // Redirect to login page with success message
            return redirect()->route('login')->with('success', 'Registration successful! Please log in with your credentials.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log failed registration due to validation
            RegistrationLog::create([
                'email' => $request->email ?? 'unknown',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'failed',
                'error_message' => json_encode($e->errors()),
                'request_data' => [
                    'name' => $request->name,
                    'email' => $request->email,
                    // Don't log password
                ],
            ]);

            throw $e;

        } catch (\Exception $e) {
            // Log failed registration due to other errors
            RegistrationLog::create([
                'email' => $request->email ?? 'unknown',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'request_data' => [
                    'name' => $request->name,
                    'email' => $request->email,
                    // Don't log password
                ],
            ]);

            // Re-throw the exception
            throw $e;
        }
    }

    /**
     * Handle registration with phone number.
     */
    public function registerWithPhone(Request $request)
    {
        try {
            $request->validate([
                'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            ]);

            $user = User::create([
                'phone' => $request->phone,
                'name' => 'PhoneUser_' . substr($request->phone, -4),
                'email' => null,
                'password' => Hash::make(Str::random(16)),
                'role' => 'customer',
                'failed_login_attempts' => 0,
                'is_locked' => false,
                'lockout_time' => null,
                'mfa_secret' => null,
                'mfa_enabled' => false,
            ]);

            // Log successful phone registration
            RegistrationLog::create([
                'email' => 'phone:' . $request->phone, // Use phone identifier
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'success',
                'request_data' => [
                    'phone' => $request->phone,
                ],
            ]);

            // Don't auto-login for phone registration, redirect to login
            // \Illuminate\Support\Facades\Auth::login($user);

            return redirect()->route('login')->with('success', 'Phone registration successful! Please log in with your phone number and the provided password.');

        } catch (\Exception $e) {
            // Log failed phone registration
            RegistrationLog::create([
                'email' => 'phone:' . ($request->phone ?? 'unknown'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'request_data' => [
                    'phone' => $request->phone,
                ],
            ]);

            throw $e;
        }
    }

}
