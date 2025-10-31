


@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/login.css') }}" />
<div class="container" style="height: auto; padding: 40px;">
    <h1 class="login-title">{{ __('Forgot Your Password?') }}</h1>
    <p class="text-center mb-4" style="color: white;">Enter your email address below and we'll send you a link to reset your password.</p>

    @if (session('status'))
        <div class="success-message">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

                        <section class="input-box">
                            <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email Address">
                            <i class='bx bxs-user'></i>
                            @error('email')
                                <span class="error-message" role="alert" style="position: absolute; top: 100%; left: 0; width: 100%;">{{ $message }}</span>
                            @enderror
                        </section>

                        <button type="submit" class="login-button" style="margin-top: 20px;">
                            {{ __('Send Reset Link') }}
                        </button>
                    </form>
</div>
@endsection

